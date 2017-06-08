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

<script src="/Public/js/uploadPreview.js" type="text/javascript"></script>
<script src="/Public/js/uploadPreview.min.js" type="text/javascript"></script>
 <script>
   window.onload = function () { 
     new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
   }
</script>
<form id="fmform" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" value="fmimg" name="imgtype" value="" />
    <input id="fmimg" style="display: none;" name="fmimg" type="file" onchange="upload('fmform','imgurl')"/>
</form>
<style type="text/css">
  table tr > td:first-child{padding-left:20px;padding-bottom:10px;width:40%;text-align:center;}
  table tr > td:last-child input{width:80%;margin-bottom:10px;  }
</style>
 <a style="margin-left:20px;margin-top:20px;width:100px;" type="button" class="btn btn-primary" onclick="kecheng()">添加服务</a>

<div>
  <div style="margin:24px;">
    名称：<input style="height:36px;" type="text" size="24px" placeholder="输入会员手机号过滤查询" id="phone" />
    编号：<input style="height:36px;" type="text" size="24px" placeholder="输入会员昵称过滤查询" id="name" />    
    <button id="submit_search" class="btn btn-primary" onclick="searchState();">查询</button>
  </div>
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="userlist" style="padding-right: 20px;">
      <table id="jqGrid" style="width: 100%;"></table>
      <div id="jqGridPager"></div>  
      </div>
      <div role="tabpanel" class="tab-pane" id="edituser">    
  </div>
<!-- 课程 -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form action="<?php echo U('Category/add');?>" method="post" onSubmit="return check();" enctype="multipart/form-data">
    <div class="modal-content" style="width:80%;margin:auto;">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加 | 编辑<span>(带<span style="color:red">*</span>号为必填项)</span></h4>
        </div>
         <div class="modal-body">
            <table class="modal-table" style="width:80%;">
              <tr>  
              <td>名称<span style="color:red">&nbsp;*</span></td>
                <td>
                <input type="text" class="form-control"  name="name" id="na" placeholder="名称"></td>
              </tr>
                                             
            </table>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_service" value="2">
          <input type="hidden" name="pid" value="0">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          <button type="submit" class="btn btn-primary" >保存</button>
        </div>         
      </div>
      </form>
    </div>
  </div>
  
</div>
</body>
</html>
<script type="text/javascript">
  // <a class='btn btn-danger' href='javascript:' onclick='deleteUser("+id+");'>删除</a>&nbsp;
  function formatLink(id) {
    return "<a class='btn btn-primary' onclick='edit("+id+");'>编辑</a>==<a class='btn btn-danger' href='javascript:' onclick='follow("+id+");'>删除</a>";
  };
 

  function kecheng() {   
    $('.bs-example-modal-lg').modal().show(); 
  }
 
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
 
 function check(){
  var na  = $.trim($("#na").val());
  if(na == ''){
     alert('分类名称');
     return false;
  }
  return true;
 }
 function checn(){
  var pi  = $.trim($("#ty").val());
  if(pi == 0){
     alert('选择课程');
     return false;
  }
  var ni  = $.trim($("#nickname").val());
  if(ni == ''){
     alert('科目名称不能为空');
     return false;
  }
  return true;
 }
  
 
  
  $(document).ready(function($) { 
    $("#jqGrid").jqGrid({
      styleUI : 'Bootstrap',
      colModel: [
          { label: '编号', name: 'id',width:'110'},
          { label: '名称', name: 'name',width:'80'},
       
          { label: '添加时间', name: 'create_at',width:'80'},
          { label: '修改时间', name: 'update-at',width:'80'},
          { label: '操作', name: 'id',width:'100',formatter: formatLink}
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