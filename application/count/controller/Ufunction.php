<?php

namespace app\count\controller;

use think\Controller;
use think\Db;
use think\Request;

class Ufunction extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    /**
     * 均值函数
    **/
    public function average($arr){
        $len = count($arr);
        $ave = array_sum($arr)/$len;
        return $ave;
    }
    /**
     * 标准差函数
    **/
    public function Standard_Deviation($arr){
        $len = count($arr);
        $ave = array_sum($arr)/$len;
        $arr_sum = array();
        foreach ($arr as $key=>$value){
            $num1 = array_values($value)[0]-$ave;
            array_push($arr_sum,pow($num1,2));
        }
        return sqrt((array_sum($arr_sum)/$len));
    }
    /**
     * get data
    **/
    public function get_data($type,$table_name){
        switch ($type){
            case 'emoi' : $str = "SELECT emoi FROM $table_name";
                break;
            case 'scl'  : $str = "SELECT scl FROM $table_name";
                break;
            case 'high' : $str = "SELECT High_alpha FROM $table_name";
                break;
            case 'gamma' : $str = "SELECT gamma FROM $table_name";
                break;
        }
        $res = Db::query($str);
        array_splice($res,0,1);
        return $res;
    }
    /**
     * 写入标准差到data表
     * */
    public function set_sdData($emoi_sd,$scl_sd,$high_sd,$gamma_sd,$game){
        $str = "UPDATE data SET emoi_sd = '$emoi_sd',scl_sd = '$scl_sd',high_sd = '$high_sd',gamma_sd = '$gamma_sd' 
                WHERE game = '$game'";
        $res = Db::execute($str);
    }
    /**
     * 写入斜率到data表
     * */
    public function set_xlData($emoi_y,$scl_y,$high_y,$gamma_y,$tag){
        $str = "UPDATE data SET emoi_y = '$emoi_y',scl_y = '$scl_y',high_y = '$high_y',gamma_y = '$gamma_y'
                WHERE game = '$tag'";
        $res = Db::execute($str);
    }
   /**
     *min-max标准化
     * */
   public function min_max($arr){
       $max = max($arr);
       $min = min($arr);
       $arr_ = array();
       foreach ($arr as $key=>$value){
           if(($max-$min) == 0){
               array_push($arr_,0);
           }else{
               array_push($arr_,($value-$min)/($max-$min));
           }
       }
       return $arr_;
       
   }
   /**
    * 移动平均
    **/
   public function move_ave($arr,$length){
       $arr_ = array();
       $len = count($arr) - $length + 1;
       for($i= 0;$i < $len ;$i++){
           array_push($arr_,$this->average(array_slice($arr,$i,$length)));
       }
       return $arr_;
   }
    /**
     * 计算结果y
     * */
   public function get_Y($arr){
       $arr_ = array();
       for ($i = 0 ; $i < count($arr)-1 ; $i++){
           array_push($arr_,abs($arr[$i+1]-$arr[$i]));
       }
       return $this->average($arr_);
   }
   /**
    * 处理查询的数组变成常规数组
    * */
   public function array_cook($arr){
       $arr_  = array();
       foreach ($arr as $key=>$value){
           array_push($arr_,array_values($value)[0]);
       }
       return $arr_;
   }
}
