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

    //重置密码
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

    //激活级别
    public function jihuo(){

        $id = isset($_POST['id'])?$_POST['id']:'';
        $grade = isset($_POST['grade'])?$_POST['grade']:'';
        $onemoney = isset($_POST['onemoney'])?$_POST['onemoney']:'';
        if(empty($id)){
            $this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));      
        }
        if(empty($grade)){
            $this->ajaxReturn(array('code'=>0,'msg'=>'激活等级不对'));      
        }
        if(empty($onemoney)){
            $this->ajaxReturn(array('code'=>0,'msg'=>'金额不能为空'));      
        }
        $r = M('user2')->where(array('id'=>$id))->find();
        if($r['grade'] >= $grade){
             $this->ajaxReturn(array('code'=>0,'msg'=>'级别选择错误'));   
        }

        if($grade == 1){
            if($onemoney<900){
                 $this->ajaxReturn(array('code'=>0,'msg'=>'激活VIP至少需要直营充值900'));  
            }
        }else if($grade == 2){
            if($onemoney<1800){
                 $this->ajaxReturn(array('code'=>0,'msg'=>'激活银卡--VIP至少需要直营充值1800'));  
            }
        }else if($grade == 3){
            if($onemoney<3600){
                 $this->ajaxReturn(array('code'=>0,'msg'=>'激活金卡--VIP至少需要直营充值3600'));  
            }
        }else if($grade == 5){
            if($onemoney<7200){
                 $this->ajaxReturn(array('code'=>0,'msg'=>'激活合伙人至少需要直营充值7200'));  
            }
        }
        $data['grade'] = $grade;
        $data['onemoney'] = $onemoney;
        $row = M('user2')->where(array('id'=>$id))->save($data);  
        if($r){
            $this->ajaxReturn(array('code'=>1,'msg'=>'激活成功'));  
        }else{
            $this->ajaxReturn(array('code'=>0,'msg'=>'激活失败'));  
        }
    }

   
}