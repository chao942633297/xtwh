<?php
namespace Admin\Controller;
use Think\Controller;
use Service\Wechat as Wechats;


class WechatController extends Controller{
	//生成菜单
	public function menu(){
        $data = [
			['name'=>'个人中心','two'=>
				[['name'=>'我的订单','event'=>'view','val'=>'http://www.taobao.com'],
				['name'=>'我的钱包','event'=>'view','val'=>'http://www.tianmao.com']]
			],
			['name'=>'客服中心','event'=>'view','val'=>'http://hr2.hongrunet.com/html/lf_kefuCenter.html'],
			['name'=>'鸿儒讲堂','event'=>'view','val'=>'http://hr.hongrunet.com/Home/Wechat/bind.html']
		];
		dump(Wechats::menu_create($data));
  	}	
	#回调方法
    public function notify(){
    	# 监听关注事件
		Wechats::addEvent('subscribe',function($result){
			# 判断是否存在此用户
			if(M('user') -> where(['openid'=>$result['FromUserName']]) -> find()){
				return true;
			}
			# 获取用户信息
			$userinfo = Wechats::get_openid_user_info($result['FromUserName']);
			# 用户数组
			$data = [];
			# 判断是否存在票据
			if($result['Ticket']!=''){
				# 获取上级信息
				if($puser = M('user') -> where(['wx_qrcode'=>$result['Ticket']]) -> find()){
					// 设置上级id
					$data['pid'] = $puser['id'];
				}
			}
			# 用户唯一标识
			$data['openid'] = $userinfo['openid'];
			# 性别 1=男 2=女性 0=未设置
			if($userinfo['sex']=='1'){
				$data['sex'] = '男';
			}else if($userinfo['sex']=='2'){
				$data['sex'] = '女';
			}else{
				$data['sex'] = '男';
			}
			# 用户昵称
			$data['name'] = $userinfo['nickname'];
			# 头像
			$data['headimg'] = $userinfo['headimgurl'];
			# 插入用户数据
			M('user') -> add($data);
		});
    }


	//微信支付成功回调
    public function payNotify(){
        # 监听回调通知
        Wechats::notitfy(function($notify){
			fxReturnMoney((String)$notify['out_trade_no']);
        });
    }    

}