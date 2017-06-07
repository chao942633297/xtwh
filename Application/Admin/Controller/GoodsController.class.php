<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends Controller {
	
	public function index(){
		$data = M('good')->select();
		foreach ($data as $k => $v) {
			 $instruments = M('goodtype')->where('id ='.$data[$k]['instruments_id'])->find();
			 $data[$k]['instruments_id'] = $instruments['name'];
			 $brand = M('goodtype')->where('id ='.$data[$k]['brand_id'])->find();
			 $data[$k]['brand_id'] = $brand['name'];
			 $material = M('goodtype')->where('id ='.$data[$k]['material_id'])->find();
			 $data[$k]['material_id'] = $material['name'];
			 
			 if( $data[$k]['status'] == 0){
			 	$data[$k]['status'] = '<font color="green">上线</font>';
			 }else{
			 	$data[$k]['status'] = '<font color="red">下架</font>';
			 }
			 $data[$k]['create_at'] = date('Y-m-d',$data[$k]['create_at']);
			 if($data[$k]['update_at']){
			 $data[$k]['update_at'] = date('Y-m-d',$data[$k]['update_at']);
			 }
			
			
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
		// $upload->rootPath="./Public/Uploads/";//根目录   
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

		  		$path =  __ROOT__.'/Uploads'.$file['savepath'].$file['savename'];   
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