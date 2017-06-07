<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsClassController extends Controller {
	
	public function index(){
		$data = M('goodtype')->select();
		
		$arr = array('','品牌','乐器','材质');
		// $this->ajaxReturn($data);
		foreach ($data as $key => $value) {
			$data[$key]['type'] = $arr[$data[$key]['type']];
			$data[$key]['create_at'] = date('Y-m-d',$data[$key]['create_at']);
			if($data[$key]['update_at']){
				$data[$key]['update_at'] = date('Y-m-d',$data[$key]['update_at']);
			}
			
		}
		$this->assign('data',json_encode($data));
		
		
		$this->display();	
	}

	public function add(){
		$this->display();
	}

	public function do_add(){
	
		$data['name'] = I('name');
		$data['type'] = I('type');
		$data['create_at'] = time();
		$row = M('goodtype')->where(array('name'=>$data['name']))->select();
		if($row){			
			$result['code']	= 0;
			$result['msg'] = '数据已存在';
			$this->ajaxReturn($result);	
			
		}
		$r = M('goodtype')->add($data);
		if($r){	
			$result['code']	= 1;
			$result['msg'] = '添加成功';
			$this->ajaxReturn($result);
			
		}else{			
			$result['code']	= 0;
			$result['msg'] = '添加成功';
			$this->ajaxReturn($result);		
		}
		
	}

	public function edit(){
		echo 22;
		// $this->display();	
	}
	
	public function update(){
		$data['name'] = isset(I('name'))?trim(I('name')):'';
		if(empty($data['name'])){
			$this->ajaxReturn(array('code'=>0,'msg'=>'名称不能为空'));	
		}
		$data['id'] = isset(I('name'))?I('id'):'';
		if(empty($data['id'])){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));	

		}
		$data['update_at'] = time();
		$r = M('goodtype')->where(array('id'=>$id))->save($data); 
		if($r){
			$this->ajaxReturn(array('code'=>1,'msg'=>'更新成功'));	

		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'更新失败'));	

		}
		// $this->display();	
	}
	
	public function delete(){
		$id = I('id');
		$id = isset($id)?$id:'';
		if(empty($id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));	
		}
		$r = M('goodtype')->where(array('id'=>$id))->delete();
		if($r){
			$this->ajaxReturn(array('code'=>1,'msg'=>'删除成功'));	
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'删除失败'));	
		}
		// $this->display();	
	}
}