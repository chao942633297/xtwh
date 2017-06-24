<?php
namespace Admin\Controller;
use Think\Controller;

class CourseController extends Controller {
	protected static $appKey    = "iey6u5sxi16asnci";	//键值
	protected static $appsecret = "AIyQo4Y9HRqbpmzy";	//私钥

	protected static $video_url_show = "http://api.quklive.com/cloud/services/user/onlineLives"; //直播房间列表
    protected static $video_url_adduser ="http://api.quklive.com/cloud/services/user/addMember";	//添加直播用户
    protected static $video_url_deluser ="http://api.quklive.com/cloud/services/user/delMember";	//删除直播用户
    protected static $video_url_updatepwd ="http://api.quklive.com/cloud/services/user/resetMemberPassword";	//删除直播用户



    #获取基本配置
    public function getConfig(){
		$nonce     = '1498095291'.rand(1,10);	//时间戳加随机数
		$qianming  = base64_encode(hash_hmac("sha1","appKey=".self::$appKey."&nonce=".$nonce,self::$appsecret,true));	
        return $data = ["nonce"=>$nonce,"signature"=>$qianming];
    }


	#添加直播用户
    public function addUser(){
    	//直播账号 为真实姓名, 密码为手机号
    	$uid  = I('id');
    	$uid = 15;
    	$yes = M('live')->where("uid=%d",$uid)->select();	//判断用户是否已存在
    	if ($yes[0]["id"] > 0 ) {
        	$this->ajaxReturn(["code"=>0,"msg"=>"此用户已存在"]);    		
    	}
    	$userinfo = M('user2')->field("id,name,phone")->where("id=%d",$uid)->find();
    	$pwd = $userinfo['phone'];
    	$name = $userinfo['name'];
    	$remark = "后台录入直播用户";
    	$data = $this->getConfig();
        $data["name"] = $name;
        $data["remark"] = $remark;
        $data["pwd"] = $pwd;
        $data["appKey"] = self::$appKey;
        $result = json_decode(http(self::$video_url_adduser,json_encode($data),'POST'),true);  
        if ($result['code'] != 0 ) {
        	// $this->error($result['msg']);
        	$this->ajaxReturn(["code"=>0,"msg"=>$result['msg']]);
        }else{
	        $arr = $result['value'];    
	        $dd['qkid'] = $arr['id'];
	        $dd['userid'] = $arr['userId'];
	        $dd['uid']    = $uid;
	        if (empty($arr['createTime'])) {
		        $dd['create_at'] = time();        	
	        }else{
		        $dd['create_at'] = $arr['createTime'];        	
	        }
	        $dd['remark'] =$remark;
	        $res = M('live')->add($dd);
        	if ($res) {
        		$this->ajaxReturn(["code"=>1,"msg"=>"添加成功"]);
        		// $this->success("添加成功",U('Admin/Course/userShow'));
        	}else{
        		$this->ajaxReturn(["code"=>0,"msg"=>"添加失败"]);
        		// $this->error("添加失败");
        	}
        }
    }


    #修改直播用户
    public function updatePwd(){
    	$data = $this->getConfig();
        $data["appKey"] = self::$appKey;
    	$data['memberId']    = I('pkid');
    	$data['newPassword'] = I('password');
        $result = json_decode(http(self::$video_url_updatepwd,json_encode($data),'POST'),true);  
        if ($result['code'] != 0 ) {
        	// $this->error($result['msg']);
        	$this->ajaxReturn(["code"=>0,"msg"=>$result['msg']]);
        }else{
        	$this->ajaxReturn(["code"=>1,"msg"=>"修改成功"]);
        }        

    }

    #删除直播用户
    public function delUser(){
    	$id = I('qkid');
    	$data = $this->getConfig();
    	$data["memberId"] = $id;
    	$data["appKey"] = self::$appKey;
        $result = json_decode(http(self::$video_url_deluser,json_encode($data),'POST'),true);  
        if ($result['code'] != 0 ) {
        	// $this->error($result['msg']);
        	$this->ajaxReturn(["code"=>0,"msg"=>$result['msg']]);
        }else{
        	$res = M('live')->where("qkid=%d",$id)->delete();
        	if ($res) {
	        	$this->ajaxReturn(["code"=>1,"msg"=>"删除成功"]);
        	}else{
	        	$this->ajaxReturn(["code"=>1,"msg"=>"删除失败"]);
        	}
        }

    }

//趣看参数
// function getConfig(){
//     $content = "appKey=iey6u5sxi16asnci&nonce=".time().rand(1,10);
//     $appsecret = "AIyQo4Y9HRqbpmzy";
//     $qianming = base64_encode(hash_hmac("sha1",$content,$appsecret,true));
//     $data = array();
//     $data = ["appKey"=>"iey6u5sxi16asnci","nonce"=>time().rand(1,10),"signature"=>$qianming];
//     return $data;
// }


