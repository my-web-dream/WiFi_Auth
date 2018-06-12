<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>欢迎使用WIFI认证系统</title><link rel="shortcut icon" href="/api.ico" type="image/x-icon">
<link rel="stylesheet" href="/mywifi/Public/css/api/style.css">
<link rel="stylesheet" href="/mywifi/Public/css/api/responsive.css">
<link rel="stylesheet" href="/mywifi/Public/css/api/skin-8171569.css">
<!-- 注意，先加载jquery，后加载layer -->
<script type="text/javascript" src="/mywifi/Public/js/jquery.min.js" ></script>
<script type="text/javascript" src="/mywifi/Public/js/layer/layer.js" ></script>
<script type="text/javascript" src="/mywifi/Public/js/api/idangerous.swiper-2.0.min.js"></script>
<script type="text/javascript" src="/mywifi/Public/js/api/jquery-validate.min.js"></script>
<script type="text/javascript" src="/mywifi/Public/js/api/js.js"></script>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<meta name="MobileOptimized" content="320">
</head>
<body>
<div class="wrap shop">
	<!--顶部 start-->
	<header class="header">
		<a href="<?php echo U('api.php/Login');?>" class="logo"><img src="/mywifi/Public/img/api/logo.png"></a>
		<h1 class="title">欢迎您使用本商场的Wifi</h1>
	</header>
	<!--顶部 end-->
	<!--主体 start-->
	<section class="main">
		<!--flash start-->
		<div class="flash swiper-container" style="width: 640px; height: 360px;">
			<ul class="swiper-wrapper" style="width: 3840px; height: 360px; -webkit-transform: translate3d(-2560px, 0px, 0px); transition: 0.3s; -webkit-transition: 0.3s;">
				<li class="swiper-slide" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/1.jpg"><h3>霸气的外观</h3>
				</li>
				<li class="swiper-slide" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/2.jpg"><h3>奢华的内饰</h3>
				</li>
				<li class="swiper-slide" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/3.jpg"><h3>便利的交通</h3>
				</li>
				<li class="swiper-slide" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/1.jpg"><h3>霸气的外观</h3>
				</li>
				<li class="swiper-slide swiper-slide-visible swiper-slide-active" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/2.jpg"><h3>奢华的内饰</h3>
				</li>
				<li class="swiper-slide" style="width: 640px; height: 360px;">
					<img src="/mywifi/Public/img/api/3.jpg"><h3>便利的交通</h3>
				</li>
			</ul>
			<div class="num">
				<span class="swiper-pagination-switch"></span>
				<span class="swiper-pagination-switch"></span>
				<span class="swiper-pagination-switch"></span>
				<span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch"></span>
			</div>
		</div>
		<!--flash end-->
		
<script type="text/javascript">
function check()
{
	//ajax访问后台，查看用户是否已关注微信公众号
	$.post("<?php echo U('api.php/Login/weixin_check');?>",{},function(data,status){
			if(data == 0){
				layer.msg("对不起，您还没有关注公众号");
			}else if(data == 1){
				layer.msg("恭喜您，即将放行",{time:3000},function(){
					$("form").submit();
				});
			}else{
				layer.msg("未知错误，请联系管理员");
			}
		});//ajax提交结束
}
</script>
	<!--认证方式 start-->
	<div class="hd_tip">&nbsp;&nbsp;您当前的认证方式为：微信认证</div>
	<!--认证方式 end-->
	<!--服务列表 start-->
	<div id="xj_weixin">
		<img src="/mywifi/Public/img/api/weixin.jpg">
		<form method="post" action="<?php echo U('api.php/Login/weixin_sub');?>">
			<button type="button" onClick="check();" class="button" >已关注，申请上网</button>
		</form>
	</div>
	
	<!--服务列表 end-->
	<!--免费上网入口 start-->
	<div class="enter"><em>欢迎您使用商场Wifi！</em></div>
		

	</section>
	<!--主体 end-->
	<!--底部 start-->
	<footer class="footer">
		<p>&#169;公共Wifi由商场提供</p>
	</footer>
	<!--底部 end-->
</div>

</body>
</html>