<?php
namespace app\demo\controller;

use think\Controller;
use think\Request;
use think\Db;

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
            print_r($data);
        }
        //$result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");

    }


    public function addGametype(Request $request){                     //添加游戏类型
        $value = $request->post('game_type');
        $name = "";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function addTerminal(Request $request){                    //添加游戏终端类型
        $value = $request->post('terminal_type');
        $name = "";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function addSexType(Request $request){                    //添加性别类型
        $value = $request->post('');
        $name = "sex_type";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function addAgeType(Request $request){                    //添加年龄范围
        $value = $request->post('');
        $name = "age_type";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function addGamee(Request $request){                    //添加游戏经历
        $value = $request->post('');
        $name = "game_experience";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function addGameYear(Request $request){                    //添加游戏年限
        $value = $request->post('');
        $name = "game_year";
        $result = Db::execute("insert into attr ( name ,value ) values ('$name','$value')");
        if($result){
            return "提交成功o(￣▽￣)d";
        }
    }
    public function deleteAttr(Request $request){
        $id = $request->post('id');
        $result = Db::execute("delete from attr where id = '$id'");
        if($result){
            return "删除成功o(￣▽￣)d";
        }
    }









}
