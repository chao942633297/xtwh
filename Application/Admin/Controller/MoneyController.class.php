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

    #分销佣金列表
    public function commission(){
        $user   = M('user2')->field("id,nickname,phone,headimg")->select();
        $money  = M('backmoney')->field("id,u2id,money")->where("money > 0 ")->select();
        foreach ($user as $k => $v) {
            $user[$k]['allmoney'] = 0;
            foreach ($money as $k1 => $v1) {
                if ($v["id"] == $v1["u2id"]) {
                    $user[$k]["allmoney"] += $v1["money"];
                }
            }
        }       
        $summoney = 0;
        foreach ($user as $ke => $va) {
            $summoney += $va["allmoney"];
        }
        $this->assign("summoney",$summoney);
        $this->assign("data",json_encode($user));
        $this->display();
    }
    //分销佣金查询
    public function search(){
        $phone = I('phone');
        if (!empty($phone)) {
            $where["phone"] = array("like","%".$phone."%");
        }
        $user   = M('user2')->field("id,nickname,phone,headimg")->where($where)->select();
        foreach ($user as $k => $v) {
            $allmoney = M('backmoney')->where("u2id = ".$v["id"]." and money >0 ")->getField("sum(money)");
            if ($allmoney == null || $allmoney == false) {
                $allmoney = 0;
            }
            $user[$k]["allmoney"] = $allmoney;
        } 
        $this->ajaxReturn($user);
    }


    #直营/非直营 充值列表
    public function OneAccount(){
        $type = I('type');
        if ($type == 1 ) {
            $message = "直营余额充值";
        }else{
            $message = "非直营余额充值";
        }
        $order  = M('order')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->where("message='".$message."' and money >0 and status =2 ")->select();
        if (empty($order) || $order ==false) {
            $order = [];
            $summoney = 0;
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
            $summoney = 0;
            foreach ($order as $ke => $va) {
                $summoney += $va["money"];
            }
        }

        $this->assign("summoney",$summoney);
        $this->assign("type",$type);
        $this->assign("data",json_encode($order));
        $this->display();
    }

    # 直营 ,非直营 查询
    public function oneSearch(){
        $type    = I('type');
        $paytype = I('paytype');
        $where1  =[]; 
        if ($type == 1 ) {
            $where["message"] = "直营余额充值";
        }else{
            $where["message"] = "非直营余额充值";
        } 
        if ($paytype != 1) {
            $where["paytype"] = $paytype;
        }

        $start   = strtotime(I('start'));
        $end     = strtotime(I('end'));
        $time    = $start.",".$end;
        $phone   = I('phone');
        if (empty($start) || empty($end) ) {
            if ( empty($start) && empty($end) ) {
            }else{
                $this->ajaxReturn(["status"=>0,'msg'=>'起始,结束时间不准为空']);                
            }
        }else{
            $where["create_at"] = array("between",$time); 
        }
        if (!empty($phone)) {
            $where1["phone"] = array("like","%".$phone."%");
        }

        $order  = M('order')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->where(" money >0 and status =2 ")->where($where)->select();
        if (empty($order) || $order ==false) {
            $order = [];
        }else{
            $userid = M('order')->where(" money >0 and status =2 ")->where($where)->getField("u2id",true);
            $userid = array_unique($userid);     
            $where1["id"] = array("in",$userid);
            $userinfo = M('user2')->field("headimg,nickname,phone,id")->where($where1)->select(); 
            foreach ($userinfo as $k1 => $v1) {
                foreach ($order as $k => $v) {
                    if ($v["u2id"] == $userinfo[$k1]["id"]) {
                        $order[$k]["nickname"] = $userinfo[$k1]["nickname"];
                        $order[$k]["phone"] = $userinfo[$k1]["phone"];
                    }
                }
            }             
        }
        $data = [];
        foreach ($order as $key => $value) {
            if ( $value["phone"] != "" ) {
                $data[] = $value;
            }
        }
        $this->ajaxReturn(["status"=>1,"data"=>$data]);
    }


    #合作机构列表
    public function organization(){
        // var_dump($course);
        $this->display();
    }














}