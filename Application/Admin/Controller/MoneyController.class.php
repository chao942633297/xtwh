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
        $user   = M('user2')->field("id,name,phone")->select();
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
        $name  = I('name');
        if (!empty($phone)) {
            $where["phone"] = array("like","%".$phone."%");
        }
        if (!empty($name)) {
            $where["name"] = array("like","%".$name."%");
        }
        $user   = M('user2')->field("id,name,phone")->where($where)->select();
        if (empty($user) || $user == false) {
            $user = [];
        }else{
            $allmoney = M('backmoney')->field("id,u2id,money")->where(" money >0 ")->select();
            if (empty($allmoney) || $allmoney== false ) {
                $allmoney = [];
            }else{
                foreach ($user as $k => $v) {
                    $user[$k]['allmoney'] = 0;
                    foreach ($allmoney as $k1 => $v1) {
                        if ($v["id"] == $v1["u2id"]) {
                            $user[$k]["allmoney"] += $v1["money"];
                        }
                    }
                }                
            }
             
        }

        $this->ajaxReturn($user);
    }


    #余额充值列表
    public function OneAccount(){
        $order  = M('order')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->where("message='充值余额' and money >0 and status =2 ")->select();
        if (empty($order) || $order ==false) {
            $order = [];
            $summoney = 0;
        }else{
            $userid = M('order')->where("message='充值余额' and money >0 and status =2 ")->getField("u2id",true);
            $userid = array_unique($userid);     
            $where["id"] = array("in",$userid);
            $userinfo = M('user2')->field("headimgurl,name,phone,id")->where($where)->select(); 
            foreach ($userinfo as $k1 => $v1) {
                foreach ($order as $k => $v) {
                    if ($v["u2id"] == $userinfo[$k1]["id"]) {
                        $order[$k]["name"] = $userinfo[$k1]["name"];
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
        $this->assign("data",json_encode($order));
        $this->display();
    }

    # 余额查询
    public function oneSearch(){
        $paytype = I('paytype');
        $where1  =[]; 
        $where["message"] = "充值余额";
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
    	$user2 = M('user2')->field("id,u1id,name,nickname,phone")->where("u1id > 0 ")->select();
    	$user1 = M('user1')->field("id,class,rebate")->where("class = 2 or (class = 1 and pid <= 0 )")->select();
    	if (empty($user2) || $user2 == false) {
    		$user2 = [];
    		$allmoney = 0;
    	}else{
	    	foreach ($user2 as $k => $v) {
	    		foreach ($user1 as $k1 => $v1) {
	    			if ($v1['id'] == $v['u1id']) {
	    				$user2[$k]['class'] = $v1['class'];
	    				$user2[$k]['rebate'] = $v1['rebate'];
	    			}
	    		}
	    	}

	    	$order   = M('order')->field("id,courseid,goodprice,create_at")->where("message='购买课程' and status=2 and courseid > 0 ")->select();
	    	$allmoney = 0;
	    	$course  = M('order')->where("message='购买课程' and status=2 and courseid > 0")->getField("courseid",true);
	    	$course1 = array_unique($course);	//无重复购买的课程ID
	    	$where['id']= ["in",$course1];
	    	$courseinfo = M('course')->field("id,user_id")->where($where)->select();

	    	foreach ($order as $k2 => $v2) {
	    		$allmoney += $v2['goodprice'];
	    		foreach ($courseinfo as $k3 => $v3) {
	    			if ($v3['id'] == $v2['courseid']) {
	    				$order[$k2]['u2id'] = $v3['user_id'];
	    			}
	    		}
	    	}	

	    	foreach ($user2 as $ke => $va) {
	    		$user2[$ke]['allmoney'] = 0;
	    		$user2[$ke]['rebatemoney'] = 0;
	    		foreach ($order as $ke1 => $va1) {
	    			if ($va1['u2id'] == $va['id']) {
	    				$user2[$ke]['allmoney'] += $va1['goodprice'];
	    				$user2[$ke]['rebatemoney'] += sprintf("%.2f", $va1['goodprice'] * ((100 - ($user2[$ke]['rebate'])) / 100 ));
	    			}
	    		}
	    	}    		
    	}

    	$this->assign("all",$allmoney);
    	$this->assign("data",json_encode($user2));
    	$this->display();
    }


    #合作机构查询
    public function organizationSearch(){
    	$start = strtotime(I('start'));
    	$end   = strtotime(I('end'));
    	$time  = $start.",".$end;
    	$user2 = M('user2')->field("id,u1id,name,nickname,phone")->where("u1id > 0 ")->select();
    	$user1 = M('user1')->field("id,class,rebate")->where("class = 2 or (class = 1 and pid <= 0 )")->select();
    	if (empty($user2) || $user2 == false) {
    		$user2 = [];
    		$allmoney = 0;
    	}else{
	    	foreach ($user2 as $k => $v) {
	    		foreach ($user1 as $k1 => $v1) {
	    			if ($v1['id'] == $v['u1id']) {
	    				$user2[$k]['class'] = $v1['class'];
	    				$user2[$k]['rebate'] = $v1['rebate'];
	    			}
	    		}
	    	}
	    	$ww['create_at'] = ["between",$time];
	    	$order   = M('order')->field("id,courseid,goodprice,create_at")->where("message='购买课程' and status=2 and courseid > 0")->where($ww)->select();
	    	$allmoney = 0;
	    	$course  = M('order')->where("message='购买课程' and status=2 and courseid > 0")->getField("courseid",true);
	    	$course1 = array_unique($course);	//无重复购买的课程ID
	    	$where['id']= ["in",$course1];
	    	$courseinfo = M('course')->field("id,user_id")->where($where)->select();

	    	foreach ($order as $k2 => $v2) {
	    		$allmoney += $v2['goodprice'];
	    		foreach ($courseinfo as $k3 => $v3) {
	    			if ($v3['id'] == $v2['courseid']) {
	    				$order[$k2]['u2id'] = $v3['user_id'];
	    			}
	    		}
	    	}	

	    	foreach ($user2 as $ke => $va) {
	    		$user2[$ke]['allmoney'] = 0;
	    		$user2[$ke]['rebatemoney'] = 0;
	    		foreach ($order as $ke1 => $va1) {
	    			if ($va1['u2id'] == $va['id']) {
	    				$user2[$ke]['allmoney'] += $va1['goodprice'];
	    				$user2[$ke]['rebatemoney'] += sprintf("%.2f", $va1['goodprice'] * ((100 - ($user2[$ke]['rebate'])) / 100 ));
	    			}
	    		}
	    	}    		
    	}

    	$dd = ["all"=>$allmoney,"data"=>$user2];
    	$this->ajaxReturn($dd);
    }

    #导出表格---分销佣金列表
    public function getExcelCom(){
        $xlsName  = "分销佣金列表";
        $xlsCell  = array(
        array('id','账号序列'),
        array('name','用户姓名'),
        array('phone','手机号'),
        array('allmoney','佣金总额(元)'),
        );
        $user   = M('user2')->field("id,name,phone")->select();
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
        getExcel($xlsName,$xlsCell,$user);

    }

    #充值余额列表--导出表格
    public function getExcelOne(){
        $xlsName  = "充值余额列表";
        $xlsCell  = array(
        array('name','姓名'),
        array('phone','手机号'),
        array('money','充值金额'),
        array('paytype','充值类型'),
        array('createtime','充值时间')
        );        
        $order  = M('order')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as createtime")->where("message='充值余额' and money >0 and status =2 ")->select();
        if (empty($order) || $order ==false) {
            $order = [];
            $summoney = 0;
        }else{
            $userid = M('order')->where("message='充值余额' and money >0 and status =2 ")->getField("u2id",true);
            $userid = array_unique($userid);     
            $where["id"] = array("in",$userid);
            $userinfo = M('user2')->field("headimg,name,phone,id")->where($where)->select(); 
            foreach ($userinfo as $k1 => $v1) {
                foreach ($order as $k => $v) {
                    if ($v["u2id"] == $userinfo[$k1]["id"]) {
                        $order[$k]["name"] = $userinfo[$k1]["name"];
                        $order[$k]["phone"] = $userinfo[$k1]["phone"];
                    }
                }
            }
            $summoney = 0;
            foreach ($order as $ke => $va) {
                $summoney += $va["money"];
            }
        }        
        getExcel($xlsName,$xlsCell,$order);

    }

    #合作机构对账列表--导出表格
    public function getExcelOrg(){
        $xlsName  = "合作机构对账";
        $xlsCell  = array(
        array('name','姓名'),
        array('nickname','机构/老师'),
        array('class','类别'),
        array('phone','联系电话'),
        array('rebate','合作折扣(%)'),
        array('allmoney','营业额'),
        array('rebatemoney','平台盈利')
        );   
        $user2 = M('user2')->field("id,u1id,name,nickname,phone")->where("u1id > 0 ")->select();
        $user1 = M('user1')->field("id,class,rebate")->where("class = 2 or (class = 1 and pid <= 0 )")->select();
        if (empty($user2) || $user2 == false) {
            $user2 = [];
            $allmoney = 0;
        }else{
            foreach ($user2 as $k => $v) {
                foreach ($user1 as $k1 => $v1) {
                    if ($v1['id'] == $v['u1id']) {
                        if ($v1['class'] == 1) {
                            $user2[$k]['class'] = '老师';
                        }else{
                            $user2[$k]['class'] = '机构';
                        }
                        $user2[$k]['rebate'] = $v1['rebate'];                            
                    }
                }
            }

            $order   = M('order')->field("id,courseid,goodprice,create_at")->where("message='购买课程' and status=2 and courseid > 0 ")->select();
            $allmoney = 0;
            $course  = M('order')->where("message='购买课程' and status=2 and courseid > 0")->getField("courseid",true);
            $course1 = array_unique($course);   //无重复购买的课程ID
            $where['id']= ["in",$course1];
            $courseinfo = M('course')->field("id,user_id")->where($where)->select();

            foreach ($order as $k2 => $v2) {
                $allmoney += $v2['goodprice'];
                foreach ($courseinfo as $k3 => $v3) {
                    if ($v3['id'] == $v2['courseid']) {
                        $order[$k2]['u2id'] = $v3['user_id'];
                    }
                }
            }   

            foreach ($user2 as $ke => $va) {
                $user2[$ke]['allmoney'] = 0;
                $user2[$ke]['rebatemoney'] = 0;
                foreach ($order as $ke1 => $va1) {
                    if ($va1['u2id'] == $va['id']) {
                        $user2[$ke]['allmoney'] += $va1['goodprice'];
                        $user2[$ke]['rebatemoney'] += sprintf("%.2f", $va1['goodprice'] * ((100 - ($user2[$ke]['rebate'])) / 100 ));
                    }
                }
            }           
        }

        getExcel($xlsName,$xlsCell,$user2);

    }





}