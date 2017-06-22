<?php
namespace Home\Model;
class CommonModel{
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
        $where = array();
        if($input){
            $cateId = $input['cateId'];
            $province = $input['province'];
            $city = $input['city'];
            $area = $input['area'];
            $arrId = D('Usercate')->where(array('categoryid'=>$cateId))->getField('user1_id',true);
            if($cateId){
                $where['id'] = array('in',$arrId);
            }
            if($area){
                $where['area'] = $area;
            }
            if($city){
                $where['city'] = $city;
            }
            if($province){
                $where['province'] = $province;
            }
        }
        return $where;
    }










}


?>