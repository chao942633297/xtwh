<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function index(){
       $this->display();
    }

    public function userall(){
       $this->display();
    }

    public function xueyuan(){
       $this->display();
    }

     public function jigou(){
    	$this->display();
    }

    public function do_jigou(){
    	$_POST['title'] = trim($_POST['title']);
    	$row = M('user1')->where(array('title'=>$_POST['title']))->select();
    	if($row){
    		$this->success('机构已存在');
    	}
    	$_POST['detail']   = trim($_POST['detail']);
    	$arr =  explode('-',$_POST['city']); 
    	$_POST['province'] = $arr[0];
    	$_POST['city']     = $arr[1];
    	$_POST['area']     = $arr[2];
    	$upload = new \Think\Upload();
		$upload->maxSize   =     3145728 ;  
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
		$upload->savePath  =      '/jigou_pic/'; 
		$upload->saveName  = md5(time().'_'.mt_rand());
		$upload->autoSub   = true;
		$upload->subName   = array('date','Ymd');
		$info   =   $upload->upload();   		
		if(!$info) {
			$this->error($upload->getError());   
		}else{		       
		  	foreach($info as $file){
		  		$path =  __ROOT__.'/Uploads'.$file['savepath'].$file['savename'];   
		  	}
		}
		$_POST['logo'] = $path;
    	$_POST['created_at'] = time();

    	$r = M('user1')->add($_POST);

    	if($r){
    		$this->success('添加成功');
    	}else{
    		$this->error('添加失败');
    	}	
    }
}