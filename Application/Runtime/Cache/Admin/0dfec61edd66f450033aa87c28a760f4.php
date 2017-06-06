<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>杏坛文化</title>
		<meta name="description" content="杏坛文化" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<script src="/Public/jquery/jquery-2.0.3.min.js"></script>
		<script src="/Public/bootstrap/js/bootstrap.min.js"></script>
		<script src="/Public/jquery/jquery.form.js"></script>
		<style type="text/css">
		canvas, body{
		  padding: 0;
		  margin: 0;
		  overflow: hidden;
		}
		.container{
			position:absolute;
			left:10%;
		}
		h2{
			text-align: center;font-family: 微软雅黑;color:#7C26BD;
		}
		.addspan{
			color:#FFFFFF;background-color:#7C26BD;
		}
		input[class="form-control"]{
			height: 40px;
		}
		.submitdiv{
			text-align: right;margin-bottom: 20px;
		}
		.submitdiv button{
			margin-top: 20px;margin-right: 10px;width: 150px;
		}
		div[class="input-group"]{
			margin-top: 20px;
			height: 40px;
		}
		#vercode{
			float: right;
			background-color: #eee;
			width:150px;
			height: 40px;
		}
		</style>
	</head>
	<body style="background-image:url(/Public/images/bj.jpg);background-position:center; background-repeat:repeat-y">
		<div class="container" style="margin-top: 160px;">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6" style="border:4px solid #DDD;border-radius: 10px;background-color: #EEE;margin-left:468px">
					<h1 style="text-align: center;font-family: 微软雅黑;padding: 10px;">杏坛文化</h1>
					<form id="loginForm" action="<?php echo U('/Admin/Login/login');?>" method="POST">
						<div class="input-group" >
							<span class="input-group-addon addspan"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" class="form-control" placeholder="用户名" id="username" name="username" />
						</div>
						<div class="input-group">
							<span class="input-group-addon addspan"><span class="glyphicon glyphicon-eye-close"></span></span>
							<input type="password"  class="form-control" placeholder="密码" id="pwd" name="password"/>
						</div>
						<div class="input-group">
							<span class="input-group-addon addspan"><span class="glyphicon glyphicon glyphicon-calendar"></span></span>
							<input type="text" class="form-control" placeholder="验证码" id="code" style="width: 150px;" name="code"/><img id="vercode" alt="验证码" src="<?php echo U('/Admin/Login/verify_code',array());?>" title="点击刷新"></img>
						</div>
						<div class="submitdiv">
							<button class="btn  btn-primary" type="button" onclick="login();" style="background-color: #7C26BD;"><span class="glyphicon glyphicon-ok" style="margin-right: 5px;"></span>登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
						</div>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
		<script type="text/javascript">
		function login(){
			if($("#username").val()==""){
				alert('请输入用户名');
				return;
			}
			if($("#pwd").val()==""){
				alert('请输入密码');
				return;
			}
			if($("#code").val()==""){
				alert('请输入验证码');
				return;
			}
      		var vars = $("#loginForm").serialize();
			$.ajax({
				type : "POST",
				url : "<?php echo U('/Admin/Login/login');?>",
				data : vars,
				dataType : "json",
				success : function(data) {
					if (data.status) {
						location.href = data.url;
					} else {
						alert(data.info);
					}
				}
			});
		}
		$(document).ready(function(){
			var captcha_img = $('#vercode');
			var verifyimg = captcha_img.attr("src");
			captcha_img.click(function(){
			    if( verifyimg.indexOf('?')>0){
			        $(this).attr("src", verifyimg+'&random='+Math.random());
			    }else{
			        $(this).attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
			    }
			});
		});
		</script>
	</body>
</html>
<script>
var width, height
var step = 0;
var canvas = document.createElement('canvas')
var ctx = canvas.getContext('2d')

var bg = [63, 57,84]



document.getElementsByTagName('body')[0].appendChild(canvas)

// mouse coordinates
// var mousex = window.innerWidth / 2, mousey = window.innerHeight;
document.onmousedown = function(e){
  // pt = pt === pt1 ? pt2 : pt1
  pt2.x = e.pageX;
  pt2.y = e.pageY;
}
document.onmousemove = function(e){
  pt1.x = e.pageX;
  pt1.y = e.pageY;
}
var pt1 = {x:window.innerWidth*0.2, y:window.innerHeight*0.3}
var pt2 = {x:window.innerWidth*0.8, y:window.innerHeight*0.7}
    
window.addEventListener('resize', setup)

setup()

function setup() {
  canvas.width = width = window.innerWidth
  canvas.height = height = window.innerHeight
  
  ctx.beginPath();
  ctx.rect(0, 0, width, height)
  ctx.fillStyle = `rgba(${bg[0]}, ${bg[1]}, ${bg[2]}, ${1})`
  ctx.fill()
  
  pt1 = {x:window.innerWidth*0.2, y:window.innerHeight*0.2}
  pt2 = {x:window.innerWidth*0.8, y:window.innerHeight*0.8}
  // draw()
}

setInterval(animate, 60)
// window.requestAnimationFrame(animate);

function blur(ctx,canvas,amt) {
  ctx.filter = `blur(${amt}px)`
  ctx.drawImage(canvas, 0, 0)
  ctx.filter = 'none'
}

function fade(ctx, amt, width, height) {
  ctx.beginPath();
  ctx.rect(0, 0, width, height)
  ctx.fillStyle = `rgba(${bg[0]}, ${bg[1]}, ${bg[2]}, ${amt})`
  ctx.fill()
}

function animate() {
  step++
  
  blur(ctx, canvas, 1)
  draw()
  fade(ctx, 0.17, width, height)
  
  // window.requestAnimationFrame(function(){animate()})
}

function draw () {
  
  var iterations = [pt1, pt2]
  var newiterations, i, j
  for (i = 0; i < 8; i++) {
    newiterations = [iterations[0]]
    for(j = 1; j < iterations.length; j++) {
      newiterations.push(getRandMidpoint(iterations[j-1], iterations[j], 200/(i*i+1)))
      newiterations.push(iterations[j])
    }
    iterations = newiterations.concat([])
  }
  ctx.beginPath();
  ctx.moveTo(iterations[0].x, iterations[0].y);
  ctx.lineWidth = 1;
  ctx.strokeStyle = '#d4e4fb';
  ctx.strokeStyle = `hsla(${Math.sin( step / 30) * 120 + 50},${90}%,${70}%,1)`
  for (i = 1; i < iterations.length; i++) {
    ctx.lineTo(iterations[i].x, iterations[i].y);
  }
  ctx.stroke()
  ctx.closePath()
}

function getRandMidpoint(pa, pb, range) {
  var a = Math.atan2(pb.y-pa.y, pb.x-pa.x) + Math.PI/2
  var half = {y:(pb.y-pa.y)/2 + pa.y, x:(pb.x-pa.x)/2 + pa.x}
  var offset = Math.random() * range - range/2
  var ho = {
    x: Math.cos(a) * offset + half.x,
    y: Math.sin(a) * offset + half.y
  }
  return ho
}</script>