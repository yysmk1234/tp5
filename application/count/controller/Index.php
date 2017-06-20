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
        $project_id = json_decode($request->post('project_id'));
//        echo $type;
        $str = "SELECT data.game,emoi,scl,High_alpha,gamma,
                            emoi_,scl_,Higt_a_,gamma_,
                            emoi_sd,scl_sd,high_sd,gamma_sd,
                            emoi_sd_,scl_sd_,high_sd_,gamma_sd_,
                            emoi_y,scl_y,high_y,gamma_y,
                            emoi_y_,scl_y_,high_y_,gamma_y_ 
                      FROM data_ls INNER JOIN data ON (data.game = data_ls.game) WHERE data.project_id = '$project_id' ORDER BY ".$type." "."ASC";
        $res = Db::query($str);
        echo json_encode($res);
    }
    public function data_SD(Request $request){
        $group_name = json_decode($request->post('cookie'));
        /**
         * 找出对应组的tag
         * */
        $str_tag = "SELECT tag FROM data_ 
                INNER JOIN group_test ON (data_.id = group_test.test_id)
                INNER JOIN group_ ON (group_test.group_id = group_.group_id)
                WHERE group_.group_name = '$group_name'";
        $res_tag = Db::query($str_tag);
        $tag = $res_tag[0]['tag'];

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
        $this->set_sdData($emoi_sd,$scl_sd,$high_alpha_sd,$gamma_sd,$tag);
        echo json_encode($data);
//        var_dump($res);

    }
    public function xie_lv(Request $request){
        $group_name = json_decode($request->post('cookie'));
        /**
         * 找出对应组的tag
         * */
        $str_tag = "SELECT tag FROM data_ 
                INNER JOIN group_test ON (data_.id = group_test.test_id)
                INNER JOIN group_ ON (group_test.group_id = group_.group_id)
                WHERE group_.group_name = '$group_name'";
        $res_tag = Db::query($str_tag);
        $tag = $res_tag[0]['tag'];


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
            $data_emoi = $this->get_Y($this->min_max($this->move_ave($this->array_cook($this->get_data("emoi",$value['data_name'])),50)));
            $data_scl = $this->get_Y($this->min_max($this->move_ave($this->array_cook($this->get_data("scl",$value['data_name'])),50)));
            $data_high = $this->get_Y($this->min_max($this->move_ave($this->array_cook($this->get_data("high",$value['data_name'])),50)));
            $data_gamma = $this->get_Y($this->min_max($this->move_ave($this->array_cook($this->get_data("gamma",$value['data_name'])),50)));

            array_push($emoi_arr,$data_emoi);
            array_push($scl_arr,$data_scl);
            array_push($high_arr,$data_high);
            array_push($gamma_arr,$data_gamma);
        }
        $data = [
            'emoi'=>$this->average($emoi_arr),
            'scl'=>$this->average($scl_arr),
            'high_a'=>$this->average($high_arr),
            'gamma'=>$this->average($gamma_arr)
        ];
        $this->set_xlData($data['emoi'],$data['scl'],$data['high_a'],$data['gamma'],$tag);
        echo json_encode($data);

    }
}
