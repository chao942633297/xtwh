<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function index(){
       $data = M('user1')->where('pid = 0')->select();
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
    	$data['address']  = trim($_POST['hcity']).trim($_POST['hproper']).trim($_POST['harea']).trim($_POST['address']);
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

     public function jiaoshi(){
     	$id = isset($_GET['id'])?$_GET['id']:0;
     	
    	$data = M('user1')->where('class = 2')->select();
    	$this->assign('data',$data);
    	$this->assign('pid',$id);
    	$this->display();
    }

    public function do_jiaoshi(){
    	$data = array();  
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
		$data['title']	  = trim($_POST['title']);
		$data['pid']	  = $_POST['pid'];
    	$data['province'] = trim($_POST['hcity']);
    	$data['city']     = trim($_POST['hproper']);
    	$data['area']     = trim($_POST['harea']);    	
    	$data['address']  = trim($_POST['hcity']).trim($_POST['hproper']).trim($_POST['harea']).trim($_POST['address']);
        $data['detail']   = trim($_POST['detail']);
		$data['logo']  	  = $path;
		$data['teacherage']     = trim($_POST['teacherage']);
		$data['motto']     = trim($_POST['motto']);
		$data['level']     = trim($_POST['level']);
		$data['class']     = $_POST['class'];
    	$data['create_at'] = time();

    	$r = M('user1')->add($data);
    	if($r){
    		$this->success('添加成功');
    	}else{
    		$this->error('添加失败');
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