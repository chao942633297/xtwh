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
    	$data = M('article')->field("id,title,desc,type,class,looknum,logo,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("is_del = 0")->select();
        if (empty($data) || $data == false) {
            $data = [];
        }else{
            $type = M('category')->field("id,name")->where("is_service =2 ")->select();
            foreach ($data as $k => $v) {
                foreach ($type as $k1 => $v1) {
                    if ($v1['id'] == $v['class']) {
                        $data[$k]['class'] = $v1['name'];
                    }
                }
            }            
        }

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
        $activity = M('article')->field("id,title,desc,type,class,looknum,logo,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("type = 4 and is_del= 0 ")->select();
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




    #服务管理
    public function service(){
        $article = M('article');
        $data = $article->field("id,title,desc,type,class,looknum,logo,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("type = 0 and is_del =0 ")->select();
        $this->assign("data",json_encode($data));
        $this->display();
   }

   #添加服务文章
   public function addservice(){
	   	$type = I('type');
	   	if ($type == 2 ) {
		   	$info = M('article')->where(['id'=>I('id')])->find();
		   	$this->assign("data",$info);
	   	}
        $cate = M('category')->field("id,name")->where("is_service = 2")->select();
        $user = M('user2')->field("id,name,phone")->where("u1id > 0 ")->select();
        $this->assign("cate",$cate);
        $this->assign("user",$user);
        $this->assign("type",$type);
        $this->display();
   }

   #保存添加的服务文章
   public function saveService(){
        $type  = I('type');
        if ($type == 1) { //type = 1 为添加
	        if (I('lunbo') != "") {
	            $arr = array_filter(explode("*|*",I('lunbo')));
	        }   
	        $data = $_POST;
	        unset($data['lunbo']);
	        unset($data['id']);
	        unset($data['type']);
	        $data['create_at'] = time();
	        $res = M('article')->add($data);
	        if ($res) {
	        	$dd = [];
	        	foreach ($arr as $k => $v) {
	        		$dd[] = array("img"=>$v,"pid"=>$res);
	        	}
	        	$rr = M('lunbo')->addAll($dd);
	        	if ($rr) {
	           		$this->ajaxReturn(['code'=>1,'msg'=>'添加成功']);
	        	}else{
	            	$this->ajaxReturn(['code'=>0,'msg'=>'添加失败']);
	        	}
	        }else{
	            $this->ajaxReturn(['code'=>0,'msg'=>'添加失败']);
	        }        	
        }else{
	        $data = $_POST;
	        unset($data['type']);
	        unset($data['lunbo']);
	        $res = M('article')->save($data);
	        	if ($res) {
	           		$this->ajaxReturn(['code'=>1,'msg'=>'编辑成功']);
	        	}else{
	            	$this->ajaxReturn(['code'=>0,'msg'=>'编辑失败']);
	        	}
        }


   }

       //文本编辑器
    public function ueditor(){
        $data = new \Org\Util\Ueditor();
        echo $data->output();
    }

}