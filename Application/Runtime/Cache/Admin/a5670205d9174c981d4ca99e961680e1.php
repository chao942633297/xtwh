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


<form id="fmform" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" value="fmimg" name="imgtype" value="" />
    <input id="fmimg" style="display: none;" name="fmimg" type="file" onchange="upload('fmform','imgurl')"/>
</form>
<style type="text/css">
  table tr > td:first-child{padding-left:20px;padding-bottom:10px;width:40%;text-align:center;}
  table tr > td:last-child input{width:80%;margin-bottom:10px;  }
</style>
<div>
  <div style="margin:24px;">
   <div style="margin:24px;">
      <a style="margin-left:20px;width:100px;" type="button" class="btn btn-primary" href="<?php echo U('Teacher/jiaoshi');?>">添加老师</a>
    </div>
    <input style="height:36px;" type="text" size="24px" placeholder="输入会员手机号过滤查询" id="phone" />
    <input style="height:36px;" type="text" size="24px" placeholder="输入会员昵称过滤查询" id="name" />
    <button id="submit_search" class="btn btn-primary" onclick="searchState();">查询会员</button>
   
  </div>
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

  // <a class='btn btn-danger' href='javascript:' onclick='deleteUser("+id+");'>删除</a>&nbsp;
  function formatLink(id) {
    return "<a class='btn btn-success' href='info/id/"+id+"'>课程列表</a>&nbsp;<a class='btn btn-primary' href='edit_jiaoshi/id/"+id+"'>编辑</a>";
  };
 
  function deleteUser(id){
    if (confirm("你确定要删除吗?")) {
      $.ajax({
        url: "<?php echo U('/Admin/User/deleteUser');?>",
        dataType: "text",
        async: true,
        data: { "id": id},
        type: "GET",   
        success: function(req) {
          if(req=="true")
          {
            $(window.parent.document).find("#main_iframe").attr("src","<?php echo U('/Admin/User/User');?>");
          }else{
            alert('用户删除失败!');
          }
        }
        });     
    }
  }
  
  
 
  //时间戳转换为日期格式
  function time(nS){
     return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' '); 
  }
 
  function formatterImg(thumbnail){
    return "<img src ="+thumbnail+" style='width: 50px;height: 50px;'  />";
  }
  $(document).ready(function($) { 
    $("#jqGrid").jqGrid({
      styleUI : 'Bootstrap',
      colModel: [
          { label: '编号', name: 'id',width:'110'},
          { label: '名称', name: 'title',width:'150'},
          { label: '封面', name: 'logo',width:'120',formatter: formatterImg },
          { label: '简介', name: 'detail',width:'300'},
          { label: '省份', name: 'province',width:'100'},
          { label: '城市', name: 'city',width:'100'},
          { label: '区县', name: 'area',width:'100'},
          { label: '详细地址', name: 'address',width:'300'},
        
          { label: '操作', name: 'id',width:380,formatter: formatLink}
      ],
      viewrecords: true,
      rownumbers: true,
      rownumWidth: 35,
      height: document.documentElement.clientHeight-135,
      autowidth:true,
      rowNum: 9,
      datatype: 'local',
      loadonce:true,
      pager: "#jqGridPager"
    });
    $("#jqGrid").jqGrid('setGridParam', { data: eval(<?php echo ($data); ?>)});
    $("#jqGrid").trigger('reloadGrid');
  });
</script>
<script>
  //模糊查询会员
  function searchState(){
    var phone = $("#phone").val();
    var name = $("#name").val();
    var grade = $("#grade").val();
    var insta = $("#insta").val();
    var inend = $("#inend").val();
    if (insta.length <0 || inend.length <0) {
      alert('请选择时间');return;
    };
    $.ajax({
      url:"<?php echo U('/Admin/User/searchUser');?>",
      type:'GET',
      data:{'start':insta,'end':inend,'phone':phone,'grade':grade,'name':name},
      dataType:'json',
      success:function(returndata){
        $("#jqGrid").clearGridData();
            $("#jqGrid").jqGrid('setGridParam', { data: returndata});
            $("#jqGrid").trigger('reloadGrid');
      }
    });
  }
</script>