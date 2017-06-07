<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{

    public function test(){
        dump('123123');
    }



    public function index(){      //首页
        $category = D('Category');
        $common = D('Common');
        $topCate = $category->where(array('pid'=>'0'))->select();  //一级分类
        $cateList = $common->getLoopCate();           //分类child为子分类
        if($cateList){
            jsonpReturn('1','查询成功',array($topCate,$cateList));
        }
    }

    public function findActive(){

    }

    public function findService(){
        $input = I('get.');
        $category = D('Category');
        $where['pid'] = '0';
        $categoryData = $category->where($where)->select();
        
    }



    public function recomCourse(){     //推荐课程
        $input = I('get.');
        $oneId = $input['oneId'];
        $classId = $input['classId'];

        $cateId = $input['cateId'];    //
        $province = $input['province'];
        $city = $input['city'];
        $area = $input['area'];
        if($area){
            $where['area'] = $area;
        }else if($city){
            $where['city'] = $city;
        }else if($province){
            $where['province'] = $province;
        }

        $category = D('Category');
        $user = D('User1');
        $arrId = $category->where(array('pid'=>$oneId))->getField('id',true);
        if($cateId){
            $where['categoryid'] = $cateId;
        }else{
            $where['categoryid'] =array('in',$arrId);
        }
        $where['class'] = $classId ? $classId : 1;
        $userData = $user->where($where)->select();

    }


    public function searchList(){    //查询列表           首页搜素需传入(classId =2)/找老师(classId =1)/找机构(classId =2)共用
        $input = I('get.');
        //查询分类需要二级分类id,cateId查询区域需要省province市city区area
        $classId = $input['classId'];
        $cateId = $input['cateId'];
        $province = $input['province'];
        $city = $input['city'];
        $area = $input['area'];
        $user = D('User1');
        if($cateId){
            $where['categoryid'] = $cateId;
        }
        if($area){
            $where['area'] = $area;
        }else if($city){
            $where['city'] = $city;
        }else if($province){
            $where['province'] = $province;
        }
        $where['class'] = $classId;
        $userData = $user->where($where)->select();
        if($userData){
            jsonpReturn('1','查询成功',$userData);
        }
    }





























}







?>