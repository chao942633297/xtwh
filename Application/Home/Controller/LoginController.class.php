<?php
namespace Home\Controller;
use Think\Controller;
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
            $userData = $user->where(array('phone'=>$mobile,'password'=>$pwd))->find();
            if($userData){
                if($isSave == '1'){
                    cookie('mobile',$userData['phone']);
                    cookie('pwd',$userData['password']);
                }
                session('home_user_id',$userData['id']);
                jsonpReturn('1','登陆成功');
            }else{
                jsonpReturn('0','账号不存在或密码错误!');
            }

        }
    }

    public function sentMsg(){          //点击发送短信验证码   需传入接收验证码的手机号mobile
        $mobile=I('get.mobile');

        $res=D('Msg')->sendMsg($mobile);

        if($res){
            jsonpReturn('1','短信验证码已经发送');
        }else{
            jsonpReturn('0','短信验证码发送失败');
        }

    }




    public function register(){          //注册页面   需传入class(3教师,2机构,1家长,0成人) 省份province  城市city  区/县area
        $input = I('get.');               //手机号phone 密码password  验证码myCode  推荐人手机号refereeMobile
        $user = D('User2');
        $msgCode = cookie('msgCode');
        if($input){
            $code = $input['myCode'];
            $class = $input['class'];
            $province = $input['province'];
            $city = $input['city'];
            $area = $input['area'];
            $phone = $input['phone'];
            $password = $input['password'];
            $refereeMobile = $input['refereeMobile'];
            if($code == $msgCode){
                if(empty($province) || empty($city) || empty($area) ){
                    jsonpReturn('0','地区不能为空');
                }
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("^1[3|4|5|7|8][0-9]{9}$",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }
                if(empty($refereeMobile)){
                    jsonpReturn('0','推荐人手机号不能为空!');
                }else if(!$user->where(array('phone'=>$refereeMobile))->count()){
                    jsonpReturn('0','推荐人手机号不存在!');
                }
                if(!$user->create($input)){
                    jsonpReturn('0',$user->getError());
                }else{
                    $res = $user->add();
                    jsonpReturn('1','注册成功');
                }
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
            if($msgCode == $code){
                if(empty($phone)){
                    jsonpReturn('0','手机号码不能为空');
                }else if(!preg_match("^1[3|4|5|7|8][0-9]{9}$",$phone)){
                    jsonpReturn('0','请输入正确的手机号码');
                }
                $res = D('User2')->where(array('phone'=>$phone))->setField('password',$pwd);
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





}


?>