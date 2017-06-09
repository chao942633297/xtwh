<?php
namespace Admin\Controller;
use Think\Controller;

class XueyuanController extends Controller
{
    public function index(){
    	// echo 2;
       $data = M('children')->order('create_at desc')->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));
    	
       $this->display();
    }

}