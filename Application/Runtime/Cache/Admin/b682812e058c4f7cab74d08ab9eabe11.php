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

        <style>
            .kll-form_out {
                position: fixed;
                top: 110px;
    			left: 20%;
                border: 1px solid #666;
                padding: 20px;
                background: #fff;
            }
            .kll-table_out {
                border-collapse: collapse;
                border-spacing:0;
            }
            .kll-th_out {
                border: 1px solid #555;
                color: #7C26BD;
                padding: 10px;
            }
            .kll-td_out {
                border: 1px solid #555;
                color: #005;
                padding: 10px;
                text-align: center;
            }
            .kll-input_out {
                border: 0;
                border-bottom: 1px solid #000;
                width: 50px;
                outline: none;
                font-size: 20px;
                color: #f90;
            }
            .kll-button_out {
                display: block;
                margin: 20px auto 0;
                width: 80px;
                height: 40px;
                font-size: 20px;
                font-weight: 900;
                color: #09f;
                background: none;
                border: 1px solid #09f;
                border-radius: 10px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <form class="kll-form_out" id="myform">
            <table class="kll-table_out">
                <tr class="kll-tr_out">
                    <th class="kll-th_out" colspan="2">会员级别</th>
                    <th class="kll-th_out">VIP(900元)</th>
                    <th class="kll-th_out">VIP银卡(1800元)</th>
                    <th class="kll-th_out">VIP金卡(3600元)</th>
                    <th class="kll-th_out">VIP钻卡(3600元)</th>
                    <th class="kll-th_out">合伙人(7200元)</th>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out" rowspan="3">直营续存</td>
                    <td class="kll-td_out">一级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my0" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my1" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my2" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my3" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my4" name="my[]"><span class="kll-span_out">%</span></td>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out">二级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my5" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my6" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my7" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my8" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my9" name="my[]"><span class="kll-span_out">%</span></td>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out">三级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my10" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my11" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my12" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my13" name="my[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="my14" name="my[]"><span class="kll-span_out">%</span></td>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out" rowspan="3">非直营续存</td>
                    <td class="kll-td_out">一级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy0" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy1" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy2" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy3" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy4" name="nomy[]"><span class="kll-span_out">%</span></td>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out">二级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy5" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy6" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy7" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy8" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy9" name="nomy[]"><span class="kll-span_out">%</span></td>
                </tr>
                <tr class="kll-tr_out">
                    <td class="kll-td_out">三级教育基金</td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy10" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy11" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy12" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy13" name="nomy[]"><span class="kll-span_out">%</span></td>
                    <td class="kll-td_out"><input class="kll-input_out" type="text" id="nomy14" name="nomy[]"><span class="kll-span_out">%</span></td>
                </tr>
            </table>
            <button class="kll-button_out" type="button" id="button1">提交</button>
        </form>
<script type="text/javascript">

	$('#button1').on('click',function(){
		// var myform = $('#myform').serialize();
		$.ajax({
			url : "<?php echo U('Admin/Admin/index1');?>",
			type:"POST",
			data : $('#myform').serialize(),
			success : function(req){
				alert(req);
			}
		})
	});

	$.ajax({
		url : "<?php echo U('Admin/Admin/index1');?>",
		type:"GET",
		data : {},
		dataType : 'json',
		success : function(req){
			// console.log(req);
			var my = req.my;
			for(var i in my){
				$('#my'+i).val(my[i]);
			}
			var nomy = req.nomy;
			for(var i in nomy){
				$('#nomy'+i).val(nomy[i]);
			}
		}
	})


	function saveUser(){
		var userid   = $("#userid").val();
		var username = $("#username").val();
		var password = $("#password").val();
		// var pattern = /^[\u4E00-\u9FA5]{1,6}$/;  //姓名正则
		var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/; 	//手机号正则

		if(username.length==0){
			alert('请输入登录名');return;
		}
		if (password.length < 6) {
			alert("密码不符合要求");return;
		}
  	$.ajax({
  		type     : "POST",
  		url      : "<?php echo U('/Admin/Admin/saveUser');?>",
  		data     : {"userid":userid,"username":username,"password":password},
  		dataType : "json",
  		error    : function(){},
  		success  : function(data){
    			console.log(data);
    			if(data.status){
    				$(window.parent.document).find("#main_iframe").attr("src",data.url);
    			}else{
    				alert(data.info);
    			}      				
  		}
  	});
	}

	$(".kll-input_out").on("keyup", function () {
		var value = $(this).val();
		// var reg = /[^0-9|\.]/g;//可带点
		var reg = /[^0-9]/g;//不可带点
		value = value.replace(reg, "");
		if (value > 100) {
			value = 99;
		}
		$(this).val(value);
	});
</script>
</body>
</html>