<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Exception;

class GetCashController extends Controller
{
    public function cashList()//提现管理列表
    {
        $where1="a.u2id=b.id";
        $mod=D('Order');
        $tables['table']='__ORDER__ a,__USER2__ b';
        $res=$mod->field('a.id,a.money,a.create_at,a.status,b.nickname,b.name,b.phone')->table($tables['table'])->where($where1)->where('a.status=3')->order('a.id desc')->select();
        $this->assign('data',json_encode($res));
        $this->display('getcash/cashList');
    }

    public function searchCashList()//提现管理搜索列表
    {
        $date=I('get.');
        if(!empty($date['phone'])){
            $where1 = "a.u2id=b.id AND b.phone = '{$date['phone']}'";
        }else{
            $where1="a.u2id=b.id";
        }
        $mod=D('Order');
        $tables['table']='__ORDER__ a,__USER2__ b';
        $res=$mod->field('a.id,a.money,a.create_at,a.status,b.nickname,b.name,b.phone')->table($tables['table'])->where($where1)->where('a.status=3')->order('a.id desc')->select();
        $this->ajaxReturn($res);
    }

    /**
     * 这里还有一个接转账的功能待做
     */
    public function cashApply()//提现审核操作
    {
        $id=I('get.id');
        $flag=I('get.flag');
        if($flag==1){//审核通过
            $date['status']=4;
            $date['update_at']=time();
            $res=M('order')->where(['id'=>$id])->setField($date);
            if($res){
                $this->redirect('GetCash/cashList',2,'审核通过');
            }else{
                $this->redirect('GetCash/cashList',2,'审核失败');
            }
        }else{//驳回
            $date['status']=5;
            $date['create_at']=time();
            $tr=M();
            $tr->startTrans();
            try{
                $res=M('order')->where(['id'=>$id])->setField($date);
                if($res){
                    $res1=M('backmoney')->where(['order_id'=>$id])->delete();
                    if($res1){
                        $tr->commit();
                    }else{
                        throw new Exception();
                    }
                }else{
                    throw new Exception();
                }
            }catch(Exception $e){
                $tr->rollback();
            }
            if($res1){
                $this->redirect('GetCash/cashList',2,'驳回成功');
            }else{
                $this->redirect('GetCash/cashList',2,'驳回失败');
            }
        }
    }
}

?>