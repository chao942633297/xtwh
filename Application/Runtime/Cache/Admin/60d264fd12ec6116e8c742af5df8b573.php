<?php if (!defined('THINK_PATH')) exit();?>

<!DOCTYPE html>
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
    <!--<a href="<?php echo U('Admin/Goods/add');?>" style="margin-left:10px;width:100px;" type="button" class="btn btn-primary">添加产品</a>-->
</div>
<div style="margin:10px;">
    <div style="margin:10px;margin-top:10px;margin-left: 1200px">
        <input style="height:36px;" type="text" size="24px" placeholder="输入要搜索的手机号" id="phone" />
        <button style="margin-left:10px;width:100px;" class="btn btn-primary" onclick="searchUser();">查询</button>
    </div>
    <!--<div style="margin:10px;float:right;margin-top:-45px;">-->
        <!--<select class="form-control" style="width:50%;height:36px;display:inline;border-radius:2px;" name="" id="cash">-->
            <!--<option value="">请选择支付状态</option>-->
            <!--<option value="1">待提现</option>-->
            <!--<option value="2">已提现</option>-->
        <!--</select>-->
        <!--<button style="margin-left:10px;width:100px;" class="btn btn-primary" onclick="searchUser();">查询</button>-->
    <!--</div>-->
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
    function  formatLink(id) {
        return "<a href='cashApply/id/"+id+"/flag/"+1+"' class='btn btn-primary' style='margin-right:5px;'>通过</a> <a href='cashApply/id/"+id+"/flag/"+2+"' class='btn btn-warning' style='margin-right:5px;'>驳回</a>";
    }
    function judge(status){
        if(status==3){
            return '<font color="red">代提现</font>';
        }
    }
    function dateTime(create_at){
        return new Date(parseInt(create_at) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');
    }
    function searchUser(){
        var phone=$('#phone').val();
        var data={
            phone:phone,
        };
        $.ajax({
            url:"<?php echo U('GetCash/searchCashList');?>",
            type:'GET',
            data:data,
            dataType:'json',
            success:function(returndata){
                $("#jqGrid").clearGridData();
                $("#jqGrid").jqGrid('setGridParam', { data: returndata});
                $("#jqGrid").trigger('reloadGrid');
            }
        });
    }
    $(document).ready(function($) {
        $("#jqGrid").jqGrid({
            styleUI : 'Bootstrap',
            colModel: [
                { label: '编号', name: 'id',width:'80'},
                { label: '昵称', name: 'nickname',width:'100'},
                { label: '真实姓名', name: 'name',width:'100'},
                { label: '手机号', name: 'phone',width:'100' },
                { label: '提现金额', name: 'money',width:'100' },
                { label: '到账金额', name: 'money',width:'100' },
                { label: '申请提现时间', name: 'create_at',width:'100',formatter:dateTime},
                { label: '状态', name: 'status',width:'100',formatter:judge},
                { label: '操作', name: 'id',width:100,formatter: formatLink}
            ],
            viewrecords: true,
            rownumbers: false,
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