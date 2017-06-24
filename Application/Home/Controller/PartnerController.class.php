<?php
namespace Home\Controller;
use Think\Controller;
class PartnerController extends Controller{

    public function doubleDetail(){        //教师详情/机构详情   需传入教师id或者机构id(doubleId)
        $input = I('get.');
        $doubleId = $input['doubleId'];
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

    public function courseDetail(){           //课程详情   需传入课程id(courseId)
        $input = I('get.');
        $courseId = $input['courseId'];
        $courseId = 2;
        $course = D('CourseRelation')->where(array('id'=>$courseId))->relation('user1')->find();
        if($course){
            jsonpReturn('1','成功',$course);
        }else{
            jsonpReturn('0','失败');
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
                jsonpReturn('1','成功',$goodData);
            }else{
                jsonpReturn('0','失败');
            }
        }
    }




//    个人中心页面

    public function myCollection(){      //我的收藏
        $userId = session('home_user_id');
        $collection = D('Collection');
        $collectionData = $collection->where(array('u2id'=>$userId))->select();
        if($collectionData){
            jsonpReturn('1','查询成功',$collectionData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }




    public function mySubData(){    //我的学员 //我的营业额
        $userId = session('home_user_id');
            $turnover = I('get.turnover');          //   需传入 turnover=1
        $user = D('User2');
        $order = D('OrderRelation');
        $userId = 11;
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