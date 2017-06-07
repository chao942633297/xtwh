<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'   =>  'Admin',    // 默认模快
    'DEFAULT_CONTROLLER'   =>   'Login',
    'DEFAULT_ACTION'       =>   'login',
    'TMPL_CACHE_ON' => false,//禁止模板编译缓存
    'HTML_CACHE_ON' => false,//禁止静态缓存
	/* 数据库设置 */
    'DB_TYPE'          =>  'mysql',      // 数据库类型
    'DB_HOST'          =>  '192.168.10.222',  // 服务器地址
    'DB_NAME'          =>  'xtwh',      // 数据库名
    'DB_USER'          =>  'root',       // 用户名
    'DB_PWD'           =>  '123456',       // 密码
    'DB_PORT'          =>  '3306',       // 端口
    'DB_PREFIX'        =>  'xt_',        // 数据库表前缀
    'SHOW_PAGE_TRACE'  =>  false,        //开启ThinkPHP页面调试
   'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
   //支付宝支付配置
    "Alipay"       =>   [ # appid
         'app_id'=>'',
        # 商户私钥，您的原始格式RSA私钥
        '',
        # 异步通知地址
        'notify_url'=>'',
        # 同步跳转
        'return_url' => "",
        # 编码格式
        'charset'=>'UTF-8',
        # 签名方式
        'sign_type' => 'RSA',
        # 支付宝网关
        'gatewayUrl'=>"https://openapi.alipay.com/gateway.do",
        # 支付宝私钥文件
        // 'rsa_private_key' => ROOT_PATH.'\private\siyao.txt',
        'rsa_private_key' => "",
        # 支付宝公钥
        'ali_public_key' => ""
        ],
//微信支付配置
    "Wechat"  =>   [
        # 微信的appid
        'appid'=>'',#
        # 公众号的secret
        'secret'=>'',#
        # 登录操作函数回调链接
        'callback'=>'',
        # 授权成功的回调链接
        'login_success_callback'=>'',
        # 微信支付key
        'pay_key'=>'',
        # 商户id
        'mchid' => '',#
        #通知回调地址
        'notify_url'=>'',
        #token定义
        'TOKEN'=>"",#
    ],
);
