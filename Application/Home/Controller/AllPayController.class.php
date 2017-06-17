<?php
namespace Home\Controller;
use Think\Controller;
class AllPayController extends Controller{

    public function paymentNow(){      //立即支付   需传入payType(1微信支付2支付宝支付3余额支付)
        $input = I('get.');
        $paymentType = $input['payType'];
        switch($paymentType){
            case '1':
                $aliPay = A('AliPay');       //调取支付宝支付
                redirect($aliPay->webPay());
                break;
            case '2':
                $wechatPay = A('Wechat');     //调取微信支付
                redirect($wechatPay->wechatPay());
                break;
            case '3':
                $balance = A('Balance');     //调取余额支付
                redirect($balance->balancePay());
                break;
        }


    }




}

?>