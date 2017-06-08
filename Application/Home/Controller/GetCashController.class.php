<?php
namespace Home\Controller;

use Think\Controller;
use Think\Exception;

class GetCashController extends Controller
{
    public function subCashApply()//基金提现申请
    {
        $date=I('post.');
        $order=M('order');
        $rules=array(
            array('money','require','提现金额不能为空'),
            array('paytype','require','提现方式不能为空'),
            array('payee_man','require','收款人不能为空'),
            array('payee_account','require','收款人账号不能为空'),
            array('twopassword','require','二级密码不能为空'),
        );
        if(!$order->validate($rules)->create()){
            $data=array('status'=>false,'error_message'=>$order->getError());
        }else{
            $totalCash=D('Backmoney')->getFoundCash();
            $find=M('user2')->where(['id'=>session('home_user_id'),'twopassword'=>md5($date['twopassword'])])->find();
            if($date['money']>=100){
                if($date['money']>$totalCash){
                    $data=array('status'=>false,'error_message'=>'余额不足');
                }else{
                    if($find){
                        $order->money='-'.($order->money*(1-0.05));
                        $order->create_at=time();
                        $order->ordercode=$this->createCode();
                        $order->u2id=session('home_user_id');
                        $order->status=3;
                        $order->message='基金提现';
                        $tr=M();
                        $tr->startTrans();
                        try{
                            $res=$order->add();
                            if($res){
                                $res2=$this->backMoney($date['money'],$res);
                                if($res2){
                                    $data=array('status'=>false,'error_message'=>'申请提现成功');
                                    $tr->commit();
                                }else{
                                    throw new Exception();
                                }
                            }else{
                                throw new Exception();
                            }
                        }catch(Exception $e){
                            $data=array('status'=>false,'error_message'=>'申请提现失败');
                            $tr->rollback();
                        }
                    }else{
                        $data=array('status'=>false,'error_message'=>'二级密码错误');
                    }
                }
            }else{
                $data=array('status'=>false,'error_message'=>'最低发起提现额度为100');
            }
        }
        $this->ajaxReturn($data,'JSONP');
    }

    public function backMoney($money,$orderId)
    {
        $date['u2id']=session('home_user_id');
        $date['money']='-'.$money;
        $date['create_at']=time();
        $date['order_id']=$orderId;
        $date['message']='提现';
        $res=M('backmoney')->data($date)->add();
        if($res){
            return true;
        }
        return false;
    }

    public function createCode()//生成订单号
    {
        $Code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderCode = $Code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderCode;
    }
}

?>