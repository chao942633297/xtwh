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
		
		$data['name'] = isset($_POST['name'])?trim($_POST['name']):'';
		$data['type'] = $_POST['type'];
		$data['create_at'] = time();
		
		$row = M('goodtype')->where(array('name'=>$data['name']))->find();
		if($row){			
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据已存在'));								
		}
		$r = M('goodtype')->add($data);
		if($r){	
			$this->ajaxReturn(array('code'=>1,'msg'=>'添加成功'));			
						
		}else{	
			$this->ajaxReturn(array('code'=>0,'msg'=>'添加失败'));							
		}
		
	}

	public function edit(){
		$id = isset($_GET['id'])?$_GET['id']:'';
		if(empty($id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));	
		}
		$r = M('goodtype')->where(array('id'=>$id))->find();
		$this->assign('data',$r);
		$this->display();	
	}
	
	public function update(){
		$data['name'] = isset($_POST['name'])?trim($_POST['name']):'';
		if(empty($data['name'])){
			$this->ajaxReturn(array('code'=>0,'msg'=>'名称不能为空'));	
		}
		$id = isset($_POST['id'])?$_POST['id']:'';
		if(empty($id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));	

		}
		$data['type'] = $_POST['type'];
		$data['update_at'] = time();
		$row = M('goodtype')->where(array('name'=>$data['name']))->find();
		if($row){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据库已存在'));	
		}
		$r = M('goodtype')->where(array('id'=>$id))->save($data); 
		if($r){
			$this->ajaxReturn(array('code'=>1,'msg'=>'更新成功'));	

		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'更新失败'));	

		}
	}
	
	public function delete(){

		$id = $_GET['id'];
	
		if(empty($id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'数据错误'));	
		}
		$ra = M('good')->where('brand_id='.$id)->find();
		if($ra) $this->ajaxReturn(array('code'=>0,'msg'=>'商品关联数据，不能删除'));
		$rb = M('good')->where('instruments_id='.$id)->find();
		if($rb) $this->ajaxReturn(array('code'=>0,'msg'=>'商品关联数据，不能删除'));
		$rc = M('good')->where('material_id='.$id)->find();
		if($rc) $this->ajaxReturn(array('code'=>0,'msg'=>'商品关联数据，不能删除'));
		
		$r = M('goodtype')->where(array('id'=>$id))->delete();
		if($r){
			$this->ajaxReturn(array('code'=>1,'msg'=>'删除成功'));	
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'删除失败'));	
		}
	}
}