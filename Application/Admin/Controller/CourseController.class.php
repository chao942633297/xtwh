<?php
namespace Admin\Controller;
use Think\Controller;

class CourseController extends Controller {
	protected static $appKey    = "iey6u5sxi16asnci";	//键值
	protected static $appsecret = "AIyQo4Y9HRqbpmzy";	//私钥

	protected static $video_url_showroom = "http://api.quklive.com/cloud/services/user/onlineLives"; //直播房间列表
    protected static $video_url_adduser ="http://api.quklive.com/cloud/services/user/addMember";	//添加直播用户
    protected static $video_url_deluser ="http://api.quklive.com/cloud/services/user/delMember";	//删除直播用户
    protected static $video_url_updatepwd ="http://api.quklive.com/cloud/services/user/resetMemberPassword";	//修改直播用户密码
    protected static $video_url_createroom ="http://api.quklive.com/cloud/services/activity/create";    //创建活动
    protected static $video_url_updateroom ="http://api.quklive.com/cloud/services/activity/update";    //修改活动信息
    protected static $video_url_delroom ="http://api.quklive.com/cloud/services/activity/delete";    //删除活动
    protected static $video_url_prohibit_act ="http://api.quklive.com/cloud/services/activity/banLive";    //禁播活动
    protected static $video_url_noprohibit_act ="http://api.quklive.com/cloud/services/activity/retrieve";    //解禁活动
    protected static $video_url_videoing ="http://api.quklive.com/cloud/services/activity/isLive";    //查看活动是否直播中



    #获取基本配置
    public function getConfig(){
		$nonce     = '1498095291'.rand(1,10);	//时间戳加随机数
		$qianming  = base64_encode(hash_hmac("sha1","appKey=".self::$appKey."&nonce=".$nonce,self::$appsecret,true));	
        return $data = ["nonce"=>$nonce,"signature"=>$qianming];
    }

    public function test(){
        $data = $this->getConfig();
        $data['name'] = "测试直播间";
        $data['startTime'] = "2017-06-27 18:35:30";
        $data['endTime'] = "2017-06-27 18:50:30";
        $data['expireTime'] = "2017-06-27 19:30:30";
        $data['memberName'] = "贾建超";
        $data['disableRecord'] = 0;
        $data['appKey'] = self::$appKey;
        $data['secretLinkAble'] =1;
        $result = http(self::$video_url_createroom,json_encode($data),'POST');  
        var_dump($result);
        $_SESSION['test'] = $result['value'];
    }

    public function test1(){
        $data = $this->getConfig();
        $data['id'] = $_SESSION['test']['id'];
        $data['appKey'] = self::$appKey;
        $data['name'] = "测试直播间";
        $data['startTime'] = "2017-06-24 18:35:30";
        $data['endTime'] = "2017-06-24 18:50:30";
        $data['expireTime'] = "2017-06-24 19:30:30";
        $data['memberName'] = "贾建超";
        $data['disableRecord'] = 0;
        $data['secretLinkAble'] =1;
        $result = json_decode(http(self::$video_url_createroom,json_encode($data),'POST'),true);  
        var_dump($result);
    }