    #直播用户管理
    public function userShow(){
    	$user = M('user2')->field("id,phone,name")->where(" u1id > 0 ")->select();
    	$data = M('live')->field("id,uid,qkid,create_at")->select(); 
    	if (empty($data) || $data == false) {
    		$data = [];
    	}else{
	    	foreach ($data as $k => $v) {
	    		$data[$k]['create_at'] = date("Y-m-d H:i:s",$v['create_at']);
	    		foreach ($user as $k1 => $v1) {
	    			if ($v['uid'] == $v1['id']) {
	    				$data[$k]['name'] = $v1['name']; 
	    				$data[$k]['phone'] = $v1['phone']; 

	    			}
	    		}
	    	}    		
    	}
    	$this->assign("user",$user);
    	$this->assign("data",json_encode($data));
    	$this->display();
    }

    # 直播房间管理
    public function roomShow()
    {
        $data  = M('videoing')->select();
        if (empty($data) || $data == false ) {
            $data = [];
        }else{
            $user  = M('live')->select();
            $user2 = M('user2')->field("id,name,phone")->where("u1id > 0 ")->select();            
            foreach ( $user as $k=>$v) {
                foreach ( $user2 as $k1=>$v1 ) {
                    if ($v['uid'] == $v1['id']) {
                        $user[$k]['name'] = $v1['name'];
                        $user[$k]['phone'] = $v1['phone'];
                    }
                }
            }

            foreach ( $data as $k=>$v ) {
                foreach ( $user as $k1=>$v1 ) {
                    if ($v['qkid'] == $v1['qkid'] ) {
                        $data[$k]['name'] = $v1['name'];
                        $data[$k]['phone'] = $v1['phone'];
                        $data[$k]['uid'] = $v1['uid'];
                    }
                }
            }
        }

        $this->assign("data",json_encode($data));
        $this->display();

    }
// --------------------------------------------------------------------------------------------------------------
// --------------------------------------------课程视频管理------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------

    #视频展示
    public function index(){
        $id = I('id');
        if (empty($id)) {
            $data = M('course')->select();
            $user = M('user2')->field("id,nickname,phone")->select();
        }else{
            $user = M('user2')->field("id,nickname,phone")->where("u1id = %d",$id)->select();            
            $data = M('course')->where(["user_id"=>$user[0]["id"]])->select();
        }
        $cate = M('category')->where("pid > 0 ")->select();
        if (empty($data)) {
        	$data = [];
        }else{
	    	foreach ($data as $k => $v) {
	    		foreach ($cate as $k1 => $v1) {
	    			if ($v['categoryid'] == $v1['id']) {
	    				$data[$k]['categoryname'] = $v1['name'];
	    			}
	    		}

	    		foreach ($user as $k2 => $v2) {
	    			if ($v['user_id'] == $v2['id']) {
	    				$data[$k]['username'] = $v2['nickname'];
	    				$data[$k]['userphone'] = $v2['phone'];
	    			}
	    		}
	    	}        	
        }

    	$this->assign("data",json_encode($data));
    	$this->display();
    }

    #添加视频到乐视页面 
    public function add(){
    	$category = M('category')->where("pid > 0 ")->select();
    	$user     = M('user2')->field("id,nickname")->where("u1id > 0 ")->select();
    	$this->assign("category",$category);
    	$this->assign("user",$user);
    	$this->display();
    }              

	#添加视频到乐视--存储到数据库
    public function insert(){
        $data = $_POST;
        if ($data['type'] == "add") {
            unset($data['type']);
            unset($data['id']);
            $data['video_id'] = $_SESSION['video_id'];
            $data['video_unique'] = $_SESSION['video_unique'];    
            $data['create_at']  = time();
            $data['start_time'] =  strtotime($_POST['start_time']); 
            $res = M('course')->add($data);                   
        }else{
            unset($data['type']);
            $res = M('course')->save($data);                   
        }

        if ($res) {
            $this->success("添加成功",U('Admin/Course/index'));
        }else{
            $this->error("添加失败");
        }
    }

    public function edit(){
    	$id = I('id');
    	$data = M('course')->where("id=%d",$id)->find();
    	$category = M('category')->where("pid > 0 ")->select();
    	$this->assign("category",$category);    	
    	$this->assign("data",$data);
    	$this->display();
    }

    #获取视频上传的api
    // public function getApi(){
    //     $url = "http://api.letvcloud.com/open.php";
    //     $time = time();
    //     $uuid = C('UUID');
    //     $secretKey = C('secretKey');
    //     $name = I('post.name');
    //     $data = "apivideo.upload.initformatjsontimestamp".$time."user_unique".$uuid."ver2.0video_name".$name;
    //     $dat = $data.$secretKey;
    //     $sign = md5($dat);
    //     $data2 = ["api"=>"video.upload.init","format"=>"json","timestamp"=>$time,"user_unique"=>$uuid,"ver"=>"2.0","video_name"=>$name,"sign"=>$sign];

    //     $result = LetvHttp($url,$data2,'POST');
    //     $this->ajaxReturn($result);
    // }


}