<?php
namespace app\count\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\Url;
class Index
{
    public function count(Request $request){
        $type = json_decode($request->post('type'));
//        echo $type;
        $str = "SELECT game,emoi,scl,High_alpha,gamma,emoi_,scl_,Higt_a_,gamma_ FROM data_ls ORDER BY ".$type." "."ASC";
        $res = Db::query($str);
        echo json_encode($res);
    }

}
