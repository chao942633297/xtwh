<?php
namespace Admin\Controller;

use Think\Controller;

class GetCashController extends Controller
{
    public function cashList()//提现管理列表
    {
        $where1="a.user_id=b.id";
        $mod=D('GetCash');
        $tables['table']='__GETCASH__ a,__USER2__ b';
        $tables['where']='a.user_id=b.id';
        $res=$mod->field('a.*,b.nickname,b.name,b.phone')->table($tables['table'])->where($where1)->order('a.id desc')->select();
        $this->assign('data',json_encode($res));
        $this->display('getcash/cashList');
    }

    public function searchCashList()//提现管理搜索列表
    {
        $date=I('get.');
        if(!empty($date['phone'])&&empty($date['status'])){
            $where1 = "a.user_id=b.id AND b.phone = '{$date['phone']}'";
        }elseif(empty($date['phone'])&&!empty($date['status'])){
            $where1 = "a.user_id=b.id AND a.status = '{$date['status']}'";
        }elseif(!empty($date['phone'])&&!empty($date['status'])){
            $where1 = "a.user_id=b.id AND a.status = '{$date['status']}' AND b.phone = '{$date['phone']}'";
        }else{
            $where1="a.user_id=b.id";
        }
        $mod=D('GetCash');
        $tables['table']='__GETCASH__ a,__USER2__ b';
        $tables['where']='a.user_id=b.id';
        $res=$mod->field('a.*,b.nickname,b.name,b.phone')->table($tables['table'])->where($where1)->order('a.id desc')->select();
        $this->ajaxReturn($res);
    }

    public function cashApply()//提现审核操作
    {
        $id=I('get.id');

    }
}

?>