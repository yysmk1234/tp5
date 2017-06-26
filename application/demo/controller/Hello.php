<?php
namespace app\demo\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\Url;
use PHPExcel_IOFactory;


class Hello extends Controller
{
    public $res_arr = array(
    'sta'=>"",
    );

    public $emoi_sort = array();

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

//            $obj_excel_tag = PHPExcel_IOFactory::load($path_tag. "\\" . $info2->getFilename());
            $obj_excel_tag = PHPExcel_IOFactory::createReader('CSV')->setInputEncoding('GBK');
            $obj_excel_tag_ = $obj_excel_tag->load($path_tag. "\\" . $info2->getFilename());
            //设置文件权限
//            chmod($path . "\\" . $info1->getFilename(),0777);
//            chmod($path_tag. "\\" . $info2->getFilename(),0777);
            //index文件数组集合
            $data1 = $obj_excel_index->getActiveSheet()->toArray(null,true,true,true);
            //tags文件数组集合
            $data2 = $obj_excel_tag_->getActiveSheet()->toArray(null,true,true,true);
//            echo count($data1);
//            var_dump($data2);
//            return false;
            //文件删除
//            unlink($path . "\\" . $info1->getFilename());
//            unlink($path_tag. "\\" . $info2->getFilename());
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
                echo Url::build('demo/hello/setattr');
            }else{
                echo Url::build('demo/hello/project');
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

//项目页面
    public function project(){
        $project_name = Db::query("select project_name from project");
        $this->assign("project_name",$project_name);
        return $this->fetch();
    }
//添加项目
    public function addProject(Request $request){
        $project_name = $request->post('project_name');
        $insert = Db::execute("insert into project (project_name) VALUE ('$project_name')");
        if ($insert){
            $res_arr['sta'] = 1;
            $data = json_encode($res_arr);
            echo $data;
        }
    }
//分组页面
    public function group(){
        //从cookie获取项目的名称并发送到前端（其实可以前端直接获取）
        $project_n = Cookie::get('project_name');
        $this->assign("project_n",$project_n);
        //获取项目的ID值发送到页面，便于以后的操作
        $project = Db::query("select project_id from project WHERE project_name = '$project_n'");
        $project_id = $project[0]['project_id'];
        $this->assign("project_id",$project_id);
        $group_name = Db::query("select group_name from group_ WHERE group_id IN (SELECT group_id FROM project_group WHERE project_id = '$project_id')");
        $this->assign("group_name",$group_name);

        return $this->fetch();
    }
//添加分组
    public function addGroup(Request $request){
        $insert = '';
        //把分组名称写入group_ 表
        $group_name = $request->post('group_name');
        $insert = Db::execute("insert into group_ (group_name) VALUE ('$group_name')");
        //分别获取分组的ID和项目的ID写入project_group 表
        $project_n =  Cookie::get('project_name');
        $project = Db::query("select project_id from project WHERE project_name = '$project_n'");
        $group = Db::query("select group_id from group_ WHERE group_name ='$group_name'");
        $group_id = $group[0]['group_id'];
        $project_id = $project[0]['project_id'];
        $insert = Db::execute("insert into project_group (project_id,group_id) VALUES ('$project_id','$group_id')");
        if ($insert){
            $res_arr['sta'] = 1;
            $data = json_encode($res_arr);
            echo $data;
        }
    }

//排序页面
    public function sort(){
        //选择被试
        $tester = Db::query("select u_id,u_name from tester ");
        $this->assign('tester',$tester);
        //选择游戏
        $game = Db::query("select g_id,g_name from game");
        $this->assign('game',$game);

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
        //测试顺序
        $status = Db::query("select DISTINCT status_ from test");
        $this->assign('status',$status);

        $group_name = Cookie::get("group_name");
        $this->assign("group_n",$group_name);
        $res = Db::query("select group_id from group_ WHERE group_name = '$group_name'");
        $group_id = $res[0]['group_id'];

        $data = Db::query("select game.g_name,data_.emoi,data_.scl,data_.High_alpha,data_.gamma,data_.tag 
                     FROM data_ LEFT JOIN test ON(data_.test_id = test.test_id) 
                     LEFT JOIN game ON (test.g_id = game.g_id) WHERE data_.id IN 
                     (SELECT test_id FROM group_test WHERE group_id = '$group_id')");
        $this->assign("data",$data);


        return $this->fetch();

    }


    //
    //
    //这个函数有bug，需要修改*******************************************************************************************
    //
    //
    public function sortData(Request $request){
        //选择数据

        $sex = $request->post('sex');
        $age = $request->post('age_group');
        $game_e = $request->post('game_experience');
        $game_y = $request->post('game_year');
        $status = $request->post('status');
        //无条件筛选
        $str1 = "";
        $str2 = "";
        if($sex == 0){
            $str1 .= "u_age_id = '$age' AND u_experience_id = '$game_e' AND u_year_id='$game_y' AND";
        }elseif($age == 0 && $sex == 0){
            $str1 .="u_experience_id = '$game_e' AND u_year_id='$game_y' AND";
        }elseif ($game_e ==0 && $age == 0 && $sex == 0){
            $str1 .="u_year_id='$game_y' AND";
        }elseif ($game_y ==0 && $game_e ==0 && $age == 0 && $sex == 0){
            $str1 .="";
        }else{
            $str1 .= "u_sex_id = '$sex' AND u_age_id = '$age' AND u_experience_id = '$game_e' AND u_year_id='$game_y' AND";
        }

        $str1 = substr($str1,0,strlen($str1)-3);
        $str_select = "WHERE " . $str1;
        //status判断
        if($status == 0 ){
            $str2 .= "";
        }else{
            $str2 .= "status_ = '$status' AND";
        }

        $data_choose = Db::query("select game.g_name,data_.id,data_.emoi,data_.scl,data_.High_alpha,data_.gamma,data_.tag 
                                          from game,data_ 
                                WHERE game.g_id IN (SELECT g_id FROM test WHERE test_id = data_.test_id) AND data_.test_id IN 
                                (SELECT test_id FROM test WHERE ".$str2." u_id IN (SELECT u_id FROM tester "." $str_select))");
        $this->assign("data_c",$data_choose);

        echo json_encode($data_choose);
    }
    //数据筛选
    public function datachoose(Request $request){
        //选择被试
        $tester = Db::query("select u_id,u_name from tester ");
        $this->assign('tester',$tester);
        //选择游戏
        $game = Db::query("select g_id,g_name from game");
        $this->assign('game',$game);

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
        //测试顺序
        $status = Db::query("select DISTINCT status_ from test");
        $this->assign('status',$status);

//        print_r($request->post());
        return $this->fetch();
    }

    //添加测试数据到组中
    public function addData(Request $request){
//        print_r($request->post());
        $group_name = Cookie::get("group_name");
        $res = Db::query("select group_id from group_ WHERE group_name = '$group_name'");
        $group_id = $res[0]['group_id'];
//        print_r($group_id);
        $data = json_decode($request->post('id'));
        print_r($data);
        foreach ($data as $key=>$value){
            $res = Db::query("select count(id) AS id from group_test WHERE group_id = '$group_id'AND test_id = '$value'");
//            print_r($res);
            if ($res[0]['id'] == 0){
                $insert = Db::execute("insert into group_test (group_id,test_id) VALUES ('$group_id','$value')");
                //此处的test_id是表中的ID
            }
        }

    }


    //数据计算
    public function data_get(Request $request){
        $group_name = Cookie::get("group_name");
        $this->assign("group_n",$group_name);
        $res = Db::query("select group_id from group_ WHERE group_name = '$group_name'");
        $group_id = $res[0]['group_id'];
        $data = Db::query("select emoi,scl,High_alpha,gamma from data_ WHERE id in (SELECT test_id FROM group_test WHERE group_id = '$group_id') ");
        //        $emoi_sort = array();
        $count_n = $request->post('count_n');
        $emoi_sort = array();
        $scl_sort = array();
        $high_a_sort = array();
        $gamma_sort = array();

        //把数据分类添加到数组中
        foreach ($data as $key=>$value){
            array_push($emoi_sort,$value['emoi']);
            array_push($scl_sort,$value['scl']);
            array_push($high_a_sort,$value['High_alpha']);
            array_push($gamma_sort,$value['gamma']);
        }
        //对数组进行排序
        sort($emoi_sort);
        sort($scl_sort);
        sort($high_a_sort);
        sort($gamma_sort);

//        print_r($high_a_sort);

        $i = count($emoi_sort)*$count_n/100;
//        echo "--" . $i . "--";
        if (is_int($i)){
            $emoi_k = ($emoi_sort[$i-2]*pow(10,8) + $emoi_sort[$i-1]*pow(10,8))/(2*pow(10,8));
            $scl_k = ($scl_sort[$i-2]*pow(10,13) + $scl_sort[$i-1]*pow(10,13))/(2*pow(10,13));
            $high_a_k = ($high_a_sort[$i-2]*pow(10,4) + $high_a_sort[$i-1]*pow(10,4))/(2*pow(10,4));
            $gamma_k = ($gamma_sort[$i-2]*pow(10,4) + $gamma_sort[$i-1]*pow(10,4))/(2*pow(10,4));
            $res_arr = array(
                'emoi'=>$emoi_k,
                'scl'=>$scl_k,
                'high_alpha'=>$high_a_k,
                'gamma'=>$gamma_k
            );

            echo json_encode($res_arr);
        }else{
            $j = ceil($i);
            $res_arr = array(
                'emoi'=>$emoi_sort[$j-1],
                'scl'=>$scl_sort[$j-1],
                'high_alpha'=>$high_a_sort[$j-1],
                'gamma'=>$gamma_sort[$j-1]
            );
            echo json_encode($res_arr);
        }
    }

    //删除项目
    function del_project(Request $request){
        $project_name = $request->post('p_n');
//        print_r($project_name) ;
        $str = "SELECT project_id FROM project WHERE project_name = '$project_name'";
        //获取项目ID号
        $project_id = Db::query($str)[0]['project_id'];
        //在删除之前先获取各个表中数据的条数，以便后面的验证是否是删除干净
        $str_count_project = "SELECT COUNT(*) AS num FROM project WHERE project.project_id  = '$project_id'";
        $str_count_group = "SELECT COUNT(*) AS num FROM group_ WHERE group_id IN (SELECT group_id FROM project_group WHERE project_id = '$project_id')";
        $str_count_test = "SELECT COUNT(*) AS num FROM group_test WHERE group_id IN (SELECT group_id FROM project_group WHERE project_id = '$project_id')";
        $str_count_p_g = "SELECT COUNT(*) AS num FROM project_group WHERE project_id = '$project_id'";
        //查询结果数据的记录数
        $count_project = Db::query($str_count_project)[0]['num'];
        $count_group = Db::query($str_count_group)[0]['num'];
        $count_test = Db::query($str_count_test)[0]['num'];
        $count_p_g = Db::query($str_count_p_g)[0]['num'];


        $str_project = "DELETE  FROM project WHERE project.project_id  = '$project_id'";
        $str_group  = "DELETE FROM group_ WHERE group_id IN (SELECT group_id FROM project_group WHERE project_id = '$project_id')";
        $str_test = "DELETE FROM group_test WHERE group_id IN (SELECT group_id FROM project_group WHERE project_id = '$project_id')";
        $str_p_g = "DELETE FROM project_group WHERE project_id = '$project_id'";

        $result_del_group = Db::execute($str_group);
        $result_del_test = Db::execute($str_test);
        $result_del_p_g = Db::execute($str_p_g);
        $result_del_project = Db::execute($str_project);
        if ( $result_del_group == $count_group ){
           if ( $result_del_p_g == $count_p_g){
               if ( $result_del_test == $count_test){
                   if ( $result_del_project == $count_project){
                       return 1;
                   }
                   else return 2;
               }else return 2;
           }else return 2;
        }else return 2;

//        $del_project = Db::query($str);
//        print_r($count_project);
//        print_r($count_group);
//        print_r($count_test);
//        print_r($count_p_g);
//        return 1;
    }
    //删除组内数据
    public function del_group(Request $request){
        $projectName = $request->post('p_n');
        $groupName = $request->post('g_n');
        $str_p_n = "SELECT project_id FROM project WHERE project_name = '$projectName'";
        $projectID = Db::query($str_p_n)[0]['project_id'];
        print_r($projectID);
        $str_g_n = "SELECT group_id FROM project_group WHERE project_id = '$projectID' AND group_id 
                      IN(SELECT group_id FROM group_ WHERE group_name = '$groupName')";

        $groupID = Db::query($str_g_n)[0]['group_id'];
        print_r($groupID);
        //获取数据库中记录的条数
        $str_count_p_g = "SELECT COUNT(*) AS num FROM project_group WHERE group_id = '$groupID'";
        $str_count_test = "SELECT COUNT(*) AS num FROM group_test WHERE group_id = '$groupID'";
        $str_count_g = "SELECT COUNT(*) AS num FROM group_ WHERE group_id = '$groupID'";
        //删除语句
        $str_del_p_g = "DELETE FROM project_group WHERE group_id = '$groupID'";
        $str_del_test = "DELETE FROM group_test WHERE group_id = '$groupID'";
        $str_del_g = "DELETE FROM group_ WHERE group_id = '$groupID'";

        $count_p_g = Db::query($str_count_p_g)[0]['num'];
        $count_test = Db::query($str_count_test)[0]['num'];
        $count_g = Db::query($str_count_g)[0]['num'];

        $result_p_g = Db::execute($str_del_p_g);
        $result_test = Db::execute($str_del_test);
        $result_g = Db::execute($str_del_g);

        if ($result_p_g == $count_p_g){
            if ( $result_test == $count_test){
                if ($result_g == $count_g){
                    return 1;
                }else return 2;
            }else return 2;
        }else return 2;
    }

    //sort_new
    public function sortNew(){
        $game = Db::query("select g_id,g_name from game");
        $this->assign('game',$game);
        $group_name = Cookie::get("group_name");
        $this->assign("group_n",$group_name);
        $res = Db::query("select group_id from group_ WHERE group_name = '$group_name'");
        $group_id = $res[0]['group_id'];

        $data = Db::query("select game.g_name,data_.emoi,data_.scl,data_.High_alpha,data_.gamma,data_.tag 
                     FROM data_ LEFT JOIN test ON(data_.test_id = test.test_id) 
                     LEFT JOIN game ON (test.g_id = game.g_id) WHERE data_.id IN 
                     (SELECT test_id FROM group_test WHERE group_id = '$group_id')");
        $this->assign("data",$data);
       return $this->fetch();
    }

    public function sortDataNew(Request $request){
        $game_id = $request->post('game');
        $tag = $request->post('tag');

//        echo $tag;
        if ($tag == '0'){
            $g_str = "SELECT id,emoi,scl,High_alpha,gamma,tag FROM 
                  data_ INNER JOIN test ON (data_.test_id = test.test_id)
                  WHERE test.g_id = '$game_id'";
        }else{
            $g_str = "SELECT id,emoi,scl,High_alpha,gamma,tag FROM 
                  data_ INNER JOIN test ON (data_.test_id = test.test_id)
                  WHERE test.g_id = '$game_id' AND data_.tag = '$tag'";
        }
        $g_res = Db::query($g_str);
        echo json_encode($g_res);
    }

    public function sortSelect(Request $request){
        $game_id = $request->post('game');
        $g_str = "SELECT distinct tag FROM 
                  data_ INNER JOIN test ON (data_.test_id = test.test_id)
                  WHERE test.g_id = '$game_id'";
        $g_res = Db::query($g_str);
        echo json_encode($g_res);
    }


/**
 * 计算分组的均值
 * */
    function sortDataAve(){
        $game = '';
        $group_name = Cookie::get('group_name');
        $project_name = Cookie::get('project_name');
        $get_projectId = "SELECT project_id FROM project WHERE project_name = '$project_name'";
        $project_res = Db::query($get_projectId);
        $projectId = $project_res[0]['project_id'];
        $str = "SELECT emoi,scl,High_alpha,gamma,tag FROM data_ 
                INNER JOIN group_test ON (data_.id = group_test.test_id)
                INNER JOIN group_ ON (group_test.group_id = group_.group_id)
                WHERE group_.group_name = '$group_name'";
        $sort_res = Db::query($str);
//        echo $group_name;
//        var_dump($sort_res);
        $emoi = array();
        $scl = array();
        $high = array();
        $gamma = array();
        $len = count($sort_res);
//        echo $len;
        foreach ($sort_res as $key=>$value){
             array_push($emoi,$value['emoi']);
             array_push($scl,$value['scl']);
             array_push($high,$value['High_alpha']);
             array_push($gamma,$value['gamma']);
             $tag = $value['tag'];
        }
        $emoi_ave = array_sum($emoi)/$len;
        $scl_ave = array_sum($scl)/$len;
        $high_ave = array_sum($high)/$len;
        $gamma_ave = array_sum($gamma)/$len;
        $data = [
            'emoi'=>$emoi_ave,
            'scl'=>$scl_ave,
            'high_a'=>$high_ave,
            'gamma'=>$gamma_ave,
        ];


        //把均值写入data表
        $str_w = "INSERT INTO data (emoi,scl,High_alpha,gamma,game,project_id)
                   VALUES ('$emoi_ave','$scl_ave','$high_ave','$gamma_ave','$tag','$projectId')";
        $str_check = "SELECT COUNT(game) AS count FROM data WHERE game = '$tag'";
        $str_del = "DELETE FROM data WHERE game = '$tag'";
        $count = Db::query($str_check);
//        var_dump($count);
        if($count[0]['count'] != 0){
            $r = Db::execute($str_del);
            $r_ = Db::execute($str_w);
        }else{
            $r = Db::execute($str_w);
        }

        echo json_encode($data);
    }

    //数据排序页面   （每次加载页面时候都重新新建表，储存排序数据，每次加载页面前，先删除临时表。）
    //取出数据存放在数组里，排序，然后转置key和value的值，读取位置
    function Count(){
        //创建临时表(改成永久表)
        $str_Ctable = "CREATE TABLE data_ls (
                            emoi DECIMAL(25,20),
                            scl DECIMAL(25,20),
                            High_alpha DECIMAL(25,20),
                            gamma DECIMAL(25,20),
                            game  VARCHAR(20),
                            emoi_ INT(4),
                            scl_   INT(4),
                            Higt_a_   INT(4),
                            gamma_   INT(4)
                           )ENGINE=InnoDB DEFAULT CHARSET=gbk";
        $project_id = Cookie::get("project_id");
        //获取data的数据
        $str_select = "SELECT game,emoi,scl,High_alpha,gamma,
                              emoi_sd,scl_sd,high_sd,gamma_sd,
                              emoi_y,scl_y,high_y,gamma_y
                              FROM data WHERE project_id = '$project_id'";
        //清空数据表
//        $ls_tab = Db::execute($str_Ctable);
        $str_del = "DELETE FROM data_ls WHERE 1=1";
        $res_del = Db::execute($str_del);


        $data_select = Db::query($str_select);
//        var_dump($data_select);
        //
        //创建数组分开存放emoi、scl、high alpha、gamma数据
        //
        $emoi_data = array();
        $scl_data = array();
        $high_a_data = array();
        $gamma_data = array();

        $emoi_sd_data = array();
        $scl_sd_data = array();
        $high_sd_data = array();
        $gamma_sd_data = array();

        $emoi_y_data = array();
        $scl_y_data = array();
        $high_y_data = array();
        $gamma_y_data = array();

        foreach ($data_select as $key=>$value){
            array_push($emoi_data,$value['emoi']);
            array_push($scl_data,$value['scl']);
            array_push($high_a_data,$value['High_alpha']);
            array_push($gamma_data,$value['gamma']);
            array_push($emoi_sd_data,$value['emoi_sd']);
            array_push($scl_sd_data,$value['scl_sd']);
            array_push($high_sd_data,$value['high_sd']);
            array_push($gamma_sd_data,$value['gamma_sd']);
            array_push($emoi_y_data,$value['emoi_y']);
            array_push($scl_y_data,$value['scl_y']);
            array_push($high_y_data,$value['high_y']);
            array_push($gamma_y_data,$value['gamma_y']);
        }
//        print_r($emoi_data) ;
        //复制数组
        $emoi_data_copy = $emoi_data;
        $scl_data_copy = $scl_data;
        $high_a_data_copy = $high_a_data;
        $gamma_data_copy = $gamma_data;
        $emoi_sd_copy = $emoi_sd_data;
        $scl_sd_copy = $scl_sd_data;
        $high_sd_copy = $high_sd_data;
        $gamma_sd_copy = $gamma_sd_data;

        $emoi_y_copy = $emoi_y_data;
        $scl_y_copy = $scl_y_data;
        $high_y_copy = $high_y_data;
        $gamma_y_copy = $gamma_y_data;


//数组排序

        rsort($emoi_data_copy);
        rsort($scl_data_copy);
        rsort($high_a_data_copy);
        rsort($gamma_data_copy);

        rsort($emoi_sd_copy);
        rsort($scl_sd_copy);
        rsort($high_sd_copy);
        rsort($gamma_sd_copy);

        rsort($emoi_y_copy);
        rsort($scl_y_copy);
        rsort($high_y_copy);
        rsort($gamma_y_copy);


        $flip_emoi = array_flip($emoi_data_copy);
        $flip_scl = array_flip($scl_data_copy);
        $flip_high = array_flip($high_a_data_copy);
        $flip_gamma = array_flip($gamma_data_copy);

        $flip_emoi_sd = array_flip($emoi_sd_copy);
        $flip_scl_sd = array_flip($scl_sd_copy);
        $flip_high_sd = array_flip($high_sd_copy);
        $flip_gamma_sd = array_flip($gamma_sd_copy);

        $flip_emoi_y = array_flip($emoi_y_copy);
        $flip_scl_y = array_flip($scl_y_copy);
        $flip_high_y = array_flip($high_y_copy);
        $flip_gamma_y = array_flip($gamma_y_copy);


        foreach ($data_select as $value){
            $game = $value['game'];
            $emoi = $value['emoi'];
            $scl = $value['scl'];
            $high_a = $value['High_alpha'];
            $gamma = $value['gamma'];

            $emoi_sd = $value['emoi_sd'];
            $scl_sd = $value['scl_sd'];
            $high_sd = $value['high_sd'];
            $gamma_sd = $value['gamma_sd'];

            $emoi_y = $value['emoi_y'];
            $scl_y = $value['scl_y'];
            $high_y = $value['high_y'];
            $gamma_y = $value['gamma_y'];

            $emoi_ = $flip_emoi[$emoi]+1;
            $scl_ = $flip_scl[$scl]+1;
            $high_ = $flip_high[$high_a]+1;
            $gamma_ = $flip_gamma[$gamma]+1;

            $emoi_sd_ = $flip_emoi_sd[$emoi_sd]+1;
            $scl_sd_ = $flip_scl_sd[$scl_sd]+1;
            $high_sd_ = $flip_high_sd[$high_sd]+1;
            $gamma_sd_ = $flip_gamma_sd[$gamma_sd]+1;

            $emoi_y_ = $flip_emoi_y[$emoi_y]+1;
            $scl_y_ = $flip_scl_y[$scl_y]+1;
            $high_y_ = $flip_high_y[$high_y]+1;
            $gamma_y_ = $flip_gamma_y[$gamma_y]+1;

            $str_in = "INSERT INTO data_ls (
                            game,emoi_,scl_,Higt_a_,gamma_,
                            emoi_sd_,scl_sd_,high_sd_,gamma_sd_,
                            emoi_y_,scl_y_,high_y_,gamma_y_
) VALUES ('$game','$emoi_','$scl_','$high_','$gamma_',
          '$emoi_sd_','$scl_sd_','$high_sd_','$gamma_sd_',
          '$emoi_y_','$scl_y_','$high_y_','$gamma_y_'
)";
            $insert = Db::execute($str_in);

        }
        $str_sel = "SELECT data.game,emoi,scl,High_alpha,gamma,
                            emoi_,scl_,Higt_a_,gamma_,
                            emoi_sd,scl_sd,high_sd,gamma_sd,
                            emoi_sd_,scl_sd_,high_sd_,gamma_sd_,
                            emoi_y,scl_y,high_y,gamma_y,
                            emoi_y_,scl_y_,high_y_,gamma_y_
                    FROM  data_ls INNER JOIN data ON (data.game = data_ls.game)";
        $data = Db::query($str_sel);
        $this->assign("data",$data);
//        print_r($str_del);
        return $this->fetch();
    }



    public function projectCount(){
        $str = "SELECT project_id,project_name FROM project";
        $data = Db::query($str);
        $this->assign("project",$data);
        return $this->fetch();
    }


}
