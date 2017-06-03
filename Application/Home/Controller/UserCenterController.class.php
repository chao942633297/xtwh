<?php
namespace Home\Controller;

use Think\Controller;
use Service\Alipay as Alipays;
use Service\AopClient;
use Payment\NotifyContext;
use Service\AlipayAopnew;
use Service\AlipayTransfer;
use Service\Wechat;
use Service\WechatRe;

class UserCenterController extends Controller {
    # 定义控制器使用表名
    const USER    = 'user';       //用户表
    const SCORE   = 'score';      //积分表
    const ACCOUNT = 'account';    //金钱详情表
    const FANS    = 'fans';       //粉丝表
    const COURSE  = 'course';     //课程表
    const ORDER   = 'order';      //订单表
    const JDJ     = 'jdj';        //见点奖表
    const USERVIP = 'uservip';    //用户购买权限表    
    const COURSE_CLASS = 'course_class';    //课程类别表
    const USERLEVEL    = 'userlevel';      //用户等级表uservip





    //个人中心
    public function myCenter(){
        $id    = $_SESSION['userid'];
        $user  = M(self::USER);
        $level = M(self::USERLEVEL);
        $users = $user->field("id,phone,headimg,pid,name,grade")->where(['id'=>$id])->find();
        $levels= $level->field("id,name,level")->select();

        foreach ($levels as $key => $value) {
            if ($users['grade'] == $value['level']) {
               $users['gradename'] = $value['name'];    
            }          
         } 

        $parentid = $users['pid']; //上级id
        $parent   = $user -> field("name as parentname,phone as parentphone") -> where(['id'=>$parentid]) -> find();
        if ($parent == null || empty($parent) || $parent == "") {
            $userinfo = $users;
            $userinfo['parentphone'] = '';
            $userinfo['parentname'] = '';
        }else{
            $userinfo = array_merge($users,$parent);
        }
        $myhuoban = $user->where(["pid"=>$id])->getField("count(1)");   //我的伙伴
		$children = getLevelUser($id);   //三级所有下级用户    
		if ($children == "" || empty($children) || $children == false) {
			$mystudent = 0;
			$myschool  = 0;
		}else{
	        $where['id']  = array("in",$children);
	        $where1['id'] = array("in",$children); 
	        $mystudent= $user->where("is_cost = 1")->where($where)->getField("count(1)"); //我的同学
	        $myschool = $user->where($where1)->getField("count(1)");    //我的校友
		}

        $data = array("userinfo"=>$userinfo,"myhuoban"=>$myhuoban,"mystudent"=>$mystudent,"myschool"=>$myschool);

        jsonpReturn("1","查询成功",$data);
    } 

    //个人设置
    public function userSetting(){
        $id   = $_SESSION['userid'];
        $user = M('user');
        $users= $user -> field("headimg,phone,rname,name,sex,age,birthday")->where("id=%d",$id)->find();
        jsonpReturn('1',"",array($users));
    } 

    //修改资料--提交
    public function updateUserInfo(){
        $id  = $_SESSION['userid']; 
        $phone = I('phone');
        // $nickname = I('rname');
        $user= M(self::USER);
        if(!preg_match("/^1[34578]\d{9}$/",$phone)){
            jsonpReturn1('0','手机号不规范');
        }        
        $yes = $user->where("phone='%s'",$phone)->getField("id");
        if ($id != $yes) {
        	jsonpReturn("0","此手机号已存在");
        }

        $data['phone']   = I('phone');
        $data['rname']   = I('rname');
        $data['name']    = I('name');
        $data['sex']     = I('sex');
        $data['age']     = I('age');
        $data['headimg'] = I('headimg');
        $data['birthday'] = I('birthday');
        $result = $user->where("id=%d",$id)->save($data);
        if ($result) {
            jsonpReturn('1','修改成功','');
        }else{
            jsonpReturn('0','修改失败','');
        }
    }

