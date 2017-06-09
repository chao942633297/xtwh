<?php
namespace Admin\Controller;
use Think\Controller;

class CourseController extends Controller {
	
    public function index(){
    	$this->display();
    }
    public function add(){
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