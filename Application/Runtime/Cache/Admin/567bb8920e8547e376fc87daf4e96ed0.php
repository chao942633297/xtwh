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

<link rel="stylesheet" type="text/css" href="/Public/jedate/skin/jedate.css">
<script type="text/javascript" src="/Public/jedate/jquery.jedate.js"></script>
<style type="text/css">
	table tr > td {padding-left:20px;padding-bottom:5px;padding-top:5px;text-align:center;}
</style>
<form id="myform">
<table>
	<tr>
		<!-- <td class="wstxt">起始日期:</td> -->
		<!-- <td><input type="text" class="workinput wicon mr25 form-control" id="inpstart" readonly placeholder="请选择开始日期" value="" name="start"></td> -->
		<!-- <td class="wstxt">结束日期:</td> -->
		<!-- <td><input type="text" class="workinput wicon mr25 form-control" id="inpend" readonly placeholder="请选择结束日期" value="" name="end"></td> -->
		<td>手机号:</td>	
		<td><input type="text" class="form-control" name="phone" value="" id="phone" placeholder="请输入数字"/>	</td>		
    <td><input type="button" name="" id="searchsub" class="btn btn-primary" value="查询"></td>
    <td><input type="reset" name="" class="btn btn-primary" value="重置"></td>
	</tr>	
</table>
</form>
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="userlist" style="padding-right: 20px;">
      <table id="jqGrid" style="width: 100%;"></table>
      <div id="jqGridPager"></div>  
      </div>
      <div role="tabpanel" class="tab-pane" id="edituser">  
      </div>
  </div>
  
</body>
</html>

<script type="text/javascript">
  function formatImg(url) {
        return "<img src='"+url+"' style='height:40px;width:40px'/>";              
  }
  $("#phone").bind("input propertychange",function(){
    var phone = $(this).val();
    if (!/^[0-9]*$/.test(phone)) {
      $(this).val("");
    }
  });
  //查询
  $("#searchsub").click(function(){
      // var myform = $("#myform").serialize();
      // console.log(myform);
      var phone = $("#phone").val();
      if (phone == "" ) {
        alert("手机号不准为空");return false;
      }
      $.ajax({
        url:"<?php echo U('Admin/Money/search');?>",
        type:"get",
        data:{phone:phone},
        dataType:'json',        
        success:function(data){
              $("#jqGrid").clearGridData();
              $("#jqGrid").jqGrid('setGridParam', { data: data});
              $("#jqGrid").trigger('reloadGrid');              
        }
      });
  });

  $(document).ready(function($) { 
    $("#jqGrid").jqGrid({
      styleUI : 'Bootstrap',
      colModel: [
          // { label: '序号ID', name: 'id',width:'80'},
          { label: '用户头像', name: 'headimg',width:'100',formatter: formatImg },
          { label: '昵称', name: 'nickname',width:'100' },
          { label: '手机号', name: 'phone',width:'100' },
          { label: '佣金总额', name: 'allmoney',width:'100' }
      ],
      gridview:true,  //加速显示
      viewrecords: true,  //显示总条数
      rownumbers: true,
      rownumWidth: 35,
      height: document.documentElement.clientHeight-135,
      autowidth:true,
      rowNum: 9,  //每页显示条数
      datatype: 'local',
      loadonce:true,
      pager: "#jqGridPager"
    });
    $("#jqGrid").jqGrid('setGridParam', { data: eval(<?php echo ($data); ?>)});
    $("#jqGrid").trigger('reloadGrid');
  });


var start = {
    format: 'YYYY-MM-DD hh:mm:ss',
    minDate: '1970-01-01 00:00:00', //设定最小日期为当前日期
    // isinitVal:true,
    festival:false,
    ishmsVal:false,
    maxDate: '2099-06-30 23:59:59', //最大日期
};
var end = {
    format: 'YYYY-MM-DD hh:mm:ss',
    // minDate: $.nowDate(0), //设定最小日期为当前日期
    minDate: '1970-01-01 00:00:00', //设定最小日期为当前日期
    festival:false,
    maxDate: '2099-06-16 23:59:59', //最大日期
};
$('#inpstart').jeDate(start);
$('#inpend').jeDate(end);	  
</script>