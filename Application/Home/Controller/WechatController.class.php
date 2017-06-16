<?php
namespace Home\Controller;

use Think\Controller;
use Vendor\Weixinpay\WxPayConf_pub;
use Vendor\Weixinpay\WxPayConf_pub\JsApi_pub;
use Vendor\Weixinpay\WxPayConf_pub\UnifiedOrder_pub;

vendor('Weixinpay.WxPayPubHelper');

class WechatController extends Controller {


	public function wechatPay(){       //微信支付
		$userId = session('home_user_id');
		$user = D('User2');
		$userData = $user->where(array('id'=>$userId))->find();
		$openid = $userData['openid'];
		$openid ='oDMupw8w9dYLr2IUOM3wHF8x1YJU';

		$jsApi = new JsApi_pub();
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->setParameter("openid","$openid");//商品描述
		$unifiedOrder->setParameter("body","贡献一分钱");//商品描述
		//自定义订单号，此处仅作举例
		$timeStamp = time();
		$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
		$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
		$unifiedOrder->setParameter("total_fee","1");//总金额
		$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
	}



}
