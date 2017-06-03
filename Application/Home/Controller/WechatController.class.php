<?php
namespace Home\Controller;

use Service\Alipay as Alipays;
use Service\AopClient;
use Payment\NotifyContext;
use Service\AlipayAopnew;
use Service\AlipayTransfer;
use Service\Wechat as Wechats;
use Service\WechatRe;
use Think\Controller;
use Service\File;

# 前台微信控制器
class WechatController extends Controller {
	# 账号手机绑定
	public function bind(){
		// 'http://'.$_SERVER['HTTP_HOST'].'/Home/Wechat/code.html?id='.$_SESSION['userid'];
		// Wechats::get_user_info('http://'.$_SERVER['HTTP_HOST'].'/Home/Wechat/wechat_login.html');
		# 判断是否为提交绑定
		if(IS_POST){
			# 验证账号密码
			$phone    = I('phone');
			$password = MD5(I('password'));
			if($user = M('user')->where(["phone"=>$phone])->find()){
				if ($password != $user['password']) {
					$this->ajaxReturn(array("status"=>0,"msg"=>"密码错误"));
				}
				if ($user['openid'] != '') {
					$this->ajaxReturn(array("status"=>0,"msg"=>"您已经绑定过了"));
				}
				$wxinfo = M('user')->where(["id"=>$_SESSION['userid']])->find();
				$daa['openid'] = $wxinfo['openid'];
				$daa['wx_qrcode'] = $wxinfo['wx_qrcode'];
				$daa['createtime'] = time();
				if ($wxinfo['pid'] != '' || $wxinfo['pid'] != null) {
					$daa['pid'] = $wxinfo['[pid]'];	
				}
				if(M('user')->where(['id'=>$user['id']])->save($daa)) {
					if (M('user')->where(['id'=>$_SESSION['userid']])->delete()) {
						$_SESSION['userid'] = $user['id'];
						$_SESSION['phone'] = $user['phone'];
						$this->ajaxReturn(array("status"=>1,"msg"=>"绑定成功"));
					}else{
						$this->ajaxReturn(array("status"=>0,"msg"=>"绑定失败"));

					}
				};
			}else{
				$wxinfo = M('user')->where(["id"=>$_SESSION['userid']])->find();
				qrcode($wxinfo['id']);
	            if ( (int)$wxinfo['pid'] >0 ) {   //上级
	                $one = M('user')->field("id,pid,grade")->where(["id"=>$wxinfo['pid']])->find();
	                if ($one['grade'] == 1) {
	                    $daa['uid'] = $wxinfo['pid'];
	                    $daa['source'] = $_SESSION['userid'];
	                    $daa['score'] = 5;
	                    $daa['message'] = "分佣积分";
	                    $daa['createtime'] = time();
	                    M('score')->add($daa);
	                }

	                if ( !empty($one['pid']) || $one['pid'] != null) {
	                    $two = M('user')->field("id,pid,grade")->where(["id"=>$one['pid']])->find();
	                    if ($two['grade'] == 1) {
	                        $daa1['uid'] = $two['id'];
	                        $daa1['source'] = $_SESSION['userid'];
	                        $daa1['score'] = 3;
	                        $daa1['message'] = "分佣积分";
	                        $daa1['createtime'] = time();
	                        M('score')->add($daa1);
	                    } 

	                    if ( !empty($two['pid']) || $two['pid'] != null) {
	                        $three = M('user')->field("id,pid,grade")->where(["id"=>$two['pid']])->find();
	                        if ($three['grade'] == 1) {
	                            $daa2['uid'] = $three['id'];
	                            $daa2['source'] = $_SESSION['userid'];
	                            $daa2['score'] = 2;
	                            $daa2['message'] = "分佣积分";
	                            $daa2['createtime'] = time();
	                            M('score')->add($daa2);
	                        }                   
	                    }                   
	                }
	            } 				
				$da['phone'] 	  = $phone;
				$da['password']  = $password;
				$da['createtime'] = time();
				$da['qrcode'] = 'http://hr.hongrunet.com/Uploads/qrcode/qrcode'.$wxinfo['id'].'.png';
				if(M('user')->where(['id'=>$_SESSION['userid']])->save($da)){				
					$_SESSION['userid'] = $wxinfo['id'];
					$_SESSION['phone'] = $phone;
					$this->ajaxReturn(array("status"=>1,"msg"=>"绑定成功"));
				}else{
					$this->ajaxReturn(array("status"=>0,"msg"=>"绑定失败"));
				}
			}
		}else{
	  		$this -> display();
		}		
	}
	
