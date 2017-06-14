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

<script type="text/javascript" language="javascript">
   		function iframeResize(iframe) {
	        iframe.height = $(window).height()-155;
	        iframe.width = $(window).width()-30;
	    }
	    function OpenPage(menu){
	    	$("#headicon").removeClass();
			$("#headicon").addClass(menu.attr("funtypeicon"));
	    	$('#funtypename').html(menu.attr("funtype"));
	    	$('#funname').html(menu.html());
	    	if(menu.attr("data-url")==""){
	    		alert('没有设置data-url');
	    		return;
	    	}
	    	$('#main_iframe').attr("src",menu.attr("data-url"));
	    }
	</script>
<div class="container maindiv">
	<div id="nav" class="row">
		<div class="headtitle">杏坛文化</div>
		<div class="headright">
			欢迎您:&nbsp;<?php echo ($_SESSION['username']); ?>&nbsp;&nbsp;
			<a href="<?php echo U('/Admin/Main/index');?>" style="color: #FFFFFF;">&nbsp;&nbsp;&nbsp;[后台首页]</a>
			<a href="<?php echo U('/Admin/Login/logout');?>" style="color: #FFFFFF">&nbsp;[退出]</a>
		</div>
	</div>
	</div>
	<div class="row">
		<div class="col-md-2" style="padding-left: 0px;padding-right: 0px;">
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingOne" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-cog"></span>
			          系统管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="系统管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/Admin/changepwd');?>" class="	glyphicon glyphicon-lock" onclick="OpenPage($(this));">&nbsp;修改密码</a></li>
			        	<li><a href="javascript:" funtype="系统管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/Admin/index');?>" class="glyphicon glyphicon-wrench" onclick="OpenPage($(this));">&nbsp;基金规则</a></li>
			        	<li><a href="javascript:" funtype="系统管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/Admin/lunbo');?>" class="glyphicon glyphicon-picture" onclick="OpenPage($(this));">&nbsp;轮播图管理</a></li>			        	
			        </ul>
			      </div>
			    </div>
			  </div>
			    <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingTw" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTw" aria-expanded="true" aria-controls="collapseTw" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-calendar"></span>
			          数据统计
			        </a>
			      </h4>
			   </div>
			   <div id="collapseTw" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTw">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="数据统计" funtypeicon="glyphicon glyphicon-cog"  data-url="" class="glyphicon glyphicon-calendar" onclick="OpenPage($(this));">&nbsp;数据统计</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>
			<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingT" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseT" aria-expanded="true" aria-controls="collapseT" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-user"></span>
			          用户管理
			        </a>
			      </h4>
			   </div>
			   <div id="collapseT" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingT">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="用户管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Teacher/index');?>" class="glyphicon glyphicon-user" onclick="OpenPage($(this));">&nbsp;老师</a></li>
			        	<li><a href="javascript:" funtype="用户管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Mechanism/index');?>" class="glyphicon glyphicon-user" onclick="OpenPage($(this));">&nbsp;机构管理</a></li>
						<li><a href="javascript:" funtype="用户管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/User/index');?>" class="glyphicon glyphicon-user" onclick="OpenPage($(this));">&nbsp;三级分销会员管理</a></li>
						<li><a href="javascript:" funtype="用户管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/User/student');?>" class="glyphicon glyphicon-user" onclick="OpenPage($(this));">&nbsp;学员管理-(家长的子账号)</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingT1" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo1" aria-expanded="true" aria-controls="collapseTwo1" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-tasks"></span>
			          课程分类
			        </a>
			      </h4>
			   </div>
			   <div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingT1">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="产品分类" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Category/kecheng');?>" class="glyphicon glyphicon-th-large" onclick="OpenPage($(this));">&nbsp;课程分类</a></li>	
			        	<li><a href="javascript:" funtype="产品分类" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Category/fuwu');?>" class="glyphicon glyphicon-th-large" onclick="OpenPage($(this));">&nbsp;服务分类</a></li>		
			        </ul>
			      </div>
			    </div>
			  </div>
			
			 <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingT1" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo12" aria-expanded="true" aria-controls="collapseTwo12" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-tasks"></span>
			          产品管理
			        </a>
			      </h4>
			   </div>
			   <div id="collapseTwo12" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingT1">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="产品分类" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/GoodsClass/index');?>" class="glyphicon glyphicon-th-large" onclick="OpenPage($(this));">&nbsp;产品分类</a></li>
						<li><a href="javascript:" funtype="产品管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Goods/index');?>" class="glyphicon glyphicon-th-large" onclick="OpenPage($(this));">&nbsp;产品管理</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>
			
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingfor" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapsefor" aria-expanded="true" aria-controls="collapsefor" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-facetime-video"></span>
			          平台录播视频
			        </a>
			      </h4>
			    </div>
			    <div id="collapsefor" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfor">
			      <div class="panel-body" style="padding: 0px;">
			         <ul class="navul">
			        	<li><a href="javascript:" funtype="课程视频发布/管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Course/platform');?>" class="glyphicon glyphicon-film" onclick="OpenPage($(this));">&nbsp;课程列表</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>

			 <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingFi" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFi" aria-expanded="true" aria-controls="collapseFive" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-comment"></span>
			          内容管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapseFi" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFi">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="内容管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/Article/article');?>" class="glyphicon glyphicon-oil" onclick="OpenPage($(this));">&nbsp;文章管理</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>
			  <!-- 	<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingFiv" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFiv" aria-expanded="true" aria-controls="collapseFiv" style="font-weight:700;">
			        	<span class="	glyphicon glyphicon-facetime-video"></span>
			          视频直播管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapseFiv" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFiv">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="直播管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/LetvList/index');?>" class="glyphicon glyphicon-flag" onclick="OpenPage($(this));">&nbsp;直播管理</a></li>
			        	<li><a href="javascript:" funtype="直播管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/LetvList/price');?>" class="glyphicon glyphicon-flag" onclick="OpenPage($(this));">&nbsp;基础收费设定</a></li>
			        </ul>
			      </div>
			    </div>
			  </div> -->
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingfor" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapsefor1" aria-expanded="true" aria-controls="collapsefor1" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-gift"></span>
			          视频直播管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapsefor1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfor">
			      <div class="panel-body" style="padding: 0px;">
			         <ul class="navul">
			        	<li><a href="javascript:" funtype="视频直播管理" funtypeicon="glyphicon glyphicon-cog"  data-url="" class="glyphicon glyphicon-th-large" onclick="OpenPage($(this));">&nbsp;直播房间管理</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>

			  	<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingThree" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-yen"></span>
			         佣金管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="佣金管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Account/commissionIndex');?>" class="glyphicon glyphicon-yen" onclick="OpenPage($(this));">&nbsp;佣金列表</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>

			  	<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingSix" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-usd"></span>
			          提现管理
			        </a>
			      </h4>
			    </div>
			    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="提现管理" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('GetCash/cashList');?>" class="	glyphicon glyphicon-align-justify" onclick="OpenPage($(this));">&nbsp;提现列表</a></li>
			        </ul>
			      </div>
			    </div>
			  </div>

			  	

			  	<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingF" style="padding-left: 5px;">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseF" aria-expanded="true" aria-controls="collapseFive" style="font-weight:700;">
			        	<span class="glyphicon glyphicon-usd"></span>
			          财务对账台
			        </a>
			      </h4>
			    </div>
			    <div id="collapseF" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingF">
			      <div class="panel-body" style="padding: 0px;">
			        <ul class="navul">
			        	<li><a href="javascript:" funtype="财务对账" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Money/oneAccount/type/1');?>" class="glyphicon glyphicon-arrow-right" onclick="OpenPage($(this));">&nbsp;直营充值列表</a></li>
			        	<li><a href="javascript:" funtype="财务对账" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Money/oneAccount/type/2');?>" class="glyphicon glyphicon-share-alt" onclick="OpenPage($(this));">&nbsp;非直营充值列表</a></li>
			        	<li><a href="javascript:" funtype="财务对账" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Money/commission');?>" class="glyphicon glyphicon-usd" onclick="OpenPage($(this));">&nbsp;分销佣金列表</a></li>			        	
			        	<li><a href="javascript:" funtype="财务对账" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('Admin/Money/organization');?>" class="glyphicon glyphicon-object-align-bottom" onclick="OpenPage($(this));">&nbsp;合作机构列表</a></li>	
			        </ul>
			      </div>
			    </div>
			    <!-- <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingFive" style="padding-left: 5px;">
				      <h4 class="panel-title">
				        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive" style="font-weight:700;">
				        	<span class="	glyphicon glyphicon-usd"></span>
				          数据统计
				        </a>
				      </h4>
				    </div>
				    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
				      <div class="panel-body" style="padding: 0px;">
				        <ul class="navul">
				        	<li><a href="javascript:" funtype="数据统计" funtypeicon="glyphicon glyphicon-cog"  data-url="<?php echo U('/Admin/Config/index');?>" class="glyphicon glyphicon-flag" onclick="OpenPage($(this));">&nbsp;数据设置管理</a></li>

				        </ul>
				      </div>
				    </div>
				</div> -->
			  </div>
			</div>
		</div>
		<div class="col-md-10" style="padding-left: 0px;padding-right: 0px;">
			<div id="header" class="row contenthead">
				<span id="headicon" class="glyphicon glyphicon-home"></span>
				<span id="funtypename">后台首页</span>
				<span id="headfh" style="margin-left: 2px; margin-right: 2px"> >> </span>
				<span id="funname"></span>
			</div>
			<div>

			</div>

			<div class="row">
				<iframe src="<?php echo U('Admin/Main/sysdatainfo');?>" id="main_iframe" name="main_iframe"
					style="width: 100%;" frameborder="0" onload="iframeResize(this);" ></iframe>
			</div>
		</div>
	</div>
</div>
</body>
</html>