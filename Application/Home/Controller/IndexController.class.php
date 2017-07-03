<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController{


    public function index(){      //首页
        $category = D('Category');
        $common = D('Common');
        $userId = session('home_user_id');
        if($userId){
            $userStatus = '1';
        }else{
            $userStatus = '0';
        }
        $lunbo = D('Lunbo');
        $topCate = $category->where(array('pid'=>'0','is_service'=>1))->select();  //一级分类
        $cateList = $common->getLoopCate();           //分类child为子分类
        $lunboData = $lunbo->where(array('type'=>1))->select();
        if($cateList){
            jsonpReturn('1','查询成功',array('topCate'=>$topCate,'cateList'=>$cateList,'lunboData'=>$lunboData,'userStatus'=>$userStatus));
        }else{
            jsonpReturn('0','查询失败');
        }
    }


    public function searchList(){    //查询列表           首页搜素需传入(classId =2)/找老师(classId =1)/找机构(classId =2)共用
        $input = I('get.');
        //查询分类需要二级分类id,cateId查询区域需要省province市city区area
        $classId = $input['classId'];
        $sort = $input['sort'];
        $page = $input['page'];
        $user = D('User1');
        $common = D('Common');
        $where = $common->getSearchCond($input);
        $where['class'] = $classId;
        // if(isset($page)){
        	$userData = $user->where($where)->limit($page,10)->select();
        // }else{
        // 	$userData = $user->where($where)->select();
        // }
        foreach($userData as $k=>$v ){
            $minPrice= D('Course')->where(array('user_id'=>$v['id']))->min('price');
            $userData[$k]['minPrice'] = isset($minPrice) ? $minPrice : '0'; 
        }
        if($sort == 'hot'){
        	$userData = multi_array_sort($userData,'click',SORT_DESC);
        }else if($sort == 'min'){       //按价格进行排序
        	$userData = multi_array_sort($userData,'minPrice',SORT_ASC);
        }else if($sort == 'max'){
        	$userData = multi_array_sort($userData,'minPrice',SORT_DESC);
        }

        if($userData){
            jsonpReturn('1','查询成功',$userData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }





    public function findActive(){    //找活动  //活动详情需传入活动id (actId)
        $actId = I('get.actId');
        $article = D('Article');
        if($actId){
            $articleData = $article->where(array('id'=>$actId))->find();
        }else{
            $articleData = $article->where(array('type'=>4))->select();
        }
        if($articleData){
            jsonpReturn('1','查询成功',$articleData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }

    public function findService(){        //找服务 页面  返回分类表 一级分类
        $category = D('Category');
        $where['pid'] = '0';
        $where['is_service'] = '2';
        $categoryData = $category->where($where)->select();
        foreach ($categoryData as $key => $value) {
        	$categoryData[$key]['create_at'] = date('Y-m-d H:i:s',$value['create_at']);
        }
        if($categoryData){
            jsonpReturn('1','成功',$categoryData);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function searchService(){      //找服务内搜索   传入服务id
        $input = I('get.');
        $article = D('Article');
        $cateId = $input['cateId'];
        $articleData = $article->where(array('class'=>$cateId,'is_del'=>0,'type'=>0))->select();
        if($articleData){
            jsonpReturn('1','成功',$articleData);
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function wonderfulRecom(){      //精彩推荐
        $article = D('Article');
        $articleData = $article->where(array('type'=>array('neq',4)))->select();    //除去活动外的文章
        foreach ($articleData as $key => $value) {
        	$articleData[$key]['create_at'] = date('Y-m-d H:i:s',$value['create_at']);
        }
        if($articleData){
            jsonpReturn('1','查询成功',$articleData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }


    public function risingStar(){       //明日之星
        $course = D('Course');
        $input = I('get.');
        $cateId = $input['cateId'];

        $where['user_id'] = '0';
        $where['price'] = '0';
        if($cateId){
        	$where['categoryid'] = $cateId;
        }
        $courseData = $course->where($where)->select();
        foreach ($courseData as $key => $value) {
            $courseData[$key]['cate_name'] = D('category')->where(array('id'=>$value['categoryid']))->getField('name');
        }
        if($courseData){
            jsonpReturn1('1','查询成功',$courseData);
        }else{
            jsonpReturn1('0','查询失败');
        }
    }
        public function videoCate(){
            $category = D('Category');
            $categoryData = $category->where(array('is_service'))->select();

        }

    public function videoZone(){          //视频专区
        $course = D('CourseRelation');
        $input = I('get.');
        $cateId = $input['cateId'];
        if($cateId){
            $where['categoryid'] = $cateId;
        }
        $courseData = $course->where($where)->relation('user1')->select();
        foreach ($courseData as $key => $value) {
            $courseData[$key]['cate_name'] = D('category')->where(array('id'=>$value['categoryid']))->getField('name');
        }
        if($courseData){
            jsonpReturn1('1','查询成功',$courseData);
        }else{
            jsonpReturn1('0','查询失败');
        }
    }

    public function liveZone(){        //直播专区
        $live = D('Videoing');
        $input = I('get.');
        $cateId = $input['cateId'];
        if($cateId){
            $where['categoryid'] = $cateId;
        }
        $liveData = $live->where($where)->select();
        foreach ($liveData as $key => $value) {
            $liveData[$key]['cate_name'] = D('category')->where(array('id'=>$value['categoryid']))->getField('name');
        }
        if($liveData){
            jsonpReturn1('1','查询成功',$liveData);
        }else{
            jsonpReturn1('0','查询失败');
        }
    }







    public function recomCourse(){     //推荐课程  需传入分类id(oneId) 和 默认显示 老师列(classId=1)/机构列(classId=2)
        $input = I('get.');            //查询分类需要二级分类id,cateId查询区域需要省province市city区area
        $oneId = $input['oneId'];
        $classId = $input['classId'] ? $input['classId'] : 1 ;

        $common = D('Common');
        $category = D('Category');
        $user = D('User1');
        $usercate = D('User1');
        $twoCateId = $category->where(array('pid'=>$oneId))->getField('id',true);
        $arrId = $usercate->where(array('categoryid'=>array('in',$twoCateId)))->getField('id',true);
        $where = $common->getSearchCond($input);
        if(empty($where['id'])){
            $where['id'] =array('in',$arrId);
        }
        $where['class'] = $classId ? $classId : 1;
        $userData = $user->where($where)->select();
        if($userData){
            jsonpReturn('1','查询成功',$userData);
        }else{
            jsonpReturn('0','查询失败');
        }
    }



    public function courseHotAdd(){       //人气加1
    	$course = D('Course');
        $courseId = I('get.courseId');
    	$result = $course->where(array('id'=>$courseId))->setInc('click',1);
    	if($result){
    		jsonpReturn('1','成功+1');
    	}else{
    		jsonpReturn('0','失败');
    	}
    }



    public function videoHotAdd(){       //人气加1
        $course = D('videoing');
        $videoId = I('get.videoId');
        $result = $course->where(array('id'=>$videoId))->setInc('clicknum',1);
        if($result){
            jsonpReturn('1','成功+1');
        }else{
            jsonpReturn('0','失败');
        }
    }

    public function user1HotAdd(){       //人气加1
        $user = D('User1');
        $userId = I('get.userId');
        $result = $user->where(array('id'=>$userId))->setInc('click',1);
        if($result){
            jsonpReturn('1','成功+1');
        }else{
            jsonpReturn('0','失败');
        }
    }





}







?>