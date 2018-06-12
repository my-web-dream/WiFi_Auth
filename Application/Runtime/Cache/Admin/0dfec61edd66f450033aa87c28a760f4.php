<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<script src="/mywifi/Public/js/jquery.min.js" type="text/javascript"></script>
<script src="/mywifi/Public/js/login.js" type="text/javascript"></script>
<script src="/mywifi/Public/js/layer/layer.js" type="text/javascript"></script>
<title>Wifi认证系统</title>
<link href="/mywifi/Public/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function check()
{
	var username = $("input[name='username']").val();
	var password = $("input[name='password']").val();
	//检查姓名是否填写
	if(!username)
	{
		layer.msg("请填写用户账号");
		return false;
	}
	if(!password)
	{
		layer.msg("请填写登陆密码");
		return false;
	}		
	$("form").submit();
}
</script>
</head>

<body>
<div id="wrapper">
     <div class="header">
	      <div class="title">商场Wifi认证系统</div>
	      <form action="<?php echo U('Login/login_in');?>" method="post">
		  <div class="login">
			   <div>
                     <label>用户名：</label>
	                 <input type="text" name="username" class="text_1"/>
            </div>
	            <div>
	                 <label>密码：</label>
	                 <input type="text" name="password" class="text_1"/>
		        </div>
		  </div>
		  </form>
		  <!-- 
		  <div class="ckp">
		    <input type="checkbox" id="save" onClick="save_ck(this)" name="checkbox2" value="checkbox" />
		    记住密码
		    <input type="checkbox" id="auto" onClick="auto_ck(this);" name="checkbox" value="checkbox" /> 
	      自动登录</div>
	       -->
        <div class="ckp" style="margin-top:16px;">
		    <input type="button" onClick="check();" name="login" class="submit_2" style="cursor:pointer;"/>
	     	&nbsp;&nbsp; 
	     	<a href="javascript:void(0);" onClick="register()">注册>></a>	     </div>
	      	<p class="gsm">成都信息工程大学：通信工程学院</p>
	 	</div>
	 <div class="footer"><a href="#"><img src="images/bg_6.jpg" border="0" /></a></div>
</div>
</body>
</html>