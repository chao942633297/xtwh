<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller {
	/**
     * Session过期重新定位到登录页面
     */
    public function _initialize(){
        if (!isset($_SESSION['userid'])){
            $this->error('你还没有登录,请重新登录', U('/Admin/Login/login'));
        }
    }
    /**
	 * 系统用户管理
	 */
	public function index(){
		$this->display();
	
	}

	public function index1(){
		// var_dump($_GET);
		if (IS_POST) {
			// dump($_POST);exit;
			$conf = serialize($_POST);
			file_put_contents('./private/conf.txt',$conf);
			$aa = unserialize(file_get_contents('./private/conf.txt'));
			$this->ajaxReturn("成功");
			// $this->success("更新规则成功！",$aa);
		}else{		
			$aa = file_get_contents('./private/conf.txt');
			$aa = unserialize($aa);
			$this->ajaxReturn($aa);		
			// $this->assign("aa",$aa);
		}		
	}



	/**
	 * 修改密码页面
	 */
	public function changepwd(){
		$this->display();
	}
	/**
	 * 验证并保存修改后的新密码
	 */
	public function savePWD(){
		$id = I("userid");
		$pwd = md5(I("password"));	
		$newpwd = I("newpassword");
		$confpwd = I("confpassword");
		$user = M("admin");
		$users = $user->where("id=%d", $id)->find();
		if($users["password"] != $pwd){
			$this->error("输入密码错误！");
		}
		$data["password"] = md5($newpwd);
		$result = $user->where("id=%d", $id)->save($data);
		if($result){
			$this->success("更新密码成功！",U('/Admin/Admin/index'));
		}else{
			$this->error('更新密码失败！');
		}
	}
}