<?php
/**
 *   配置账号信息
 *   配置要和证书在一起！！！！
 */
namespace Vendor\WxTx;
class WxTransfersConfig
{
    //=======【基本信息设置】=====================================
    //
    /**
     * TODO: 修改这里配置为您自己申请的商户信息
     * 微信公众号信息配置
     *
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     *
     * MCHID：商户号（必须配置，开户邮件中可查看）
     *
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     *
     */
    const APPID = 'wx82f96da4bdf876f7';
    const MCHID = '1467802102';
    const KEY = '6220C6DF0B490DE48435D99CF5A7DBD9';
    //=======【证书路径设置】=====================================
    /**
     * TODO：设置商户证书路径
     * 证书路径,注意应该填写绝对路径,发送红包和查询需要，可登录商户平台下载
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * @var path 跟这个文件同一目录下的cert文件夹放置证书！！！！
     */
    const SSLCRET12 = 'cacert/apiclient_cert.p12';
    const SSLCERT_PATH = 'cacert/apiclient_cert.pem';
    const SSLKEY_PATH = 'cacert/apiclient_key.pem';
    const SSLROOTCA = 'cacert/rootca.pem';

    //=======【证书路径设置】=====================================
    /**
     * 获取文件的路径，证书需要完整路径
     * @return string
     */
    public static function getRealPath(){
        $path = __DIR__;
        return substr($path,'0',strlen($path)-4).'Weixinpay/';
    }
}