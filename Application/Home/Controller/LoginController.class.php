<?php
namespace Home\Controller;
use Think\Controller;
use Vendor\Weixinpay\WxPayConf_pub;
use Vendor\Weixinpay\WxPayConf_pub\JsApi_pub;

vendor('Weixinpay.WxPayPubHelper');
class LoginController extends Controller{


    public function login(){            //登陆页面  若获取到信息, 需显示用户名,用户密码,保存密码选中状态
        $user = D('User2');
        if(!empty(cookie('mobile')) && !empty(cookie('pwd')) ){
            $mobile = cookie('mobile');
            $pwd = md5(cookie('pwd'));
            $userData = $user->where(array('phone'=>$mobile,'password'=>$pwd))->find();
            if($userData){
                session('home_user_id',$userData['id']);
                jsonpReturn('1','登陆成功',$userData);
            }else{
                jsonpReturn('0','账号不存在或密码错误!');
            }
        }else{
            jsonpReturn('0','失败');
        }
    }


    public function actLogin(){           //点击登陆操作      需传入手机号mobile,用户密码pwd,如果点击保存密码,需传入isSave =1
        $input = I('get.');
        $user = D('User2');
        if($input){
            $mobile = $input['mobile'];
            $pwd = md5($input['pwd']);
            $isSave = $input['isSave'];
            $userData = $user->where(array('phone'=>$mobile))->find();
            if($userData){
                if($userData['password'] == $pwd){       //判断user2表中密码是否匹配
                    if($isSave == '1'){
                        cookie('mobile',$userData['phone']);
                        cookie('pwd',$userData['password']);
                    }
                    session('home_user_id',$userData['id']);
                    jsonpReturn('1','登陆成功');
                }else{
                    $fid = $userData['id'];
                    $res = D('children')->where(array('fid'=>$fid,'password'=>$pwd))->find();
                    if($res){
                        session('home_children_id',$res['id']);
                        jsonpReturn('1','登录成功');
                    }else{
                        jsonpReturn('0','账号不存在或密码错误!');
                    }
                }
            }else{
                jsonpReturn('0','账号不存在或密码错误!');
            }

        }
    }

    public function sentMsg(){          //点击发送短信验证码   需传入接收验证码的手机号mobile

        $mobile=I('get.mobile');
        if(empty($mobile)){
            jsonpReturn('0','手机号码不能为空');
        }else if(!preg_match("/^1[3|4|5|7|8][0-9]{9}$/",$mobile)){
            jsonpReturn('0','请输入正确的手机号码');
        }
        $res=D('Msg')->sendMsg($mobile);
        if($res){
            jsonpReturn('1','短信验证码已经发送');
        }else{
            jsonpReturn('0','短信验证码发送失败');
        }

    }


    public function userScanCode(){      //用户授权登陆
        $jsApi = new JsApi_pub();
        $user = D('User2');
        $pid = I('get.refereeid');
        if (!isset($_GET['code'])){
            $url = 'http://xtwh.yjj-jj.top/home/Login/userScanCode/pid/'.$pid;  //授权后回调页面
            //触发微信返回code码
            $urlData = $jsApi->userAuthorizationLanding($url);
            Header("Location:$urlData");
        }else{
            //获取code码，以获取openid
            $code = $_GET['code'];
            $pid = $_GET['pid'];
            $jsApi->setCode($code);
            $getOpenidUrl =$jsApi->createOauthUrlForOpenid();
            $createData =$jsApi->http_curl($getOpenidUrl);     //获取access_token 和openid
//            dump($createData);
            $openid = $createData['openid'];
            $oneSelf = $user->where(array('openid'=>$openid))->find();
            if($oneSelf['phone']){                          //判断是否已注册
                session('home_user_id',$oneSelf['id']);
                redirect('Index/index');
            }else{
                $access_token = $createData['access_token'];
                $userInfoUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
                $userInfo = $jsApi->http_curl($userInfoUrl);
//                dump($userInfo);die;
                if(!$user->create($userInfo)){
                    jsonpReturn('0','失败',$user->getError());
                }else {
                    $user->refereeid = $pid;
                    if($oneSelf){                   //判断是否已授权登陆,更换上下级关系
                        $uId = $user->where(array('openid'=>$openid))->save();
                    }else{
                        $uId = $user->add();
                    }
                    if($uId){
                        $refereeMobile = $user->where(array('id'=>$pid))->getField('phone');
                        header('location:http://xtwhjy.com/xt/jyt_register.html?phone='.$refereeMobile.'&uId='.$uId);
                    }
                }
            }

        }
    }




