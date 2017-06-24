<?php
/**
* 	配置账号信息
*/
namespace Vendor\Weixinpay;
class WxPayConf_pub {
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wx82f96da4bdf876f7';
	//受理商ID，身份标识
	const MCHID = '1467802102';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = '6220C6DF0B490DE48435D99CF5A7DBD9';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = '5dbf05c401d4478c4c7be5f9fb5453c8';
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'http://xtwh.yjj-jj.top/home/Wechat/wechatPay';
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = __DIR__.'/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = __DIR__.'/cacert/apiclient_key.pem';
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://http://xtwh.yjj-jj.top/home/Notify/wechatNotify';
	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}	
?>