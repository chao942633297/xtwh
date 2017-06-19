<?php
namespace Admin\Controller;
use Think\Controller;

class CategoryController extends Controller
{   

    public function kecheng(){
    	$row = M('category')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where(array('is_service'=>1,'pid'=>0))->select();
    	// $data = M('category')->where(array('is_service'=>1))->order('id desc')->select();
    	// $category_list = $this->toLevel($data, '&nbsp;&nbsp;&nbsp;&nbsp;',0);
    	// var_dump($category_list);exit;
    	$this->assign('row',$row);
    	$this->assign('data',json_encode($row));
    	$this->display();
    }

    # 根据名称 ,模糊查询一级分类
    public function searchType(){
        $name = I('name');
        $where["name"] = ["like","%".$name."%"];
        $where["is_service"] = 1;
        $where["pid"] = 0;

        $data = M('category')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where($where)->select();
        if ($data == false || empty($data)) {
            $data = [];
        }
        $this->ajaxReturn($data);
    }

    #根据名称 ,模糊搜索服务类型
    public function searchService(){
        $name = I('name');
        $where["name"] = ["like","%".$name."%"];
        $where["is_service"] = 2;
        $data = M('category')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where($where)->select();
        if ($data == false || empty($data)) {
            $data = [];
        }
        $this->ajaxReturn($data);        
    }

    #获取 二级分类
    public function getTwo(){
        $id = I('id');
        $data = M('category')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("pid=%d",$id)->select();
        if (empty($data) || $data == false) {
            $data = [];
        }

        $this->ajaxReturn($data);
    }

    #编辑分类-获取信息
    public function edit(){
        $id = I('id');
        $data = M('category')->field("*,FROM_UNIXTIME(create_at,'%Y-%m-%d %H:%i:%s') as create_at")->where("id=%d",$id)->find();
        $this->ajaxReturn($data);
    }

    #执行修改
    public function doEdit(){
        $id = I('id');
        $name = I('name');
        $where['id'] = $id;
        $where['name'] = $name;
        $where['update_at'] =time();
        $res = M('category')->save($where);
        if ($res) {
            echo true;
        }else{
            echo false;
        }
    }
    //  public function toLevel($cate, $delimiter = '———', $parent_id = 0, $level = 0) {
 
    //     $arr = array();
    //     foreach ($cate as $v) {
    //         if ($v['parent_id'] == $parent_id) {
    //             $v['level'] = $level + 1;
    //             $v['delimiter'] = str_repeat($delimiter, $level);
    //             $arr[] = $v;
    //             $arr = array_merge($arr, $this->toLevel($cate, $delimiter, $v['id'], $v['level']));
    //         }
    //     }
 
    //     return $arr;
 
    // }


    public function fuwu(){
    	$data = M('category')->where(array('is_service'=>2))->order('id desc')->select();
    	foreach ($data as $k => $v) {
    		$data[$k]['create_at'] = date('Y-m-d',$data[$k]['create_at']);
    		if($data[$k]['update_at']){
    			$data[$k]['update_at'] = date('Y-m-d',$data[$k]['update_at']);
    		}
    	}
    	$this->assign('data',json_encode($data));
    	$this->display();
    }

   
    public function add(){
    	$_POST['name'] = trim($_POST['name']);
    	$row = M('category')->where(array('name'=>$_POST['name']))->find();
    	if($row) $this->error('课程已存在');
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
			  		$path =  __ROOT__.'/Uploads'.$file['savepath'].$file['savename'];   
			  	}
			}
			$_POST['icon_img'] = $path;
		}
		
		$_POST['create_at'] = time();
		$r = M('category')->add($_POST);
		if($r){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
    }

}