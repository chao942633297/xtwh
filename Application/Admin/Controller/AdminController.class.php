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


	#轮播图
	public function lunbo(){
		$data = M('lunbo')->select();
		$this->assign("data",json_encode($data));
		$this->display();
	}


	public function addLunbo1(){
		$this->display();
	}

	#添加轮播图
	public function addlb(){
		$data["img"] = I("img");
		$data["createtime"] = time();
		$yes = M('lunbo')->add($data);
		$this->ajaxReturn($yes);
	}

	#删除轮播图
	public function deleteLunbo(){
		$id  = I('id');
		$res = M('lunbo')->where("id=%d",$id)->delete();
		if ($res) {
			echo true;
		}else{
			echo false;
		}
	}

	#数据统计
	public function data(){
		$t = time();
		$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));  //当天开始时间
		$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); //当天结束时间
		// 累计会员总量
		$userall = M('user2')->count();
		// 累计收入总额
		$moneyall= M('order')->where("status =2 and money > 0 and message='充值余额'")->getField("abs(sum(money))");
		// 今日提现总额
		$todaytx  = M('order')->where("status >2 and money >0 and  create_at > '%s' and create_at < '%s'",$start,$end)->getField("abs(sum(money))");
		// 今日提现手续费总计
		if (empty($todaytx) || $todaytx <= 0 ) {
			$todaytx  = 0;
			$todaytx1 = 0;
		}else{
			$todaytx1 = $todaytx * 0.01; 
		}
		// 已发放提现总额
		$aftertx = M('order')->where("status = 4 and money < 0 ")->getField("abs(sum(money))");
		// 分销佣金总计
		$account = M('backmoney')->where("money > 0 ")->getField("abs(sum(money))");

		// 会员账户月总计	
		// 今日新增会员量
		$todayuser  = M('user2')->where("create_at > '%s' and create_at < '%s'",$start,$end)->count();		
		// 今日余额互转总计
		// 今日余额互转手续费总计
		// 今日分销佣金总计
		$todayaccount  = M('backmoney')->where(" money > 0 and create_at > '%s' and create_at < '%s'",$start,$end)->getField("abs(sum(money))");

		$this->assign("userall",$userall);
		
		$this->assign("moneyall",$moneyall);
		
		$this->assign("todaytx",$todaytx);
		
		$this->assign("todaytx1",$todaytx1);
		
		$this->assign("aftertx",$aftertx);
		
		$this->assign("account",$account);
		
		$this->assign("todayuser",$todayuser);
		
		$this->assign("todayaccount",$todayaccount);
		
		$this->display();
	}

}