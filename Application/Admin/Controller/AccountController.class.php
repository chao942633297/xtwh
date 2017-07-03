<?php
namespace Admin\Controller;
use Think\Controller;

class AccountController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    #佣金列表
    public function commissionIndex(){
        $comm = M('backmoney')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->select();  //所有佣金记录        
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

        // var_dump($comm);
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
            $where["create_at"] = array("between",$time); 
        }
        // if (!empty($phone)) {
            $where1["phone"] = array("like","%".$phone."%");
        // }
        $comm = M('backmoney')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->where($where)->select();  //所有佣金记录   

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
        $data = [];
        foreach ($comm as $key => $value) {
            if ($value["phone"] != "" ) {
                $data[] = $value;
            }
        }
        $this->ajaxReturn(["status"=>1,"data"=>$data]);       
    }

    /**
     *
     * 导出Excel -- 例子
     */
    public function getExcel(){//导出Excel
        $xlsName  = "佣金列表";
        $xlsCell  = array(
        array('id','账号序列'),
        array('nickname','昵称'),
        array('phone','手机号'),
        array('money','佣金金额'),
        array('message','佣金来源'),
        array('createtime','时间')
        );
        $comm = M('backmoney')->field("id,money,message,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->select();  //所有佣金记录        
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
        // var_dump($comm);
        getExcel($xlsName,$xlsCell,$comm);
    }

}