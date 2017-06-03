<?php
namespace Home\Controller;

use Think\Controller;


class AlipayController extends Controller{
    //跳转定向
    public function redir(){
        $id = $_SESSION['userid'];
        $order = M('order')->where('uid='.$id)->order('createtime','DESC')->find();
        if($order['type'] == '1'){
            $letvid = M('letv')->where('uid='.$id)->order('createtime','DESC')->find()['id'];
            header("location:http://hr2.hongrunet.com/html/wy_myVideo.html?id=$letvid");
        }elseif($order['type'] == '6'){
            $letvid = M('letv')->where('uid='.$id)->order('createtime','DESC')->find()['id'];
            header("location:http://hr2.hongrunet.com/html/lf_videoDetail.html?id=$letvid");
        }else{
            header('location:http://hr2.hongrunet.com/html/lf_personal.html');
        }
    }

}