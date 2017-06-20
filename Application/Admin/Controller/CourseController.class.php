<?php
namespace Admin\Controller;
use Think\Controller;

class CourseController extends Controller {
	protected static $video_url = "http://api.quklive.com/cloud/services/user/onlineLives"; //直播房间列表
    // protected static $video_url =

    public function index(){
        $data = getConfig();
        $result = json_decode(http(self::$video_url,json_encode($data),'POST'),true);  
        $arr = $result['value'];    
        if (!empty($arr)) {
            M('video')->add($arr);
        }
    	$this->display();
    }

    // http://v.youku.com/v_show/id_XMjgxMjgzNjkxNg==.html?spm=a2hww.20023042.m_223473.5~5~5~5~A
    public function insert(){
    	$type = $_POST['type'];
    	// var_dump($_POST);exit;
    	$data = array();
    	$data['categoryid'] = $_POST['categoryid'];
    	$data['kecheng_id'] = $_POST['kecheng_id'];
    	$data['kecheng_id'] = $_POST['kecheng_id'];
    	$data['user_id']    = $_POST['user_id'];
    	$data['title']      = trim($_POST['title']);
    	if(!empty($_FILES['photo']['name'])){
			$upload = new \Think\Upload();// 实例化上传类   
			$upload->maxSize   =     3145728 ;// 设置附件上传大小    
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
			$upload->savePath  =      '/course/'; // 设置附件上传目录   
			 // 上传文件     
			$upload->saveName = md5(time().'_'.mt_rand());
			// 开启子目录保存 并以日期（格式为Ymd）为子目录
			$upload->autoSub = true;
			$upload->subName = array('date','Ymd');
			$info   =   $upload->upload();   		
			if(!$info) {// 上传错误提示错误信息   
				$this->error($upload->getError());   
			}else{// 上传成功 			       
			  	foreach($info as $file){
			  		$path =  __ROOT__.'/Uploads'.$file['savepath'].$file['savename'];   
			  	}
			}
			$data['logo'] = $path;
		}
		$data['video']      = trim($_POST['video']);
		$data['description']= trim($_POST['description']);
		$data['start_time'] = $_POST['start_time'];
		$data['price']      = $_POST['price'];
		$data['discount']   = $_POST['discount'];
		$data['click']      = $_POST['click'];
		$data['status']     = $_POST['status'];
		$data['line']       = $_POST['line'];
		if($type == 'add'){
			$data['create_at']  = time();
			$id = M('course')->add($data);
			if($id){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}else if($type == 'edit'){
			$data['update_at']  = time();
			$r = M('course')->where(array('id'=>$_POST['id']))->save($data);
			if($r){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}
				
    }


// -----------------------------------趣看直播-----------------------------------------------------
// ----------------------------------------------------------------------------------------

}