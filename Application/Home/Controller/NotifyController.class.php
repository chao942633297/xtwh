<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class NotifyController extends Controller{

    public function notify1(){
        $user = D('User2');
        $orderCode = 'G608905744125829';
        $total_fee = 1;

        $orderData = D('OrderRelation')->where(array('ordercode'=>$orderCode))->relation(true)->find();
        dump($orderData);
        $rebate_money = $orderData['user2']['rebate_money'];   //用户已参与第一返佣的钱
        $limit_money = $orderData['course']['limit_price']?$orderData['course']['limit_price']:7200;    //课程限制可用于第一返佣的钱  充值则为默认(7200)
        $surplus = 7200 - $rebate_money;     //用户剩余可用于第一返佣规则的钱
        if($surplus - $limit_money >=0 ){
            //返佣$limit_money
            $manyMoney = $limit_money;
            $lessMoney = $total_fee - $limit_money;
        }else{
            //返佣$surplus
            $manyMoney = $surplus;
            $lessMoney = $total_fee - $surplus;
        }
        $result = $this->rebate($manyMoney,$lessMoney,$orderData['u2id']);
        if($result){
            $res =$user->where(array('id'=>$orderData['u2id']))->setInc('rebate_money',$manyMoney);
            if($res){
                jsonpReturn('1','保存成功');
            }else{
                jsonpReturn('0','保存失败');
            }
        }else{
            jsonpReturn('0','系统错误!');
        }

    }


    public function test(){    //测试分佣
        $result = $this->rebate('1000','2000','14');
        dump($result);
    }

    public function rebate($manyMoney,$lessMoney,$userId){      //消费返佣
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