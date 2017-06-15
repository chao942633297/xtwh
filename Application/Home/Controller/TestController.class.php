<?php
namespace Home\Controller;

use Think\Controller;
use Vendor\AliPay\Config;
use Vendor\AliPay\AlipayTradeService;
use Vendor\AliPay\AlipayTradeWapPayContentBuilder;

vendor('AliPay.Config','','.php');
vendor('AliPay.AlipayTradeService');
vendor('AliPay.AlipayTradeWapPayContentBuilder');
class TestController extends Controller{

    public function index(){
        $input = I('get.');
        $body = '课程';
        $subject = '杏坛文化';
        $out_trade_no = 'G608905744125829';
        $total_amount = '0.01';
        $timeout_express = '1m';
        $config = Config::config();
        $payResponse = new AlipayTradeService($config);
        $payRequestBuilder =new AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $result=$payResponse->wapPay($payRequestBuilder);

    }


}
