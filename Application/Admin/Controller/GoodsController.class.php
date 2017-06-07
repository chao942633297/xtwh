<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends Controller {
	
	public function index(){
		
		$this->display();	
	}

	public function add(){
		$brand = M('goodtype')->where('type = 1')->select();
		$instruments = M('goodtype')->where('type = 2')->select();
		$material = M('goodtype')->where('type = 3')->select();
		$this->assign('brand',$brand);//品牌
		$this->assign('instruments',$instruments);//类型
		$this->assign('material',$material);//材质
		$this->display();	
	}

	public function insert(){
		var_dump($_POST);
	}

	public function edit(){
		echo 22;
		// $this->display();	
	}
	
	public function update(){
		echo 22;
		// $this->display();	
	}
	
	public function delete(){
		echo 22;
		// $this->display();	
	}
}