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
        $class = M('category')->where("pid > 0")->select();
        $this->assign("type",$class);
    	$this->display();
    }

    public function edit_jiaoshi(){
    	$data = M('user1')->where(array('id'=>$_GET['id']))->find();
    	$this->assign('data',$data);
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
        $data['rebate']       = $_POST['rebate']; 
		$data['phone']        = $_POST['phone'];  
		$data['address']      = trim($_POST['address']);
        $data['detail']       = trim($_POST['detail']);		
		$data['teacherage']   = trim($_POST['teacherage']);
		$data['motto']        = trim($_POST['motto']);
		$data['level']        = trim($_POST['level']);
		$data['class']        = 1;  	
    	$row = M('user1')->where(array('title'=>$data['title'],'pid'=>$_POST['pid']))->find();
    	
    	if($type == 'edit'){
    		// var_dump($data);exit;
    		$data['update_at'] = time();
    		$id = M('user1')->where(array('id'=>$_POST['id']))->save($data);
	    	if($id){
	    		// var_dump($data);exit;
	    		$zid = M('user1')->where(array('id'=>$_POST['id']))->find();
	    		
	    		$this->success('编辑成功',U("/Admin/Teacher/index"));
                // exit("<script>alert('编辑成功!');history.go(-1)</script>");
	    	}else{
	    		$this->error('编辑失败');
	    	}	
    	}elseif($type == 'add'){
    		$data['create_at'] = time();
    		$id = M('user1')->add($data); //添加老师到user1表中
            $typeid = explode(",",I('classall'));
            $dd = [];
            foreach ($typeid as $k => $v) {
                $dd[$k]["user1_id"] = $id;
                $dd[$k]["categoryid"] = $v;   
            }
            $res = M('usercate')->addAll($dd); //添加类别到usercate表中
	    	if($res){
	    		$r = $this->tuiguang($id);
	    		if($r){
	    			$this->success('添加成功',U('Admin/Teacher/index'));
	    		}else{
	    			M('user1')->where(array('id'=>$id))->delete();
	    			$this->error('添加失败');
	    		}
	    		
	    	}else{
	    		$this->error('添加失败');
	    	}	
    	}
    
    }

     public function tuiguang($id){
    	$r = M('user1')->where(array('id'=>$id))->find();
        
    	$da = array();
    	$da['u1id']        = $id;
    	$da['nickname']    = $r['title'];
    	$da['password']    = md5('a123456');
    	$da['twopassword'] = md5('a123456');
    	$da['phone']       = $r['phone'];
    	$da['class']       = 3;//教师
    	$da['grade']       = 0;//路人甲
    	$da['address']     = $r['address'];
    	$da['province']    = $r['province'];
    	$da['city']        = $r['city'];
    	$da['area']        = $r['area'];
    	$da['headimg']     = $r['logo'];
    	$da['source']      = 1;
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


    public function search(){
       $phone = I('phone');
       $map['phone'] = array('like','%'.$phone.'%');
       $data  = M('user1')->where('pid = 0 AND class = 1')->where($map)->select();
       if (empty($data) || $data == false ) {
            $data= [];
       }
       $this->ajaxReturn($data);
    }
}
