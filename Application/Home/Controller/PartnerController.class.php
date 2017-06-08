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










}



?>