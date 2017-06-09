<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    public function index(){
       $data = M('user2')->order('create_at desc')->select();
       // var_dump($data);
       $this->assign('data',json_encode($data));

       $this->display();
    }

    public function add(){
        $this->display();
    }

    public function insert(){
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
            $_POST['headimg'] = $path;
       }
       $_POST['password']    = md5('a123456');
       $_POST['twopassword'] = md5('a123456');
       $_POST['create_at']   = time();
       $id = M('user2')->add($_POST);
       if($id){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }

    public function reset_pwd(){
        $id = isset($_GET['id'])?$_GET['id']:'';
        if(!$id){
            $this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));      
        }
        $password = md5('a123456');
        $row = M('user2')->where(array('id'=>$id))->find();
        if($row['password'] == $password){
            $this->ajaxReturn(array('code'=>1,'msg'=>'重置成功')); 
        }
        $r = M('user2')->where(array('id'=>$id))->save(array('password'=>$password));  
        if($r){
            $this->ajaxReturn(array('code'=>1,'msg'=>'重置成功'));  
        }else{
            $this->ajaxReturn(array('code'=>0,'msg'=>'重置失败'));  
        }
    }

   
}