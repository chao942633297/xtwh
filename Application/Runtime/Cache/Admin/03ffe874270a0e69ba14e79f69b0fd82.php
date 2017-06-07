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

<div>
    <ul id="myTab" class="nav nav-tabs" role="tablist">
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="userlist">
        	<div class="formdiv">
        		<form id="changepwd">          
        			<table>
        				<tr>
        					<td>原始密码</td>
        					<td><input type="hidden" name="userid" id="userid" value="<?php echo ($_SESSION['userid']); ?>" />
								<input type="password" name="password" id="password" class="form-control"  placeholder="原始密码">
                            </td>
        				</tr>
        				<tr>
        					<td>新密码</td>
        					<td><input type="password" name="newpassword" id="newpassword" class="form-control"  placeholder="六位以上的数字和字母">
                            </td>
        				</tr>
        				<tr>
        					<td>重复密码</td>
        					<td><input type="password" name="confpassword" id="confpassword" class="form-control"  placeholder="六位以上的数字和字母"></td>
        				</tr>
        				   				
        	            <tr>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-lg btn-primary" style="background-color: #7C26BD;" onclick="changepwd();">保 存</button>
                            </td>
        	            </tr> 				
        			</table>
        		</form>
        	</div>      
        </div>
    </div>
</div>
<script type="text/javascript">
    var pp = /^[a-zA-Z][a-zA-Z0-9]{5,19}/;//密码正则      
    function changepwd(){
		var password=$("#password").val();
		var newpassword=$("#newpassword").val();
		var confpassword=$("#confpassword").val();
		if(password==""){
			alert('请输入原始密码');
			return;
		}
		if(newpassword=="" || confpassword==""){
			alert('请输入新密码');
			return;
		}
		if(newpassword != confpassword){
			alert('两次输入的密码不一致');
			return;
		}
        if (!pp.test(newpassword)) {
            alert("新密码不符合要求");return;
        }
      	var formdata = $("#changepwd").serialize();
        $.ajax({
            type     : "POST",
            url      : "<?php echo U('/Admin/Admin/savePWD');?>",
            data     : formdata,
            dataType : "json",
            success  : function(data){
                if(data.status){
                    $(window.parent.document).find("#main_iframe").attr("src",data.url);
                }else{
                    alert(data.info);
                }
            }
        });
	}

</script>

</body>
</html>