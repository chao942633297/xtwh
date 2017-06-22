<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller{
    # 定义控制器使用表名
    const USERID = 'userid';    //登录的用户id
    const USER    = 'user2';        //用户表
    const USER1   = 'user1';        //教师培训机构表
    const CHILDREN = 'children';     //子女表
    const BACKMONEY = 'backmoney';  //返佣记录表
    const ORDER = 'order';          //订单表
    const CATEGORY = 'category';    //兴趣类别表
    const COLLECTION = 'collection';//收藏表
    const COURSE = 'course';        //课程表
    const VIDEO = 'video';			//直播表
    public function login_test(){
        session(self::USERID,16);
    }
    //=================用户信息页====================
    public function index(){
        $id = session(self::USERID);
        $return = [];		
        $user = M(self::USER)->where('id='.$id)->find();
        switch ($user['grade']) {
            case '1':
                $return['grade'] = '路人甲';
                break;
            case '2':
                $return['grade'] = 'vip';
                break;
            case '3':
                $return['grade'] = 'VIP银卡';
                break;
            case '4':
                $return['grade'] = 'VIP金卡';
                break;
            case '5':
                $return['grade'] = 'VIP钻石';
                break;
            case '6':
                $return['grade'] = '合伙人';
                break;
        }
        $parent = M(self::USER)->where('id='.$user['refereeid'])->find();
        $return['headimg'] = $user['headimg'];
        $return['name'] = $user['name'];
        $return['nickname'] = $user['nickname'];
        if($parent){
            $return['p_name'] = $parent['name'];
            $return['p_nickname'] = $parent['nickname'];
            $return['p_phone'] = $parent['phone'];
        }
        $return['onemoney'] = $user['onemoney'];
        // $return['twomoney'] = $user['twomoney'];
        $childen = '';
        for($i = 1;$i <= 3;$i++){
            $childen    .= getChilden($id,$i).',';
        }
        $return['childennum'] = count(explode(',',trim($childen,','))); //下级人数
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');//教育基金
        dump($return);
    }
    //===================编辑资料页面====================
    public function useredit(){
        $id = session(self::USERID);
        $user = M(self::USER)->where(['id'=>$id])->find();
        $return['nickname'] = $user['nickname'];
        $return['headimg'] = $user['headimg'];
        $return['name'] = $user['name'];
        $return['phone'] = $user['phone'];
        $parent = M(self::USER)->where('id='.$user['refereeid'])->find();
        if($parent){
            $return['p_name'] = $parent['name'];
            $return['p_nickname'] = $parent['nickname'];
            $return['p_phone'] = $parent['phone'];
        }
        if($user['sex'] == 1){
            $return['sex'] == '男';
        }else{
            $return['sex'] == '女';
        }
        $return['birthday'] = $user['birthday'];
        $children = M(self::CHILDREN)->where(['fid'=>$user['childrenid']])->find();
        if($children){
            $return['grade'] = $children['grade'];
            $return['birthday'] = $children['birthday'];
            $return['interest'] = $children['interest'];
        }
        $return['category']['one'] = M(self::CATEGORY)->where(['pid'=>0])->select();//所有一级分类
        $return['category']['two'] = M(self::CATEGORY)->where(['pid'=>['neq',0]])->select();
        dump($return);
    }
    //=================执行编辑资料==============
    public function do_useredit(){
        //需要序列化后传过来的表单数据forms
        $id = session(self::USERID);
        $forms = I('forms');
        $user = M(self::USER)->where(['id'=>$id])->find();
        $my['id'] = $id;
        $my['nickname'] = $forms['nickname'];
        $my['headimg'] = $forms['headimg'];
        $my['name'] = $forms['name'];
        $my['phone'] = $forms['phone'];
        $my['sex'] = $forms['sex'];
        $my['birthday'] = $forms['birthday'];
        M(self::USER)->save($my);
        $child_info = M(self::CHILDREN)->where(['fid'=>$user['id']])->find();
        if($child_info){
            $child['id'] = $child_info['id'];
            $child['grade'] = $forms['grade'];
            $child['birthday'] = $forms['children_birthday'];
            $child['interest'] = $forms['interest'];
            M(self::USER)->save($child);
        }
        echo '成功';
    }
    //=================我的余额页面==============
    public function mymoney(){
        $id = session(self::USERID);
        $user = M(self::USER)->where(['id'=>$id])->find();
        $return['money']['onemoney'] = $user['onemoney'];
        // $return['money']['twomoney'] = $user['twomoney'];
        $order = M(self::ORDER)->where(['u2id'=>$id,'status'=>['neq','1']])->order('id desc')->select();
        $return['order'] = $order;
        dump($return);
    }
    //====================我的余额----余额充值========================
    public function addmoney(){         //须完善
        //需要传来充值金额      支付类型  '微信支付','支付宝支付'
        $id = session(self::USERID);
        $money = I('money');
        $paytype = I('paytype');
        //添加订单
        $add['ordercode'] = createCode();
        $add['u2id'] = $id;
        $add['money'] = $money;
        $add['status'] = '1';
        $add['message'] = '充值余额';
        $add['paytype'] = $paytype;
        $add['create_at'] = time();
        $order = M(self::ORDER)->add($add);
        if($order){
        	if($paytype = '微信'){
        		A('Wechat')->wechatPay($order);
        	}else{
        		A('AliPay')->webPay($order);
        	}
        }
		dump($order);
    }
    //=====================教育基金=========================
    public function backmoney(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        $backmoney = M(self::BACKMONEY)->where(['u2id'=>$id])->select();
        $back = array();
        foreach($backmoney as $k=>$v){
            $order = M(self::ORDER)->where(['id'=>$v['order_id']])->find();
            $return['order'][$k]['message'] = $order['message'];
            $return['order'][$k]['createtime'] = $order['createtime'];
            $return['order'][$k]['money'] = $order['money'];
        }
        dump($return);
    }
    //====================我的基金==========================
    public function mybackmoney(){
        $id = session(self::USERID);
        $one_children = getChilden($id,1);
        $two_children = getChilden($id,2);
        $three_children = getChilden($id,3);
        $all = M(self::BACKMONEY)->where(['u2id'=>$id,'money'=>['GT',0]])->select();
        $return['promotion'] = getPromotion($id);
        $return['one'] = null;
        $return['two'] = null;
        $return['three'] = null;
        if(!empty($one_children)){
            $one = M(self::BACKMONEY)->where(['source'=>['in',$one_children],'money'=>['GT',0]])->select();
            foreach($one as $k=>$v){
                $user = M(self::USER)->where(['id'=>$v['source']])->find();
                $ones[$k]['name'] = $user['name'];
                $ones[$k]['nickname'] = $user['nickname'];
                $ones[$k]['create_at'] = date('Y-m-d H:i:s',$v['create_at']);
                $ones[$k]['headimg'] = $user['headimg'];
                $ones[$k]['grade'] = $user['grade'];
            }
            $return['one'] = $ones;
        }
        if(!empty($two_children)){
            $two = M(self::BACKMONEY)->where(['source'=>['in',$two_children],'money'=>['GT',0]])->select();
            foreach($two as $k=>$v){
                $user = M(self::USER)->where(['id'=>$v['source']])->find();
                $twos[$k]['name'] = $user['name'];
                $twos[$k]['nickname'] = $user['nickname'];
                $twos[$k]['create_at'] = date('Y-m-d H:i:s',$v['create_at']);
                $twos[$k]['headimg'] = $user['headimg'];
                $twos[$k]['grade'] = $user['grade'];
            }
            $return['two'] = $twos;
        }
        if(!empty($three_children)){
            $three = M(self::BACKMONEY)->where(['source'=>['in',$three_children],'money'=>['GT',0]])->select();
            $threes = array();
            foreach($three as $k=>$v){
                $user = M(self::USER)->where(['id'=>$v['source']])->find();
                $threes[$k]['name'] = $user['name'];
                $threes[$k]['nickname'] = $user['nickname'];
                $threes[$k]['create_at'] = date('Y-m-d H:i:s',$v['create_at']);
                $threes[$k]['headimg'] = $user['headimg'];
                $threes[$k]['grade'] = $user['grade'];
            }
            $return['three'] = $threes;
        }
        dump($return);
    }
    //基金提现
    public function subCashApply(){         //基金提现申请
        // $id = session(self::USERID);
        $id = 16;
        $user = M(self::USER)->where(['id'=>$id])->find();
        if($user['rebate_money'] < 3600){
            $this->ajaxReturn(['status'=>false,'error_message'=>'充值/消费金额不足'],'JSONP');
        }
        $date=I('post.');
        $order=M('order');
        $rules=array(
            array('money','require','提现金额不能为空'),
            array('paytype','require','提现方式不能为空'),
            array('payee_man','require','收款人不能为空'),
            array('payee_account','require','收款人账号不能为空'),
            array('twopassword','require','二级密码不能为空'),
        );
        if(!$order->validate($rules)->create()){
            $data=array('status'=>false,'error_message'=>$order->getError());
        }else{
            $totalCash= M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
            $find=M('user2')->where(['id'=>$id,'twopassword'=>md5($date['twopassword'])])->find();
            if($date['money']>=100){
                if($date['money']>$totalCash){
                    $data=array('status'=>false,'error_message'=>'余额不足');
                }else{
                    if($find){
                        $order->money='-'.($order->money*(1-0.05));
                        $order->create_at=time();
                        $order->ordercode=createCode();
                        $order->u2id=$id;
                        $order->status=3;
                        $order->message='基金提现';
                        $tr=M();
                        $tr->startTrans();
                        try{
                            $res=$order->add();
                            if($res){
                                $res2=$this->addBackMoney($date['money'],$res);
                                if($res2){
                                    $data=array('status'=>true,'error_message'=>'申请提现成功');
                                    $tr->commit();
                                }else{
                                    throw new Exception();
                                }
                            }else{
                                throw new Exception();
                            }
                        }catch(Exception $e){
                            $data=array('status'=>false,'error_message'=>'申请提现失败');
                            $tr->rollback();
                        }
                    }else{
                         $data=array('status'=>false,'error_message'=>'二级密码错误');
                    }
                }
            }else{
                $data=array('status'=>false,'error_message'=>'最低发起提现额度为100');
            }
        }
        $this->ajaxReturn($data,'JSONP');
    }
    public function addBackMoney($money,$orderId){  //添加到基金表里数据
        $date['u2id']=session(self::USERID);
        $date['money']='-'.$money;
        $date['create_at']=time();
        $date['order_id']=$orderId;
        $date['message']='提现';
        $res=M('backmoney')->data($date)->add();
        if($res){
            return true;
        }
        return false;
    }
    //好友互转
    public function toFriend(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        if(!empty(I())){    //需要互转金额，好友昵称，好友电话，二级密码
            $money = I('money');
            $friend_nickname = I('nickname');
            $friend_phone = I('phone');
            $twopassword = md5(I('twopassword'));
            $my = M(self::USER)->where(['id'=>$id])->find();
            if($my['twopassword'] != $twopassword){
                $this->ajaxReturn(['status'=>2,'message'=>'二级密码错误'],'JSONP');
            }
            if($return['backmoney'] < $money || $money < 0){
                $this->ajaxReturn(['status'=>2,'message'=>'教育基金不足'],'JSONP');
            }
            $friend = M(self::USER)->where(['nickname'=>$friend_nickname,'phone'=>$friend_phone])->find();
            if(empty($friend)){
                $this->ajaxReturn(['status'=>2,'message'=>'用户不存在'],'JSONP');
            }
            $myorder['ordercode'] = createCode();
            $myorder['u2id'] = $id;
            $myorder['money'] = '-'.$money;
            $myorder['message'] = '好友互转转出';
            $myorder['paytype'] = '基金余额充值';
            $myorder['payee_man'] = $friend['name'];
            $myorder['payee_account'] = $friend['id'];
            $myorder['create_at'] = time();
            $myid = M(self::ORDER)->add($myorder);  //转出
            M(self::BACKMONEY)->add(['u2id'=>$id,'money'=>'-'.$money,'source'=>$friend['id'],'message'=>'好友互转转出','create_at'=>time(),'order_id'=>$myid]);
            $forder['ordercode'] = createCode();
            $forder['u2id'] = $friend['id'];
            $forder['money'] = $money - ($money * 0.01);
            $forder['message'] = '好友互转转入';
            $forder['paytype'] = '基金余额充值';
            $forder['payee_man'] = $friend['name'];
            $forder['payee_account'] = $friend['id'];
            $forder['create_at'] = time();
            $fid = M(self::ORDER)->add($forder);  //转入
            M(self::BACKMONEY)->add(['u2id'=>$friend['id'],'money'=>$money - $money * 0.01,'source'=>$id,'message'=>'好友互转转入','create_at'=>time(),'order_id'=>$fid]);
            $this->ajaxReturn(['status'=>1,'message'=>'互转成功'],'JSONP');
        }
        dump($return);
    }
	//忘记二级密码
	public function forgetpwd(){
		$id = session(self::USERID);
		//需要手机号，新的密码，确认新的密码，验证码
		$phone = I('phone');
		$password = I('password');
		$twopassword = I('twopassword');
		if($password != $twopassword){
			$this->ajaxReturn(['status'=>2,'message'=>'两次密码不相同'],'JSONP');
		}
		$code = I('code');
		if($code != cookie('msgCode')){
			$this->ajaxReturn(['status'=>2,'message'=>'验证码错误'],'JSONP');
		}
		M(self::USER)->where(['id'=>$id])->save(['twopassword'=>$password]);
		$this->ajaxReturn(['status'=>1,'message'=>'修改成功'],'JSONP');
	}
    //转入余额
    public function toMoney(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        if(!empty(I())){    //需要转入金额，转入类型 one 或者 two，二级密码
            $money = I('money');
            $type = I('type');
            $twopassword = md5(I('twopassword'));
            $my = M(self::USER)->where(['id'=>$id])->find();
            if($my['twopassword'] != $twopassword){
                $this->ajaxReturn(['status'=>2,'message'=>'二级密码错误'],'JSONP');
            }
            if($return['backmoney'] < $money || $money < 0){
                $this->ajaxReturn(['status'=>2,'message'=>'教育基金不足'],'JSONP');
            }
            $myorder['ordercode'] = createCode();
            $myorder['u2id'] = $id;
            $myorder['money'] = $money;
            // if($type == 'one'){
                $myorder['message'] = '直营余额充值';
                M(self::USER)->save(['onemoney'=>$my['onemoney']+$money]);
            // }else{
            //     $myorder['message'] = '非直营余额充值';
            //     M(self::USER)->save(['twomoney'=>$my['twomoney']+$money]);
            // }
            $myorder['paytype'] = '基金余额充值';
            $myorder['create_at'] = time();
            $myid = M(self::ORDER)->add($myorder);  //添加订单
            M(self::BACKMONEY)->add(['u2id'=>$id,'money'=>'-'.$money,'source'=>$id,'create_at'=>time(),'order_id'=>$myid]);
            $this->ajaxReturn(['status'=>1,'message'=>'转入余额成功'],'JSONP');
        }
        dump($return);
    }
    //我的伙伴1
    public function myPartner(){
        $id = session(self::USERID);
        $children = '';
        for($i = 1;$i <= 3;$i++){
            $children .= getChilden($id,$i).',';
        }
        $children = explode(',',trim($children,','));
        $return = array();
        foreach($children as $k=>$v){
            $user = M(self::USER)->where(['id'=>$v])->find();
            // dump($user);exit;
            $return[$k]['name'] = $user['name'];
            $return[$k]['nickname'] = $user['nickname'];
            $return[$k]['phone'] = $user['phone'];
            $return[$k]['create_at'] = $user['create_at'];
            $return[$k]['headimg'] = $user['headimg'];
            $return[$k]['grade'] = $user['grade'];
        }
        dump($return);
    }
    //我的预存
    public function myStored(){     //功能已删除
    }
    //我的课程
    public function myCourse(){
        $id = session(self::USERID);
        $order = M(self::ORDER)->where(['u2id'=>$id,'courseid'=>['neq',0],'status'=>['neq',1]])->select();
        foreach($order as $k=>$v){
            $course = M(self::COURSE)->where(['id'=>$v['courseid']])->find();
            $class = M(self::USER1)->where(['class'=>2,'id'=>$course['user_id']])->find();
            if($class){
                $return['course'][$k]['user1'] = $class['title'];
            }else{
                $return['course'][$k]['user1'] = '平台';
            }
            $return['collection'][$k]['status'] = $course['status'];
            $return['course'][$k]['title'] = $course['title'];
            $return['course'][$k]['start_time'] = date('Y-m-d H:i:s',$course['start_time']);
            $return['course'][$k]['click'] = $course['click'];
            $return['course'][$k]['line'] = $course['line'];
            $return['course'][$k]['logo'] = $course['logo'];
            $return['collection'][$k]['id'] = $course['id'];
        }
        dump($return);
    }
    //我的直播
    public function myLive(){
        $id = session(self::USERID);
		
    }
    //我的二维码
    public function myScanCode(){
		
    }
    //提现详情
    public function moneyDetail(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        $return['detail'] = M(self::ORDER)->where(['u2id'=>$id,'status'=>'5'])->select();
        dump($return);
    }
    //我的收藏
    public function myCollection(){
        $id = session(self::USERID);
        $collection = M(self::COLLECTION)->where(['u2id'=>$id,'courseid'=>['neq',0],'status'=>['neq',1]])->select();
        $return = array();
        foreach($controller as $k=>$v){
            $course = M(self::COURSE)->where(['id'=>$v['courseid']])->find();
            $class = M(self::USER1)->where(['class'=>2,'id'=>$course['user_id']])->find();
            if($class){
                $return['course'][$k]['user1'] = $class['title'];
            }else{
                $return['course'][$k]['user1'] = '平台';
            }
            $return['collection'][$k]['status'] = $course['status'];
            $return['collection'][$k]['title'] = $course['title'];
            $return['collection'][$k]['start_time'] = date('Y-m-d H:i:s',$course['logo']);
            $return['collection'][$k]['click'] = $course['click'];
            $return['collection'][$k]['id'] = $course['id'];
            $return['collection'][$k]['line'] = $course['line'];
        }
        dump($return);
    }
    public function test(){
        $user = promotion();

    }
}
