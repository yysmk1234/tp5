<?php
namespace app\demo\controller;

use think\Controller;
use think\Request;
use think\Db;
use PHPExcel_IOFactory;

class Hello extends Controller
{
    public $res_arr = array(
    'sta'=>"",
    );
    public function hello()                                                             //欢迎页面
    {
        return $this->fetch();
    }


    public function setattr(){                                                          //设置页面（这个不声明不行啊，要不页面不显示，哎……
        //给页面上添加游戏类型的变量
        $game_type = Db::query('select id,value from attr where name = "game_type"');
        $this->assign('game_type',$game_type);
        //终端类型
        $terminal_type = Db::query('select id,value from attr where name = "terminal_type"');
        $this->assign('terminal_type',$terminal_type);
        //性别
        $sex = Db::query('select id,value from attr where name = "sex_type"');
        $this->assign('sex',$sex);
        //年龄
        $age_group = Db::query('select id,value from attr where name = "age_type"');
        $this->assign('age_group',$age_group);
        //游戏经历
        $game_experience = Db::query('select id,value from attr where name = "game_experience"');
        $this->assign('game_experience',$game_experience);
        //游戏年限
        $game_year = Db::query('select id,value from attr where name = "game_year"');
        $this->assign('game_year',$game_year);
        return $this->fetch();
    }

    //文件上传页面
    public function upload(){
        //选择被试
        $tester = Db::query("select u_id,u_name from tester ");
        $this->assign('tester',$tester);
        //选择游戏
        $game = Db::query("select g_id,g_name from game");
        $this->assign('game',$game);
        return $this->fetch();
    }


    //上传文件处理
    public function upfile(Request $request){
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
            $obj_excel_tag = PHPExcel_IOFactory::load($path_tag. "\\" . $info2->getFilename());
            //index文件数组集合
            $data1 = $obj_excel_index->getActiveSheet()->toArray(null,true,true,true);
            //tags文件数组集合
            $data2 = $obj_excel_tag->getActiveSheet()->toArray(null,true,true,true);
//            echo count($data1);
            //获取index文件的表名称
            $table_name_index = $this->guid();
            //获取tags表名称
            $table_name_tags = $this->guid();

            //创建一个存放index数据的table
            $create_index = Db::execute("create table $table_name_index (emoi FLOAT(20),scl FLOAT(20),High_alpha FLOAT(20),gamma FLOAT(20),t INT(10))ENGINE=InnoDB DEFAULT CHARSET=gbk;");
            $create_tags = Db::execute("create table $table_name_tags (studioeventdata VARCHAR(20), tags_t INT(10))ENGINE=InnoDB DEFAULT CHARSET=gbk;");
            //将数据存入测试表中
            $insert_test = Db::execute("insert into test (u_id , g_id , status_ ,test_name,data_name,tag_name) VALUES ('$tester','$game','$status','$test_name','$table_name_index','$table_name_tags')");


            $insert_table = '';
            $str = "insert into $table_name_index (emoi,scl,High_alpha,gamma,t) VALUES ";
            $str_ = "insert into $table_name_tags(studioeventdata,tags_t) VALUES ";

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
                  $str = "insert into $table_name_index (emoi,scl,High_alpha,gamma,t) VALUES ";
            }

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
            $str = substr($str,0,strlen($str)-1);
            $insert_table = Db::execute($str);

            //写入tags数据
            foreach ($data2 as $key=>$value){
                    $tags = $value['H'];
                    $t = $value['D'];
                    if ($key==1){
                        $str_ = "insert into $table_name_tags(studioeventdata,tags_t) VALUES ";
                    }else{
                        $str_ .="('$tags','$t'),";
                    }
                }
            $str_ = substr($str_,0,strlen($str_)-1);
            $insert_table = Db::execute($str_);

              if ($insert_table){
                  $res_arr['sta'] = 1;
                  $data = json_encode($res_arr);
                  echo $data;
              }



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
            $arr_select = Db::query("select emoi , scl , High_alpha , gamma from $table_name_index WHERE t>='$t1'AND t<=$t2");
            $arr_emoi = array();
            $arr_scl = array();
            $arr_high_a = array();
            $arr_gamma = array();
            foreach ($arr_select as $key=>$value){
                array_push($arr_emoi,$value['emoi']);
                array_push($arr_scl,$value['scl']);
                array_push($arr_high_a,$value['High_alpha']);
                array_push($arr_gamma,$value['gamma']);
            }
            $emoi = array_sum($arr_emoi)/($t2-$t1);
            $scl = array_sum($arr_scl)/($t2-$t1);
            $high_a = array_sum($arr_high_a)/($t2-$t1);
            $gamma = array_sum($arr_gamma)/($t2-$t1);

//            print_r($arr_emoi);
            echo $emoi;
            echo '<br / >';
            echo $scl;
            echo '<br / >';
            echo $high_a;
            echo '<br / >';
            echo $gamma;
            echo '<br / >';
            echo $tag;
            echo '<br / >';
        }