	# 扫码后的第一个转折点
	public function code(){
        # 获取上级id
        $pid = I('pid');
		# 判断是否在微信中打开
        if(is_weixin()){
            # 查询上级信息
            $user = M('user') -> where(['id'=>$pid]) -> find();
            # 定义二维码的链接
            $url = '';
            # 判断用户是否生成过微信二维码
            if($user['wx_qrcode']!=''){
                #获取生成过的
                $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$user['wx_qrcode'];
                $this->assign("qrcode",$user['wx_qrcode']);
            }else{
                # 生成微信二维码
                $qrcode = preg_replace('!https:\/\/mp\.weixin\.qq\.com\/cgi-bin\/showqrcode\?ticket=!','',Wechats::get_Qrcode($pid));
                # 保存微信二维码
                M('user') -> where(['id'=>$pid]) -> save(['wx_qrcode'=>$qrcode]);
                # 返回二维码链接
                $url = 'http://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode;
               	# 下载二维码
               	File::_download($url,ROOT_PATH.'/Uploads/qrcode/',$qrcode.'.jpg');

				# 分配数据模板引擎
				$this -> assign('qrcode',$qrcode);
            }
            # 输出二维码
			# 获取jsapi
			$jsapi_config = Wechats::get_jsapi_config(['onMenuShareTimeline','onMenuShareAppMessage'],false,false);

			# 分配JSapi配置
			$this -> assign('jsapi_config',$jsapi_config);
			$this -> assign("pid",$pid);
			# 渲染模板
			exit($this -> display());            
            // exit('<img src="'.$url.'" />');
        }else{
            # 带着上级信息直接去注册页面
             redirect('http://hr2.hongrunet.com/html/jyt_register.html?pid='.$pid); 
        }
	}
	// 微信登录回调
	public function wechat_login(){
		// 获取用户信息
		$userinfo = Wechats::get_user_info('http://hr.hongrunet.com/Home/Wechat/wechat_login.html');
					// 判断用户是否已经注册过了
		if($user = M('user') -> where(['openid'=>$userinfo['openid']]) -> find()){

			// 存储用户信息
			$_SESSION['userid'] = $user['id'];
			if ($user['phone'] !='') {
				$_SESSION['phone']  = $user['phone'];			
			}
			if($_SESSION['brak_url']!=''){
				redirect($_SESSION['brak_url']);
			}else{
				if ($user['phone'] == '' || $user['phone'] == null ) {
					redirect('bind');								
				}
				// 跳转到首页
				redirect('http://hr2.hongrunet.com/html/kll_index.html');
			}
		}else{
			# 获取用户信息
			# 用户数组
			$data = [];
			# 用户唯一标识
			$data['openid'] = $userinfo['openid'];
			# 性别 1=男 2=女性 0=未设置
			$data['sex'] = $userinfo['sex'];
			# 城市
			//$data['city'] = $userinfo['city'];
			# 省份
			//$data['province'] = $userinfo['province'];
			# 用户昵称
			$data['name'] = $userinfo['nickname'];
			# 国籍
			//$data['country'] = $userinfo['country'];
			# 下载头像到本地
			//File::_download($userinfo['headimgurl'],ROOT_PATH.'public/headimg/',$userinfo['openid'].'.jpg');
			echo $userinfo['headimgurl'];
			$upload =new \Think\Upload();
	        $upload->maxSize=3145728000;
	        $upload->exts= array('jpg','gif','png');
	        $upload->rootPath = './Uploads/';
	        $upload->savePath = '';
	        $info=$upload->upload();
	        //等到上传图片在服务器的路径
	        $savePath=__ROOT__.'/Uploads/'.$info[$userinfo['headimgurl']]["savepath"].$info[$userinfo['headimgurl']]["savename"];
			# 头像
			$data['headimg'] = $savePath;
			# 创建时间
			$data['createtime'] = time();
			# 插入用户数据
			$userId = M('user') -> add($data);
			$user = M('user') -> where(['id'=>$userId]) -> find();
			if($user){
				$_SESSION['userid'] = $user['id'];
				if ($user['phone'] !='') {
					$_SESSION['phone'] = $user['phone'];					
				}else{
					redirect('bind');
				}
			}
			if($_SESSION['brak_url']!=''){
				redirect($_SESSION['brak_url']);
			}else{
				if ($user['phone'] !='') {
					$_SESSION['phone'] = $user['phone'];					
				}else{
					redirect('bind');
				}				
				// 跳转到首页
				redirect('http://hr2.hongrunet.com/html/kll_index.html');
			}
		}
	}

