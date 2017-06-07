<?php
namespace Home\Controller;
use Think\Controller;
class PartnerController extends Controller{

    public function allDetail(){        //教师详情/机构详情
        $input = I('get.');
        $teacherId = $input['teacherId'];
        $teacherId = 2;
        $user = D('User1Relation')->where(array('id'=>$teacherId))->relation(true)->find();
        if(empty($user['user1'])){
            unset($user['user1']);
        }
        if($user){
            jsonpReturn('1','成功',$user);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function mechanDetail(){           //机构详情
        $input = I('get.');
    }



}



?>