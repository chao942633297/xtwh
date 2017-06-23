<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }

    #文章管理
    public function Article(){
    	$data = M('article')->field("id,title,desc,looknum,logo,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->select();
    	$this->assign("data",json_encode($data));
    	$this->display();
    }

    #文章详情
    public function info(){
    	$id = I('id');
    	$data = M('article')->where("id=%d",$id)->find();
    	$this->assign("data",$data);
    	$this->display();
    }

    #编辑文章
    public function edit(){
 		$id = I('id');
		$data = M('article')->where("id=%d",$id)->find();
		$this->assign("data",$data);
		$this->display();   	
    }

    #添加文章
    public function doSomething(){
    	$data = $_POST;
    	$id = I('post.id');
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

    	if (empty($id)) {
			$data['create_at'] = time();
			$res = M('article')->add($data);
			if ($res) {
				$this->success("添加成功",U('Admin/Article/article'));
			}else{
				$this->error("添加失败");
			}    		
    	}else{
    		$res = M('article')->save($data);
			if ($res) {
				$this->success("修改成功",U('Admin/Article/article'));
			}else{
				$this->error("修改失败");
			}    		
    	}

    }


    #删除文章
    public function del(){
    	$id = I('id');
    	$res = M('article')->where("id=%d",$id)->save(['is_del'=>1]);
    	if ($res) {
    		echo true;
    	}else{
    		echo false;
    	}
    }
 

    #活动管理
    public function activity(){
        $activity = M('article')->where("type = 4 ")->select();
        $ac       = M('activity')->select();
        if (empty($activity)) {
            $activity = [];
        }else{
             foreach ($activity as $k => $v) {
                $activity[$k]['create_at'] = date("Y-m-d H:i:s",$v['create_at']);
                $activity[$k]['num'] = 0;
                foreach ($ac as $k1 => $v1) {
                    if ($v['id'] == $v1['aid']) {
                        $activity[$k]['num'] += 1;
                    }
                }
            }           
        }

        $this->assign("data",json_encode($activity));
        $this->display();
    }


    #活动人员
    public function activityUser(){
        $id   = I('id');
        $user = M('activity')->where("aid=%d",$id)->getField("uid",true);
        $user1 = M('activity')->where("aid=%d",$id)->select();
        if (empty($user)) {
            $userinfo = [];
        }else{
            $user = implode(",",$user);
            $where["id"] = array("in",$user);
            $userinfo = M('user2')->field("id,name,phone,province,city,area")->where($where)->select();
            foreach ($userinfo as $k => $v) {
                foreach ($user1 as $key => $value) {
                    if ($v['id'] == $value['uid']) {
                        $userinfo[$k]['create_at'] = date("Y-m-d H:i:s",$value['create_at']);
                    }
                }
            }            
        }
        $this->ajaxReturn($userinfo);
    }

}