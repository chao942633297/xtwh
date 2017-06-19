<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Exception;
use Service\Alipay as Alipays;
use Service\AopClient;
use Service\AlipayTransfer;

class GetCashController extends Controller
{
    public function cashList()//提现管理列表
    {
        $order = M('order');
        $user  = M('user2');
        $orders= $order->where("message = '基金提现' and money < 0")->order("status asc")->select();
        $idall = $order->where("message = '基金提现' and money < 0")->getField("u2id",true);
        $idall = array_unique($idall);
        $users = $user->field("name,phone,id")->where(["id"=>["in",$idall]])->select();
        foreach ($orders as $k => $v) {
            $orders[$k]["money"] = abs($v["money"]);
            $orders[$k]["zkmoney"] = sprintf('%.2f',$orders[$k]["money"] * 0.95);
            $orders[$k]["create_at"] = date("Y-m-d H:i:s",$v["create_at"]);
            foreach ($users as $k1 => $v1) {
                if ($v["u2id"] == $v1["id"]) {
                    $orders[$k]["name"] = $v1["name"];
                    $orders[$k]["phone"] = $v1["phone"];
                }
            }
        }
        $this->assign('data',json_encode($orders));
        $this->display();
    }

    public function search()//提现管理搜索列表
    {
        $phone = I('phone');
        $status= I('status');
        $where1 = [];
        $where = [];
        if (!empty($phone)) {
            $where["phone"] = ["like","%".$phone."%"];
        }
        if (!empty($status) && $status != -1) {
            $where1["status"] = $status;
        }

        $order = M('order');
        $user  = M('user2');
        $orders= $order->where("message = '基金提现' and money < 0  ")->where($where1)->order("status asc")->select();
        // var_dump($orders);
        // die();
        if (empty($orders) || $orders == false) {
            $orders = [];
        }else{
            $idall = $order->where("message = '基金提现' and money < 0")->where($where1)->getField("u2id",true);
            $idall = array_unique($idall);
            $where["id"] = ["in",$idall];
            $users = $user->field("name,phone,id")->where($where)->select();
            if ($users == false || empty($users)) {
                $orders = [];        
            }else{
                foreach ($orders as $k => $v) {
                    $orders[$k]["money"] = abs($v["money"]);
                    $orders[$k]["zkmoney"] = sprintf('%.2f',$orders[$k]["money"] * 0.95);
                    $orders[$k]["create_at"] = date("Y-m-d H:i:s",$v["create_at"]);
                    foreach ($users as $k1 => $v1) {
                        if ($v["u2id"] == $v1["id"]) {
                            $orders[$k]["name"] = $v1["name"];
                            $orders[$k]["phone"] = $v1["phone"];
                        }
                    }
                }                
            }           
        }


        $this->ajaxReturn($orders);
    }

    //提现审核操作
    public function cashApply()
    {
        $id=I('get.id');
        $flag=I('get.flag');
        if($flag==1){//审核通过
                //提现
                $yes = $this->AlipayTx($id);    
                if ($yes) {
                    $date['status']=4;
                    $date['update_at']=time();
                    $res=M('order')->where(['id'=>$id])->setField($date);
                    if($res){
                        $this->success('审核通过',U('GetCash/cashList'));
                    }else{
                        $this->error('审核失败');
                    }                    
                }else{
                    $this->error('审核失败');
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
                $this->success('驳回成功',U('GetCash/cashList'));
            }else{
                $this->error('驳回失败');
            }
        }
    }

    #调用提现
    public function AlipayTx($id){
        $order = A('Home/AliPay');
        return $order->test($id);
    }
}
