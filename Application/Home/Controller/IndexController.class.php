<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{


    public function index(){      //首页
        $category = D('Category');
        $common = D('Common');
        $lunbo = D('Lunbo');
        $topCate = $category->where(array('pid'=>'0','is_service'=>1))->select();  //一级分类
        $cateList = $common->getLoopCate();           //分类child为子分类
        $lunboData = $lunbo->select();
        if($cateList){
            jsonpReturn('1','查询成功',array('topCate'=>$topCate,'cateList'=>$cateList,'lunboData'=>$lunboData));
        }else{
            jsonpReturn('0','查询失败');
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





    public function findActive(){    //找活动
        $article = D('Article');
        $articleData = $article->where(array('type'=>4))->select();
        
        if($articleData){
            jsonpReturn('1','查询成功',$articleData);
        }else{
            jsonpReturn('1','查询成功');
        }

    }

    public function findService(){        //找服务 页面  返回分类表 一级分类
        $category = D('Category');
        $where['pid'] = '0';
        $where['is_service'] = '2';
        $categoryData = $category->where($where)->select();
        if($categoryData){
            jsonpReturn('1','成功',$categoryData);
        }
    }

    public function searchService(){      //找服务内搜索   传入服务id
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
        }
    }





    public function test(){
        $secretKey = '5e9480a7778c62a7b0c3c5b3400bb2cc';
        $url = 'http://api.letvcloud.com/open.php';
        $time = time();
        $uuid = '82128f0e5b';
        $api = 'video.upload.init';
        $name = '123456';
//        $data ='user_unique='.$uuid.'&timestamp='.$time.'&api='.$api.'&format=json&ver=2.0';
//        $sign = md5($data.'&secretKey='.$secretKey);
//        $urlData = urlencode($data).'&sign='.$sign.'&video_name='.$name;

        $data['user_unique'] = $uuid;
        $data['timestamp'] = $time;
        $data['api'] = $api;
        $data['format'] = 'json';
        $data['ver'] = '2.0';
        $data['secretKey'] = $secretKey;
        $data['sign'] = md5($this->ToUrlParams($data));
        $data['name'] = '123456';

        $return =  http($url,$data,'POST');
        var_dump($return);
        die();
    }


    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }























}







?>