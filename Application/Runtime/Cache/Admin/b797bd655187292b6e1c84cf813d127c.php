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
  table tr > td:first-child{padding-left:20px;padding-bottom:10px;width:40%;text-align:center;}
  table tr > td:last-child input{width:60%;margin-bottom:10px;  }
</style>
  <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">推荐　添加 | 编辑</h4>
    </div>
     <div class="modal-body">
        <input type="hidden" name="id" id="id" value="<?php echo ($data['id']); ?>">
        <table style="width: 100%;">
          
            <tr style="margin-bottom:5px;">         
            <td>选择类型</td>
            <td><select class="form-control" style="width:200px;" id="ty" name="type"> 
                  <option value="0" >选择类型</option>           
                  <option value="1" <?php if($data['type'] == 1) echo 'selected';?>>品牌</option>              
                  <option value="2" <?php if($data['type'] == 2) echo 'selected';?>>乐器</option> 
                  <option value="3" <?php if($data['type'] == 3) echo 'selected';?>>材质</option>         
            </select></td>
            </tr>
            <tr style="margin-top:5px;">
              <td>名称</td>
              <td><input type="text" class="form-control" name="name" placeholder="推荐名称" id="name" value="<?php echo ($data['name']); ?>"></td>
            </tr>       
       
        
        </table>
      </div>      
<script id="container" name="content" type="text/plain" style="min-height: 400px;">
</script>   
<button class="btn btn-default" style="width:200px;text-align:center;position:absolute;left:630px;margin-top:15px;" onclick="saveProduct()">完成</button>
</body>
</html>
<script src="/Public/ueditor/ueditor.config.js"></script>
<script src="/Public/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">

  $("#detail").hide();
   
  //保存添加或修改的推荐信息
  function saveProduct(){  
    var name     = $("#name").val();  
    var ty       = $("#ty").val();
    var id       = $("#id").val(); 
    if (ty == 0) {
      alert('请选择类型!');return;
    };   
    if (name == '') {
      alert('名称不能为空');return;
    };
      
    $.ajax({
      type     : "POST",
      url      : "<?php echo U('/Admin/GoodsClass/update');?>",
      data     : {"name":name,"type":ty,'id':id},
      dataType : "json",

      success  : function(data){
         if(data['code'] == 1){
         	alert(data['msg']);
         	window.location.href="<?php echo U('/Admin/GoodsClass/index');?>";
         }else{
         	alert(data['msg']);
         }
      }, error : function(){
          alert('响应失败');
      }
    });
  } 


</script>