	public function callback(){
		# 监听关注事件
		Wechats::addEvent('subscribe',function($result){
			# 判断是否存在此用户
			if(M('users') -> where(['openid'=>$result['FromUserName']]) -> first()){
				return true;
			}
			# 自动回复
			$userinfo = Wechats::get_openid_user_info($result['FromUserName']);
			# 用户数组
			$data = [];
			# 判断是否存在票据
			if($result['Ticket']!=''){
				# 获取上级信息
				if($puser = M('users') -> where(['qrcode'=>$result['Ticket']]) -> first()){
					// 设置上级id
					$data['pid'] = $puser['id'];
				}
			}
			# 用户唯一标识
			$data['openid'] = $userinfo['openid'];
			# 性别 1=男 2=女性 0=未设置
			$data['sex'] = $userinfo['sex'];
			# 城市
			$data['city'] = $userinfo['city'];
			# 省份
			$data['province'] = $userinfo['province'];
			# 用户昵称
			$data['nickname'] = $userinfo['nickname'];
			# 国籍
			$data['country'] = $userinfo['country'];
			# 下载头像到本地
			File::_download($userinfo['headimgurl'],ROOT_PATH.'public/headimgurl/',$userinfo['openid'].'.jpg');
			# 头像
			$data['headimgurl'] = '/headimgurl/'.$userinfo['openid'].'.jpg';
			# 创建时间
			$data['created_at'] = time();
			# 最后更新时间
			$data['updated_at'] = $data['created_at'];
			# 插入用户数据
			M('users') -> insert($data);
		});
		if($event = M('wechat_message') -> get()){
			$event = $event -> toArray();
		}else{
			unset($event);
		}
		foreach ($event as $value) {
			Wechats::addEvent($value['event'],function($result,$value){
				if($result['Event']==$value['event'] || ($result['Content']==$value['message'] || ($value['is_blurry'] == '1' && preg_match('!.*?'.$value['message'].'.*?!',$result['Content']) == 1)) ){
					# 自动回复
					exit('<xml>
				      <ToUserName><![CDATA['.$result['FromUserName'].']]></ToUserName>
				      <FromUserName><![CDATA['.$result['ToUserName'].']]></FromUserName>
				      <CreateTime>'.time().'</CreateTime>
				      <MsgType><![CDATA[text]]></MsgType>
				      <Content><![CDATA['.$value['reply'].']]></Content>
				      </xml>');
				}
			},$value);
		}
		#监听文本消息
		Wechats::addEvent('text',function($result){
			# 自动回复
			exit('<xml>
		      <ToUserName><![CDATA['.$result['FromUserName'].']]></ToUserName>
		      <FromUserName><![CDATA['.$result['ToUserName'].']]></FromUserName>
		      <CreateTime>'.time().'</CreateTime>
		      <MsgType><![CDATA[text]]></MsgType>
		      <Content><![CDATA[您好，如果您有关于注册、分佣、推广、提现、获取积分的问题，
  请回复序号，自动解答：
   [1]如何注册
   [2]如何分佣
   [3]如何推广
   [4]提现要求
   [5]获取积分
   如果您有其他问题需要解决，可以在线留言，客服人员会尽快给您答复。
   如客服人员未能及时回复，可在工作日（08:00-17:00）拨打电话010-60305020进行人工服务。]]></Content>
		      </xml>');
		});
	}

}