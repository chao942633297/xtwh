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
<script src="/Public/js/uploadPreview.js" type="text/javascript"></script>
<script src="/Public/js/uploadPreview.min.js" type="text/javascript"></script>
 <script>
       window.onload = function () { 
            new uploadPreview({ UpBtn: "up_img", DivShow: "imgdiv", ImgShow: "imgShow" });
        }
    </script>
<style type="text/css">
	table tr > td:first-child{padding-left:20px;padding-bottom:10px;width:40%;text-align:center;}
	table tr > td:last-child input{width:60%;margin-bottom:10px;	}
ul, ol, dl { list-style: none; }
._citys { width: 450px; display: inline-block; border: 2px solid #eee; padding: 5px; position: relative; background: #fff;}
._citys span { color: #56b4f8; height: 15px; width: 15px; line-height: 15px; text-align: center; border-radius: 3px; position: absolute; right: 10px; top: 10px; border: 1px solid #56b4f8; cursor: pointer; }
._citys0 { width: 100%; height: 34px; display: inline-block; border-bottom: 2px solid #56b4f8; padding: 0; margin: 0; }
._citys0 li { display: inline-block; line-height: 34px; font-size: 15px; color: #888; width: 80px; text-align: center; cursor: pointer; }
.citySel { background-color: #56b4f8; color: #fff !important; }
._citys1 { width: 100%; display: inline-block; padding: 10px 0; }
._citys1 a { width: 83px; height: 35px; display: inline-block; background-color: #f5f5f5; color: #666; margin-left: 6px; margin-top: 3px; line-height: 35px; text-align: center; cursor: pointer; font-size: 13px; overflow: hidden; }
._citys1 a:hover { color: #fff; background-color: #56b4f8; }
.AreaS { background-color: #56b4f8 !important; color: #fff !important; }
</style>
	<div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">视频　添加 | 编辑</h4>
    </div>
    <form action="<?php echo U('Teacher/do_jiaoshi');?>" method="post" onSubmit="return check();" enctype="multipart/form-data">
     <div class="modal-body">       
		<table style="width: 100%;">
		<tr style="margin-bottom:5px;">
            <td>选择课程分类</td>
            <td><select name="kecheng_id" class="form-control" id="ke" style="width:300px;margin-bottom: 10px;" >
              <option value="0">选择分类</option>
              <?php if(is_array($data)): foreach($data as $key=>$va): ?><option value="<?php echo ($va['id']); ?>"><?php echo ($va['name']); ?></option><?php endforeach; endif; ?>
            </select></td>
          </tr>
           <tr style="margin-bottom:5px;">
            <td>选择科目</td>
            <td><select name="categoryid" class="form-control" id="mu" style="width:300px;margin-bottom: 10px;" >
              <option value="0">选择科目</option>
             
            </select></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>标题</td>
            <td><input type="text" class="form-control" name="title" placeholder="名称" id="name" value=""><font color="red">*不可以为空*</font></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>封面</td>
            <td id="imgdiv"><input type="file" style="width:100px;" class="form-control" id="up_img" name="photo" ><img id="imgShow" width="100" height="100" /></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>视频连接</td>
            <td><input type="text" class="form-control" name="video" placeholder="等级为1-5数字" id="name" value=""><font color="red">*乐视视频连接*</font></td>
          </tr>          
          <tr style="margin-bottom:5px;">
            <td>价格</td>
            <td><input type="text" class="form-control" name="price" placeholder="等级为1-5数字" id="name" value=""><font color="red">*价格不可以为空*</font></td>
          </tr> 
          <tr>        
             <td>简介</td>
             <td><textarea style="resize:none;width:60%;height:200px;margin-top: 10px;" type="text" rows="4" class="form-control" name="description" id="desc" ></textarea><font color="red">*老师的详细介绍*</font></td>
         </tr> 
          
          <tr style="margin-bottom:5px;">
            <td>开课时间</td>
            <td><input type="text" class="form-control" name="start_time" placeholder="教龄为数字" id="jl" value=""><font color="red">*线下开课时间*</font></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>会员打折</td>
            <td><input type="text" class="form-control" name="discount" placeholder="老师的座右铭" id="zy" value=""><font color="red"></font></td>
          </tr>
           <tr style="margin-bottom:5px;">
            <td>点击量</td>
            <td><input type="text" class="form-control" name="click" placeholder="老师的座右铭" id="zy" value=""><font color="red"></font></td>
          </tr>
           <tr style="margin-bottom:5px;">
            <td>视频状态</td>
            <td><select name="status" class="form-control" style="width:200px;margin-bottom: 10px;margin-top:10px; " >
                <option value="0">上架</option>
              
                <option value="1">下架</option>          
            </select></td>
          </tr>
           <tr style="margin-bottom:5px;">
            <td>授课方式</td>
            <td><select name="line" class="form-control" style="width:200px;margin-bottom: 10px;margin-top:10px; " >
                <option value="0">线上</option>
              
                <option value="1">线下</option>          
            </select></td>
          </tr>  
	             
		</table>
	    </div> 
        <input type="hidden" name="type" value="add">   
		<button class="btn btn-default" type="submit" style="width:200px;text-align:center;margin-top:15px;margin-left:600px;">完成</button>
</form>
</body>
</html>
<script src="/Public/ueditor/ueditor.config.js"></script>
<script src="/Public/ueditor/ueditor.all.min.js"></script>
<script src="/Public/js/jquery-1.11.3.min.js"></script>
<script src="/Public/js/Popt.js"></script>
<script src="/Public/js/cityJson.js"></script>
<script src="/Public/js/citySet.js"></script>
<script type="text/javascript">
	$("#city").click(function (e) {
		SelCity(this,e);
	    console.log("inout",$(this).val(),new Date())
	});
 $("#ke").change(function(){
    // alert($(this).val());
    var id = $("#ke").val();
    if(id != 0){
          $.ajax({
            type     : "POST",
            url      : "<?php echo U('Course/kemu');?>",
            data     : {"id":id,},
            dataType : "json",
            success  : function(data){   
                $("#mu option").remove();          
                for (var i = data.length - 1; i >= 0; i--) {

                  $("#mu").append("<option value='" + data[i]['id'] + "'>" + data[i]['name'] + "</option>");               
                }
            }, error : function(){
              alert('数据获取失败');
            }
          }); 
      }else{
         $("#mu option").remove();
         $("#mu").append("<option value='0'>选择科目</option>");    
      }
  });
	//保存添加或修改的推荐信息
	function check(){
    var name   = $("#name").val();  
    if(name == 0){
      alert('教师名称不能为空');
       return false;
    }
    var pattern = /^1[34578]\d{9}$/;   
    var ph = $("#ph").val();  
    if(ph == 0){
       alert('手机号不能为空');
       return false;
    }
    if(!pattern.test(ph)){
      alert('手机号码格式不对');
      return false;
    }
    var price  = $("#desc").val();
    if(price == 0){
      alert('老师的介绍不能为空');
       return false;
    }
     var up_img = $("#up_img").val();
    if(up_img == 0){
      alert('必须上传一张封面');
       return false;
    }
    var jl = $("#jl").val();
    if(jl== 0){
      alert('教龄');
       return false;
    }
    var zy = $("#zy").val();
    if(zy== 0){
      alert('座右铭不能为空');
       return false;
    }
    var up_img = $("#city").val();
    if(up_img == 0){
      alert('选择地址');
       return false;
    }
   
   
	  return true; 	
	}

</script>