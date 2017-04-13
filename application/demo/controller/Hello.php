<?php
namespace app\demo\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
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
            $obj_excel_tag = PHPExcel_IOFactory::load($path_tag. "\\" . $info2->getFilename());
            //设置文件权限
//            chmod($path . "\\" . $info1->getFilename(),0777);
//            chmod($path_tag. "\\" . $info2->getFilename(),0777);
            //index文件数组集合
            $data1 = $obj_excel_index->getActiveSheet()->toArray(null,true,true,true);
            //tags文件数组集合
            $data2 = $obj_excel_tag->getActiveSheet()->toArray(null,true,true,true);
//            echo count($data1);
            //文件删除
//            unlink($path . "\\" . $info1->getFilename());
//            unlink($path_tag. "\\" . $info2->getFilename());
            //获取index文件的表名称
            $table_name_index = $this->guid();
            //获取tags表名称
            $table_name_tags = $this->guid();

            //创建一个存放index数据的table
            $create_index = Db::execute("create table $table_name_index (emoi FLOAT(20),scl FLOAT(20),High_alpha FLOAT(20),gamma FLOAT(20),t INT(10))ENGINE=InnoDB DEFAULT CHARSET=gbk;");
            $create_tags = Db::execute("create table $table_name_tags (studioeventdata VARCHAR(200), tags_t INT(10))ENGINE=InnoDB DEFAULT CHARSET=UTF8;");
            //将数据存入测试表中
            $insert_test = Db::execute("insert into test (u_id , g_id , status_ ,test_name,data_name,tag_name) VALUES ('$tester','$game','$status','$test_name','$table_name_index','$table_name_tags')");


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
                array_push($arr_emoi,$value['emoi']);
                array_push($arr_scl,$value['scl']);
                array_push($arr_high_a,$value['High_alpha']);
                array_push($arr_gamma,$value['gamma']);
            }
            if (($t2-$t1) != 0){
                $emoi = array_sum($arr_emoi)/($t2-$t1);
                $scl = array_sum($arr_scl)/($t2-$t1);
                $high_a = array_sum($arr_high_a)/($t2-$t1);
                $gamma = array_sum($arr_gamma)/($t2-$t1);
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
                echo "http://localhost/tp5/public/index.php/demo/hello/setattr.html";
            }else{
                echo "http://localhost/tp5/public/index.php/demo/hello/project.html";
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
        $group_name = $request->post('project_name');
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



    //这个函数有bug，需要修改
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
        $str = "SELECT * FROM project WHERE project_name = '$project_name'";
        $del_project = Db::query($str);
//        print_r($del_project);
        return 1;
    }


    //sort_new
    function sortNew(){
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

    function sortDataNew(Request $request){
        return 1;
    }


}
