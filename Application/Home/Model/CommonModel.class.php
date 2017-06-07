<?php
namespace Home\Model;
use Think\Model;
class CommonModel extends Model{

    public function getLoopCate($pid = '0'){    //获取视频的分类
        $category = D('Category');
        $topCate = $category->where(array('pid'=>$pid,'is_service'=>1))->select();
        foreach($topCate as $k=> $val){
            $secCate = $this->loopCate($val['id']);
            if($secCate){
                $topCate[$k]['child'] = $secCate;
            }else{
                $topCate[$k] = $val;
            }
        }
        return $topCate;
    }

    public function getSearchCond($input){
        if($input){
            $cateId = $input['cateId'];
            $province = $input['province'];
            $city = $input['city'];
            $area = $input['area'];
        }
        $where = array();
        if($cateId){
            $where['categoryid'] = $cateId;
        }
        if($area){
            $where['area'] = $area;
        }else if($city){
            $where['city'] = $city;
        }else if($province){
            $where['province'] = $province;
        }
        return $where;
    }










}


?>