	#添加直播用户
    public function addUser(){
    	//直播账号 为user+id, 密码为手机号
    	$uid  = I('id');
    	$yes = M('live')->where("uid=%d",$uid)->select();	//判断用户是否已存在
    	if ($yes[0]["id"] > 0 ) {
        	$this->ajaxReturn(["code"=>0,"msg"=>"此用户已存在"]);    		
    	}
    	$userinfo = M('user2')->field("id,name,phone")->where("id=%d",$uid)->find();
    	$pwd = $userinfo['phone'];
    	$name = "user".$userinfo['id'];        //趣看账户 为 "user" + id
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


    #修改直播用户密码
    public function updatePwd(){
    	$data = $this->getConfig();
        $data["appKey"] = self::$appKey;
    	$data['memberId']    = I('qkid');
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
        	$this->ajaxReturn(["code"=>0,"msg"=>$result['msg']]);
        }else{
        	$res = M('live')->where("qkid=%d",$id)->delete();
        	if ($res) {
	        	$this->ajaxReturn(["code"=>1,"msg"=>"删除成功"]);
        	}else{
	        	$this->ajaxReturn(["code"=>0,"msg"=>"删除失败"]);
        	}
        }

    }


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
        $data  = M('videoing')->where("state != -1")->select();
        $type  = M('category')->where("is_service=1 and pid > 0 ")->select();

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
        $this->assign("type",$type);
        $this->assign("data",json_encode($data));
        $this->display();

    }

    #获取活动信息
    public function getRoomInfo(){
        $id  = I('id');
        $res = M('videoing')->where("id=%d",$id)->find();
        $uid = M('live')->where(['qkid'=>$res['qkid']])->getField("uid");
        $_SESSION['membername'] = 'user'.$uid;   //存直播用户
        $res['starttime'] = date("Y-m-d H:i:s",$res['starttime']);
        $res['endtime'] = date("Y-m-d H:i:s",$res['endtime']);
        $res['recordexpire'] = date("Y-m-d H:i:s",$res['recordexpire']);
        $this->ajaxReturn($res);
    }

    #修改活动信息
    public function saveRoomInfo(){
        $data = $this->getConfig();
        $data['id']   = I('id');    
        $data['name'] = I('name');
        $data['startTime'] = date("Y-m-d H:i:s",strtotime(I('starttime')));
        $data['endTime'] = date("Y-m-d H:i:s",strtotime(I('endtime')));
        $data['expireTime'] = date("Y-m-d H:i:s",strtotime(I('expiretime')));
        $data['memberName'] = $_SESSION['membername'];
        $data['disableRecord'] = 0;
        $data['appKey'] = self::$appKey;
        $data['secretLinkAble'] =1;

        $result = json_decode(http(self::$video_url_updateroom,json_encode($data),'POST'),true);  
        if ($result['code'] != 0 ) {
            $this->ajaxReturn(['code'=>0,'msg'=>$result['value']]);
        }else{
            $where['id']        = I('id1');
            $where['videoid']   = I('id');
            $where['videoname'] = str_replace('宋亚威','纪春雷',I('name'));
            $where['starttime'] = strtotime(I('starttime'));
            $where['endtime'] = strtotime(I('endtime'));
            $where['recordexpire'] = strtotime(I('expiretime'));
            $res = M('videoing')->save($where);
            if ($res) {
                $this->ajaxReturn(['code'=>1,'msg'=>'修改成功']);
            }else{
                $this->ajaxReturn(['code'=>0,'修改失败']);
            }

        }
    }



    #禁播活动   
    public function prohibit_act(){
        $id = I('id');
        $videoid = I('videoid');
        $data = $this->getConfig();
        $data['appKey'] = self::$appKey;
        $data['activityId'] = $videoid;

        $result = json_decode(http(self::$video_url_prohibit_act,json_encode($data),'POST'),true);
        if ($result['code'] != 0) {
            $this->ajaxReturn(['code'=>0,'msg'=>$result['value']]);
        }else{
            $where['state'] = 0;
            $res = M('videoing')->where("id=%d",$id)->save($where);
            if ($res) {
                $this->ajaxReturn(['code'=>1,'msg'=>'禁播成功']);
            }else{
                $this->ajaxReturn(['code'=>0,'msg'=>'禁播失败']);
            }
        }
    }


    #解禁活动   
    public function noprohibit_act(){
        $id = I('id');
        $videoid = I('videoid');

        $data = $this->getConfig();
        $data['appKey'] = self::$appKey;
        $data['activityId'] = $videoid;

        $result = json_decode(http(self::$video_url_noprohibit_act,json_encode($data),'POST'),true);
        
        if ($result['code'] != 0) {
            $this->ajaxReturn(['code'=>0,'msg'=>$result['value']]);
        }else{
            $where['state'] = 1;
            $res = M('videoing')->where("id=%d",$id)->save($where);
            if ($res) {
                $this->ajaxReturn(['code'=>1,'msg'=>'解禁成功']);
            }else{
                $this->ajaxReturn(['code'=>0,'msg'=>'解禁失败']);
            }
        }
    }


    #查看活动是否在直播中
    public function videoing(){
        $id = I('id');
        $videoid = I('videoid');
        $liveIdList[] = $videoid;
        $data = $this->getConfig();
        $data['liveIdList'] = $liveIdList;
        $data['appKey'] = self::$appKey;
        $result = json_decode(http(self::$video_url_videoing,json_encode($data),'POST'),true);
        if ($result['code'] != 0) {
            $this->ajaxReturn(['code'=>0,'msg'=>$result['value']]);
        }else{
            $res = $result['value'];
            if ($res[$id]) {
                $this->ajaxReturn(['code'=>1,'直播中']);
            }else{
                $this->ajaxReturn(['code'=>0,'未直播']);
            }
        }

    }


    #删除活动
    public function delroom(){
        $id = I('id');
        $data = $this->getConfig();
        $data['appKey'] = self::$appKey;
        $data['id'] = $id;

        $result = json_decode(http(self::$video_url_delroom,json_encode($data),'POST'),true);

        if ($result['code'] != 0) {
            $this->ajaxReturn(['code'=>0,'msg'=>$result['value']]);
        }else{
            $where['state'] = -1;
            $res = M('videoing')->where("videoid=%d",$id)->save($where);
            if ($res) {
                $this->ajaxReturn(['code'=>1,'删除成功']);
            }else{
                $this->ajaxReturn(['code'=>0,'删除失败']);
            }
        }
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
            $this->success("处理成功",U('Admin/Course/index'));
        }else{
            $this->error("处理失败");
        }
    }

    public function edit(){
    	$id = I('id');
    	$data = M('course')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("id=%d",$id)->find();
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