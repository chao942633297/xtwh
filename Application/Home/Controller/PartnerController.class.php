<?php
namespace Home\Controller;
use Think\Controller;
class PartnerController extends BaseController{

    public function doubleDetail(){        //教师详情/机构详情   需传入教师id或者机构id(doubleId)
        $input = I('get.');
        $userId =session('home_user_id');
        $doubleId = $input['doubleId'];
        $user = D('User1Relation')->where(array('id'=>$doubleId))->relation(true)->find();
        if($userId){
            $status = D('Collection')->where(array('u1id'=>$doubleId,'u2id'=>$userId))->find();
        }
        if($status){
            $user['collStatus'] = 1;
        }else{
            $user['collStatus'] = 0;
        }

        if(empty($user['user1'])){
            unset($user['user1']);
        }
        if($user){
            jsonpReturn('1','成功',$user);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function courseDetail(){           //课程详情   需传入课程id(courseId)
        $courseId = I('get.courseId');
        $course = D('CourseRelation')->where(array('id'=>$courseId))->relation('user1')->find();
        if($course['user1']['class'] == 1){
            $course['user1']['userType'] = '讲师';
        }else{
            $course['user1']['userType'] = '合作机构';
        }
        if($course){
            jsonpReturn('1','成功',$course);
        }else{
            jsonpReturn('0','失败');
        }
    }


    public function activityDetail(){             //活动详情  需传入活动id(actId)
        $actId = I('get.id');
        $userId = session('home_user_id');
        $actData = D('article')->where(array('id'=>$actId))->find();
        $actData['create_at'] = date('Y-m-d H:i:s',$actData['create_at']);
        $state = D('activity')->where(array('aid'=>$actId,'uid'=>$userId))->find();   //用户是否已参加活动
        if($state){
            $actData['state'] = 1;
        }else{
            $actData['state'] = 0;
        }
        if($actData){
            jsonpReturn('1','查询成功',$actData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }


    public function actGo(){            //活动报名
        $actId = I('get.id');
        $userId = session('home_user_id');
        if($actId){
            $data['aid'] = $actId;
            $data['uid'] = $userId;
            $data['create_at'] = time();
            $res = D('activity')->add($data);
        }
        if($res){
            jsonpReturn('1','报名成功');
        }else{
            jsonpReturn('0','报名失败');
        }
    }


    public function recomDetail(){           //精彩推荐详情
        $recomId = I('get.recomId');
        $recomData = D('Article')->where(array('id'=>$recomId))->find();
        if($recomData){
            jsonpReturn('1','查询成功',$recomData);
        }else{
            jsonpReturn('1','查询失败');
        }
    }







    public function serviceDetail(){           //服务详情   需传入服务id(serviceId)
        $serviceId = I('get.serviceId');
        $serData = D('ArticleRelation')->where(array('id'=>$serviceId))->relation('user1')->find();
        if($serData){
            jsonpReturn('1','查询成功',$serData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }









    public function musicShop(){             //音乐商城
       $goodtype = D('Goodtype');
        $goodtypeData = $goodtype->select();
        foreach($goodtypeData as $k=>$v){
            if($v['type'] == '1' ){
                $brand[$k] = $v;
            }else if($v['type'] == '2'){
                $instrument[$k] = $v;
            }else if($v['type'] == '3'){
                $material[$k] = $v;
            }
        }
        if($brand && $instrument && $material){
            $data = array('brand'=>$brand,'instrument'=>$instrument,'material'=>$material);
            jsonpReturn('1','成功',$data);
        }else{
            jsonpReturn('0','失败');
        }

    }

    public function searchMusicShop(){        //音乐商城搜索
        $input = I('get.');
        if($input){
            $brandId = $input['brandId'];
            $instrumentId = $input['instrumentId'];
            $materialId = $input['materialId'];
            if($brandId){
                $where['brand_id'] = $brandId;
            }
            if($instrumentId){
                $where['instrument_id'] = $instrumentId;
            }
            if($materialId){
                $where['material_id'] = $materialId;
            }
            $goodData = D('Good')->where($where)->select();
            if($goodData){
                jsonpReturn1('1','成功',$goodData);
            }else{
                jsonpReturn1('0','失败');
            }
        }
    }


    //收藏按钮
    public function actCollertion(){
        $u1id = I('get.id');
        $userId = session('home_user_id');
        $check = D('Collection')->where(array('u2id'=>$userId,'u1id'=>$u1id))->find();
        if($check){
            $res = D('Collection')->where(array('id'=>$check['id']))->delete();
            if($res){
                jsonpReturn1('1','已取消收藏');
            }else{
                jsonpReturn1('0','取消收藏失败');
            }
        }else{
            $data['u1id'] = $u1id;
            $data['u2id'] = $userId;
            $data['create_at'] = time();
            $res = D('Collection')->add($data);
            if($res){
                jsonpReturn1('1','已收藏');
            }else{
                jsonpReturn1('0','收藏失败');
            }
        }
    }



    public function mySubData(){    //我的学员 //我的营业额
        $userId = session('home_user_id');
            $turnover = I('get.turnover');          //   需传入 turnover=1
        $user = D('User2');
        $order = D('OrderRelation');
        $course = D('Course');
        $u1id = $user->where(array('id'=>$userId))->getField('u1id');
        $arrId = $course->where(array('user_id'=>$u1id))->getField('id',true);
        $where['courseid']=array('in',$arrId);
        if($turnover == '1'){
            $map['message'] = '基金提现';
            $map['u2id'] = $userId;
            $map['_logic'] = 'and';
            $where['_complex'] = $map;
            $where['_logic'] = 'or';
        }
        $orderData = $order->where($where)->relation(['user2','course'])->select();
        if($turnover == '1'){
            $totalTurnover = 0;
            foreach($orderData as $k=>$v){
                $totalTurnover += $v['goodprice'];
            }
        }
        if($orderData){
            jsonpReturn('1','查询成功',array($orderData,$totalTurnover));
        }else{
            jsonpReturn('0','查询失败');
        }
    }







    






}



?>