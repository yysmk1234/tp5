<?php
/**
 * Created by PhpStorm.
 * User: BP01
 * Date: 2017/6/12
 * Time: 14:53
 */

namespace app\file\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\Url;
use app\count;
class ufunction extends controller
{
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
}