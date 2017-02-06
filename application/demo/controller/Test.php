<?php
namespace app\demo\controller;

use think\Controller;
use think\Request;

class Test extends Hello
{
    public function test(){
        $guid = $this->guid();
        $this->assign('guid',$guid);
        return $this->fetch();
    }
}
