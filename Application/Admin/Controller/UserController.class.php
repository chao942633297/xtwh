<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function index(){
       $data = M('user2')->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));

       $this->display();
    }

    public function add(){
        $this->display();
    }
   
}