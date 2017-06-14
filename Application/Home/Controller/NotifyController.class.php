<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class NotifyController extends Controller{

    public function notify1(){
        $orderCode = 'G608905744125829';
        $total_fee = 1;

        $orderData = D('OrderRelation')->where(array('ordercode'=>$orderCode))->relation(true)->find();
        $rebate_money = $orderData['user2']['rebate_money'];
        $limit_money = $orderData['course']['limit_price'];
        $surplus = 7200 - $rebate_money;
        if($surplus - $limit_money >=0 ){
            //返佣$limit_money
            $manyMoney = $limit_money;
            $lessMoney = $total_fee - $limit_money;

        }else{
            //返佣$surplus
            $manyMoney = $surplus;
            $lessMoney = $total_fee - $surplus;
        }

    }

    public function rebate($manyMoney='1000',$lessMoney='',$userId='14'){      //消费返佣
        $config = file_get_contents('./private/conf.txt');
        $conf = unserialize($config);
        $user = D('User2');
        $backmoney = D('Backmoney');
        $userData = $user->where(array('id'=>$userId))->find();
        $firstData = $user->field('id,grade')->where(array('id'=>$userData['refereeid']))->find();
        if($firstData){
            $num = $firstData['grade'] * 1 - 1;
                //一级直营返佣
            $one[1]['money'] = $manyMoney * $conf['my'][$num]*0.01;
            $one[1]['u2id'] = $firstData['id'];
            $one[1]['source'] = $userId;
            $one[1]['message'] = '直营分佣基金';
                //一级非直营返佣
            if($lessMoney > 0) {
                $one[2]['u2id'] = $firstData['id'];
                $one[2]['money'] = $lessMoney * $conf['my'][$num] * 0.01;
                $one[2]['source'] = $userId;
                $one[2]['message'] = '非直营分佣基金';
            }
            $twoData = $user->field('id,grade')->where(array('id'=>$firstData['id']))->find();
            if($twoData){
                $num = $twoData['grade'] * 1 - 1;
                    //二级直营返佣
                $two[1]['money'] = $manyMoney * $conf['my'][$num]*0.01;
                $two[1]['u2id'] = $twoData['id'];
                $two[1]['source'] = $userId;
                $two[1]['message'] = '直营分佣基金';
                    //二级非直营返佣
                if($lessMoney > 0) {
                    $two[2]['u2id'] = $twoData['id'];
                    $two[2]['money'] = $lessMoney * $conf['my'][$num] * 0.01;
                    $two[2]['source'] = $userId;
                    $two[2]['message'] = '非直营分佣基金';
                }
                $threeData = $user->field('id,grade')->where(array('id'=>$twoData['id']))->find();
                if($threeData){
                    $num = $threeData['grade'] * 1 - 1;
                    //三级直营返佣
                    $three[1]['money'] = $manyMoney * $conf['my'][$num]*0.01;
                    $three[1]['u2id'] = $threeData['id'];
                    $three[1]['source'] = $userId;
                    $three[1]['message'] = '直营分佣基金';
                    //三级非直营返佣
                    if($lessMoney) {
                        $three[2]['u2id'] = $threeData['id'];
                        $three[2]['money'] = $lessMoney * $conf['my'][$num] * 0.01;
                        $three[2]['source'] = $userId;
                        $three[2]['message'] = '非直营分佣基金';
                    }
                }
            }
        }
        $backmoney->startTrans();
        try{
            $res = D('backmoney')->addAll($one);
            if($res){
                $res2 = D('backmoney')->addAll($two);
                if($res2){
                    $res3 = D('backmoney')->addAll($three);
                    if($res3){
                        $backmoney->commit();
                    }else{
                        throw new Exception();
                    }
                }else{
                    throw new Exception();
                }
            }else{
                throw new Exception();
            }
        }catch(Exception $e){
            $backmoney->rollback();
        }

    }






}


?>