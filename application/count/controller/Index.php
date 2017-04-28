<?php
namespace app\count\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\Url;
use app\count;
class Index extends Ufunction
{
    public function count(Request $request){
        $type = json_decode($request->post('type'));
//        echo $type;
        $str = "SELECT game,emoi,scl,High_alpha,gamma,emoi_,scl_,Higt_a_,gamma_ FROM data_ls ORDER BY ".$type." "."ASC";
        $res = Db::query($str);
        echo json_encode($res);
    }
    public function data_SD(Request $request){
        $group_name = json_decode($request->post('cookie'));
        $str = "SELECT data_name FROM test
                INNER JOIN data_ ON (data_.test_id = test.test_id)
                INNER JOIN group_test ON (data_.id = group_test.test_id)
                INNER JOIN group_ ON (group_test.group_id = group_.group_id)
                WHERE group_.group_name = '$group_name'";
        $res = Db::query($str);
//        echo $group_name;

//        var_dump($r);

    }
}
