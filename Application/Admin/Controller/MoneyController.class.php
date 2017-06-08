<?php
namespace Admin\Controller;
use Think\Controller;

class MoneyController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    #佣金列表
    public function commission(){
        $comm = M('backmoney')->field("*,FROM_UNIXTIME(createtime,'%Y-%m-%d %H:%i:%s') as createtime")->select();  //所有佣金记录        
        $userid = M('backmoney')->getField("u2id",true);
        $userid = array_unique($userid);
        $where["id"] = array("in",$userid);
        $userinfo = M('user2')->where($where)->select();   //所有佣金记录的用户

        foreach ($comm as $k => $v) {
            foreach ($userinfo as $k1 => $v1) {
                if ($v['u2id'] == $v1["id"]) {
                    $comm[$k]["nickname"] = $v1["nickname"];
                    $comm[$k]["phone"] = $v1["phone"]; 
                }
            }
        }
        $this->assign("data",json_encode($comm));
        $this->display();
    }
    //查询
    public function search(){
        $start = strtotime(I('start'));
        $end   = strtotime(I('end'));
        $time = $start.",".$end;
        $phone = I('phone');
        if (empty($start) || empty($end) ) {
            if ( empty($start) && empty($end) ) {
            }else{
                $this->ajaxReturn(["status"=>0,'msg'=>'起始,结束时间不准为空']);                
            }
        }else{
            $where["createtime"] = array("between",$time); 
        }
        // if (!empty($phone)) {
            $where1["phone"] = array("like","%".$phone."%");
        // }
        $comm = M('backmoney')->field("*,FROM_UNIXTIME(createtime,'%Y-%m-%d %H:%i:%s') as createtime")->where($where)->select();  //所有佣金记录   

        if (empty($comm) || $comm == false ) {
            $comm = [];
        }else{
            $userid = M('backmoney')->where($where)->getField("u2id",true);
            $userid = array_unique($userid);
            $where1["id"] = array("in",$userid);
            $userinfo = M('user2')->where($where1)->select();   //所有佣金记录的用户
            foreach ($comm as $k => $v) {
                foreach ($userinfo as $k1 => $v1) {
                    if ($v['u2id'] == $v1["id"]) {
                        $comm[$k]["nickname"] = $v1["nickname"];
                        $comm[$k]["phone"] = $v1["phone"]; 
                    }
                }
            } 
        }

        foreach ($comm as $key => $value) {
            if ($value["phone"] == "" || empty($value["phone"])) {
                unset($comm[$key]);
            }
        }
        $this->ajaxReturn(["status"=>1,"data"=>$comm]);       
    }


    #直营充值列表
    public function OneAccount(){
        $type = I('type');
        if ($type == 1 ) {
            $message = "直营余额充值";
        }else{
            $message = "非直营余额充值";
        }
        $order  = M('order')->field("*,FROM_UNIXTIME(createtime,'%Y-%m-%d %H:%i:%s') as createtime")->where("message='".$message."' and money >0 and status =2 ")->select();
        if (empty($order) || $order ==false) {
            $data = [];
        }else{
            $userid = M('order')->where("message='".$message."' and money >0 and status =2 ")->getField("u2id",true);
            $userid = array_unique($userid);     
            $where["id"] = array("in",$userid);
            $userinfo = M('user2')->field("headimg,nickname,phone,id")->where($where)->select(); 
            foreach ($userinfo as $k1 => $v1) {
                foreach ($order as $k => $v) {
                    if ($v["u2id"] == $userinfo[$k1]["id"]) {
                        $order[$k]["nickname"] = $userinfo[$k1]["nickname"];
                        $order[$k]["phone"] = $userinfo[$k1]["phone"];
                    }
                }
            }             
        }


        $this->assign("data",json_encode($order));
        $this->display();
    }
}