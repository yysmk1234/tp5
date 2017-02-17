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
        $tester = $request->post('tester');
        $game = $request->post('game');
        $index_file = request()->file('index');
        $info1 = $index_file->move(ROOT_PATH . 'public' . DS . 'uploads');
        $tags_file = request()->file('tags');
        $info2 = $tags_file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info2&&$info1){
              $path = $info1->getPath();
            $obj_excel_index = PHPExcel_IOFactory::load($path . "\\" . $info1->getFilename());

            $data1 = $obj_excel_index->getActiveSheet()->toArray(null,true,true,true);
//            echo count($data1);
//取的表名称
            $table_name = $this->guid();

            //创建一个table
            $create = Db::execute("create table $table_name (emoi FLOAT(20),scl FLOAT(20),High_alpha FLOAT(20),gamma FLOAT(20),t INT(10))ENGINE=InnoDB DEFAULT CHARSET=gbk;");
              $insert_table = '';
              $flag = 0;
              $str = "insert into $table_name (emoi,scl,High_alpha,gamma) VALUES ";

              //数组长度
              $num = count($data1);
              //长度分组
              $num_group  = $num/100;

              $num_yu = $num%100;

//              for ( $i = 0 ;i<= $num_group; $i++){
//                  for ($j = 0 ; j <= 100; $j++){
//                      $num_key = $i*100 +$j;
//                      $emoi = $data1[$num_key]['A'];
//                      $scl = $data1[$num_key]['B'];
//                      $high_a = $data1[$num_key]['C'];
//                      $gammar = $data1[$num_key]['D'];
//                      if ($num_key==0){
//                        $str .= "('$emoi','$scl','$high_a','$gammar')";
//                      }else{
//                        $str .=",('$emoi','$scl','$high_a','$gammar')";
//                      }
//                  }
//            }

            foreach ($data1 as $key=>$value){
                    $emoi = $value['A'];
                    $scl = $value['B'];
                    $high_a = $value['C'];
                    $gammar = $value['D'];
//                    $time = $value['E'];
                    if ($flag == 0){
                        $str .= "('$emoi','$scl','$high_a','$gammar')";
                        $flag ++;
                    } else if($flag <= 99){
                        $str .=",('$emoi','$scl','$high_a','$gammar')";
                        $flag ++;
                    }else{
                        $insert_table = Db::execute($str);
                        $flag = 0;
                        $str = "insert into $table_name (emoi,scl,High_alpha,gamma) VALUES ";
                    }

                }

              if ($insert_table){
                  $res_arr['sta'] = 1;
                  $data = json_encode($res_arr);
                  echo $data;
              }



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
