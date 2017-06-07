<?php
namespace Home\Controller;

class IndexController extends BaseController {

	#初始化
    public function _initialize(){
		// if (!isset($_SESSION['userid'])) {
    //  redirect("http://");
		// }

    }

    #会员中心
    public function vipCenter(){
  		$id = $_SESSION['userid'];
      // $id =  1;
  		$userinfo = D('VipUserView');
      #获取本月时间戳
      # 计算开始时间戳
      $start = strtotime(date('Y-m'.'-01 00:00:00'));
      // $d = $days = date('t', time());
      # 计算结束时间戳
      $end = time();
  		$data = $userinfo->field("headimg,name,shopname,allscore,monthscore")->where("a.id=".$id." and b.create_at > ".$start." and b.create_at < ".$end)->find();
      $this->ajaxReturn(['status'=>1,'msg'=>'','data'=>$data],'jsonp');
    }

    #店铺奖励机制
    public function dpjljz(){
      $id = $_SESSION['userid'];
      $shopid = M('users')->where('id=%d',$id)->getField("shop_id");
      if (empty($shopid)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'此用户暂无商家'],'jsonp');
      }
      $shop = M('shops');
      $data = $shop->where('id=%d',$shopid)->getField("user_text");
      $this->ajaxReturn(['status'=>1,'msg'=>'','data'=>$data],'jsonp');
    }

    #推广二维码
    public function qrcode(){
      $id = $_SESSION['userid'];
      $data = M('users')->field("headimg,name")->where("id=%d",$id)->find();
      qrcode($id);  //生成二维码
      $data['qrcode'] = "./Uploads/qrcode/qrcode".$id.".png";
      $this->ajaxReturn(['status'=>1,'msg'=>'','data'=>$data],'jsonp');
   }


    #获取红包价钱 --未完成
    public function getRedPacketMoney(){

    }


    #领取红包 --未完成
    public function redPacket(){
      $id = $_SESSION['userid'];
      $phone = I('phone');
      $name  = I('name');
      if (empty($phone) || empty($name)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'信息不完整'],'jsonp');
      }
      if (!preg_match("/^1[34578][0-9]{9}$/", $phone)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'手机号不符合规则']);
      }
      $data['phone'] = $phone;
      $data['name'] = $name;
    }

    #获取用户资料
    public function getUserInfo(){
      $id   = $_SESSION['userid'];
      if (empty($id)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'未登录'],'jsonp');
      }
      $da   = M('users')->field('headimg,name,sex')->where("id=%d",$id)->find();
      $this->ajaxReturn(['status'=>1,'msg'=>'sex字段0为女,1为男','data'=>$da],'jsonp');
    }

    #修改资料
    public function updateUserInfo(){
      $id   = $_SESSION['userid'];
      $name =  I('name');
      $sex  =  I('sex');
      $headimg = I('headimg');
      if (empty($name) || empty($sex) || empty($headimg) ) {
        $this->ajaxReturn(['status'=>0,'msg'=>'资料不可为空'],'jsonp');
      }
      $data['name'] = $name;
      $data['sex'] = $sex;
      $data['headimg'] = $headimg;

      $res = M('users')->where("id=%d",$id)->save($data);
      if ($res) {
        $this->ajaxReturn(['status'=>1,'msg'=>'修改成功'],'jsonp');
      }else{
        $this->ajaxReturn(['status'=>0,'msg'=>'修改失败'],'jsonp');

      }
    }

    #获取手机号验证码
    public function getCode(){
      $phone = I('phone');
      if (!preg_match("/^1[34578][0-9]{9}$/", $phone)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'手机号不符合规则'],'jsonp');
      }
      // -------------------发送短信，生成验证码--------------------
      $code = GetAPPYZCode($phone); //发送验证码
      // $data['phone'] = $phone;
      // $data['code'] = $code;
      // $res = M('pcode')->where("phone='%s'",$phone)->getField("id");
      // if (empty($res) || $res == false || $res == '') {
      //   $data['create_at'] = time();
      //   M('pcode')->add($data);
      // }else{
      //   $data['update_at'] = time();
      //   M('pcode')->save($data);
      // }

      $_SESSION['code'] = $code;
      $this->ajaxReturn(['status'=>1,'msg'=>'短信验证码已发送','code'=>$code],'jsonp');
    }

    #修改密码-忘记密码
    public function updatePassword(){
      $id   = $_SESSION['userid'];
      $phone= I('phone');
      $password  = I('password');
      $rpassword = I('rpassword');
      $code      = I('code');

      // $code1 = M('pcode')->where("phone='%s'",$phone)->getField("code");

      if (!preg_match("/^1[34578][0-9]{9}$/", $phone)) {
        $this->ajaxReturn(['status'=>0,'msg'=>'手机号不符合规则'],'jsonp');
      }
      // if (!preg_match("/^[1-9a-Z]{6,12}$/", $password)) {
      //   $this->ajaxReturn(['status'=>0,'msg'=>'密码为6到12为数字和字母'],'jsonp');

      // }

      if ($password != $rpassword) {
        $this->ajaxReturn(['status'=>0,'msg'=>'两次输入密码不一致'],'jsonp');

      }

      if ($code != $_SESSION['code']) {
        $this->ajaxReturn(['status'=>0,'msg'=>'验证码错误'],'jsonp');
      }
      $data['password'] = md5($password);
      $res = M('users')->where("id=%d",$id)->save($data);
      if ($res) {
        $this->ajaxReturn(['status'=>1,'msg'=>'修改成功'],'jsonp');
      }else{
        $this->ajaxReturn(['status'=>0,'msg'=>'修改失败'],'jsonp');
      }

    }

    #我的分享
    public function myShare(){
      $id    = $_SESSION['userid'];
      $share = M('share');
      $data  = $share->where(['pid'=>$id])->select();
      if (empty($data)) {
      $this->ajaxReturn(['status'=>0,'msg'=>'暂无分享'],'jsonp');

      }
      $this->ajaxReturn(['status'=>1,'msg'=>'','data'=>$data],'jsonp');
    }

    #我的积分
    public function myIntegral(){

      $id   = $_SESSION['userid'];
      $userinfo = D('VipUserView');

      $start = strtotime(date('Y-m'.'-01 00:00:00'));
      // $d = $days = date('t', time());
      # 计算结束时间戳
      $end = time();
      $data = $userinfo->field("headimg,allscore,monthscore")->where("a.id=".$id." and b.create_at > ".$start." and b.create_at < ".$end)->find();
      if ($data['monthscore'] == null || $data['monthscore'] == '') {
        $data['monthscore'] = 0;
      }
      $this->ajaxReturn($data);
    }

    #我的积分时间搜索
    public function scoreTimeSearch(){
      $id = $_SESSION['userid'];
      $start  = strtotime(I('start'));
      $end    = strtotime(I('end'));
      if (empty($start) || empty($end) ) {
        $this->ajaxReturn(['status'=>0,'msg'=>'起始和结束时间不准为空']);
      }

      $score  = M('user_rewards');
      $score1 = $score->where("user_id=".$id." and create_at > ".$start." and create_at < ".$end)->getField("sum(score)");
      if ($score1 == null || $score1 == '') {
        $score1 = 0;
      }

      $this->ajaxReturn(['status'=>1,'msg'=>'','score'=>$score1],'jsonp');

    }



    #我的朋友
    public function myFriend(){
        //排单表上级id为我的所有单子
        $list = M('lists')->where('up_id='. $_SESSION['userid'])->order('id desc')->select();
        foreach($list as $k=>$v){
            $user_info = M('users')->where('id='.$v['user_id'])->find();
            $return[$k]['id'] = $v['id'];
            $return[$k]['username'] = $user_info['name'];
            $return[$k]['phone'] = $user_info['phone'];
            $return[$k]['init_score'] = $v['init_score'];
            $return[$k]['time'] = date('Y-m-d',$v['create_at']);    //创建时间?
        }
        $this->ajaxReturn(['status'=>1,'msg'=>'','data'=>$return],'jsonp');
    }


    public function test(){
        $id = 1;
        echo $id;
    }


}