    public function actRegister(){          //提交注册   需传入class(3教师,2机构,1家长,0成人) 省份province  城市city  区/县area
        $input = I('get.');               //手机号phone 密码password  验证码myCode  推荐人手机号refereeMobile
        $user = D('User2');
        $msgCode = cookie('msgCode');
        if($input){
            $code = $input['myCode'];
            $uId = $input['uId'];
            $class = $input['class'];
            $province = $input['province'];
            $city = $input['city'];
            $area = $input['area'];
            $phone = $input['phone'];
            $password = $input['password'];
            $password2 = $input['password2'];
            $refereeMobile = $input['refereeMobile'];
            if($code == $msgCode){
                if(empty($province)){
                    jsonpReturn('0','省份不能为空');
                }else if(empty($city)){
                    jsonpReturn('0','城市不能为空');
                }else if(empty($area)){
                    jsonpReturn('0','区/县不能为空');
                }
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("/^1[3|4|5|7|8][0-9]{9}$/",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }else if(empty($password)){
                    jsonpReturn('0','密码不能为空');
                }else if($password != $password2){
                    jsonpReturn('0','两次输入密码不一致');
                }else if(empty($code)){
                    jsonpReturn('0','验证码不能为空');
                }
                if($refereeMobile){
                        $refereeId = $user->where(array('phone'=>$refereeMobile))->getField('id');
                    if (!$refereeId) {
                        jsonpReturn('0','推荐人手机号不存在!');
                    }else if($password != $password2){
                        jsonpReturn('0','两次输入的密码不一致');
                    }else{
                        $user->refereeid = $refereeId;
                    }
                }
                if(!$user->create($input)){
                    jsonpReturn('0',$user->getError());
                }
                $user->password = md5($password);
                if($uId){
                    $res = $user->where(array('id'=>$uId))->save();
                }else{
                    $res = $user->add();
                }
                jsonpReturn('1','注册成功');
            }else{
                jsonpReturn('0','验证码错误!');
            }
        }else{
            jsonpReturn('0','数据错误!');
        }
    }


    public function findPwd(){                      //找回密码  需传入用户手机号phone, 用户设置的密码password , 验证码myCode
        $input = I('get.');
        $msgCode = cookie('msgCode');
        if($input){
            $code = $input['myCode'];
            $phone = $input['phone'];
            $pwd = md5($input['password']);
            $pwd2 = md5($input['password2']);
            if($msgCode == $code){
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("/^1[3|4|5|7|8][0-9]{9}$/",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }else if(empty($input['password'])){
                    jsonpReturn('0','请输入新密码');
                }else if($input['password'] != $input['password2'] ){
                    jsonpReturn('0','两次输入密码不一致');
                }else if(empty($code)){
                    jsonpReturn('0','验证码不能为空');
                }
                $res = D('User2')->where(array('phone'=>$phone))->setField(['password'=>$pwd,'update_at'=>time()]);
                if($res){
                    jsonpReturn('1','修改成功',$res);
                }else{
                    jsonpReturn('0','修改失败');
                }
            }else{
                jsonpReturn('0','验证码错误');
            }
        }else{
            jsonpReturn('0','数据错误');
        }
    }

    public function loginOut(){      //退出登录
        session_destroy();
        cookie('mobile',null);
        cookie('pwd',null);
        jsonpReturn('1','退出成功');
    }





}


?>