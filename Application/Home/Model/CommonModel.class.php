<?php
namespace Home\Model;
use Think\Model;
class CommonModel extends Model{

    public function getLoopCate($pid = '0'){    //获取视频的分类
        $category = D('Category');
        $topCate = $category->where(array('pid'=>$pid))->select();
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
}


?>