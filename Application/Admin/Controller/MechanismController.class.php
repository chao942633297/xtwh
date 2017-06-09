<?php
namespace Admin\Controller;
use Think\Controller;

class MechanismController extends Controller
{
    public function index(){
       $data = M('user1')->where('pid = 0 AND class = 2')->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));
       $this->display();
    }

    public function jigou(){
    	$this->display();
    }

    public function edit_jigou(){
    	$data = M('user1')->where(array('id'=>$_GET['id']))->find();
    	$this->assign('data',$data);
    	$this->display();
    }

    public function do_jigou(){  

    	$type = $_POST['type']; 	
    	$data = array();
    	$data['title']	  = trim($_POST['title']);
    	if($_POST['hcity']){
    		$data['province'] = trim($_POST['hcity']);
	    	$data['city']     = trim($_POST['hproper']);
	    	$data['area']     = trim($_POST['harea']); 
    	}
    	$data['phone']        = $_POST['phone'];    	
    	$data['address']      = trim($_POST['address']);
        $data['detail']       = trim($_POST['detail']);  	
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
			$data['logo']  = $path;
		}
		
		$data['class'] = 2;    	
    	if($type == 'add'){
    		$row = M('user1')->where(array('title'=>$_POST['title']))->select();
    		if($row){    		
    			$this->error('机构已存在');
    		}  
    		$data['create_at'] = time();
    		$id = M('user1')->add($data);

	    	if($id){
	    		$d = $this->tuiguang($id);
	    		if($d){
	    			$this->success('添加成功','/Admin/Mechanism/index');
	    		}else{
                    M('user1')->where(array('id'=>$id))->delete();
	    			$this->error('添加失败');
	    		}	    		
	    	}else{
	    		$this->error('添加失败');
	    	}	
    	}elseif($type == 'edit'){
    		
    		$data['update_at'] = time();
    		$r = M('user1')->where(array('id'=>$_POST['id']))->save($data);
	    	if($r){
	    		$this->success('修改成功','/Admin/Mechanism/index');
	    	}else{
	    		$this->error('修改失败');
	    	}	
    	}
    	
    }

    public function jiaoshi(){
     	// var_dump($_GET);exit;
     	$pid = isset($_GET['pid'])?$_GET['pid']:''; 
     	if(!$pid){
     		$this->error('数据读取错误');
     	}  
    	$this->assign('pid',$pid);
    	$this->display();
    }

    public function tream(){
     	$id = isset($_GET['id'])?$_GET['id']:''; 
     	if(!$id){
     		$this->error('数据读取错误');
     	} 
     	$data = M('user1')->where('pid = '.$id)->select();
        $name = M('user1')->where('id = '.$id)->find();
        $this->assign('name',$name['title']);
    	$this->assign('pid',$id);
    	$this->assign('data',json_encode($data));
    	$this->display();
    }
    public function edit_jiaoshi(){
    	$r = M('user1')->where(array('id'=>$_GET['id']))->find();
    	$this->assign('pid',$r['pid']);
    	$this->assign('data',$r);
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
		$data['pid']	  = $_POST['pid'];
		if($_POST['hcity']){
			$data['province'] = trim($_POST['hcity']);
	    	$data['city']     = trim($_POST['hproper']);
	    	$data['area']     = trim($_POST['harea']);    	
	    	
		}   
        $data['phone']       = $_POST['phone']; 
		$data['address']     = trim($_POST['address']);
        $data['detail']      = trim($_POST['detail']);		
		$data['teacherage']  = trim($_POST['teacherage']);
		$data['motto']       = trim($_POST['motto']);
		$data['level']       = trim($_POST['level']);
		$data['class']       = $_POST['class'];  	
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
    		$id = M('user1')->add($data);
	    	if($id){
            	// $r = $this->tuiguang($id);
             //    if($r){
                  $this->success('添加成功','/Admin/Mechanism/tream/id/'.$_POST['pid']);      
              // }else{
              //   M('user1')->where(array('id'=>$id))->delete();
              //    $this->error('添加失败');
              // }
	    		    		
	    	}else{
	    		$this->error('添加失败');
	    	}	
    	}
    
    }

    public function tuiguang($id,$){
    	$r = M('user1')->where(array('id'=>$id))->find();
        
    	$da = array();
    	$da['u1id']        = $id;
    	$da['nickname']    = $r['title'];
    	$da['password']    = md5('a123456');
    	$da['twopassword'] = md5('a123456');
    	$da['phone']       = $r['phone'];
    	$da['class']       = 2;//机构
    	$da['grade']       = 0;//路人甲
    	$da['address']     = $r['address'];
    	$da['province']    = $r['province'];
    	$da['city']        = $r['city'];
    	$da['area']        = $r['area'];
    	$da['headimg']     = $r['logo'];
    	$da['create_at']   = time();
    	$res = M('user2')->where(array('u1id'=>$id))->find();
    	if(!$res){
    		$row = M('user2')->add($da);
    		if($row){
    			return true;
    		}else{
    			return false;
    		}
    	}
    	
    }

 //  public  function getpassword( $length = 6 ) { 
		 
	// 	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 
	// 	$password = ''; 
	// 	for ( $i = 0; $i < $length; $i++ ){
	// 		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
	// 	} 
	// 	return $password; 
	// } 
   
    public function del_jiaoshi(){
    	$r = M('user1')->where(array('id'=>$_GET['id']))->delete();
    	if($r){
    		$this->ajaxReturn(array('code'=>1,'msg'=>'删除成功'));	
    	}else{
    		$this->ajaxReturn(array('code'=>0,'msg'=>'删除失败'));	
    	}
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