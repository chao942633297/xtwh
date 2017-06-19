<?php
namespace Home\Controller;

use Think\Controller;
use Vendor\AliPay\Config;
use Vendor\AliPay\AopClient;
use Vendor\AliPay\AlipayTradeService;
use Vendor\AliPay\AlipayTradeWapPayContentBuilder;
use Vendor\AliPay\AlipayFundTransToaccountTransferRequest;

vendor('AliPay.Config');
vendor('AliPay.AopClient');
vendor('AliPay.AlipayTradeService');
vendor('AliPay.AlipayTradeWapPayContentBuilder');
vendor('AliPay.AlipayFundTransToaccountTransferRequest');
class AliPayController extends Controller{

    public function webPay($orderId){             //支付宝支付
        $order = D('Order');
        $orderData = $order->where(array('id'=>$orderId))->find();
        $body = '课程';
        $subject = '杏坛文化';
        $out_trade_no = $orderData['ordercode'];
        $total_amount = $orderData['goodprice'];
        $timeout_express = '1m';
        $config = Config::config();
        $payRequestBuilder =new AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $payResponse = new AlipayTradeService($config);
        $result=$payResponse->wapPay($payRequestBuilder);

    }



    public function test(){            //支付宝转账
        $config = Config::config();
        $aop = new AopClient ($config);
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2017052607354892';
        $aop->rsaPrivateKey = 'MIICWwIBAAKBgQDPiUFiAPrSQ/wLplqPq285wl5xmG96naszmqsqU0qsXRn1fY5JyCoKfcqlWcGslmrnhiw79n8tTw3ESIyI+P9HSr3ismLLOztdkILr3zSg4em6fH8ZISNaLjyf6atD1vI2hTZNmAbTApu+4YxL6u+nBhwbwtBRWPTwc1q6vVmB+QIDAQABAoGAQWrwcyX/6huH7Vwom7TcQIamIoR8T1g3yPJuFc9fcGmAb0N+gH9Z0SjJocljJTXcyNIgS15txChxHHgJ5HsobA0ZXPc4KvM+MyQ2NDvMGJPu2/EP78Ds3tQ1OMvXwXEVkMemHnCKiTFeLpHwi57bUPWXSrLsRvrZybHCwgHbl4kCQQDu39N+k/FqZ5WFSEtz/tzIwvxdjlIELRZ4qdv8Z3lMYW8HkLQ5/L/HbAl9ZFBM0qh9Dun+wRZL2qaiGgqN6CYrAkEA3mpD1m4wWJU+wcaODas9hIAXt9aSA4zPIk1635oGjyNhvCrQraj/R+JB6xgs/vW2hl8nR4E9sGxxR7FP8GuqawJAEkDoWHPVrtvbgSPVIDgJhw3fWwbVHZyUawQP22nMyxlm8p0MKKI3xXVsBDj2KeivF19cYis/GOzMbvaud8mVoQJAG8mzCAtkRuz+lj80aEjIutE2JWXNgFwLVQHRJDaeMyv8fgHraIcAvf5qtfCjTodscoVY5voitvQVgxuIHUWWWQJADMYqUedtVlxbvyJ5btyhciHhG5ffVyD7sy71Xt9Ucg/Ve6bCjy82dEyDdkqGjjqTIcjk778rAyzVmVyDqZWqXw==';
        $aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new AlipayFundTransToaccountTransferRequest();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"3142321423432\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"13733899540\"," .
            "\"amount\":\"0.1\"," .
            "\"payer_show_name\":\"杏坛文化退款\"," .
            "\"payee_real_name\":\"贾建超\"," .
            "\"remark\":\"转账备注\"" .
            "  }");
        $result = $aop->execute ( $request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//        dump($responseNode);
        $resultCode = $result->$responseNode->code;
//        dump($result->$responseNode);
//        dump($resultCode);
        if(!empty($resultCode)&&$resultCode == 10000){
            echo "成功";
        } else {
            echo "失败";
        }
    }





}
