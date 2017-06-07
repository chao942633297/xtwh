<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{


    public function index(){      //首页
        $category = D('Category');
        $common = D('Common');
        $topCate = $category->where(array('pid'=>'0','is_service'=>1))->select();  //一级分类
        $cateList = $common->getLoopCate();           //分类child为子分类
        if($cateList){
            jsonpReturn('1','查询成功',array('topCate'=>$topCate,'cateList'=>$cateList));
        }
    }

    public function findActive(){    //找活动

    }

    public function findService(){        //找服务
        $input = I('get.');
        $category = D('Category');
        $where['pid'] = '0';
        $where['is_service'] = '2';
        $categoryData = $category->where($where)->select();
        if($categoryData){
            jsonpReturn('1','成功',$categoryData);
        }
    }

    public function searchService(){      //找服务内搜索
        $input = I('get.');
        $course = D('Course');
        $cateId = $input['cateId'];
        $courseData = $course->where(array('categoryid'=>$cateId))->select();
        if($courseData){
            jsonpReturn('1','成功',$courseData);
        }

    }

    public function hotRecom(){      //热门推荐

    }



    public function recomCourse(){     //推荐课程
        $input = I('get.');
        $oneId = $input['oneId'];
        $classId = $input['classId'];
        $common = D('Common');
        $category = D('Category');
        $user = D('User1');
        $arrId = $category->where(array('pid'=>$oneId))->getField('id',true);
        $where = $common->getSearchCond($input);
        if(empty($where['categoryid'])){
            $where['categoryid'] =array('in',$arrId);
        }
        $where['class'] = $classId ? $classId : 1;
        $userData = $user->where($where)->select();
        if($userData){
            jsonpReturn('1','查询成功',$userData);
        }
    }


    public function searchList(){    //查询列表           首页搜素需传入(classId =2)/找老师(classId =1)/找机构(classId =2)共用
        $input = I('get.');
        //查询分类需要二级分类id,cateId查询区域需要省province市city区area
        $classId = $input['classId'];
        $user = D('User1');
        $common = D('Common');
        $where = $common->getSearchCond($input);
        $where['class'] = $classId;
        $userData = $user->where($where)->select();
        if($userData){
            jsonpReturn('1','查询成功',$userData);
        }
    }





























}







?>