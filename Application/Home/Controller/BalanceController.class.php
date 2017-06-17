<?php
namespace Home\Controller;
use Think\Controller;
class BalanceController extends Controller{

    public function balancePay(){
        $input = I('get.');
        $order = D('OrderRelation');
        $backmoney = D('Backmoney');
        $orderId = $input['inputId'];
        $orderId = '17';
        $orderData = $order->where(array('id'=>$orderId))->relation(array('user2','course'))->find();
        dump($orderData);

        $userMoney = $orderData['user2']['onemoney'];   //用户钱包余额
        $rebateMoney = D('Backmoney')->where(array('u2id'=>$orderData['user2']['id']))->sum('money');   //用户返佣的钱
        $rebate_money = $orderData['user2']['rebate_money'];   //用户已参与第一返佣的钱
        $limit_money = $orderData['course']['limit_price']?$orderData['course']['limit_price']:7200;    //课程限制可用于第一返佣的钱  充值则为默认(7200)
        $spareMoney = 7200 - $rebate_money;     //用户剩余可用于第一返佣规则的钱
        if($spareMoney > 0){                      //可返佣的钱大于0  则最多消费课程限制的钱 ,
            if(($sur1 = $userMoney - $limit_money) >= 0){         //若余额大于 课程限制的钱  则只能消费 课程限制的钱
                $userDescMoney = $limit_money;
                $lessMoney = $orderData['money'] - $limit_money;                //第二返佣规则的钱
            }else{                              //否则  可以使用全部余额
                $userDescMoney = $userMoney;
                $lessMoney = $orderData['money'] - $userMoney;
                if( $rebateMoney - $lessMoney >=0 ){     //若基金足够,则直接消费
                    $rebateDescMoney = $lessMoney;
                }else{
                    $rebateDescMoney = $rebateMoney;      //不足则先扣除基金
                    //调 微信或支付宝进行支付
                }
            }

        }else{                            // //可返佣的钱小于0 ,说明7200已全部返完 ,
            if($userMoney - $orderData['money'] >= 0 ){     //余额大于 订单金额,则用余额直接购买,(不返佣)
                $userDescMoney = $orderData['money'];
                $lessMoney = '0';
            }else{                                     //否则  可以使用全部余额
                $userDescMoney = $userMoney;
                $lessMoney =$orderData['money'] - $userMoney ;
                if( $rebateMoney - $lessMoney >=0 ){     //若基金足够,则直接消费
                    $rebateDescMoney = $lessMoney;
                }else{
                    $rebateDescMoney = $rebateMoney;      //不足则先扣除基金
                    //调 微信或支付宝进行支付
                }
            }
        }


        if(($surplus = $userMoney - $limit_money) >= 0 ){     //钱包钱不足购买
            $userDescMoney = $limit_money;

//            if(($sur2 =  $surplus - $rebateMoney) >= 0){      //返佣的钱也不足以支付剩余的钱
//                $rebateDescMoney = $rebateMoney;
//
//            }else{
//                $rebateDescMoney = $surplus;
//            }
//            $back['u2id'] = $orderData['user2']['id'];
//            $back['money'] = -$rebateDescMoney;
//            $back['message'] = '购物';
//            $back['create_at'] = time();
//            $back['order_id'] = $orderData['id'];
//            $res1 = $backmoney->add($back);
//        }else{
//            $userDescMoney = $orderData['money'];
        }
        $res = D('User2')->where(array('id'=>$orderData['user2']['id']))->setDec('onemoney',$userDescMoney);



























        if(($surplus = $orderData['money'] - $userMoney) < 0 ){    //订单钱 小于 钱包余额
//            $order->startTrans();                          //则钱包余额直接购买, (没有返佣)
//            try{
//                $data['status'] = 2;
//                $data['update_at'] = time();
//                $data['paytype'] = '余额';
//                $data['real_money'] = $orderData['money'];
//                $res = $order->where(array('id'=>$orderId))->save($data);
//                if($res){
//                    D('User2')->where(array('id'=>$orderData['user2']['id']))->setDec('onemoney',$orderData['money']);
//                    $order->commit();
//                }else{
//                    $order->rollback();
//                }
//
//            }catch(Exception $e){
//                $order->rollback();
//            }
        }else{                              //订单钱 大于 钱包余额,  则 先扣除钱包的钱,剩余$surplus ,选择返佣的钱购买
            if($rebateMoney - $surplus >= 0 ){    // 若返佣的钱 大于 $surplus 则用返佣的钱抵扣购买 (执行2返佣规则$surplus)


            }
        }
        $res = D('User2')->where(array('id'=>$orderData['user2']['id']))->setDec('onemoney',$orderData['money']);


    }








}

?>