<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends Controller {
	
	public function index(){
		$data = M('good')->field("id,name,brand_id,instruments_id,material_id,pic,desc,price,discount,status,create_at")->select();
		$type = M('goodtype')->field("id,name")->select();
		foreach ($data as $k => $v) {
			foreach ($type as $ke => $va) {
				if ($v['brand_id'] == $va['id']) {
					$data[$k]['brand_id'] = $va['name'];
				}else if($v['instruments_id'] == $va['id']){
					$data[$k]['instruments_id'] = $va['name'];	
				}else if($v['material_id'] == $va['id']){
			 		$data[$k]['material_id'] = $va['name'];
				}
			}
			$data[$k]['create_at'] = date('Y-m-d H:i:s',$data[$k]['create_at']);
		}
		$this->assign('data',json_encode($data));
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
		$upload = new \Think\Upload();// 实例化上传类   
		$upload->maxSize   =     3145728 ;// 设置附件上传大小    
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
		$upload->savePath  =      '/goods_pic/'; // 设置附件上传目录   
		 // 上传文件     
		$upload->saveName = md5(time().'_'.mt_rand());
		// 开启子目录保存 并以日期（格式为Ymd）为子目录
		$upload->autoSub = true;
		$upload->subName = array('date','Ymd');
		$info   =   $upload->upload();   		
		if(!$info) {// 上传错误提示错误信息   
			$this->error($upload->getError());   
		}else{// 上传成功 			       
		  	foreach($info as $file){
		  		$path =  '/Uploads'.$file['savepath'].$file['savename'];   
		  	}
		}
		$data = $_POST;
		$data['pic'] = $path;
		$data['create_at'] = time();
		$r = M('good')->add($data);
		if($r){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
		
	}

	public function edit(){	
		$id = $_GET['id'];
		$data = M('good')->where('id = '.$id)->find();
		$brand = M('goodtype')->where('type = 1')->select();
		$instruments = M('goodtype')->where('type = 2')->select();
		$material = M('goodtype')->where('type = 3')->select();
		$this->assign('brand',$brand);//品牌
		$this->assign('instruments',$instruments);//类型
		$this->assign('material',$material);//材质
		$this->assign('data',$data);
		$this->display();	
	}
	
	public function update(){
		$data = $_POST;
		if(!empty($_FILES['photo']['name'])){
			$upload = new \Think\Upload();// 实例化上传类   
			$upload->maxSize   =     3145728 ;// 设置附件上传大小    
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型 
			$upload->savePath  =      '/goods_pic/'; // 设置附件上传目录   
			 // 上传文件     
			$upload->saveName = md5(time().'_'.mt_rand());
			// 开启子目录保存 并以日期（格式为Ymd）为子目录
			$upload->autoSub = true;
			$upload->subName = array('date','Ymd');
			$info   =   $upload->upload();   		
			if(!$info) {// 上传错误提示错误信息   
				$this->error($upload->getError());   
			}else{// 上传成功 			       
			  	foreach($info as $file){
			  		$path =  '/Uploads'.$file['savepath'].$file['savename'];   
			  	}
			}
			$data['pic'] = $path;
		}
		$data['update_at'] = time();
		$r = M('good')->save($data);
		if($r){
			$this->success('编辑成功');
		}else{
			$this->error('编辑失败');
		}
		// $this->display();	
	}
	
	#搜索商品
	public function search(){
		$name   = I('name');
		$status = I('status');
		if (!empty($name)) {
		 	$where['name'] = ['like','%'.$name.'%'];
		}

		if ($status != -1) {
			$where['status'] = $status; 	
		} 

		$data = M('good')->field("id,name,brand_id,instruments_id,material_id,pic,desc,price,discount,status,create_at")->where($where)->select();
		if (empty($data) || $data == false ) {
			$data = [];
		}else{
			$type = M('goodtype')->field("id,name")->select();
			foreach ($data as $k => $v) {
				foreach ($type as $ke => $va) {
					if ($v['brand_id'] == $va['id']) {
						$data[$k]['brand_id'] = $va['name'];
					}else if($v['instruments_id'] == $va['id']){
						$data[$k]['instruments_id'] = $va['name'];	
					}else if($v['material_id'] == $va['id']){
				 		$data[$k]['material_id'] = $va['name'];
					}
				}
				$data[$k]['create_at'] = date('Y-m-d H:i:s',$data[$k]['create_at']);
			}
		}
		$this->ajaxReturn($data);
	}

	#改变状态
	public function updateStatus(){
		$id = I('id');
		$status = M('good')->where("id=%d",$id)->getField("status");
		if ($status == 1) {
			$where['status'] = 0;
		}else{
			$where['status'] = 1;
		}
		$where['id'] = $id;
		$res = M('good')->save($where);
		if ($res) {
			echo true;
		}else{
			echo false;
		}
	}

	public function ueditor(){
		$data = new \Org\Util\Ueditor();
        echo $data->output();
	}
}