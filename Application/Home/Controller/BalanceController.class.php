<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class BalanceController extends Controller{

    public function balancePay(){
        $input = I('get.');
        $orderId = $input['inputId'];
        $orderId = '17';

        $order = D('OrderRelation');
        $backmoney = D('Backmoney');
        $orderData = $order->where(array('id' => $orderId))->relation(array('user2', 'course'))->find();

        $userMoney = $orderData['user2']['onemoney'];   //用户钱包余额
        $rebateMoney = D('Backmoney')->where(array('u2id' => $orderData['user2']['id']))->sum('money');   //用户返佣的钱
//        dump($rebateMoney);die;
        $rebate_money = $orderData['user2']['rebate_money'];   //用户已参与第一返佣的钱
        $limit_money = $orderData['course']['limit_price'] ? $orderData['course']['limit_price'] : 7200;    //课程限制可用于第一返佣的钱  充值则为默认(7200)
        $spareMoney = 7200 - $rebate_money;     //用户剩余可用于第一返佣规则的钱
        if($orderData['status'] == '1') {
            if ($spareMoney > 0) {                      //可返佣的钱大于0
                //判断余额和课程限制的钱, 决定用户需扣除余额多少
                if (($sur1 = $limit_money - $userMoney) < 0) {         //若余额大于 课程限制的钱  则只能消费 课程限制的钱
                    $userDescMoney = $limit_money;
                    $manyMoney = '0';
                } else {                              //否则  可以使用全部余额
                    $userDescMoney = $userMoney;
                    $manyMoney = $sur1;          //可用于第一返佣的钱
                }

                //判断可用于返佣的钱 和 此次返佣的钱, 决定此次1类返佣的钱
                if ($spareMoney - $manyMoney < 0) {        //若可用返佣的钱 小于 返佣的钱,则全部用于第一返佣,否则只能 选择 可用返佣的钱进行返佣
                    $manyMoney = $spareMoney;            //第一返佣的钱
                    $lessMoney = $orderData['goodprice'] - $spareMoney;     //第二返佣的钱
                } else {
                    $lessMoney = $orderData['goodprice'] - $limit_money;
                }
                //判断基金 和 (订单金额 除去 扣除的余额 外的金额) 决定扣除的基金
                if ($rebateMoney - ($rep = $orderData['goodprice'] - $userDescMoney) >= 0) {     //若基金足够,则直接消费
                    $rebateDescMoney = $rep;
                } else {
                    $rebateDescMoney = $rebateMoney;      //不足则先扣除基金
                    //调 微信或支付宝进行支付
                    $returnVal['userMoney'] = $userDescMoney;         //可扣除用户的钱
                    $returnVal['rebateMoney'] = $rebateDescMoney;     //扣除基金的钱
                    $lastMoney = $orderData['goodprice'] - $userDescMoney - $rebateDescMoney;    //订单扣除用户余额,扣除基金后的钱

                    return array('0', '余额不足,请选择其他支付方式');
                }
            } else {                             //可返佣的钱小于0 ,说明7200已全部返完 ,余额可全部消费
                if ($userMoney - $orderData['goodprice'] >= 0) {     //余额大于 订单金额,则用余额直接购买,(不返佣)
                    $userDescMoney = $orderData['goodprice'];
                    $lessMoney = '0';
                } else {                                     //否则  可以使用全部余额
                    $userDescMoney = $userMoney;
                    $lessMoney = $orderData['goodprice'] - $userMoney;
                    if ($rebateMoney - $lessMoney >= 0) {     //若基金足够,则直接消费
                        $rebateDescMoney = $lessMoney;
                    } else {
                        $rebateDescMoney = $rebateMoney;      //不足则先扣除基金
                        //调 微信或支付宝进行支付
                        return array('0', '余额不足,请选择其他支付方式');
                    }
                }
            }

            $backmoney->startTrans();
            try {
                //减少用户余额
                if ($userMoney) {
                    $use['onemoney'] = $userMoney - $userDescMoney;
                    $use['rebate_money'] = $manyMoney;
                    $use['update_at'] = time();
                    $res = D('User2')->where(array('id' => $orderData['user2']['id']))->save($use);
                }
                // 进行上级返佣
                $notify = A('Notify');
                $res1 = $notify->rebate($manyMoney, $lessMoney, $orderData['user2']['id']);
                //改变订单状态
                $orde['status'] = 2;
                $orde['paytype'] = '余额';
                $orde['update_at'] = time();
                $res2 = $order->where(array('id' => $orderData['id']))->save($orde);
                // 减少用户基金
                $back['u2id'] = $orderData['user2']['id'];
                $back['money'] = -$rebateDescMoney;
                $back['message'] = '购物';
                $back['create_at'] = time();
                $back['order_id'] = $orderData['id'];
                if ($res && $res1 && $res2) {
                    $res3 = $backmoney->add($back);
                    if ($res3) {
                        $backmoney->commit();
//                    jsonpReturn('1','购买成功');
                        return array('1', '购买成功');
                    } else {
                        throw new Exception();
                    }
                } else {
                    throw new Exception();
                }
            } catch (Exception $e) {
                $backmoney->rollback();
            }
        }

    }






}

?>