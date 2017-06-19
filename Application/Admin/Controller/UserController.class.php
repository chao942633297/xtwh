<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller
{
    /**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }   

    //首页
    public function index(){
        // FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s')
       $data = M('user2')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d') as create_at")->order('create_at desc')->select();

       $back = M('backmoney')->field("u2id,sum(money) as money")->group('u2id')->select();  //基金
       
       foreach ($data as $k => $v) {
            foreach ($back as $ke => $va) {
                if ($v['id'] == $va['u2id']) {
                    $data[$k]['backmoney'] = $va['money'];
                }
            }
           foreach ($data as $k1 => $v1) {
              if ($v['refereeid'] == $v1['id']) {
                  $data[$k]['fatherphone'] = $v1['phone'];
              }
           }
       }
       $this->assign('data',json_encode($data));
       $this->display();
    }

    public function add(){
        $this->display();
    }

    //添加会员
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
       if ($_POST['grade'] == "-1") {
           unset($_POST['grade']);
       }
       $_POST['password']    = md5('a123456');
       $_POST['twopassword'] = md5('a123456');
       $_POST['create_at']   = time();
       $id = M('user2')->add($_POST);
       if($id){
            $this->success('添加成功',U('Admin/User/index'));
        }else{
            $this->error('添加失败');
        }
    }

    //会员信息页面
    public function info(){
        $id = I('id');
        $data = M('user2')->field("id,phone,name,nickname,headimg,class,grade,province,city,area,address")->where("id=%d",$id)->find();
        $this->assign("data",$data);
        $this->display();
    }

    //修改会员
    public function save(){
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
        $res = M('user2')->save($_POST);
        if ($res) {
            $this->success("修改成功!",U('Admin/User/index'));
        }else{
            $this->error("修改失败");
        }       
    }

    //修改上级
    public function updatePid(){
        $phone = I('phone');
        $id    = I('id');
        $pid = M('user2')->where(["phone"=>$phone])->getField("id");
        if (empty($pid) || $pid == false || $pid == null) {
             $this->ajaxReturn(["status"=>0,"msg"=>"平台没有此用户"]);
        }else{
            $data["refereeid"] = $pid;
            $data["id"] = $id;
        } 
        if(M('user2')->save($data)){
            $this->ajaxReturn(["status"=>1,"msg"=>"修改成功"]);
        }else{
             $this->ajaxReturn(["status"=>0,"msg"=>"修改失败"]);
        }
    }

    //根据手机号和等级搜索
    public function searchUser(){
        $phone = I('phone');
        $grade = I('grade');
        if (!empty($phone)) {
            $where['phone'] = ["like","%".$phone."%"];
        }

        if ($grade != -1) {
            $where['grade'] = $grade;
        }

       $data = M('user2')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d') as create_at")->where($where)->order('create_at desc')->select();

       if (empty($data)) {
           $data = [];
       }else{
            $back = M('backmoney')->field("u2id,sum(money) as money")->group('u2id')->select();  //基金
           foreach ($data as $k => $v) {
                foreach ($back as $ke => $va) {
                    if ($v['id'] == $va['u2id']) {
                        $data[$k]['backmoney'] = $va['money'];
                    }
                }
               foreach ($data as $k1 => $v1) {
                  if ($v['refereeid'] == $v1['id']) {
                      $data[$k]['fatherphone'] = $v1['phone'];
                  }
               }
           }        
       }

       $this->ajaxReturn($data);
    }

    //重置密码
    // public function reset_pwd(){
    //     $id = isset($_GET['id'])?$_GET['id']:'';
    //     if(!$id){
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));      
    //     }
    //     $password = md5('a123456');
    //     $row = M('user2')->where(array('id'=>$id))->find();
    //     if($row['password'] == $password){
    //         $this->ajaxReturn(array('code'=>1,'msg'=>'重置成功')); 
    //     }
    //     $r = M('user2')->where(array('id'=>$id))->save(array('password'=>$password));  
    //     if($r){
    //         $this->ajaxReturn(array('code'=>1,'msg'=>'重置成功'));  
    //     }else{
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'重置失败'));  
    //     }
    // }

    //激活级别
    // public function jihuo(){

    //     $id = isset($_POST['id'])?$_POST['id']:'';
    //     $grade = isset($_POST['grade'])?$_POST['grade']:'';
    //     $onemoney = isset($_POST['onemoney'])?$_POST['onemoney']:'';
    //     if(empty($id)){
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));      
    //     }
    //     if(empty($grade)){
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'激活等级不对'));      
    //     }
    //     if(empty($onemoney)){
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'金额不能为空'));      
    //     }
    //     $r = M('user2')->where(array('id'=>$id))->find();
    //     if($r['grade'] >= $grade){
    //          $this->ajaxReturn(array('code'=>0,'msg'=>'级别选择错误'));   
    //     }

    //     if($grade == 1){
    //         if($onemoney<900){
    //              $this->ajaxReturn(array('code'=>0,'msg'=>'激活VIP至少需要直营充值900'));  
    //         }
    //     }else if($grade == 2){
    //         if($onemoney<1800){
    //              $this->ajaxReturn(array('code'=>0,'msg'=>'激活银卡--VIP至少需要直营充值1800'));  
    //         }
    //     }else if($grade == 3){
    //         if($onemoney<3600){
    //              $this->ajaxReturn(array('code'=>0,'msg'=>'激活金卡--VIP至少需要直营充值3600'));  
    //         }
    //     }else if($grade == 5){
    //         if($onemoney<7200){
    //              $this->ajaxReturn(array('code'=>0,'msg'=>'激活合伙人至少需要直营充值7200'));  
    //         }
    //     }
    //     $data['grade'] = $grade;
    //     $data['onemoney'] = $onemoney;
    //     $row = M('user2')->where(array('id'=>$id))->save($data);  
    //     if($r){
    //         $this->ajaxReturn(array('code'=>1,'msg'=>'激活成功'));  
    //     }else{
    //         $this->ajaxReturn(array('code'=>0,'msg'=>'激活失败'));  
    //     }
    // }

    //删除用户
    public function del(){
        $id = I('id');
        $res = M('user2')->where("id=%d",$id)->delete();
        if ($res) {
            echo true;
        }else{
            echo false;
        }
    }


    // -----------------------------家长的小孩管理---------------------------------------
    #小孩管理首页
    public function student(){

        $data = M('children')->field("id,fid,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->select();
        $pid  = M('children')->getField("fid",true);
        if (empty($pid)) {
            $pidinfo = [];
        }else{
            $pidinfo = M('user2')->field("id,phone,name")->where(['fid'=>["in",$pid]])->select();
            foreach ($pidinfo as $k => $v) {
                foreach ($data as $ke => $va) {
                    if ($va["fid"] == $v['id']) {
                        $pidinfo[$k]["create_at"] = $va['create_at']; 
                    }
                }
            }             
        }
        $this->assign("data",json_encode($pidinfo));
        $this->display();
    }

    #搜索小孩
    public function searchChildren(){
        $phone = I('phone');
        $where["phone"] = ["like","%".$phone."%"];
        $fidall = M('user2')->where($where)->getField("id",true);
        if (empty($fidall) || $fidall == false) {
            $userinfo = [];
        }else{
            $child = M('children')->field("id,fid,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where(["fid"=>["in",$fidall]])->select();
            $userinfo = M('user2')->field("id,phone,name")->where($where)->select();
            foreach ($userinfo as $k => $v) {
                foreach ($child as $ke => $va) {
                    if ($va["fid"] == $v['id']) {
                        $userinfo[$k]["create_at"] = $va['create_at']; 
                    }
                }
            } 
        }
        $this->ajaxReturn($userinfo);
    }
}