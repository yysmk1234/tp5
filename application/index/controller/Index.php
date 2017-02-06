<?php
namespace app\index\controller;

class Index
{
    public function hello()
    {
        return 'hello,thinkphp!';
    }

    public function test()
    {
        return '这是一个测试方法!';
    }

    protected function hello2()
    {
        return '只是protected方法!';
    }

    private function hello3()
    {
        return '这是private方法!';
    }
}
