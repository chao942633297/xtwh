<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>杏坛文化</title>
	<meta name="description" content="杏坛文化" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/Public/other/other.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid/css/ui.jqgrid-bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/Public/jedate/skin/jedate.css">
	<link rel="stylesheet" type="text/css" href="/Public/layui/css/layui.css">
	<script src="/Public/jquery/jquery-2.0.3.min.js"></script>
	<script src="/Public/bootstrap/js/bootstrap.min.js"></script>
	<script src="/Public/bootstrap/js/bootstrap-typeahead.js"></script>
	<script src="/Public/jquery/jquery.form.js"></script>
	<script src="/Public/jqGrid/js/i18n/grid.locale-cn.js"></script>
    <script src="/Public/jqGrid/js/jquery.jqGrid.min.js"></script>
	<script src="/Public/layui/layui.js"></script>
<script type="text/javascript" src="/Public/jedate/jquery.jedate.js"></script>
<script type="text/javascript">
		// var db_root="http://localhost/ydys_yg/statics/uploads/";
	</script>
	<style>
		div[class="row"]{
			margin-left: 0px;
			width: 100%;
		}
		.maindiv{
			width: 100%;padding:0px;
		}
		#nav{
			width: 100%;height: 80px;background-color:#7C26BD;margin-left: 0px;
		}
		.headtitle{
			float:left;
			font-size: 30px;
			font-weight: 800;
			color: #FFFFFF;
			padding-top: 25px;
			margin-left: 20px;
		}
		.headright{
			float: right;color: #FFFFFF;margin-right: 10px;margin-top: 43px;font-size: 18px;
		}
		.contenthead{
			background-color: #F5F5F5;height: 40px;border: 1px solid #eee; padding-top: 10px; font-size: 16px;font-weight: 700px;padding-left: 10px;
			margin-left: 0px;
			width: 100%;
		}
		.navul{
				padding-left: 0px;
		}
		.navul li{
			list-style-type: none;
			line-height: 40px;
			background-color: #eee;
			padding-left: 15px;
			border: 1px solid #ddd;

		}
		.navul li a{
			color: #000000;
		}

		.home_content li {
			width: 49%;
			float: left;
			border-bottom: 1px solid #ebebeb;
			line-height: 30px;
			overflow: hidden;
			text-indent: 10px;
		}
		.home_content li span.ture,
		.home_content li span.flase {
			line-height: 30px;
			display: inline-block;
			padding-left: 10px;
			/*background: url(../../../Public/images/filestate.png) no-repeat;*/
		}
		.home_content li span.ture {
			background-position: 0 8px;
		}
		.home_content li span.flase {
			background-position: 0 -18px;
		}
		.formdiv{
			width: 500px;
			margin-left: auto;
			margin-right: auto;
			border: 1px solid #eee;
			margin-top: 5px;
			padding: 10px;
		}
		.formdiv table{
			width: 100%;
		}
		.formdiv table tr{
			height: 50px;
		}
		.formdiv table tr td:first-child{
			float: right;
			padding-top: 15px;
			padding-right: 5px;
		}
		.listtable{}
		.listtable th{
			background-color: #eee;
			text-align: center;
		}

		.hx_table table{

				margin:10px 276px 15px 176px;
				width:800px;
				height:86px;
		}
		.hx_table table tr:first-child{
			width:70px;
			background-color:rgba(51, 153, 204,1);
		}
		.hx_table table tr:nth-child(2){

			width:70px;
			background-color:rgba(51, 153, 204,1);
		}
		.hx_table table tr:nth-child(3){
			width:70px;
			background-color:rgba(51, 153, 204,0.5);
		}

		.hx_table table tr td{
				border-right:80px solid white;
				border-left:80px solid white;
				font-family:'ArialMT', 'Arial';
				font-size:16px;
				color:#FFFFFF;
			  text-align:center; /*设置水平居中*/
 	     vertical-align:middle;/*设置垂直居中*/
		}
	</style>
</head>
<body>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">系统信息</h3>
                </div>
                <div class="box-body">
                    <ul class="home_content">
                        <li>服务器名： <?php echo $_SERVER['SERVER_NAME']; ?> </li>
                        <li>MySql数据库： <?php echo showResult(function_exists('mysql_close')); ?> </li>
                        <li>服务器IP： <?php echo gethostbyname($_SERVER['SERVER_NAME']); ?> </li>
                        <li>ODBC数据库： <?php echo showResult(function_exists('odbc_close')); ?> </li>
                        <li>服务器端口： <?php echo $_SERVER['SERVER_PORT']; ?> </li>
                        <li>Socket支持： <?php echo showResult(function_exists('socket_accept')); ?> </li>
                        <li>服务器时间： <?php echo date('Y年m月d日H点i分s秒'); ?> </li>
                        <li>GD Library： <?php echo showResult(function_exists('imageline')); ?> </li>
                        <li>PHP版本： <?php echo PHP_VERSION; ?> </li>
                        <li>XML解析： <?php echo showResult(function_exists('xml_set_object')); ?> </li>
                        <li>WEB服务器版本： <?php echo $_SERVER['SERVER_SOFTWARE']; ?> </li>
                        <li>FTP登陆： <?php echo showResult(function_exists('ftp_login')); ?> </li>
                        <li>脚本超时时间： <?php echo get_cfg_var('max_execution_time').'秒';; ?> </li>
                        <li>PDF支持： <?php echo showResult(function_exists('pdf_close')); ?> </li>
                        <li>脚本上传文件大小限制： <?php echo get_cfg_var('upload_max_filesize')?get_cfg_var('upload_max_filesize'):'不允许上传附件'; ?> </li>
                        <li>显示错误信息： <?php echo showResult(get_cfg_var('display_errors')); ?> </li>
                        <li>POST提交内容限制： <?php echo get_cfg_var('post_max_size'); ?> </li>
                        <li>使用URL打开文件： <?php echo showResult(get_cfg_var('allow_url_fopen')); ?> </li>
                        <li>脚本运行时可占最大内存： <?php echo get_cfg_var('memory_limit')?get_cfg_var('memory_limit'):'无'; ?> </li>
                        <li>压缩文件支持(Zlib)： <?php echo showResult(function_exists('gzclose')); ?> </li>
                        <li style="border-bottom:none;">服务器操作系统： <?php echo PHP_OS; ?> </li>
                        <li style="border-bottom:none;">ZEND支持： <?php echo showResult(function_exists('zend_version')); ?> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
 function showResult($v) { if($v == 1) echo'<span class="ture" style="color:#1E90FF">支持</span>'; else echo'<span class="flase">不支持</span>'; } ?>

</body>
</html>