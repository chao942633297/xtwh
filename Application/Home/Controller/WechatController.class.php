<?php
namespace Home\Controller;

use Think\Controller;
use Vendor\Weixinpay\WxPayConf_pub;
use Vendor\Weixinpay\WxPayConf_pub\JsApi_pub;
use Vendor\Weixinpay\WxPayConf_pub\UnifiedOrder_pub;
use Vendor\WxTx\WxTransfers;
use Vendor\WxTx\WxTransfersConfig;

vendor('Weixinpay.WxPayPubHelper');
vendor('WxTx.WxTransfers');
vendor('WxTx.WxTransfersConfig');
class WechatController extends BaseController {

	public function checktoken()
	{
		JsApi_pub::checkToken('xingtanwenhua');

	}


		public function wechatPay(){       //微信支付
			$orderId = I('get.orderId');
            $order = D('OrderRelation');
            $orderData = $order->where(array('id'=>$orderId))->relation('user2')->find();
            $jsApi = new JsApi_pub();
            //自定义订单号，此处仅作举例
            $openid =$orderData['user2']['openid'];
            if(empty($openid)) {
                if (!isset($_GET['code'])) {
                    //触发微信返回code码
                    $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL.'/orderId/'.$orderId);
                    Header("Location: $url");
                } else {
                    //获取code码，以获取openid
                    $orderId = $_GET['orderId'];
                    $orderData = $order->where(array('id'=>$orderId))->relation('user2')->find();
                    $code = $_GET['code'];
                    $jsApi->setCode($code);
                    $openid = $jsApi->getOpenId();
                }
            }
            $timeStamp = time();
            $out_trade_no = $orderData['ordercode'];
            $total_fee = $orderData['goodprice'] * 100;
            $unifiedOrder = new UnifiedOrder_pub();
            $unifiedOrder->setParameter("openid","$openid");//商品描述
            $unifiedOrder->setParameter("body","杏坛文化");//商品描述
            $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
			$unifiedOrder->setParameter("total_fee",$total_fee);//总金额
			$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			$this->assign('jsApiParameters',$jsApiParameters);
			$this->display('Wechat/wxchatpay');
	}



	public function wxTransfer(){          //微信提现
		$path = WxTransfersConfig::getRealPath(); // 证书文件路径

//		dump($path);die;
		$config['wxappid'] = WxTransfersConfig::APPID;
		$config['mch_id'] = WxTransfersConfig::MCHID;
		$config['key'] = WxTransfersConfig::KEY;
		$config['PARTNERKEY'] = WxTransfersConfig::KEY;
		$config['api_cert'] = $path . WxTransfersConfig::SSLCERT_PATH;
		$config['api_key'] = $path . WxTransfersConfig::SSLKEY_PATH;
		$config['rootca'] = $path . WxTransfersConfig::SSLROOTCA;

		$wxtran=new WxTransfers($config);
		$wxtran->setLogFile('transfers.log');//日志地址
		//转账
		$data=array(
			'openid'=>'oZpwf01K2-n9c7v9wH1Tg_sb4bRc',//openid
			'check_name'=>'NO_CHECK',//是否验证真实姓名参数
			're_user_name'=>'11',//姓名
			'amount'=>100,//最小1元 也就是100
			'desc'=>'企业转账测试',//描述
			'spbill_create_ip'=>$wxtran->getServerIp(),//服务器IP地址
		);
		var_dump(json_encode($wxtran->transfers($data),JSON_UNESCAPED_UNICODE));
		var_dump($wxtran->error);

		//获取转账信息
		var_dump($wxtran->getInfo('11111111'));
		var_dump($wxtran->error);
	}















}