//        print_r($arr_result);

//        foreach ($tag_name as $key=>$value){
//            $re = array("-s","-e");
//            $tag_tip = str_replace($re,"",$value);
//            array_push($tag_n,$tag_tip);
//        }
//        print_r(array_unique($tag_n));
    }

//用户和游戏设置
    public function testerandgame(){
        //给页面上添加游戏类型的变量
        $game_type = Db::query('select id,value from attr where name = "game_type"');
        $this->assign('game_type',$game_type);
        //终端类型
        $terminal_type = Db::query('select id,value from attr where name = "terminal_type"');
        $this->assign('terminal_type',$terminal_type);
        //性别
        $sex = Db::query('select id,value from attr where name = "sex_type"');
        $this->assign('sex',$sex);
        //年龄
        $age_group = Db::query('select id,value from attr where name = "age_type"');
        $this->assign('age_group',$age_group);
        //游戏经历
        $game_experience = Db::query('select id,value from attr where name = "game_experience"');
        $this->assign('game_experience',$game_experience);
        //游戏年限
        $game_year = Db::query('select id,value from attr where name = "game_year"');
        $this->assign('game_year',$game_year);
        return $this->fetch();
    }



    public function reload(Request $request){
            if ($request->post('name')=='set'){                                         //根据按钮的属性页面重定向
                echo "http://localhost/tp5/public/index.php/demo/hello/setattr.html";
            }
            //else

    }
    public function guid(){                                                             //生成GUID函数
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = // "{"  没了
                 substr($charid, 0, 8)
                .substr($charid, 8, 4)
                .substr($charid,12, 4)
                .substr($charid,16, 4)
                .substr($charid,20,12)
                ;// "}"   没了，手动删除
            return $uuid;
        }
    }
    //添加字段
    public function addAttr(Request $request){

        $res = array_keys($request->post())[0];
        $name = '';
        switch ($res){
            case 'game_type': $name = 'game_type';
                break;
            case 'terminal_type': $name = 'terminal_type';
                break;
            case 'sex': $name = 'sex_type';
                break;
            case 'age_group': $name = 'age_type';
                break;
            case 'game_experience': $name = 'game_experience';
                break;
            case 'game_year': $name = 'game_year';
                break;
        }

        $value = array_values($request->post())[0];
        $check = Db::query("select count(name) as num from attr where value = '$value'");
       // print_r($check[0]['num']);
        if ($check[0]['num'] > 0){
            $res_arr['sta'] = 2;
            $data = json_encode($res_arr);
            echo $data;
        }else{
            $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
            if ($result){
                $res_arr['sta'] = 1;
                $data = json_encode($res_arr);
                echo $data;
            }
        }

    }


//删除字段
    public function deleteAttr(Request $request){
        $id = $request->post('id');
        $result = Db::execute("delete from attr where id = '$id'");
        if($result){
            return "删除成功o(￣▽￣)d";
        }
    }

//添加被试信息
    public function addTester(Request $request){
        $tester_name = $request->post('tester_name');
        $sex_id = $request->post('sex');
        $age_group = $request->post('age_group');
        $game_experience = $request->post('game_experience');
        $game_year = $request->post('game_year');
        $result = Db::execute("insert into tester (u_name,u_sex_id,u_age_id,u_year_id,u_experience_id) values
                              ('$tester_name','$sex_id','$age_group','$game_year','$game_experience')");
        if ($result){
            $res_arr['sta'] = 1;
                print_r(json_encode($res_arr));
        }


    }

    //添加游戏信息
    public function addGame(Request $request){
        $game_name = $request->post('game_name');
        $game_type = $request->post('game_type');
        $terminal_type = $request->post('terminal_type');
        $result = Db::execute("insert into game(g_name,g_type,terminal_type_id) values('$game_name','$game_type','$terminal_type')");
        if($result){
            $res_arr['sta'] = 1;
            print_r(json_encode($res_arr));
        }
    }







}
