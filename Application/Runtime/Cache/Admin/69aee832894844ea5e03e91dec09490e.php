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
      <a style="margin-left:20px;width:100px;" type="button" class="btn btn-primary" onclick="addUser()">添加会员</a>
    </div>
    <input style="height:36px;" type="text" size="24px" placeholder="输入会员手机号过滤查询" id="phone" />
    <input style="height:36px;" type="text" size="24px" placeholder="输入会员昵称过滤查询" id="name" />
    <select class="form-control" style="width:24%;height:36px;display:inline;border-radius:2px;" id="grade">
        <option value="">请选择会员等级</option>
        <option value="1">学童</option>
        <option value="2">学霸</option>
        <option value="3">讲师</option>
        <option value="4">合伙人</option>
    </select>
    <button id="submit_search" class="btn btn-primary" onclick="searchState();">查询会员</button>
   
  </div>
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="userlist" style="padding-right: 20px;">
      <table id="jqGrid" style="width: 100%;"></table>
      <div id="jqGridPager"></div>  
      </div>
      <div role="tabpanel" class="tab-pane" id="edituser">    
  </div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="width:80%;margin:auto;">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加 | 编辑机构　<span>(带<span style="color:red">*</span>号为必填项)</span></h4>
        </div>
         <div class="modal-body">
            <table class="modal-table" style="width:80%;">
              <tr>
              <td>手机号<span style="color:red">&nbsp;*</span></td>
              <td>
                <input type="tel" class="form-control" id="tel" placeholder="请输入正确手机号"></td>
            </tr>
              <tr>
              <td>真实姓名<span style="color:red">&nbsp;*</span></td>
              <td><input type="hidden" name="userid" id="userid"/>
                <input type="text" class="form-control" name="rname" placeholder="真实姓名" id="rname"></td>
            </tr>
              
            <tr id="pwdhide"> 
              <td>密码<span style="color:red">&nbsp;*</span></td>
              <td><input type="password" class="form-control"  name="password" id="password" placeholder="六位以上的数组和字母"></td>
              </tr>
            <tr>  
              <td>昵称<span style="color:red">&nbsp;*</span></td>
                <td>
                <input type="text" class="form-control"  name="nickname" id="nickname" placeholder="昵称"></td>
              </tr>
              <tr>
              <td>头像</td>
              <td><img id="imgurl" name="imgurl" style="width: 130px;height: 130px;margin:5px auto;" class="form-control" src="/Public/images/default.png" onclick="javascript:$('#fmimg').click();"/>
              <input id="thumbnail" type="hidden" name="thumbnail" value="" />
              <span style="color:red;">上传图片尺寸为:1145 * 500 px</span>
                            </td>
            </tr>
              <tr>
                <td>会员等级</td>
                    <td>
                        <select class="form-control"  name="level" id="level" style="width:80%;margin-bottom:10px">
                  <option value="1" >学童</option>
                  <option value="2" >学霸</option>
                  <option value="3" >讲师</option>
                  <option value="4" >合伙人</option>
                        </select>
                    </td>               
              </tr>
              <tr id="isNo">
                <td>是否可用</td>
                    <td>
                        <select class="form-control"  name="isenable" id="isenable" style="width:80%;margin-bottom:10px">
                  <option value="0" >不可用</option>
                  <option value="1" >可用</option>
                        </select>
                    </td>               
              </tr>
            <tr>  
              <td>备注</td>
              <td><input type="text" class="form-control" name="remark" id="remark" placeholder="备注"></td>
                  </tr>            
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-primary" onclick="saveUser();">保存</button>
        </div>         
      </div>
    </div>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
  // <a class='btn btn-danger' href='javascript:' onclick='deleteUser("+id+");'>删除</a>&nbsp;
  function formatLink(id) {
    return "<a class='btn btn-primary' onclick='edit("+id+");'>编辑</a>&nbsp;<a class='btn btn-danger' onclick='fall("+id+");'>查看下级</a>&nbsp;<a class='btn btn-primary' href='javascript:' onclick='fans("+id+");'>我的粉丝</a>&nbsp;<a class='btn btn-danger' href='javascript:' onclick='follow("+id+");'>我的关注</a>";
  };
  //上传图片
  function upload(fromID,imgid){
    var vars=$("#"+fromID);
    var options={
        type:"post",
        url:"<?php echo U('/Admin/File/Upload');?>",
        dataType:'json',
        contentType:"application/json;charset=utf-8",
        success:function(data){
            if(data!="false"){
                $("#"+imgid).attr('src',data);
                $("#vidurl").attr('poster',data);
                $("#thumbnail").attr('value',data);
            }
        }
    }
    vars.ajaxSubmit(options);
  }
  function formatIs(is) {
    if (parseInt(is)) {
      return "可用";      
    }else{
      return "不可用";
    }
  }
  function addUser() {
      $("#rname").val("");
    $("#phone").val("");
    $("#password").val("");   
    $("#nickname").val("");
    $("#level").val("");
    $("#thumbnail").val("");
    $("#userid").val("");
    $("#isNo").hide();
    $('.bs-example-modal-lg').modal().show(); 
  }
    //编辑用户信息
  function edit(id){
    $.ajax({
      url: "<?php echo U('/Admin/User/getOneUser');?>",
      dataType: "json",
      async: false,
      data: { "id": id},
      type: "GET",  
      success: function(user) {
          $("#rname").val(user.rname);
          $("#tel").val(user.phone);
          $("#password").val(user.password);    
          $("#nickname").val(user.name);
          $("#imgurl").attr('src',user.headimg);
          $("#vidurl").attr('poster',user.headimg);     
          $("#thumbnail").val(user.headimg);
          $("#level option").each(function(){
            var val = $(this).val();
            if (val == user.grade) {
              $(this).prop("selected","selected");
            }else{
              $(this).removeProp("selected");
            }
          })
          $("#remark").val(user.remark);
          $("#isenable option").each(function(){
            var val = $(this).val();
            if (val == user.isenable) {
              $(this).prop("selected","selected");
            }else{
              $(this).removeProp("selected");
            }
          });       
        $("#userid").val(id);
      }  
    });
    $("#isNo").show();
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
  //我的下级
  function fall(id){
    $.ajax({
      url: "<?php echo U('/Admin/User/getOneFall');?>",
      dataType: "json",
      async: false,
      data: { "id": id},
      type: "GET",   
      success: function(data) {
        // alert(data);
        if(data >0){
          $(window.parent.document).find("#main_iframe").attr("src","<?php echo U('/Admin/User/fall');?>"+"?falluid="+data);
        }else{
          alert('用户下级为空!');
        }
      }
      });     
  }
  //我的粉丝
  function fans(id){
    $.ajax({
      url: "<?php echo U('/Admin/User/getOneFans');?>",
      dataType: "json",
      async: false,
      data: { "id": id},
      type: "GET",   
      success: function(data) {
        // alert(data);
        if(data >0){
          $(window.parent.document).find("#main_iframe").attr("src","<?php echo U('/Admin/User/fans');?>"+"?fansuid="+data);
        }else{
          alert('用户粉丝为空!');
        }
      }
      });     
  }
  //我的关注人
  function follow(id){
    $.ajax({
      url: "<?php echo U('/Admin/User/getOneFol');?>",
      dataType: "json",
      async: false,
      data: { "id": id},
      type: "GET",   
      success: function(data) {
        // alert(data);
        if(data >0)
        {
          $(window.parent.document).find("#main_iframe").attr("src","<?php echo U('/Admin/User/follow');?>"+"?fid="+data);
        }else{
          alert('用户关注人为空!');
        }
      }
      });     
  }
  //保存添加或修改的用户信息
  function saveUser(){
    var userid   = $("#userid").val();
    var rname    = $("#rname").val();
    var password = $("#password").val();
    var tel  = $("#tel").val();
    var remark   = $("#remark").val();
    var thumbnail= $("#thumbnail").val();
    var nickname      = $("#nickname").val();
    var isenable   = $("#isenable option:selected").val();
    var level   =  $("#level").val();
    var preg_name= /^[\u4E00-\u9FA5]{1,6}$/;  //姓名正则
    var preg_tel = /^1[34578]\d{9}$/; //手机号
        if(!preg_tel.test(tel)) {
            alert('请输入有效的手机号码!');return;
        }
    if (!preg_name.test(rname)) {
      alert('请输入有效姓名!');return;
    }         
      if (password.length <6) {
        alert('密码不符合要求');
      } 
      $.ajax({
        type     : "POST",
        url      : "<?php echo U('/Admin/User/saveUser');?>",
        data     : {"userid":userid,"rname":rname,"nickname":nickname,"password":password,"tel":tel,"thumbnail":thumbnail,"remark":remark,"isenable":isenable,"level":level},
        dataType : "json",
        error    : function(){},
        success  : function(data){
            // console.log(data);
            if(data.status){
              $(window.parent.document).find("#main_iframe").attr("src",data.url);
            }else{
              alert(data.info);
            }             
        }
      });
  }
  //时间戳转换为日期格式
  function time(nS){
     return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' '); 
  }
  function grade(as) {
    switch(parseInt(as)){
      case 1:
        return "学童";
        break;
      case 2:
        return "学霸";
        break;
      case 3:
        return "讲师";
        break;
      case 4:
        return "合伙人";
        break;
    } 
  }
  function formatterImg(thumbnail){
    return "<img src ="+thumbnail+" style='width: 50px;height: 50px;'  />";
  }
  $(document).ready(function($) { 
    $("#jqGrid").jqGrid({
      styleUI : 'Bootstrap',
      colModel: [
        { label: '手机号', name: 'phone',width:'110'},
          { label: '昵称', name: 'name',width:'80'},
          { label: '图片', name: 'headimg',width:'120',formatter: formatterImg },
          { label: '注册时间', name: 'createtime',width:'125',formatter: time},
          { label: '账户余额', name: 'money',width:'100'},
          { label: '积分', name: 'score',width:'50' },
          { label: '累计提现', name: 'bnm',width:'70' },
          { label: '会员等级', name: 'grade',width:'80',formatter: grade},
          { label: '直推人数', name: 'person',width:'70'},
          { label: '操作', name: 'id',width:280,formatter: formatLink}
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