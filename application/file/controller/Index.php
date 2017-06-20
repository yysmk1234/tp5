<?php
namespace app\file\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\Url;
use app\count;
use PHPExcel_IOFactory;

class Index extends ufunction
{
    public function Upfile(Request $request)
    {
        $insert_table = '';
        $test_name = $request->post('test_name');
        $status = $request->post('status');
        $tester = $request->post('tester');
        $game = $request->post('game');
        $index_file = request()->file('index');

        //index文件
        $info1 = $index_file->move(ROOT_PATH . 'public' . DS . 'uploads');


        $tags_file = request()->file('tags');
        //tags文件
        $info2 = $tags_file->move(ROOT_PATH . 'public' . DS . 'uploads');

        if ($info2&&$info1){
            $path = $info1->getPath();
            $path_tag = $info2->getPath();
            $obj_excel_index = PHPExcel_IOFactory::load($path . "\\" . $info1->getFilename());
            $obj_excel_tag = PHPExcel_IOFactory::createReader('CSV')->setInputEncoding('GBK');
            $obj_excel_tag_ = $obj_excel_tag->load($path_tag. "\\" . $info2->getFilename());
            //设置文件权限
//            chmod($path . "\\" . $info1->getFilename(),0777);
//            chmod($path_tag. "\\" . $info2->getFilename(),0777);
            //index文件数组集合
            $data1 = $obj_excel_index->getActiveSheet()->toArray(null,true,true,true);
            //tags文件数组集合
            $data2 = $obj_excel_tag_->getActiveSheet()->toArray(null,true,true,true);

            //获取index文件的表名称
            $table_name_index = $this->guid();
            //获取tags表名称
            $table_name_tags = $this->guid();

            //创建一个存放index数据的table
            $create_index = Db::execute("create table $table_name_index (emoi DECIMAL (25,20),scl DECIMAL (25,20),High_alpha DECIMAL (25,20),gamma DECIMAL (25,20),t INT(10))ENGINE=InnoDB DEFAULT CHARSET=gbk;");
            $create_tags = Db::execute("create table $table_name_tags (studioeventdata VARCHAR(200), tags_t INT(10))ENGINE=InnoDB DEFAULT CHARSET=UTF8;");
            //将数据存入测试表中
            $insert_test = Db::execute("INSERT INTO test (u_id , g_id , status_ ,test_name,data_name,tag_name) VALUES ('$tester','$game','$status','$test_name','$table_name_index','$table_name_tags')");


            $str = "INSERT INTO $table_name_index (emoi,scl,High_alpha,gamma,t) VALUES ";
            $str_ = "INSERT INTO $table_name_tags(studioeventdata,tags_t) VALUES ";

            //index文件数组长度
            $num = count($data1);
            //长度分组
            $num_group  = floor($num/100);
            //获取余下的数组个数
            $num_yu = $num%100;
            //100的倍数循环插入
            for ( $i = 0 ;$i< $num_group; $i++){
                for ($j = 1 ; $j <= 100; $j++){
                    $num_key = $i*100 +$j;
                    $emoi = $data1[$num_key]['A'];
                    $scl = $data1[$num_key]['B'];
                    $high_a = $data1[$num_key]['C'];
                    $gamma = $data1[$num_key]['D'];
                    $t = $num_key-1;
                    $str .="('$emoi','$scl','$high_a','$gamma','$t'),";
                }

                $str = substr($str,0,strlen($str)-1);
                $insert_table = Db::execute($str);
                $str ="INSERT INTO $table_name_index (emoi,scl,High_alpha,gamma,t) VALUES ";
            }

            if($num_yu!=0){
                //余下的插入
                for ($i = 1 ; $i <=$num_yu ; $i ++){
                    $num_key  = $num_group*100+$i;
                    $emoi = $data1[$num_key]['A'];
                    $scl = $data1[$num_key]['B'];
                    $high_a = $data1[$num_key]['C'];
                    $gamma = $data1[$num_key]['D'];
                    $t = $num_key-1;
                    $str .="('$emoi','$scl','$high_a','$gamma','$t'),";
                }
            }
            $str = substr($str,0,strlen($str)-1);
            $insert_table = Db::execute($str);

            //写入tags数据
            foreach ($data2 as $key=>$value){
                $tags = $value['H'];
                $t = $value['D'];
                if ($key==1){
                    $str_ = "INSERT INTO $table_name_tags(studioeventdata,tags_t) VALUES ";
                }else{
                    $str_ .="('$tags','$t'),";
                }
            }
            $str_ = substr($str_,0,strlen($str_)-1);
            $insert_table = Db::execute($str_);


        }

        $select = Db::query("select studioeventdata,tags_t from $table_name_tags");
        $tag_name = array();
        //遍历数组获取事件名称，去除名字重复的
        foreach ($select as $key=>$value){
            $re = array("-s","-e");
            $tag_tip = str_replace($re,"",$value['studioeventdata']);
            array_push($tag_name,$tag_tip);
        }
        $tag_n = array_unique($tag_name);
        $arr_result =array();
        //在数据库中查找对应数据进行归类
        foreach ($tag_n as $key=>$value){
            $arr_res= Db::query("select tags_t from $table_name_tags WHERE studioeventdata LIKE '$value%'");
//            print_r($arr_res);
            $arr_re = array();
            if ($arr_res[0]['tags_t']>$arr_res[1]['tags_t']){
                $arr_re[$value] = array(
                    $arr_res[1]['tags_t'],
                    $arr_res[0]['tags_t']
                );
            }else{
                $arr_re[$value] = array(
                    $arr_res[0]['tags_t'],
                    $arr_res[1]['tags_t']
                );
            }
            array_push($arr_result,$arr_re);
        }
        //根据分类后的数据进行计算
        foreach ($arr_result as $key=>$value){
//            print_r(array_values($value)[0][1]);
//            print_r(array_keys($value)[0]);
            $tag = array_keys($value)[0];
            $t1 = round(array_values($value)[0][0]/400);
            $t2 = round(array_values($value)[0][1]/400);

//            echo $t1;
//            echo "<br / >";
//            echo $t2;
            $arr_select = Db::query("select emoi , scl , High_alpha , gamma from $table_name_index WHERE t>='$t1'AND t<='$t2'");
            $arr_emoi = array();
            $arr_scl = array();
            $arr_high_a = array();
            $arr_gamma = array();
            foreach ($arr_select as $key=>$value){
                array_push($arr_emoi,$value['emoi']*pow(10,20));
                array_push($arr_scl,$value['scl']*pow(10,20));
                array_push($arr_high_a,$value['High_alpha']*pow(10,20));
                array_push($arr_gamma,$value['gamma']*pow(10,20));
            }
            if (($t2-$t1) != 0){
                $emoi = array_sum($arr_emoi)/(($t2-$t1+1)*pow(10,20));             //点出问题，是包含两个点，不是单纯的相减！！
                $scl = array_sum($arr_scl)/(($t2-$t1+1)*pow(10,20));
                $high_a = array_sum($arr_high_a)/(($t2-$t1+1)*pow(10,20));
                $gamma = array_sum($arr_gamma)/(($t2-$t1+1)*pow(10,20));
            }else{
                $emoi = array_sum($arr_emoi);
                $scl = array_sum($arr_scl);
                $high_a = array_sum($arr_high_a);
                $gamma = array_sum($arr_gamma);
            }


            //获取test_id
            $test_num = Db::query("SELECT test_id FROM `test` WHERE data_name='$table_name_index'AND tag_name='$table_name_tags'");
            $test_id = $test_num[0]['test_id'];
            //把数据插入到表中
            $insert_table = Db::execute("insert into data_ (test_id,emoi,scl,High_alpha,gamma,tag) VALUES ('$test_id','$emoi','$scl','$high_a','$gamma','$tag')");

        }

        if ($insert_table){
            $res_arr['sta'] = 1;
            $data = json_encode($res_arr);
            echo $data;
        }


    }
}
