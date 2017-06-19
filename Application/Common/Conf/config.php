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
         'app_id'=>'2088621960554840',
        # 商户私钥，您的原始格式RSA私钥
        'merchant_private_key'=>'MIICWwIBAAKBgQDPiUFiAPrSQ/wLplqPq285wl5xmG96naszmqsqU0qsXRn1fY5JyCoKfcqlWcGslmrnhiw79n8tTw3ESIyI+P9HSr3ismLLOztdkILr3zSg4em6fH8ZISNaLjyf6atD1vI2hTZNmAbTApu+4YxL6u+nBhwbwtBRWPTwc1q6vVmB+QIDAQABAoGAQWrwcyX/6huH7Vwom7TcQIamIoR8T1g3yPJuFc9fcGmAb0N+gH9Z0SjJocljJTXcyNIgS15txChxHHgJ5HsobA0ZXPc4KvM+MyQ2NDvMGJPu2/EP78Ds3tQ1OMvXwXEVkMemHnCKiTFeLpHwi57bUPWXSrLsRvrZybHCwgHbl4kCQQDu39N+k/FqZ5WFSEtz/tzIwvxdjlIELRZ4qdv8Z3lMYW8HkLQ5/L/HbAl9ZFBM0qh9Dun+wRZL2qaiGgqN6CYrAkEA3mpD1m4wWJU+wcaODas9hIAXt9aSA4zPIk1635oGjyNhvCrQraj/R+JB6xgs/vW2hl8nR4E9sGxxR7FP8GuqawJAEkDoWHPVrtvbgSPVIDgJhw3fWwbVHZyUawQP22nMyxlm8p0MKKI3xXVsBDj2KeivF19cYis/GOzMbvaud8mVoQJAG8mzCAtkRuz+lj80aEjIutE2JWXNgFwLVQHRJDaeMyv8fgHraIcAvf5qtfCjTodscoVY5voitvQVgxuIHUWWWQJADMYqUedtVlxbvyJ5btyhciHhG5ffVyD7sy71Xt9Ucg/Ve6bCjy82dEyDdkqGjjqTIcjk778rAyzVmVyDqZWqXw==',
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
        'rsa_private_key' => "MIICWwIBAAKBgQDPiUFiAPrSQ/wLplqPq285wl5xmG96naszmqsqU0qsXRn1fY5JyCoKfcqlWcGslmrnhiw79n8tTw3ESIyI+P9HSr3ismLLOztdkILr3zSg4em6fH8ZISNaLjyf6atD1vI2hTZNmAbTApu+4YxL6u+nBhwbwtBRWPTwc1q6vVmB+QIDAQABAoGAQWrwcyX/6huH7Vwom7TcQIamIoR8T1g3yPJuFc9fcGmAb0N+gH9Z0SjJocljJTXcyNIgS15txChxHHgJ5HsobA0ZXPc4KvM+MyQ2NDvMGJPu2/EP78Ds3tQ1OMvXwXEVkMemHnCKiTFeLpHwi57bUPWXSrLsRvrZybHCwgHbl4kCQQDu39N+k/FqZ5WFSEtz/tzIwvxdjlIELRZ4qdv8Z3lMYW8HkLQ5/L/HbAl9ZFBM0qh9Dun+wRZL2qaiGgqN6CYrAkEA3mpD1m4wWJU+wcaODas9hIAXt9aSA4zPIk1635oGjyNhvCrQraj/R+JB6xgs/vW2hl8nR4E9sGxxR7FP8GuqawJAEkDoWHPVrtvbgSPVIDgJhw3fWwbVHZyUawQP22nMyxlm8p0MKKI3xXVsBDj2KeivF19cYis/GOzMbvaud8mVoQJAG8mzCAtkRuz+lj80aEjIutE2JWXNgFwLVQHRJDaeMyv8fgHraIcAvf5qtfCjTodscoVY5voitvQVgxuIHUWWWQJADMYqUedtVlxbvyJ5btyhciHhG5ffVyD7sy71Xt9Ucg/Ve6bCjy82dEyDdkqGjjqTIcjk778rAyzVmVyDqZWqXw==",
        # 支付宝公钥
        'ali_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB"
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
