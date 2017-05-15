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
        $str = "SELECT game,emoi,scl,High_alpha,gamma,emoi_,scl_,Higt_a_,gamma_,
                emoi_sd,scl_sd,high_sd,gamma_sd,emoi_sd_,scl_sd_,high_sd_,gamma_sd_ FROM data_ls ORDER BY ".$type." "."ASC";
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
        $emoi_arr = array();
        $scl_arr = array();
        $high_arr = array();
        $gamma_arr = array();

        foreach ($res as $key=>$value){
            $data_sd_emoi = $this->Standard_Deviation($this->get_data("emoi",$value['data_name']));
            $data_sd_scl = $this->Standard_Deviation($this->get_data("scl",$value['data_name']));
            $data_sd_high = $this->Standard_Deviation($this->get_data("high",$value['data_name']));
            $data_sd_gamma = $this->Standard_Deviation($this->get_data("gamma",$value['data_name']));
            array_push($emoi_arr,$data_sd_emoi);
            array_push($scl_arr,$data_sd_scl);
            array_push($high_arr,$data_sd_high);
            array_push($gamma_arr,$data_sd_gamma);
        }
        $emoi_sd =  $this->average($emoi_arr);
        $scl_sd = $this->average($scl_arr);
        $high_alpha_sd = $this->average($high_arr);
        $gamma_sd = $this->average($gamma_arr);

        $data = [
            'emoi'=>$emoi_sd,
            'scl'=>$scl_sd,
            'high_a'=>$high_alpha_sd,
            'gamma'=>$gamma_sd
        ];
        $this->set_sdData($emoi_sd,$scl_sd,$high_alpha_sd,$gamma_sd,$group_name);
        echo json_encode($data);
//        var_dump($res);

    }
    public function xie_lv(Request $request){
        $group_name = json_decode($request->post('cookie'));
        $str = "SELECT data_name FROM test
                INNER JOIN data_ ON (data_.test_id = test.test_id)
                INNER JOIN group_test ON (data_.id = group_test.test_id)
                INNER JOIN group_ ON (group_test.group_id = group_.group_id)
                WHERE group_.group_name = '$group_name'";
        $res = Db::query($str);
        $emoi_arr = array();
        $scl_arr = array();
        $high_arr = array();
        $gamma_arr = array();


        $arr = [1,2,3,4,5];
        $this->move_ave($arr,2);

    }
}
