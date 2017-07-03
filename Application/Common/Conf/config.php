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
    'DB_HOST'          =>  'localhost',  // 服务器地址
    'DB_NAME'          =>  'xtwh',      // 数据库名
    'DB_USER'          =>  'xtwh',       // 用户名
    'DB_PWD'           =>  'xtwh2017',       // 密码
    'DB_PORT'          =>  '3306',       // 端口
    'DB_PREFIX'        =>  'xt_',        // 数据库表前缀
    'SHOW_PAGE_TRACE'  =>  false,        //开启ThinkPHP页面调试
   'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
   //网站域名
    'WEB'       =>'ht.xtwhjy.com',

);
