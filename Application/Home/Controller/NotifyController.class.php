<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;
use Vendor\AliPay\AlipayTradeService;
use Vendor\Weixinpay\WxPayConf_pub\Notify_pub;

class NotifyController extends Controller{

    public function aliNotify(){
        $arr = $_GET;                 //获取回调数据
        $user = D('User2');
        $alipayService = new AlipayTradeService();
        $result = $alipayService->check($arr);
        if($result) {       //验签成功
            $orderCode = htmlspecialchars($_GET['out_trade_no']);
            $total_fee = $_GET['total_amount'];
            $orderData = D('OrderRelation')->where(array('ordercode' => $orderCode))->relation(true)->find();
            if ($orderData['status'] == '1') {
                $this->returnNotify($orderData,$total_fee,'支付宝');
            }
        }

    }

    public function wechatNotify()
    {
        vendor('Weixinpay.WxPayPubHelper');     //获取回调数据
        $notify = new Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        if ($notify->checkSign() == FALSE) {
            $notify->setReturnParameter("return_code", "FAIL");//返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");//返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS");//设置返回码
        }
        $log_name = "./notify_url.log";//log文件路径
//        $this->log_result($log_name, "【接收到的notify通知】:\n" . $xml . "\n");

        if ($notify->checkSign() == TRUE) {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【通信出错】:\n" . $xml . "\n");
            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【业务出错】:\n" . $xml . "\n");
            } else {
                //此处应该更新一下订单状态，商户自行增删操作
                $this->log_result($log_name, "【支付成功】:\n" . $xml . "\n");
                $returnData = $notify->xmlToArray($xml);
                $total_fee = $returnData['total_fee'];
                $orderData = D('OrderRelation')->where(array('ordercode'=>$returnData['out_trade_no']))->relation(true)->find();
                if($orderData['status'] == 1){         //订单待支付状态
                    $this->returnNotify($orderData,$total_fee,'微信');
                }
            }
        }
    }


    // 打印log
    public function  log_result($file,$word)
    {
        $fp = fopen($file,"a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H：%M：%S",time())."\n".$word."\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

            //回调后,计算返佣的钱,和改变订单状态
    public function returnNotify($orderData,$total_fee,$paytype){
        $rebate_money = $orderData['user2']['rebate_money'];   //用户已参与第一返佣的钱
        $limit_money = $orderData['course']['limit_price'] ? $orderData['course']['limit_price'] : 7200;    //课程限制可用于第一返佣的钱  充值则为默认(7200)
        $surplus = 7200 - $rebate_money;     //用户剩余可用于第一返佣规则的钱
        if ($surplus - $limit_money >= 0) {
            //返佣$limit_money
            $manyMoney = $limit_money;
            $lessMoney = $total_fee - $limit_money;
        } else {
            //返佣$surplus
            $manyMoney = $surplus;
            $lessMoney = $total_fee - $surplus;
        }
        $rebateResult = $this->rebate($manyMoney, $lessMoney, $orderData['u2id']);
        if ($rebateResult) {
            $res = D('User2')->where(array('id' => $orderData['u2id']))->setInc('rebate_money', $manyMoney);
            $newData['status'] = '2';
            $newData['paytype'] = $paytype;
            $newData['real_money'] = $total_fee;
            $res1 = D('Order')->where(array('id'=>$orderData['id']))->save($newData);
            $res2 = promotion($total_fee,$orderData['u2id']);
            if ($res && $res1 && $res2 ) {
                exit('success');
            } else {
                exit('fail');
            }
        } else {
            exit('fail');
        }
    }






    public function test(){    //测试分佣
        $result = $this->rebate('1000','2000','14');
        dump($result);
    }

    /**
     * @param $manyMoney
     * @param $lessMoney
     * @param $userId
     * @return bool
     */
    public function rebate($manyMoney, $lessMoney, $userId){      //消费返佣
        $config = file_get_contents('./private/conf.txt');
        $conf = unserialize($config);
        $user = D('User2');
        $backmoney = D('Backmoney');
        $userData = $user->where(array('id'=>$userId))->find();
        $firstData = $user->field('id,refereeid,grade')->where(array('id'=>$userData['refereeid']))->find();
        $backmoney->startTrans();
        try {
            if ($firstData) {
                $num = $firstData['grade'] * 1 - 1;
                //一级直营返佣
                $i = 0;
                if($manyMoney > 0) {
                    $one[$i]['money'] = $manyMoney * $conf['my'][$num] * 0.01;
                    $one[$i]['u2id'] = $firstData['id'];
                    $one[$i]['source'] = $userId;
                    $one[$i]['message'] = '直营分佣基金';
                    $one[$i]['create_at'] = time();
                    $i++;
                }
                //一级非直营返佣
                if ($lessMoney > 0) {
                    $twoMoney1 = $lessMoney * $conf['nomy'][$num] * 0.01;
                    if ($twoMoney1 > 0) {
                        $one[$i]['money'] = $twoMoney1;
                        $one[$i]['u2id'] = $firstData['id'];
                        $one[$i]['source'] = $userId;
                        $one[$i]['message'] = '非直营分佣基金';
                        $one[$i]['create_at'] = time();
                    }
                }
                $res = $backmoney->addAll($one);
                if ($res) {
                    $twoData = $user->field('id,refereeid,grade')->where(array('id' => $firstData['refereeid']))->find();
                    if ($twoData) {
                        $num = $twoData['grade'] * 1 - 1;
                        //二级直营返佣
                        $y = 0;
                        if($manyMoney > 0) {
                            $two[$y]['money'] = $manyMoney * $conf['my'][$num] * 0.01;
                            $two[$y]['u2id'] = $twoData['id'];
                            $two[$y]['source'] = $userId;
                            $two[$y]['message'] = '直营分佣基金';
                            $two[$y]['create_at'] = time();
                            $y++;
                        }
                        //二级非直营返佣
                        if ($lessMoney > 0) {
                            $twoMoney2 = $lessMoney * $conf['nomy'][$num] * 0.01;
                            if ($twoMoney2 > 0) {
                                $two[$y]['money'] = $twoMoney2;
                                $two[$y]['u2id'] = $twoData['id'];
                                $two[$y]['source'] = $userId;
                                $two[$y]['message'] = '非直营分佣基金';
                                $two[$y]['create_at'] = time();
                            }
                        }
                        $res2 = $backmoney->addAll($two);
                        if ($res2) {
                            $threeData = $user->field('id,refereeid,grade')->where(array('id' => $twoData['refereeid']))->find();
                            if ($threeData) {
                                $num = $threeData['grade'] * 1 - 1;
                                //三级直营返佣
                                $j = 0;
                                if($manyMoney > 0) {
                                    $three[$j]['money'] = $manyMoney * $conf['my'][$num] * 0.01;
                                    $three[$j]['u2id'] = $threeData['id'];
                                    $three[$j]['source'] = $userId;
                                    $three[$j]['message'] = '直营分佣基金';
                                    $three[$j]['create_at'] = time();
                                    $j++;
                                }
                                //三级非直营返佣
                                if ($lessMoney) {
                                    $twoMoney3 = $lessMoney * $conf['nomy'][$num] * 0.01;
                                    if ($twoMoney3 > 0) {
                                        $three[$j]['money'] = $twoMoney3;
                                        $three[$j]['u2id'] = $threeData['id'];
                                        $three[$j]['source'] = $userId;
                                        $three[$j]['message'] = '非直营分佣基金';
                                        $three[$j]['create_at'] = time();
                                    }
                                }
                                $res3 = $backmoney->addAll($three);
                                if ($res3) {
                                    return  $backmoney->commit();
                                } else {
                                    throw new Exception();
                                }
                            }else{
                                return $backmoney->commit();
                            }
                        } else {
                            throw new Exception();
                        }
                    }else{
                        return $backmoney->commit();
                    }
                } else {
                    throw new Exception();
                }
            }
        }catch(Exception $e){
            $backmoney->rollback();
        }

    }






}


?>