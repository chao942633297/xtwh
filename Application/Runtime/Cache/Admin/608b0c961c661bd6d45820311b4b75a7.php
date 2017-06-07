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
</style>
	<div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">产品　添加 | 编辑</h4>
    </div>
    <form action="<?php echo U('goods/update');?>" method="post" onSubmit="return check();" enctype="multipart/form-data">
     <div class="modal-body">
       <input type="hidden" name="id" value="<?php echo ($data['id']); ?>">
				<table style="width: 100%;">
          <tr style="margin-bottom:5px;">
            <td>选择类型</td>
            <td><select name="instruments_id" class="form-control" id="instruments_id" style="width:300px;margin-bottom: 10px;" >
              <option value="0">选择类型</option>
              <?php if(is_array($instruments)): foreach($instruments as $key=>$va): ?><option value="<?php echo ($va['id']); ?>" <?php if($data['instruments_id'] == $va['id']) echo 'selected';?>><?php echo ($va['name']); ?></option><?php endforeach; endif; ?>
            </select></td>
          </tr>
            <tr style="margin-bottom:5px;">
            <td>选择品牌</td>
            <td><select name="brand_id" class="form-control" id="brand_id" style="width:300px;margin-bottom: 10px;" >
             <option value="0">选择品牌</option>
              <?php if(is_array($brand)): foreach($brand as $key=>$vb): ?><option value="<?php echo ($vb['id']); ?>" <?php if($data['brand_id'] == $vb['id']) echo 'selected';?>><?php echo ($vb['name']); ?></option><?php endforeach; endif; ?>
            </select></td>
          </tr>
            <tr style="margin-bottom:5px;">
            <td>选择材质</td>
            <td><select name="material_id" class="form-control" id="material_id" style="width:300px;margin-bottom: 10px;" >
             <option value="0">选择材质</option>
              <?php if(is_array($material)): foreach($material as $key=>$vc): ?><option value="<?php echo ($vc['id']); ?>" <?php if($data['material_id'] == $vc['id']) echo 'selected';?>><?php echo ($vc['name']); ?></option><?php endforeach; endif; ?>
            </select></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>产品名称</td>
            <td><input type="text" class="form-control" name="name" placeholder="名称" id="name" value="<?php echo ($data['name']); ?>"></td>
          </tr>
          <tr style="margin-bottom:5px;">
            <td>封面主图</td>
            <td><input type="file" style="width:100px;" class="form-control" id="up_img" name="photo" > <div id="imgdiv"><img id="imgShow" width="100" height="100" src="<?php echo ($data['pic']); ?>" /></div></td>
          </tr>
					<tr style="margin-bottom:5px;">
						<td>产品价格</td>
						<td><input type="text" style="width:200px"  class="form-control" name="price" placeholder="商品价格" id="price" value="<?php echo ($data['price']); ?>"></td>
					</tr>
          <tr style="margin-bottom:5px;">
            <td>会员打折</td>
            <td><input type="text" style="width:200px" class="form-control" name="discount" placeholder="会员打折" id="discount" value="<?php echo ($data['discount']); ?>">(例：9表示打9折 或者9.5表示打9.5折)</td>
          </tr>
           <tr>        
            <td>产品简述</td>
            <td><textarea style="resize:none;width:410px" type="text" rows="4" class="form-control" name="desc" id="desc" ><?php echo ($data['desc']); ?></textarea> 
         </tr>
          <tr style="margin-bottom:5px;">
            <td>产品状态</td>
            <td><select name="status" class="form-control" style="width:200px;margin-bottom: 10px;margin-top:10px; " >
                <option value="0" <?php if($data['status'] == 0) echo 'selected';?> >上线</option>
              
                <option value="1" <?php if($data['status'] == 1) echo 'selected';?>>下架</option>          
            </select></td>
          </tr>
	       
	       <tr>
						<td style='margin-top:10px;padding-bottom:0px;'>产品详情</td>
						<td>
							<span id="detail" name="detail" style='display:block;margin-top:10px;'><?php echo ($data['content']); ?></span> 					
						</td>	       	
	       </tr> 
				</table>
	    </div>      
<script id="container" name="content" type="text/plain" style="min-height: 400px;">
</script>		
<button class="btn btn-default" type="submit" style="width:200px;text-align:center;position:absolute;right:100px;margin-top:15px;">完成</button>
</form>
</body>
</html>
<script src="/Public/ueditor/ueditor.config.js"></script>
<script src="/Public/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
  $("#detail").hide();
    var ue;
    $(function(){
    	$("#container").height(document.documentElement.clientHeight-160);
         ue = UE.getEditor('container',{
            serverUrl :"<?php echo U('/Admin/goods/ueditor');?>"
        });
            ue.ready(function() {
		    //设置编辑器的内容
		    ue.setContent($("#detail").html());      
		});
    });

	//保存添加或修改的推荐信息
	function check(){
   var instruments_id = $("#instruments_id").val();
   if(instruments_id == 0){
      alert('请选择类型');
       return false;
    }
    var brand_id = $("#brand_id").val();
    if(brand_id == 0){
      alert('请选择品牌');
      return false;
    }
   
    var material_id= $("#material_id").val();
    if(material_id == 0){
      alert('请选择材质');
       return false;
    }
    var name   = $("#name").val();  
    if(name == 0){
      alert('产品名称不能为空');
       return false;
    }
   
    var price      = $("#price").val();
    if(price == 0){
      alert('产品价格不能为空');
       return false;
    }
   //  var container = $("#container").val();
   //  alert(container);
  	// if(container == 0){
   //    alert('产品详情不能为空');
   //     return false;
   //  }
	  return true;
  	
	}

</script>