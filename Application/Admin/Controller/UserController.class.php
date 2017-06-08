<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function index(){
       $data = M('user1')->where(array('class'=>2))->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));
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
    	
    	$data = array();
    	$data['title']	  = trim($_POST['title']);
    	$data['province'] = trim($_POST['hcity']);
    	$data['city']     = trim($_POST['hproper']);
    	$data['area']     = trim($_POST['harea']);    	
    	$data['address']  = trim($_POST['address']).trim($_POST['hcity']).trim($_POST['hproper']).trim($_POST['harea']);
        $data['detail']   = trim($_POST['detail']);
    	$row = M('user1')->where(array('title'=>$_POST['title']))->select();
    	if($row){
    		$this->success('机构已存在');
    	}   	
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
		$data['logo']  = $path;
		$data['class'] = 2;
    	$data['create_at'] = time();

    	$r = M('user1')->add($data);

    	if($r){
    		$this->success('添加成功');
    	}else{
    		$this->error('添加失败');
    	}	
    }
}