<?php
namespace Admin\Controller;
use Think\Controller;

class TeacherController extends Controller
{
    public function index(){
       $data = M('user1')->where('pid = 0 AND class = 1')->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));
       $this->display();
    }

     public function jiaoshi(){
     	$id = isset($_GET['id'])?$_GET['id']:0;
     	
    	$data = M('user1')->where('class = 2')->select();
    	$this->assign('data',$data);
    	$this->assign('pid',$id);
    	$this->display();
    }

    public function do_jiaoshi(){
    	$type = $_POST['type'];
    	
    	$data = array(); 
    	if(!empty($_FILES['photo']['name'])){ 
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
			$data['logo'] = $path;
		}
		$data['title']	  = trim($_POST['title']);
		if($_POST['hcity']){
			$data['province'] = trim($_POST['hcity']);
	    	$data['city']     = trim($_POST['hproper']);
	    	$data['area']     = trim($_POST['harea']);    	
	    	
		}    
		$data['address']  = trim($_POST['address']);
        $data['detail']   = trim($_POST['detail']);		
		$data['teacherage']     = trim($_POST['teacherage']);
		$data['motto']     = trim($_POST['motto']);
		$data['level']     = trim($_POST['level']);
		$data['class']     = $_POST['class'];  	
    	$row = M('user1')->where(array('title'=>$data['title'],'pid'=>$_POST['pid']))->find();
    	
    	if($type == 'edit'){
    		// var_dump($data);exit;
    		$data['update_at'] = time();
    		$r = M('user1')->where(array('id'=>$_POST['id']))->save($data);
	    	if($r){
	    		// var_dump($data);exit;
	    		$zid = M('user1')->where(array('id'=>$_POST['id']))->find();
	    		
	    		$this->success('编辑成功','/Admin/Mechanism/tream/id/'.$zid['pid']);
	    	}else{
	    		$this->error('编辑失败');
	    	}	
    	}elseif($type == 'add'){
    		if($row){
    			$this->error('该机构已经添加该老师');
    		}
    		$data['create_at'] = time();
    		$r = M('user1')->add($data);
	    	if($r){
	    		$this->success('添加成功','/Admin/Mechanism/tream/id/'.$_POST['pid']);
	    	}else{
	    		$this->error('添加失败');
	    	}	
    	}
    
    }

    public function info(){
    	$this->assign('id',$_GET['id']);
    	$this->display();
    }

    public function kecheng(){
    	$data = M('category')->where('pid = 0 AND is_service = 1')->select();   	
    	$this->assign('data',$data);
    	$this->display();
    }
    public function kemu(){
    	if($_POST['id']){
	    	$data = M('category')->where('pid = '.$_POST['id'])->select();   	
	    	$this->ajaxReturn($data);
    	}   		
    }
}
