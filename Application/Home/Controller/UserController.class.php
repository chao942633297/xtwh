<?php
namespace Home\Controller;
use Think\Controller;
use Vendor\phpqrcode\QRcode;

class UserController extends BaseController {
    # 定义控制器使用表名
    const appKey = 'iey6u5sxi16asnci';  //直播的appkey
    const appSecret = 'AIyQo4Y9HRqbpmzy'; //直播的appSecret
    const USERID = 'home_user_id';    //登录的用户id
    const CHILDRENID = 'home_children_id'; //登录孩子id
    const USER    = 'user2';        //用户表
    const USER1   = 'user1';        //教师培训机构表
    const CHILDREN = 'children';     //子女表
    const BACKMONEY = 'backmoney';  //返佣记录表
    const ORDER = 'order';          //订单表
    const CATEGORY = 'category';    //兴趣类别表
    const COLLECTION = 'collection';//收藏表
    const COURSE = 'course';        //课程表
    const VIDEOING = 'videoing';			//直播视频表
    const LIVE     = 'live';          //直播表
    const CHAT     = 'chat';            //聊天表
    const LIVEUSER = 'user';            //直播用户前缀

//    public function _initialize(){
//        session('home_user_id',15);
//    }

    //=================用户信息页====================
    public function index(){
        $id = session(self::USERID);
        $return = [];
        $user = M(self::USER)->where('id='.$id)->find();
        if(session(self::CHILDRENID)){
            $return['children'] = M(self::CHILDREN)->where(['id'=>session(self::CHILDRENID)])->find();
            $return['parent'] = M(self::USER)->where(['id'=>$return['children']['fid']])->find();
            $return['type'] = '3';
            $this->ajaxReturn(['status'=>1,'message'=>'查询孩子成功','data'=>$return],'JSONP');
        }
        if($user['u1id']){
            $return['type'] = '2';
        }else{
            $return['type'] = '1';
            $return['class'] = $user['class'];
        }
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
                $return['grade'] = 'VIP钻卡';
                break;
            case '6':
                $return['grade'] = '合伙人';
                break;
        }
        $parent = M(self::USER)->where(['id'=>$user['refereeid']])->find();
        $return['headimg'] = $user['headimgurl'];
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
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //===================编辑资料页面====================
    public function useredit(){
        $id = session(self::USERID);
        $user = M(self::USER)->where(['id'=>$id])->find();
        $return['nickname'] = $user['nickname'];
        $return['headimg'] = $user['headimgurl'];
        $return['name'] = $user['name'];
        $return['phone'] = $user['phone'];
        $return['sex'] = $user['sex'];
        $parent = M(self::USER)->where(['id'=>$user['refereeid']])->find();
        if($parent){
            $return['p_name'] = $parent['name'];
            $return['p_nickname'] = $parent['nickname'];
            $return['p_phone'] = $parent['phone'];
        }

        $return['birthday'] = $user['birthday'];
        $children = M(self::CHILDREN)->where(['fid'=>$user['childrenid']])->find();
        if($children){
            $return['c_grade'] = $children['grade'];
            $return['c_birthday'] = $children['birthday'];
            $return['c_interest'] = $children['interest'];
        }
        $return['category']['one'] = M(self::CATEGORY)->where(['pid'=>0])->select();//所有一级分类
        $return['category']['two'] = M(self::CATEGORY)->where(['pid'=>['neq',0]])->select();
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //=================执行编辑资料==============
    public function do_useredit(){
        //需要序列化后传过来的表单数据forms
        $id = session(self::USERID);
        $forms = parseParams(I('forms'));
        $user = M(self::USER)->where(['id'=>$id])->find();
//        dump($forms);exit;
        $my['id'] = $id;
        $my['nickname'] = $forms['nickname'];
        $my['headimgurl'] = $forms['headimg'];
        $my['name'] = $forms['name'];
        $my['phone'] = $forms['phone'];
        $my['sex'] = $forms['sex'];
        $my['birthday'] = $forms['birthday'];
        M(self::USER)->save($my);
        $child_info = M(self::CHILDREN)->where(['fid'=>$user['id']])->find();
        if($child_info){
            $child['id'] = $child_info['id'];
            $child['grade'] = $forms['c_grade'];
            $child['birthday'] = $forms['c_birthday'];
            $child['interest'] = $forms['c_interest'];
            M(self::USER)->save($child);
        }
        $this->ajaxReturn(['status'=>1,'message'=>'更新成功'],'JSONP');
    }
    //=================我的余额页面==============
        public function mymoney(){
        $id = session(self::USERID);
        $user = M(self::USER)->where(['id'=>$id])->find();
        $return['money']['onemoney'] = $user['onemoney'];
        // $return['money']['twomoney'] = $user['twomoney'];
        $order = M(self::ORDER)->where(['u2id'=>$id,'status'=>['neq','1']])->order('id desc')->select();
        foreach($order as $k=>$v){
            $order[$k]['update_at'] = date('Y-m-d H:i',$v['update_at']);
        }
        $return['order'] = $order;
            $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
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
        $this->ajaxReturn(['status'=>1,'message'=>'充值完成'],'JSONP');
    }
    //=====================教育基金=========================
    public function backmoney(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        $backmoney = M(self::BACKMONEY)->where(['u2id'=>$id])->select();
        $back = array();
        foreach($backmoney as $k=>$v){
//            $order = M(self::ORDER)->where(['id'=>$v['order_id']])->find();
//            $return['order'][$k]['message'] = $order['message'];
//            $return['order'][$k]['createtime'] = $order['create_at'];
//            $return['order'][$k]['money'] = $order['money'];
            $return['order'][$k]['message'] = $v['message'];
            $return['order'][$k]['createtime'] = date('Y-m-d H:i:s',$v['create_at']);
            $return['order'][$k]['money'] = $v['money'];
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //====================我的基金==========================
    public function mybackmoney(){
        $id = session(self::USERID);
        $one_children = getChilden($id,1);
        $two_children = getChilden($id,2);
        $three_children = getChilden($id,3);
//       jsonpReturn('1','123',$two_children);
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
                $ones[$k]['headimg'] = $user['headimgurl'];
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
                $twos[$k]['headimg'] = $user['headimgurl'];
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
                $threes[$k]['headimg'] = $user['headimgurl'];
                $threes[$k]['grade'] = $user['grade'];
            }
            $return['three'] = $threes;
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //基金提现
    public function subCashApply(){         //基金提现申请
         $id = session(self::USERID);
//        $id = 16;
        $user = M(self::USER)->where(['id'=>$id])->find();
        if($user['rebate_money'] < 3600){
            $this->ajaxReturn(['status'=>false,'message'=>'充值/消费金额不足'],'JSONP');
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
            $data=array('status'=>false,'message'=>$order->getError());
        }else{
            $totalCash= M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
            $find=M('user2')->where(['id'=>$id,'twopassword'=>md5($date['twopassword'])])->find();
            if($date['money']>=100){
                if($date['money']>$totalCash){
                    $data=array('status'=>false,'message'=>'余额不足');
                }else{
                    if($find){
                        $order->money='-'.($data['money']*(1-0.05));
                        $order->create_at=time();
                        $order->ordercode=createCode();
                        $order->u2id=$id;
                        $order->status=3;
                        $order->message='基金提现';
                        $order->paytype = $data['paytype'];
                        $order->payee_man = $data['payee_man'];
                        $order->payee_account = $data['payee_account'];
                        $tr=M();
                        $tr->startTrans();
                        try{
                            $res=$order->add();
                            if($res){
                                $res2=$this->addBackMoney($date['money'],$res);
                                if($res2){
                                    $data=array('status'=>true,'message'=>'申请提现成功');
                                    $tr->commit();
                                }else{
                                    throw new Exception();
                                }
                            }else{
                                throw new Exception();
                            }
                        }catch(Exception $e){
                            $data=array('status'=>false,'message'=>'申请提现失败');
                            $tr->rollback();
                        }
                    }else{
                         $data=array('status'=>false,'message'=>'二级密码错误');
                    }
                }
            }else{
                $data=array('status'=>false,'message'=>'最低发起提现额度为100');
            }
        }
        $this->ajaxReturn($data,'JSONP');
    }
    public function addBackMoney($money,$orderId){  //添加到基金表里数据
        $date['u2id']=session(self::USERID);
        $date['money']='-'.$money;
        $date['create_at']=time();
        $date['order_id']=$orderId;
        $date['message']='基金提现';
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
        $this->ajaxReturn(['status'=>2,'message'=>'请完善信息'],'JSONP');
    }
	//忘记二级密码
	public function forgetpwd(){
		$id = session(self::USERID);
		//需要手机号，新的密码，确认新的密码，验证码

		if(empty(I('phone')) || empty(I('password')) || empty(I('twopassword')) || empty(I('code'))){
            $this->ajaxReturn(['status'=>2,'message'=>'请完善信息'],'JSONP');
        }
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
        if(!empty(I('get.money'))){    //需要转入金额，二级密码
            $money = I('money');
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
                $myorder['message'] = '余额充值';
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
        $this->ajaxReturn(['status'=>1,'message'=>'查询基金成功','data'=>$return],'JSONP');
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
            $return[$k]['create_at'] = date('Y-m-d H:i:s',$user['create_at']);
            $return[$k]['headimg'] = $user['headimgurl'];
            $return[$k]['grade'] = $user['grade'];
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
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
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //我的直播
    public function myLive(){
        $id = session(self::USERID);

        $qkid = M(self::LIVE)->where(['uid'=>$id])->find();
        $user = M(self::USER)->where(['id'=>$id])->find();
        $class = M(self::USER1)->where(['id'=>$user['u1id']])->find();
        if($class['pid']){
            $class = M(self::USER1)->where(['id'=>$class['pid']])->find();
        }
        if(!$qkid){
            $this->ajaxReturn(['status'=>2,'message'=>'您没有开启直播的权限'],'JSONP');
        }

		$video = M(self::VIDEOING)->where(['qkid'=>$qkid['qkid'],'state'=>1])->order('id desc')->select();
        foreach($video as $k=>$v){
            $return[$k]['videoid'] = $v['videoid'];
            $return[$k]['class_name'] = $class['title'];
            $return[$k]['logo'] = $class['logo'];
            $return[$k]['title'] = $v['videoname'];
            $return[$k]['clicknum'] = $v['clicknum'];
            $return[$k]['starttime'] = date('Y-m-d H:i',$v['starttime']);
            $return[$k]['pushUrl'] = json_decode($v['urlgroup'])->pushUrl;
            $return[$k]['id'] = $v['id'];
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }

    //开启直播页面
    public function addLive(){
        $type = M(self::CATEGORY)->where(['pid'=>['neq',0]])->select();
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$type],'JSONP');
    }
    //开启直播
    public function startLive(){
        //需要参数:活动名称name,开始时间startTime,结束时间endTime,活动终止时间expireTime,是否关闭录像disableRecord(0否,1是)
        $id = session(self::USERID);
        $pd = M(self::LIVE)->where(['uid'=>session(self::USERID)])->find();
        if(!$pd){
            $this->ajaxReturn(['status'=>2,'message'=>'您没有权限开启直播'],'JSONP');
        }
        $video = M(self::VIDEOING)->where(['qkid'=>$pd['qkid']])->order('endtime desc')->find();
        if($video['endtime'] > time()){
            $this->ajaxReturn(['status'=>2,'message'=>'您有未结束的直播存在'],'JSONP');
        }
        if(strtotime(I(startTime)) < time()){
            $this->ajaxReturn(['status'=>2,'message'=>'直播开始时间不能为过去'],'JSONP');
        }
        $url = 'http://api.quklive.com/cloud/services/activity/create';
        $user = M(self::USER)->where(['id'=>$id])->find();
        $nonce = time().mt_rand(1000,9999);
        $content = 'appKey='.self::appKey.'&nonce='.$nonce;
        $signature =  base64_encode(hash_hmac("sha1",$content, self::appSecret,true));
        $data = [
            'name' => I('name'),
            'startTime' => I('startTime'),
            'endTime' => I('endTime'),
            'expireTime' => I('expireTime'),
            'memberName' => self::LIVEUSER.$user['id'],
            'disableRecord' => I('disableRecord'),
            'appKey' => self::appKey,
            'nonce' => $nonce,
            'signature' => $signature,
            'secretLinkAble' => 0,
        ];
        $data = json_encode($data);
//        var_dump($data);exit;

//        $data = http_build_query($data);
        $content_length = strlen($data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    "Content-type: application/json\r\n" .
                    "Content-length: {$content_length}\r\n".
                    "charset=utf-8",
                'content' => $data
            )
        );
//        dump($options);exit;
        $result = file_get_contents($url, false, stream_context_create($options));
        $result = json_decode($result);
        if($result->code == 0){
            $insert['videoid'] = $result->value->id;
            $insert['qkid'] = $pd['qkid'];
            $insert['videoname'] = I('name');
            $insert['state'] = 1;
            $insert['starttime'] = strtotime(I('startTime'));
            $insert['endtime'] = strtotime(I('endTime'));
            $insert['recordexpire'] = strtotime(I('expireTime'));
            $insert['livetype'] = I('livetype');
            $insert['urlgroup'] = json_encode($result->value->urlGroup);
            M(self::VIDEOING)->add($insert);
            $return['pushUrl'] = $result->value->urlGroup->pushUrl;
            $return['lookUrl'] = $result->value->urlGroup->iframeU;

            $this->ajaxReturn(['status'=>1,'message'=>'开启成功','data'=>$return],'JSONP');
        }else{
            $this->ajaxReturn(['status'=>2,'message'=>$result->msg],'JSONP');
        }

    }

    //下载说明
    public function windowopen(){
        $file = I('filename').'.txt';

    }

    //直播页
    public function live(){
        //需要参数:视频id
        $id = I('id');
        $video = M(self::VIDEOING)->where(['id'=>$id])->find();
        if($video['state'] != 1 ){
            $this->ajaxReturn(['status'=>2,'message'=>'视频被删除或被禁播'],'JSONP');
        }
        M(self::VIDEOING)->where(['id'=>$id])->setInc('clicknum');
        $live = M(self::LIVE)->where(['qkid'=>$video['qkid']])->find();
        $user = M(self::USER)->where(['id'=>$live['uid']])->find();

        $return['clicknum'] = $video['clicknum'];
        $return['headimg'] = $user['headimgurl'];
        $return['name'] = $user['name'];
        $return['title'] = $video['videoname'];
        $urlgroup = json_decode($video['urlgroup']);
        $return['videourl'] = $urlgroup->iframeUrl;
        $chat = M(self::CHAT)->where(['vid'=>$id])->select();
        foreach($chat as $k=>$v){
            $u = M(self::USER)->where(['id'=>$v['uid']])->find();
            $chat[$k]['headimg'] = $u['headimgurl'];
            $chat[$k]['name'] = $u['name'];
            $chat[$k]['nickname'] = $u['nickname'];
        }
        $return['chat'] = $chat;
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }

    //发送消息
    public function reply(){
        //需要视频id,内容,是否为回复,如果为回复的回复内容
        $id = session(self::USERID);
        $add['uid'] = $id;
        $add['vid'] = I('vid');
        $add['content'] = I('content');
        $add['type'] = I('type');
        if(I('reply') && I('type') != '0'){
            $add['reply'] = I('reply');
        }

        M(self::CHAT)->add($add);
        $this->ajaxReturn(['status'=>1,'message'=>'插入消息成功'],'JSONP');
    }

    //我的二维码
    public function myScanCode(){
        $id = session(self::USERID);
        $user = M(self::USER)->where(['id'=>$id])->find();
        $qrcode = '';
        if(!$user['qrcode'] || !is_file('.'.$user['qrcode'])){
            vendor("phpqrcode.phpqrcode");
            $data = 'http://'.$_SERVER['SERVER_NAME'] .'/home/Login/userScanCode?refereeid='.$id;
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
            $path = "./Uploads/code/";
            // 生成的文件名1
            $fileName = $path.time().mt_rand(1000,9999).'.png';
            QRcode::png($data,$fileName,$level,$size);
            $qrcode = substr($fileName,1);
            //将二维码存入数据库
            M(self::USER)->where(['id'=>$id])->save(['qrcode'=>$qrcode]);
        }else{
            $qrcode = $user['qrcode'];
        }

        $return['name'] = $user['name'];
        $return['nickname'] = $user['nickname'];
        $return['headimg'] = $user['headimgurl'];
        $return['qrcode'] = $qrcode;

        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }

    //我的机构
    public function myuser1(){
        $id = session(self::USERID);
        $order = M(self::ORDER)->where(['u2id'=>$id,'courseid'=>['neq',0],'status'=>['neq',1]])->select();
        $user1 = array();
        foreach($order as $k=>$v){
            $course = M(self::COURSE)->where(['id'=>$v['courseid']])->find();
            $class = M(self::USER1)->where(['class'=>2,'id'=>$course['user_id']])->find();
            if(!in_array($class['id'],$user1) && !empty($class['id'])){
                $user1[] = $class['id'];
            }
        }
//        dump($user1);exit;
        if($user1){
            foreach($user1 as $k=>$v){
                $uclass = M(self::USER1)->where(['class'=>2,'id'=>$v])->find();
                $return[$k]['title'] = $uclass['title'];
                $return[$k]['major'] = $uclass['major'];
                $return[$k]['level'] = $uclass['level'];
                $return[$k]['price'] = $uclass['price'];
                $return[$k]['logo'] = $uclass['logo'];
                $return[$k]['id'] = $v;
            }
            $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
        }else{
            $this->ajaxReturn(['status'=>1,'message'=>'暂无'],'JSONP');
        }


    }

    //提现详情
    public function moneyDetail(){
        $id = session(self::USERID);
        $return['backmoney'] = M(self::BACKMONEY)->where(['u2id'=>$id])->sum('money');
        $return['detail'] = M(self::BACKMONEY)->where(['u2id'=>$id,'message'=>'基金提现'])->order('id desc')->select();
        foreach($return['detail'] as $k=>$v){
            $return['detail'][$k]['create_at'] = date('Y-m-d H:i',$v['create_at']);
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    //我的收藏
    public function myCollection(){
        $id = session(self::USERID);
        $collection = M(self::COLLECTION)->where(['u2id'=>$id,'u1id'=>['neq',0]])->select();
//        dump($collection);exit;
        $return = array();
        foreach($collection as $k=>$v){
            $class = M(self::USER1)->where(['id'=>$v['u1id']])->find();
            if($class['class'] == 1){
                $return['collection'][$k]['teacherage'] = $class['teacherage'];
            }else{
                $return['collection'][$k]['level'] = $class['level'];
                $return['collection'][$k]['major'] = $class['major'];
            }
            $return['collection'][$k]['id'] = $class['id'];
            $return['collection'][$k]['title'] = $class['title'];//老师,机构名称
            $return['collection'][$k]['logo'] = $class['logo'];
            $return['collection'][$k]['price'] = $class['price'];
            $return['collection'][$k]['class'] = $class['class'];//1老师,2机构
        }
        $this->ajaxReturn(['status'=>1,'message'=>'查询成功','data'=>$return],'JSONP');
    }
    public function test(){
        echo 1;

    }
}