    //我的账户
    public function myCount(){
       $id    = $_SESSION['userid'];
        //$id = 5;
        $score  = M(self::SCORE);
        $account= M(self::ACCOUNT);
        $allscores= $score->where("uid=%d and score >=0",$id)->getField("sum(score)");    //累计积分
        $myscores = $score->where("uid=%d",$id)->getField("sum(score)"); //我的积分

        $allmoney = $account->where("uid=%d and money >=0",$id)->getField("sum(money)");    //累计金额
        $mymoney  = $account->where("uid=%d",$id)->getField("sum(money)");  //我的余额

        $shrdetail= $account->field("message,money,createtime")->where("uid=%d and money >=0",$id)->select();
        $zhcdetail= $account->field("message,money,createtime")->where("uid=%d and money < 0",$id)->select();
        foreach ($shrdetail as $k => $v) {
            $shrdetail[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
        }
        foreach ($zhcdetail as $k1 => $v1) {
            $zhcdetail[$k1]['createtime'] = date('Y-m-d H:i:s',$v1['createtime']);
        }        
        $data = array("allscores"=>$allscores,"myscores"=>$myscores,"allmoney"=>$allmoney,"mymoney"=>$mymoney,"shrdetail"=>$shrdetail,"zhcdetail"=>$zhcdetail,);
        jsonpReturn('1','',array($data));
    } 

    //积分兑换
    public function scoreExchange(){
        $id = $_SESSION['userid'];
        $user = M('user')->where(["id"=>$id])->find();
        $time = time() - 60*60*24*30;
        $where['add_time'] = array('GT',$time);
        $vip = M(self::USERVIP)->where(["user_id"=>$id])->where($where)->getField("id");
        if (!empty($vip)) {
            jsonpReturn('0','一月之内不可重复购买','');
        }
        $money = 19;    //购买权限卡金额价格
        $score = 99;    //购买权限卡积分价格
        $paymenttype = I('paymenttype');    //支付方式--(1:微信支付,2:支付宝支付,3:余额支付)
        if (!is_weixin() && $paymenttype == 1) {
            jsonpReturn('0','浏览器不支持微信支付,请选择其他支付方式');
        }    
        // if (is_weixin() && $paymenttype == 2) {
        //         jsonpReturn('0','请在浏览器下打开,进行支付宝支付');
        //  }    
        $account = M(self::ACCOUNT);
        $order   = M(self::ORDER);

        switch ($paymenttype) {
            case 1:   //微信支付
            $order->startTrans(); //开启事务
            $datt['pay_order_num'] = orderNum();
            $datt['uid']    = $id;
            $datt['money']  = $money;
            $datt['status'] = '1';
            $datt['type']   = 5;
            $datt['message']= '购买观看视频权限卡';
            $datt['payment'] = '微信';
            $datt['createtime'] = time();
            $result = $order->add($datt); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                # 定义下单内容
                $data = [
                'body'=>'购买观看视频权限卡',
                'total_fee'=>$money * 100,
                'openid'=> $user['openid'],
                'trade_type'=>'JSAPI',
                'out_trade_no'=>$datt['pay_order_num'],
                'notify_url'=>'http://hr.hongrunet.com/Admin/Wechat/payNotify.html'
                ];
                # 获取JSAPI
                $jsapi = Wechat::get_jsapi_config(['chooseWXPay'],false,false);
               $jsapi = str_replace('timeStamp','timestamp',$jsapi);
                # 获取微信支付配置
                $payConfig = Wechat::ChooseWXPay($data,false); 

                foreach ((array)$payConfig as $key => $value) {
                    $payconfig = $value;
                }
                jsonpReturn('1','',array('jsapi'=>$jsapi,'payConfig'=>$payconfig,"class"=>"微信"));
            }else{
                jsonpReturn('0','下单失败,请刷新页面重试','');
            }                           
              
                break;
            case 2:   //支付宝支付

            $order->startTrans(); //开启事务
            $dat['pay_order_num'] =orderNum();   //生成唯一订单号
            $dat['uid']     = $id;
            $dat['type']    = 5;
            $dat['money']   = $money;
            $dat['status']  = '1';
            $dat['message'] = '购买观看视频权限卡';
            $dat['payment'] = '支付宝';
            $dat['createtime'] = time();
            $result = $order->add($dat); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                $data['order_no'] = $dat['pay_order_num'];
                // $data['amount'] = $money;
                $data['amount'] = 0.01;
                $data['body']   = '视频权限卡';
                $data['subject']= $dat['message'];
                $data['timeout_express'] = (time()+1800);
                $res = Alipays::create($data);  //提交订单 
                jsonpReturn("1","下单成功",array("class"=>"支付宝","res"=>$res));

            }else{
                jsonpReturn('0','下单失败,请刷新页面重试',' ');
            }            

                break;
            case 3:   //余额支付
            $allmoney = M(self::ACCOUNT)->where(['uid'=>$id])->getField('sum(money)');
            $mm = floatval($allmoney) - intval($money);
            if ($mm < 0 ) {
               jsonpReturn('0','余额不够,请切换其他支付方式!',''); 
            }
            $account->startTrans(); //开启事务
            $data['uid']    = $id;
            $data['money']  = -$money;
            $data['source'] = $id;
            $data['message']= '购买权限';
            $data['createtime']= time();
            $data['paymenttype'] = '余额支付';
            $result = $account->add($data); //生成用户消费余额订单
            $dd['user_id'] = $id;
            $dd['add_time'] = time();
            $results= M(self::USERVIP)->add($dd); //购买权限卡写入表中
            if ($result && $results) {
                $account->commit(); //执行事务
                jsonpReturn('1','支付成功','');
            }else{
                $account->rollback();   //回滚事务
                jsonpReturn('0','支付失败','');
            }

                break;  
                                                    
        }
    }


    //用户可用总积分
    public function useYesScore(){
    	$id = $_SESSION["userid"];
    	$score = M(self::SCORE)->where("uid=%d",$id)->getField("sum(score)");
    	jsonpReturn("1","",array("score"=>$score));
    }

    //积分支付购买权限
    public function payScore(){
        $id = $_SESSION['userid'];
    	$score = M(self::SCORE)->where("uid=%d",$id)->getField("sum(score)");
    	$score1 = 99;	
    	if ($score < $score1) {
    		jsonpReturn("0","积分不够");
    	}        
        $user = M('user')->where(["id"=>$id])->find();
        $time = time() - 60*60*24*30;
        $where['add_time'] = array('GT',$time);
        $vip = M(self::USERVIP)->where(["user_id"=>$id])->where($where)->getField("id");
        if (!empty($vip)) {
            jsonpReturn('0','一月之内不可重复购买','');
        }    	

        $scor = M(self::SCORE);
        $scor->startTrans();   //开启事务
        $da['uid'] = $id;
        $da['source'] = $id;
        $da['score'] = -$score1;
        $da['message'] = '购买权限';
        $da['createtime'] = time();
        $res = $scor->add($da); //消费积分写入account表中
        $dd1['user_id'] = $id;
        $dd1['add_time'] = time();
        $vipres= M(self::USERVIP)->add($dd1); //购买权限卡写入表中               
        if ($res && $vipres) {
            $scor->commit();
            jsonpReturn('1','购买成功','');
        }else{
            $scor->rollback();
            jsonpReturn('0','购买失败','');
        }

    }

    //余额提现
    public function accountWithdrawals(){ 
        $id   = $_SESSION['userid'];
        $money= M(self::ACCOUNT)->where('uid=%d',$id)->getField('sum(money)');  //我的余额
        if (!isset($money) || empty($money) || $money == false )  {
            $data = array('money'=>0);
        }
        $data = array('money'=>$money);
        jsonpReturn('1','',$data);
    }

    //确认提现 
    public function withdrawalsYes(){
        $id      = $_SESSION['userid'];
        $account = M(self::ACCOUNT);
        $order   = M(self::ORDER);
        $money= I('money'); 

        if (!isset($money) || empty($money) || $money < 100) {
        // if (!isset($money) || empty($money) || $money < 1) {
            jsonpReturn('0','提现金额有误','');
        }

        $truemoney= $account->where('uid=%d',$id)->getField('sum(money)');  //我的余额
        $mm = floatval($truemoney) - floatval($money) ;
        if ($truemoney <=0 || $mm < 0 ) {   
            jsonpReturn('0','金额不足','');
        }
        //提现订单生成
        $dd['pay_order_num']  = orderNum();
        $dd['uid']  = $id;
        $dd['money']= $money;
        $dd['status']= 3;   //待提现
        $dd['payment']='支付宝';
        $dd['message']= '余额提现';
        $dd['createtime']= time();
        $txmoney = $money * 0.01;	//提现金额(扣除手续费)
        $orders   = $order->add($dd);      //记录保存到order表中
        if ($orders) {
            $osn = $dd['pay_order_num'];    //订单号
            $payee_account = I('account');  //提现账号
            $amount = $txmoney;   	//提现金额
            $zsname = I('zsname');  //提现人真实姓名
            $data = [
                'trans_no' => $osn,
                'payee_type' => 'ALIPAY_LOGONID',
                'payee_account' => $payee_account,
                'amount' => $amount,
                'remark' => $dd['message'],
                'payer_show_name' => $zsname,
                ];
            $msg = AlipayTransfer::querys($data);
            if ($msg->msg == 'Success') {
                $oa["status"] = 4;
                $order->where(["pay_order_num"=>$osn])->save($oa); 
		        $data['uid']    = $id;
		        $data['money']  = -$money;
		        $data['source'] = $id;  
		        $data['message']='余额提现'; 
		        $data['paymenttype']='支付宝';
		        $data['createtime']=time(); 
        		$accounts = $account->add($data);   //记录保存到account表中               	
                jsonpReturn('1','提现成功');
            }else{
            	$account->where("id=%d",$accounts)->delete();
                jsonpReturn('0',$msg->msg);
            }

        }else{
            jsonpReturn('0','申请提交失败!','');
        }

    }

    //我的奖学金 
    public function myMoney(){
        $id   = $_SESSION['userid']; 
        $type = I('type');
        $account = M(self::ACCOUNT);
        $user    = M(self::USER);
        if ($type == 1) {
            $child = getChilden($id,1);
        }else if($type ==2){
            $child = getChilden($id,2);

        }else if($type ==3) {
            $child = getChilden($id,3);
        }else{
            $child = getChilden($id,1);
        }
        $where['source'] = array("in",$child);
        $where1['id'] = array("in",$child);
        $userinfo= $user->field("id,headimg,name as nickname,grade")->where($where1)->select(); 
        $result  = $account->field("source,createtime,money")->where("uid=%d and money>0",$id)->where($where)->select();

        $count = count($userinfo);
        foreach ($result as $k1 => $v1) {
            $result[$k1]['createtime'] = date('Y-m-d H:i:s',$v1['createtime']);
            for ($i=0; $i < $count; $i++) { 
                if ($v1['source'] == $userinfo[$i]['id']) {
                	$result[$k1]['grade']   = $userinfo[$i]['grade'];
                    $result[$k1]['headimg']  = $userinfo[$i]['headimg'];
                    $result[$k1]['nickname'] =$userinfo[$i]['nickname'];
                }
            } 
        }
        jsonpReturn('1','',$result);
    } 



    //我的校友
    public function mySchoolFellow(){
        $id   = $_SESSION['userid']; 
        $user = M(self::USER);
        $type= I('type');
        if ($type == 1) {
            $child = getChilden($id,1);
        }else if($type ==2){
            $child = getChilden($id,2);

        }else if($type ==3) {
            $child = getChilden($id,3);
        }else{
            $child = getChilden($id,1);
        }
        $where['id'] = array("in",$child);
        $all = $user->field("headimg,name,createtime,phone,id,grade")->where($where)->select(); 
        foreach ($all as $key => $value) {
            $all[$key]["createtime"] = date("Y-m-d H:i:s",$value['createtime']);
        }
        jsonpReturn('1','',$all);
    } 

    //推广二维码
    public function userQrcode(){
        $id = $_SESSION['userid'];
        $userinfo = M(self::USER)->field("name,headimg,qrcode")->where('id=%d',$id)->find(); 
        jsonpReturn('1','',$userinfo);
    }

    //晋级学分
    public function promotionCredit(){
        $id = $_SESSION['userid'];
        $result = M(self::USER)->field('headimg,grade')->where('id=%d',$id)->find();
        $userlevel = M(self::USERLEVEL)->field("id,name")->select();
        foreach ($userlevel as $k => $v) {
            if ($result['grade'] == $v['id']) {
                $result['gradename'] = $v['name'];
            }
        }
        jsonpReturn('1','',$result);
    }

    //晋级申请
    public function jjsq(){ 
        $id = $_SESSION['userid'];
        $users = M(self::USER)->where('id=%d',$id)->getField("grade");
        if ($users == 3) {
            jsonpReturn('0','想升级为合伙人,请与客服联系');
        }         
        if ($users == 4) {
            jsonpReturn('0','已是最高等级','');
        }
            jsonpReturn('1','可以申请升级');
    }

    //晋级申请进去
    public function studentUpgrade(){ 
        $id = $_SESSION['userid'];
        $users = M(self::USER)->where('id=%d',$id)->getField("grade");
        $level = M(self::USERLEVEL)->field("id,name,level,money")->select();
        $data  = array();
        $levels= array();
        foreach ($level as $key => $value) {
            if ($users == $value['level']) {
                $data['nowgrade'] = $value['name'];
            }
            if ($users < $value['level'] && $value['level'] !=4) {
                $levels[] = $value;   
            }
        }
        $data['level'] = $levels;
        jsonpReturn('1','',$data);
    }


    //立即支付--晋级申请
    public function paymentApplication(){ 
        $id = $_SESSION['userid'];
        $user  = M(self::USER)->field("id,openid,grade")->where("id=%d",$id)->find(); 
        $level = I('level');    //晋级等级
        if ($level == 4) {
            jsonpReturn('0','晋升为合伙人,请与客服联系');
        }        
        if ($level <= $user['grade'] || $level >4) {
            jsonpReturn('0','晋升等级有误');
        }
        $paymenttype = I('paymenttype');    //支付方式--(1:微信支付,2:支付宝支付,3:余额支付)
        if (!is_weixin() && $paymenttype == 1) {
            jsonpReturn('0','浏览器不支持微信支付,请选择其他支付方式');
        }
        $dj = M(self::USERLEVEL)->field("money,name")->where(['id'=>$level])->find();  //获取晋级等级需要的钱
        $order = M(self::ORDER);
        switch ($paymenttype) {
            case 1:   //微信支付
            $order->startTrans(); //开启事务
            $datt['pay_order_num'] = orderNum();
            $datt['uid']    = $id;
            $datt['money']  = $dj['money'];
            $datt['status'] = '1';
            $datt['type']    = $level;
            $datt['message']= '购买用户等级';
            $datt['payment'] = '微信';
            $datt['createtime'] = time();
            $result = $order->add($datt); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                # 定义下单内容
                $data = [
                'body'=>'购买用户等级',
                'total_fee'=>$dj['money'] * 100,
                'openid'=> $user['openid'],
                'trade_type'=>'JSAPI',
                'out_trade_no'=>$datt['pay_order_num'],
                'notify_url'=>'http://hr.hongrunet.com/Admin/Wechat/payNotify.html'
                ];
                # 获取JSAPI
                $jsapi = Wechat::get_jsapi_config(['chooseWXPay'],false,false);
               $jsapi = str_replace('timeStamp','timestamp',$jsapi);
                # 获取微信支付配置
                $payConfig = Wechat::ChooseWXPay($data,false); 

                foreach ((array)$payConfig as $key => $value) {
                    $payconfig = $value;
                }
                jsonpReturn('1','',array('jsapi'=>$jsapi,'payConfig'=>$payconfig,"class"=>"微信"));
            }else{
                jsonpReturn('0','下单失败,请刷新页面重试','');
            }                   
                break;
            case 2:   //支付宝支付 
            $order->startTrans(); //开启事务
            $dat['pay_order_num'] = orderNum();
            $dat['uid']    = $id;
            $dat['money']  = $dj['money'];
            $dat['status'] = '1';
            $dat['message']= '购买用户等级';
            $dat['payment']= '支付宝';
            $dat['type']   = $level;
            $dat['createtime']   = time();
            $result = $order->add($dat); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                $data['order_no'] = $dat['pay_order_num'];
                // $data['amount'] = $dj['money'];
               $data['amount'] = 0.01;
                $data['body']   = $dj['name'];
                $data['subject']= '购买'.$dj['name'];
                $data['timeout_express'] = (time()+1800);
                $res = Alipays::create($data);  //提交订单 
                jsonpReturn('1','下单成功',array("res"=>$res,"class"=>"支付宝"));
            }else{
                jsonpReturn('0','下单失败,请刷新页面重试',' ');
            }                  
                break;
            case 3:   //余额支付
            $allmoney = M(self::ACCOUNT)->where(['uid'=>$id])->getField('sum(money)');
            $mm = floatval($allmoney) - $dj['money'];
            if ($mm < 0 ) {
               jsonpReturn('0','余额不够,请切换其他支付方式!',''); 
            }
            $account = M(self::ACCOUNT);
            $account->startTrans(); //开启事务
            $data['uid']    = $id;
            $data['money']  = -$dj['money'];
            $data['source'] = $id;
            $data['message']= '升级等级';
            $data['createtime']= time();
            $data['paymenttype'] = '余额支付';
            $result = $account->add($data); //生成用户消费余额订单
            if ($level == 2) {  //升级等级为学霸时,期限为一年,插入购买时间
                $data1['id'] = $id;
                $data1['xbtime'] = time();
                $data1['grade'] = $level;
                $results = M('user')->save($data1);
            }else{
                $dd['grade'] = $level;
                $dd['id']    = $id;
                $results= M(self::USER)->save($dd); //修改用户等级                
            }

            if ($result && $results) {
                $account->commit(); //执行事务
                jsonpReturn('1','支付成功','');
            }else{
                $account->rollback();   //回滚事务
                jsonpReturn('0','支付失败','');
            }

                break;                                            
        }

    }

    //提现记录
    public function withdrawalsLog(){
        $id = $_SESSION['userid'];
        $result = M(self::ACCOUNT)->field("money,message,createtime")->where("uid=%d and source=%d and money <0 and message='余额提现'",$id,$id)->select(); //提现记录
        $allmoney = M(self::ACCOUNT)->where("uid=%d and source=%d and money <0 and message='余额提现'",$id,$id)->getField('abs(sum(money))'); //提现总额  
        foreach ($result as $key => $value) {
            $result[$key]['createtime'] = date("Y-m-d H:i:s",$value['createtime']);        
        }      
        $data = array('allmoney'=>$allmoney,'txlog'=>$result);
        jsonpReturn('1','',$data);        
    }

    //我的直播--未完成
    public function myLiving(){

    }

    //我的粉丝
    public function myFans(){
        $id = $_SESSION['userid'];
        // $id = 1;
        $result = M(self::FANS)->field('gzh,gzhtime,isfans')->where('uid=%d',$id)->select();

        $allid  =''; 
        foreach ($result as $key => $value) {
            $result[$key]['num'] = 0;
            $allid .= $value['gzh'].',';
        }
        $allid = trim($allid,',');
        $where['uid'] = array('in',$allid);
        $where1['id'] = array('in',$allid);
        $userinfo = M(self::USER)->field("id,headimg,name")->where($where1)->select(); //粉丝的信息
        $num = count($userinfo);
        foreach ($result as $k1 => $v1) {
            for ($i=0; $i < $num; $i++) { 
                if ($userinfo[$i]['id'] == $v1['gzh']) {
                    $result[$k1]['headimg']  = $userinfo[$i]['headimg'];
                    $result[$k1]['nickname'] = $userinfo[$i]['name'];
                }
            }
        }

        $results = M(self::FANS)->field('uid,gzhtime')->where($where)->select();   //我的粉丝的所有粉丝
        $count   = count($results);
        foreach ($result as $k => $v) {
            for ($i=0; $i < $count; $i++) { 
                if ($results[$i]['uid'] == $v['gzh']) {
                    $result[$k]['num'] += 1;
                }
            }

        }

        // dump($result);die;
        jsonpReturn('1','',$result);
    }

    //我的关注
    public function myFollow(){
        $id = $_SESSION['userid'];
        // $id = 3;
        $result = M(self::FANS)->field('uid,gzhtime,isfans')->where('gzh=%d',$id)->select();
        $allid  =''; 
        foreach ($result as $key => $value) {
            $result[$key]['num'] = 0;
            $allid .= $value['uid'].',';
        }
        $allid = trim($allid,',');
        $where['uid'] = array('in',$allid);
        $where1['id'] = array('in',$allid);
        $userinfo = M(self::USER)->field("id,headimg,name")->where($where1)->select();  //关注人的信息
        $num = count($userinfo);
        foreach ($result as $k1 => $v1) {
            for ($i=0; $i < $num; $i++) { 
                if ($userinfo[$i]['id'] == $v1['uid']) {
                    $result[$k1]['headimg'] = $userinfo[$i]['headimg'];
                    $result[$k1]['nickname'] = $userinfo[$i]['name'];
                }
            }
        }
        $results = M(self::FANS)->field('uid,gzh,gzhtime')->where($where)->select();
        $count   = count($results);
        foreach ($result as $k => $v) {
            for ($i=0; $i < $count; $i++) { 
                if ($results[$i]['uid'] == $v['uid']) {
                    $result[$k]['num'] += 1;
                }
            }
        }
        jsonpReturn('1','',$result);          
    }

    //点击关注
    public function clickFollow(){
        $gzh    = $_SESSION['userid']; //关注人id
        $uid    = I('id');    //被关注人id
        $aa = M(self::FANS)->where("gzh=".$uid." and uid=".$gzh)->getField("id");
        if ($aa) {
            $data['isfans'] = 1;
            $data['id'] = $aa;
            M(self::FANS)->save($data);
            $userinfo['isfans'] = 1;
        }
        $userinfo['uid'] = $uid;
        $userinfo['gzh'] = $gzh;
        $userinfo['gzhtime'] = time();
        $result  = M(self::FANS)->add($userinfo); 
        if ($result) {
            jsonpReturn('1','关注成功','');
        }else{
            jsonpReturn('0','关注失败','');
        }
    }

    //点击取消关注
    public function clickNoFollow(){
        $gzh    = $_SESSION['userid']; //关注人id
        $uid    = I('id');    //被关注人id
            
        $result = M(self::FANS)->where('uid=%d and gzh=%d',$uid,$gzh)->delete();

        $aa = M(self::FANS)->where("gzh=".$uid." and uid=".$gzh)->getField("id");
        if ($aa) {
            $data['isfans'] = 0;
            $data['id'] = $aa;
            M(self::FANS)->save($data);                    
        }        
        if ($result) {
            jsonpReturn('1','取消关注成功','');
        }else{
            jsonpReturn('0','取消关注失败','');
        }                
    }

    /**
    * 判断是否互相关注
    */
    public function isfans(){
        $id = $_SESSION['userid'];  //用户id
        $iid = I('id'); //被关注人id
        $where['gzh | uid'] = $id;
        $where['gzh | uid'] = $iid;
        $result = M('fans')->where($where)->select();
        dump($result);
        die();
    }



    //我的课程
    public function myCourse(){
        $id = $_SESSION['userid'];
        //$id = 3;
        $result = M(self::COURSE)->field('id,classid,thumbnail,title,createtime,looknum,level,video')->where('uid=%d',$id)->select();   //我的课程
        $class  = M(self::COURSE_CLASS)->field('id,name')->select();    //课程类别 
        $levels= M(self::USERLEVEL)->field("id,name")->select();        
        foreach ($result as $k => $v) {
            $result[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            foreach ($levels as $key => $value) {
                if ($v['level'] == $value['id']) {
                  $result[$k]['levelname'] = $value['name'];                  
                }          
             }             
            foreach ($class as $k1 => $v1) {
                if ($v['classid'] == $v1['id']) {
                    $result[$k]['classname'] = $v1['name'];
                }
            }
        }
        jsonpReturn('1','',$result);

    }  

    //我的积分 
    public function myScore(){
        $id   = $_SESSION['userid'];
        $type = I('type');
        $user  = M(self::USER);
        $score = M(self::SCORE);
        if ($type == 1) {
            $child = getChilden($id,1);
        }else if($type ==2){
            $child = getChilden($id,2);

        }else if($type ==3) {
            $child = getChilden($id,3);
        }else{
            $child = getChilden($id,1);
        }

        $where['source'] = array("in",$child);
        $where1['id'] = array("in",$child);
        $userinfo= $user->field("id,headimg,name,grade")->where($where1)->select();         
        $result = $score->field("source,createtime,score")->where("uid=%d and score > 0 and message='分佣积分'",$id)->where($where)->select();
        $count = count($userinfo);
        foreach ($result as $k1 => $v1) {
            $result[$k1]['createtime'] = date('Y-m-d H:i:s',$v1['createtime']);
            for ($i=0; $i < $count; $i++) { 
                if ($v1['source'] == $userinfo[$i]['id']) {
                    $result[$k1]['grade']    = $userinfo[$i]['grade'];
                    $result[$k1]['headimg']  = $userinfo[$i]['headimg'];
                    $result[$k1]['nickname'] =$userinfo[$i]['name'];
                }
            } 
        }
        jsonpReturn('1','',$result); 
    }


    //见点奖 
    public function PointAward(){
        $id  = $_SESSION['userid'];
        $jdj = M(self::JDJ)->where('uid=%d',$id)->find();

        if (empty($jdj) || $jdj == false) {
            $y = '2017';
            $m = '5';     
            $start = strtotime(date($y.'-'.$m.'-01 00:00:00'));
        }else{
            $start = $jdj['receivetime'];   //上次领取时间戳 
        }  

        # 计算结束时间戳(本月的结束时间戳)
        $end  = time();        
        $time = $start.','.$end;
        $where['createtime'] = array('between',$time);
        $children = M(self::USER)->where('pid=%d and is_cost=1',$id)->where($where)->getField('count(1)');   //新增付费直属下级会员数量

        //获取用户第三级用户后的所有用户
        $allid = getThreeEnd($id,3);    //获取用户  

        if (empty($allid) || $allid == false) {
            $num   = 0;
            $money = 0;
        }else{
            $where1['id'] = array('in',$allid);
            $num  = M(self::USER)->where($where1)->getField('count(1)'); //团队新增人数  
            $where2['createtime'] = array('between',$time);
            $where2['uid & source'] = array('in',$allid);
            $where2['message'] = '会员升级';    
            $money    = M(self::ACCOUNT)->where($where2)->getField('sum(money)'); //新增业绩                        
        }

        $jdjmoney = $money * 0.01;  //见点奖
        jsonpReturn('1','返回见点奖金额',array('jdjmoney'=>$jdjmoney));
    }

    //领取见点奖
    public function myPointAward(){
        $id   = $_SESSION['userid'];
        $user = M(self::USER)->where("id=%d",$id)->getField("grade");
        if ($user < 3 ) {
            jsonpReturn('0','用户等级不够!','');
        }
    
        $jdj = M(self::JDJ)->where('uid=%d',$id)->find();
        if (empty($jdj) || $jdj == false) {
            $y = '2017';
            $m = '5';     
            $start = strtotime(date($y.'-'.$m.'-01 00:00:00'));
        }else{
            $start = $jdj['receivetime'];   //上次领取时间戳 
        }
        # 计算结束时间戳(本月的结束时间戳)
        $end  = time();        
        $time = $start.','.$end;
        $where['createtime'] = array('between',$time);
        $children = M(self::USER)->where('pid=%d and is_cost=1',$id)->where($where)->getField('count(1)');   //新增付费直属下级会员数量
        if ($children <3 ) {
           jsonpReturn('0','直推付费会员不足3人',''); 
        }

        # 计算领取时间(每月十五号之后可领取)
        $nowy = date('Y');
        $nowm = date('m');

        $now = strtotime(date($nowy.'-'.$nowm.'-01 00:00:00'));
        $now += 60*60*24*14;    //当月十五号

        if ($end < $start) {
            jsonpReturn('0','没到领取时间!','');
        }


        //获取用户第三级用户后的所有用户
        $allid = getThreeEnd($id,3);    //获取用户  
        if (empty($allid) || $allid== false ) {
            jsonpReturn('0','不符合领取条件','');
        }
        $where1['id'] = array('in',$allid);
        $num  = M(self::USER)->where($where1)->getField('count(1)'); //团队新增人数
        
        $where2['createtime'] = array('between',$time);
        $where2['uid & source'] = array('in',$allid);
        $where2['message'] = '会员升级';    
        $money    = M(self::ACCOUNT)->where($where2)->getField('sum(money)'); //新增业绩
        
        $jdjmoney = $money * 0.01;  //见点奖
        if ( ( $num >=30 || $money >=1000 ) && $children >=3 ) {
            $account = M(self::ACCOUNT);
            $account->startTrans();
            $data['uid']     = $id;
            $data['source']  = $id;
            $data['money']   = $jdjmoney;
            $data['message'] = '见点奖';
            $data['createtime'] = $end;
            $res = $account->add($data);
            $dd['uid'] = $id;
            $dd['receivetime'] = $end;
            $res1= M(self::JDJ)->add($dd);
            if ($res && $res1) {
                $account->commit();
                jsonpReturn('1','领取成功!','');
            }else{
                $account->rollback();
                jsonpReturn('0','领取失败','');
            }
        }else{
            if ($num < 30) {
                jsonpReturn('0','团队新增人数不足');
            }
            if ($money < 1000) {
                jsonpReturn('0','新增业绩不足');
            }
        }


    }

    //获取验证码
    public function getCode(){
        $phone = I('phone');
        if(!preg_match("/^1[34578]\d{9}$/",$phone)){
            jsonpReturn1('0','手机号不规范');
        }
        if (isset($_SESSION['time'])) {
            if ($_SESSION['time'] <= time()) {
                $_SESSION['time'] = time() + 60;            
                $data = NewSms($phone);  
                $_SESSION['code'] = $data['code'];                   
                jsonpReturn1('1','验证码发送成功!',array('code'=>$data['code']));                     
            }else{
                jsonpReturn1('0','一分钟之内不可重复获得验证码');
            }
        }else{
            $_SESSION['time'] = time() + 60;            
            $data = NewSms($phone);   
            $_SESSION['code'] = $data['code'];                   
            jsonpReturn1('1','验证码发送成功!',array('code'=>$data['code']));  
        }
    }

    //注册
    public function rigister(){
        $phone    = I('phone');
        $password = I('password');
        $rpassword= I('rpassword');
        $pid      = I('pid');
        $code     = I('code');
        if ($code != $_SESSION['code']) {
            jsonpReturn('0','验证码错误');
        }
        if (!preg_match("/^[a-zA-Z0-9]{6,15}$/",$password)) {
            jsonpReturn('0','密码为6到12位的数字和字母');
        }        
        if ( (int)$pid >0 ) {   //上级
            $data['pid'] = $pid;
        }
        $user  = M(self::USER);
        $users = $user->where('phone="%s"',$phone)->find();
        if (!empty($users)) {
            jsonpReturn('0','手机号已存在','');
        }
        if ($password != $rpassword ) {
            jsonpReturn('0','两次输入密码不一致','');
        }
        $data['phone'] = $phone;
        $data['password'] = MD5($password);
        $data['headimg'] = "http://hr2.hongrunet.com/lf_img/lf_portrait.png";
        $data ["name"] = "昵称".$phone;
        $data ["createtime"] = time();
        $result = $user->add($data);
        if ($result) {
            qrcode($result);  //生成推广二维码
            $da['qrcode'] = 'http://hr.hongrunet.com/Uploads/qrcode/qrcode'.$result.'.png';
            $user->where("id=%d",$result)->save($da);
            if ( (int)$pid >0 ) {   //上级
                $one = M('user')->field("id,pid,grade")->where('id=%d',$pid)->find();
                if ($one['grade'] == 1) {
                    $daa['uid'] = $pid;
                    $daa['source'] = $result;
                    $daa['score'] = 5;
                    $daa['message'] = "分佣积分";
                    $daa['createtime'] = time();
                    M('score')->add($daa);
                }

                if ( !empty($one['pid']) || $one['pid'] != null) {
                    $two = M('user')->field("id,pid,grade")->where(["id"=>$one['pid']])->find();
                    if ($two['grade'] == 1) {
                        $daa1['uid'] = $two['id'];
                        $daa1['source'] = $result;
                        $daa1['score'] = 3;
                        $daa1['message'] = "分佣积分";
                        $daa1['createtime'] = time();
                        M('score')->add($daa1);
                    } 

                    if ( !empty($two['pid']) || $two['pid'] != null) {
                        $three = M('user')->field("id,pid,grade")->where(["id"=>$two['pid']])->find();
                        if ($three['grade'] == 1) {
                            $daa2['uid'] = $three['id'];
                            $daa2['source'] = $result;
                            $daa2['score'] = 2;
                            $daa2['message'] = "分佣积分";
                            $daa2['createtime'] = time();
                            M('score')->add($daa2);
                        }                   
                    }                   
                }
              
            }            
            jsonpReturn('1','注册成功!','');
        }else{
            jsonpReturn('0','注册失败!','');
        }
    }

    //登录
    public function login(){
        $phone    = I('phone');
        $password = MD5(I('password'));
        $user = M(self::USER)->where("phone='%s'",$phone)->find();
        if (empty($user) || $user == false) {
            jsonpReturn('0','此号未注册,请先注册','');
        }

        if ($password != $user['password']) {
            jsonpReturn('0','密码错误!','');
        }

        $_SESSION['userid'] = $user['id'];  //用户id 写入session
        jsonpReturn('1','登录成功!');
    }     


    /**
    * 判断是否登录
    */
    public function isLogin(){
    	if (isset($_SESSION['userid']) || $_SESSION['userid'] != '') {
    		jsonpReturn('1','登录过了');
    	}else{
    		jsonpReturn('11','未登录');
    	}
    }
    /**
    * 忘记密码
    */
    public function forgetPwd(){
        $phone    = I('phone');
        $password = I('password');
        $rpassword= I('rpassword');
        $code     = I('code');
        $user  = M(self::USER);
        $users = $user->where("phone='%s'",$phone)->find();
        if (empty($users) || $users == false) {
            jsonpReturn('0','此账号不存在');
        }
        if ($password != $rpassword ) {
            jsonpReturn('0','两次输入密码不一致');
        }
        if ($code != $_SESSION['code']) {
            jsonpReturn('0','验证码错误');
        }

        $data['password'] = MD5($password);
        $result = $user->where("phone='%s'",$phone)->save($data);
        if ($result) {
            jsonpReturn('1','修改成功!');
        }else{
            jsonpReturn('0','修改失败!');
        }        
    }


    //退出登录
    public function logOut(){
        unset($_SESSION["userid"]);//清空指定的session
        jsonpReturn('1','退出成功!','');
    }


    //支付宝支付回调  
    public function alipayCallBack(){
        $result = new NotifyContext;
        $data  =['app_id'=>C('Alipay')['app_id'],'notify_url'=>C('Alipay')['notify_url'],'return_url'=>C('Alipay')['return_url'],'sign_type'=>C('Alipay')['sign_type'],'ali_public_key'=>C('Alipay')['ali_public_key'],'rsa_private_key'=>C('Alipay')['rsa_private_key']];
        # 校验信息
        $result -> initNotify('ali_charge',$data);
        # 接受返回信息
        $information = $result -> getNotifyData();
        if ( $information['trade_status'] == 'TRADE_SUCCESS' ) {
            $pay_order_num = $information['out_trade_no'];
            $order = M('order')->where('pay_order_num='.$pay_order_num)->find();
            if($order['status'] == 2){
                return;
            }
            if($order['type']== 1){
                $letv = M('letv')->where('title='.$order['remark'])->find();
                $data['ver']    = C('Letv')['ver'];
                $data['userid'] = C('Letv')['userid'];
                $data['method'] = 'lecloud.cloudlive.activity.create';
                $data['timestamp'] = time()*1000;
                $data['activityName'] = $letv['title']; //直播活动名称
                $data['activityCategory'] = "012"; //012    教育
                $data['startTime'] = time()*1000; //开始时间
                switch ($letv['timelength']) {
                    case '1'://40
                        $tim = (int)time()+(40*60);
                        $data['endTime'] = $tim*1000;//结束时间
                        break;
                    case '2'://1
                        $tim = (int)time()+(60*60);
                        $data['endTime'] = $tim*1000;//结束时间
                        break;
                    case '3'://1.5
                        $tim = (int)time()+(90*60);
                        $data['endTime'] = $tim*1000;//结束时间
                        break;
                    case '4'://2
                        $tim = (int)time()+(120*60);
                        $data['endTime'] = $tim*1000; //结束时间
                        break;
                    case '5'://2.5
                        $tim = (int)time()+(150*60);
                        $data['endTime'] = $tim*1000; //结束时间
                        break;
                    case '6'://3
                        $tim = (int)time()+(180*60);
                        $data['endTime'] = $tim*1000;//结束时间
                        break;
                    default:
                        break;
                }
                $data['playMode'] = 1; //播放模式，0：实时直播 1：流畅直播
                $data['liveNum'] = 1;//机位数量，范围为：1,2,3,4. 默认为1
                //13 流畅；16 高清；19 超清； 25   1080P；99 原画。默认按最高码率播放，如果有原画则按原画播放
                $data['codeRateTypes'] = $letv['coderatetypes'];
                $data['coverImgUrl'] = $letv['coverimgurl'];//活动封面地址，如果为空，则系统会默认一张图片
                $sign = getSign($data);
                $data['sign'] = $sign;
                $activityId = LetvHttp($this->url,$data,'POST',C('Letv')['headers']);
                preg_match('!\{(.*?)\}$!',$activityId,$arr);
                $result4 = json_decode($arr[0],true);
                $ds['ver'] =     C('Letv')['ver'];
                $ds['userid'] =  C('Letv')['userid'];
                $ds['method'] = 'lecloud.cloudlive.activity.getPushUrl';
                $ds['timestamp'] = time()*1000;
                $ds['activityId'] = $result4['activityId'];
                $sign1 = getSign($ds);
                $ds['sign'] = $sign1;
                $mm = "ver=%s&userid=%s&method=%s&timestamp=%s&activityId=%s&sign=%s";
                $url="http://api.open.lecloud.com/live/execute?";
                $rds = sprintf($mm,$ds['ver'],$ds['userid'],$ds['method'],$ds['timestamp'],$ds['activityId'],$ds['sign']);
                $rs = file_get_contents($url.$rds);
                $mms = json_decode($rs,true);
                $pushUrl = $mms['lives'][0]['pushUrl'];
                //dump($pushUrl);exit;
                $ress = M('letv')->where('title='.$order['remark'])->save(['endTime'=>$tim,'pushUrl'=>$pushUrl,'activityId'=>$result4['activityId']]);
                echo "success";return;
            }else if($order['type'] == 2 || $order['type'] == 3 || $order['type'] == 4 ){
                //改订单状态,分佣
                fxReturnMoney($pay_order_num);return;
            }else if($order['type'] == 6 ) {  //打赏
            	$dsid = $order['remark'];
	            $data1['uid']    = $dsid;
	            $data1['money']  = $order['money'];
	            $data1['source'] = $order['uid'];
	            $data1['message']= '收到红包';
	            $data1['createtime']= time();
	            $data1['paymenttype'] = '支付宝';
	            $results= $account->add($data1);    
	            echo "success";return;
            }else if( $order['type'] == 5 ){    //购买权限卡
                $orr['user_id'] = $order['uid'];
                $orr['add_time']= time();
                M('uservip')->add($orr);
                echo "success";return;
            }else{
                echo "fail2";return;
            }

        }else{
            echo "fail1";return;
        }
    }


    // /**测试回调*/
    // public function alitest(){
    // 	$pay_order_num = "201706149631862219";
    //     $order   = M('order');
    //     $info   = $order->field("uid,money,type")->where("pay_order_num='%s'",$pay_order_num)->find();
    // //     if ($info['status'] == 2) { //判断订单是否处理过了
    // //         echo "success";exit;
    // //     }
    //     $userid = $info['uid']; //用户id
    //     $money  = $info['money'];   //金额
    //     $type   = $info['type'];    //购买类型(1:发起直播,2:学霸,3:讲师,4:合伙人,,5:购买权限)
    //     $use['is_cost'] = 1;    //首次消费
    //     $use['id']      = $userid;

    //     if ($type == 5) {
    //         //购买权限
    //         $dd1['user_id'] = $userid;
    //         $dd1['add_time'] = time();
    //         $results= M('uservip')->add($dd1); //购买权限卡写入表中
    //     }

    //     if ($type != 5 && $type != 1) {
    //         $use['grade'] = $type;
    //     }

    //     if($type ==2){
    //           $use['xbtime'] = time();
    //     }

    //     $rr = M('user')->save($use);    //购买用户等级修改状态

        //分销
        //获取分销规则
        // $config = M('config')->field("student,scholar,lecturer,partner,name")->select();
        // // ----获取用户的上三级

        // $one = M('user')->where('id=%d',$userid)->getField("pid");
        // if ( !empty($one) || $one != null ) {
        //     $user[] = $one;
        //     $two = M('user')->where('id=%d',$one)->getField("pid");
        // }

        // if ( !empty($two) || $two != null) {
        //     $user[] = $two;
        //     $three = M('user')->where('id=%d',$two)->getField("pid");
        // }
        // if (!empty($three) || $three != null ) {
        //     $user[] = $three;           
        // }
        // // dump($user);
        // // die();
        // foreach($user as $k=>$v){
        //     if (empty($v) || $v <=0 || $v == null || $v ==false ) {
        //         $v = -1;
        //     }
        //     $grade = M('user')->where('id=%d',$v)->getField("grade");
        //     switch ($grade){
        //         case 1 :
        //         $level = 'student';
        //         break;
        //         case 2 :
        //         $level = 'scholar';
        //         break;
        //         case 3 :
        //         $level = 'lecturer';
        //         break;
        //         case 4 :
        //         $level = 'partner';
        //         break;
        //     }
        //     $return[$k]['id'] =$v;
        //     $return[$k]['level'] = $config[$k][$level];
        // }
    //     foreach($return as $v){
    //         if(is_numeric($v['level'])){    //判断是否为学童,返积分
    //             $da['uid'] = $v['id'];
    //             $da['source'] = $userid;
    //             $da['score']  = $v['level'];
    //             $da['message']   = '分佣积分';
    //             $da['createtime']= time();
    //             $sco = M('score')->add($da);
    //         }else{
    //             $daa['money'] = $money * ((int)$v['level'] / 100);
    //             $daa['uid']   = $v['id'];
    //             $daa['source']= $userid;
    //             $daa['message']= '分佣金额';
    //             $daa['createtime']= time();
    //             $acc = $acc11->add($daa);
    //         }
    //     }
        // echo "success";exit;
    // }

    public function getCodes(){
        $str = "1234567890123456789012345678901234567890";
        $str = str_shuffle($str);
        $code= substr($str,3,6);
        echo $code;
        return $code;
    }
    public function Codes(){
        $code = file_get_contents('http://hr.hongrunet.com/Home/UserCenter/getCodes');
        echo $code;
    }


    /**
    * 积分互转
    */
    public function scoreHuZhuan(){
    	$id    = $_SESSION['userid'];
    	$score = M(self::SCORE);
        $myscores = $score->where("uid=%d",$id)->getField("sum(score)"); //我的积分
        jsonpReturn('1','我的积分',array("myscore"=>$myscores));
   	}
	
	/**
	* 转让积分
	*/  
	public function scoreYes(){
		$id = $_SESSION['userid'];
		$score = (int)I('score');
		$phone = I('phone');
		$user  = M(self::USER)->where("phone='%s'",$phone)->getField("id");
		if ( $user <0 || $user == false) {
			jsonpReturn('0','此用户不存在');	
		}

		$allscore = M(self::SCORE)->where('uid=%d',$id)->getField("sum(score)");
		$aa = $allscore -  $score ;
		
		if ($score <= 0 ) {
			jsonpReturn('0','转让积分有误');
		}
		if ($aa < 0 ) {
			jsonpReturn('0','积分不够');
		}

		$data['score'] = -$score;
		$data['uid']   = $id;
		$data['source']= $user;
		$data['message']= '积分转让';
		$data['createtime'] = time();

		$data1['score']= $score;
		$data1['uid'] = $user;
		$data1['source'] = $id;
		$data1['message']= '积分转让';
		$data1['createtime']= time();

		$arr[] = $data;
		$arr[] = $data1;
		if(M(self::SCORE)->addAll($arr)) {
			jsonpReturn('1','转让成功');
		}else{
			jsonpReturn('0','转让失败');
		}
	}

	/**
	* 积分互转记录
	*/
	public function scoreHuZhuanLog(){
		$score = M(self::SCORE);
		$id = $_SESSION['userid'];
		$userinfo = M(self::USER)->field("headimg,rname")->where("id=%d",$id)->find();
		$scores = $score->field("uid,source,score,createtime")->where("uid=%d and message='积分转让'",$id)->select();
		$allid = "";
		foreach ($scores as $key => $value) {
			$allid .= $value["source"].",";
			$scores[$key]["createtime"] = date("Y-m-d H:i:s",$value["createtime"]);
		}
		$allid = rtrim($allid,",");
		$where["id"] = array("in",$allid);
		$users = M(self::USER)->field("id,headimg,rname")->where($where)->select();
		$count = count($users);
		foreach ($scores as $k => $v) {
			for ($i=0; $i <$count ; $i++) { 
				if ($v['score'] <0 ) {
						$scores[$k]["headimg"] = $userinfo["headimg"];
						$scores[$k]["rname"]   = $userinfo["rname"];			
				}else{
					if ($v["source"] == $users[$i]["id"]) {
						$scores[$k]["headimg"] = $users[$i]["headimg"];
						$scores[$k]["rname"]   = $users[$i]["rname"];			
					}					
				}

			}
		}
		jsonpReturn("1","",$scores);
	}


	/**
	*  打赏
	*/

	public function dashang(){
		$id = $_SESSION['userid'];
		$money = I('money');
		$dsid    = I('jsid');
		$user = M('user')->field("openid,id")->where("id=%d",$id)->find();
        $paymenttype = I('paymenttype');    //支付方式--(1:微信支付,2:支付宝支付,3:余额支付)
        if (!is_weixin() && $paymenttype == 1) {
            jsonpReturn('0','浏览器不支持微信支付,请选择其他支付方式');
        }   
        $account = M(self::ACCOUNT);
        $order   = M(self::ORDER);

        switch ($paymenttype) {
            case 1:   //微信支付
            $order->startTrans(); //开启事务
            $datt['pay_order_num'] = orderNum();
            $datt['uid']    = $id;
            $datt['money']  = $money;
            $datt['status'] = '1';
            $datt['type']   = 6;
            $datt['message']= '打赏';
            $datt['payment'] = '微信';
            $datt['remark'] = $dsid;
            $datt['createtime'] = time();
            $result = $order->add($datt); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                # 定义下单内容
                $data = [
                'body'=>'打赏',
                'total_fee'=>$money * 100,
                'openid'=> $user['openid'],
                'trade_type'=>'JSAPI',
                'out_trade_no'=>$datt['pay_order_num'],
                'notify_url'=>'http://hr.hongrunet.com/Admin/Wechat/payNotify.html'
                ];
                # 获取JSAPI
                $jsapi = Wechat::get_jsapi_config(['chooseWXPay'],false,false);
                $jsapi = str_replace('timeStamp','timestamp',$jsapi);
                # 获取微信支付配置
                $payConfig = Wechat::ChooseWXPay($data,false); 

                foreach ((array)$payConfig as $key => $value) {
                    $payconfig = $value;
                }
                jsonpReturn('1','',array('jsapi'=>$jsapi,'payConfig'=>$payconfig,"class"=>"微信"));
            }else{
                jsonpReturn('0','下单失败,请刷新页面重试','');
            }                           
              
                break;
            case 2:   //支付宝支付

            $order->startTrans(); //开启事务
            $dat['pay_order_num'] =orderNum();   //生成唯一订单号
            $dat['uid']     = $id;
            $dat['type']    = 6;
            $dat['money']   = $money;
            $dat['status']  = '1';
            $dat['message'] = '打赏';
            $dat['payment'] = '支付宝';
            $datt['remark'] = $dsid;
            $dat['createtime'] = time();
            $result = $order->add($dat); //生成用户消费余额订单
            if ($result) {
                $order->commit();
                $data['order_no'] = $dat['pay_order_num'];
                // $data['amount'] = $money;
                $data['amount'] = 0.01;
                $data['body']   = '打赏';
                $data['subject']= $dat['message'];
                $data['timeout_express'] = (time()+1800);
                $res = Alipays::create($data);  //提交订单 
                jsonpReturn("1","下单成功",array("class"=>"支付宝","res"=>$res));

            }else{
                jsonpReturn('0','下单失败,请刷新页面重试',' ');
            }            

                break;
            case 3:   //余额支付
            $allmoney = M(self::ACCOUNT)->where(['uid'=>$id])->getField('sum(money)');
            $mm = floatval($allmoney) - intval($money);
            if ($mm < 0 ) {
               jsonpReturn('0','余额不够,请切换其他支付方式!',''); 
            }
            $account->startTrans(); //开启事务
            $data['uid']    = $id;
            $data['money']  = -$money;
            $data['source'] = $dsid;
            $data['message']= '发出红包';
            $data['createtime']= time();
            $data['paymenttype'] = '余额支付';
            $result = $account->add($data); //生成用户消费余额订单
            $data1['uid']    = $dsid;
            $data1['money']  = $money;
            $data1['source'] = $id;
            $data1['message']= '收到红包';
            $data1['createtime']= time();
            $data1['paymenttype'] = '余额支付';
            $results= $account->add($data1); 
            if ($result && $results) {
                $account->commit(); //执行事务
                jsonpReturn('1','支付成功','');
            }else{
                $account->rollback();   //回滚事务
                jsonpReturn('0','支付失败','');
            }

                break;  
                                                    
        }
	}
    
}