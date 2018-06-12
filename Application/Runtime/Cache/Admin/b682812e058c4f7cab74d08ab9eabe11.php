<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Wifi管理平台</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/mywifi/Public/css/main/bootstrap.min.css" />
<link rel="stylesheet" href="/mywifi/Public/css/main/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/mywifi/Public/css/main/fullcalendar.css" />
<link rel="stylesheet" href="/mywifi/Public/css/main/matrix-style.css" />
<link rel="stylesheet" href="/mywifi/Public/css/main/matrix-media.css" />
<link href="/mywifi/Public/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="/mywifi/Public/css/jquery.gritter.css" />
<link href='http://fonts.useso.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
<script src="/mywifi/Public/js/main/jquery.min.js"></script> 
</head>
<body>


<!--Logo-->
<div id="header">
 <h1></h1>
</div>


<!--顶部导航条-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">欢迎光临</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#"><i class="icon-user"></i> 修改密码</a></li>
        <li class="divider"></li>
        <li>&nbsp;&nbsp;&nbsp;<i class="icon-ok-sign"></i>&nbsp;&nbsp;您的权限：<?php echo (session('auth_name')); ?></li>
        <li class="divider"></li>
        <li><a href="<?php echo U('Login/login_out');?>"><i class="icon-off"></i> 退出</a></li>
      </ul>
    </li>
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">设置</span></a></li>
    <li><a title="" href="<?php echo U('Login/login_out');?>"><i class="icon icon-share-alt"></i> <span class="text">退出</span></a></li>
  </ul>
</div>
<!--顶部导航条结束-->

<!--顶部导航搜索框-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--顶部导航搜索框结束-->


<!--左侧导航条-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i>首页</a>
	<ul>
  		<!-- 首页 -->
    	<li id="index"><a href="<?php echo U('Admin/index');?>"><i class="icon icon-home"></i> <span>首页</span></a> </li>
    	<!-- 商家管理 -->
    	<li id="business" class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>商家管理</span> </a>
      		<ul>
        		<li><a href="<?php echo U('Admin/busin_add');?>">添加商家</a></li>
        		<li><a href="<?php echo U('Admin/busin');?>">查看商家</a></li>
      		</ul>
   	 	</li>
    	<!-- 活动管理 -->
    	<li id="active"> <a href="<?php echo U('Admin/active');?>"><i class="icon icon-inbox"></i> <span>活动查看</span></a> </li>
		<!-- 模板管理 -->
    	<li id="auth_view"> <a href="<?php echo U('Admin/auth_view');?>"><i class="icon icon-fullscreen"></i> <span>认证页面</span></a></li>
    	<!-- 路由器管理 -->
    	<li id="router"> <a href="<?php echo U('Admin/router');?>"><i class="icon icon-fullscreen"></i> <span>路由器管理</span></a></li>
    	<!-- 系统公告 -->
    	<li id="notice" class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>系统公告</span></a>
      		<ul>
        		<li><a href="<?php echo U('Admin/notice_add');?>">发布公告</a></li>
        		<li><a href="<?php echo U('Admin/notice');?>">历史公告</a></li>
      		</ul>
    	</li>
    	<!-- 联系我们 -->
    	<li id="connect"><a href="<?php echo U('Admin/connect');?>"><i class="icon icon-pencil"></i> <span>联系我们</span></a></li>
  	</ul>
</div>
<!--左侧导航条结束-->

<!--主页面-->
<div id="content">

<!-- 添加自定义信息 -->

<script type="text/javascript">
$(function(){  
	$('#index').addClass('active');//添加选中样式
})
</script>
<!-- 导航条 -->
<div id="content-header">
    <div id="breadcrumb"> <a href="#" title="返回首页" class="tip-bottom"><i class="icon-home"></i> 首页</a></div>
    <h1>WiFi管理平台</h1>
</div>
<!-- 导航条结束-->
<div class="container-fluid"><hr>
	<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-briefcase"></i> </span>
            <h5 >系统信息</h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span6">
                <table class="">
                  <tbody>
                    <tr>
                      <td><h4><?php echo ($info["name"]); ?></h4></td>
                    </tr>
                    <tr>
                      <td>位置：<?php echo ($info["position"]); ?></td>
                    </tr>
                    <tr>
                      <td>路线：<?php echo ($info["road"]); ?></td>
                    </tr>
                    <tr>
                      <td >简介：<?php echo ($info["describe"]); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="span6">
                <table class="table table-bordered table-invoice">
                  	<tbody>
	                    <tr>
	                      	<td class="width30">Server:</td>
	                      	<td class="width70"><strong><?php echo ($info["server"]); ?></strong></td>
	                    </tr>
	                    <tr>
	                      	<td>Database:</td>
	                      	<td><strong><?php echo ($info["database"]); ?></strong></td>
	                    </tr>
	                    <tr>
	                      	<td>Language</td>
	                      	<td><strong><?php echo ($info["language"]); ?></strong></td>
	                    </tr>
	                    <tr>
	                  		<td class="width30">Framework:</td>
	                    	<td class="width70"><strong><?php echo ($info["Framework"]); ?></strong></td>
	                  	</tr>
                	</tbody>   
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



</div>
<!--主页结束-->


<!--底部导航条-->

<div class="row-fluid">
  <div id="footer" class="span12"> 2017 &copy; 商家管理平台  </div>
</div>

<!--底部导航条结束-->

<!--底部加载JS文件-->

<script src="/mywifi/Public/js/main/excanvas.min.js"></script> 
<script src="/mywifi/Public/js/main/jquery.ui.custom.js"></script> 
<script src="/mywifi/Public/js/main/bootstrap.min.js"></script> 
<script src="/mywifi/Public/js/main/jquery.flot.min.js"></script> 
<script src="/mywifi/Public/js/main/jquery.flot.resize.min.js"></script> 
<script src="/mywifi/Public/js/main/jquery.peity.min.js"></script> 
<script src="/mywifi/Public/js/main/fullcalendar.min.js"></script> 
<script src="/mywifi/Public/js/main/matrix.js"></script> 
<script src="/mywifi/Public/js/main/matrix.dashboard.js"></script> 
<script src="/mywifi/Public/js/main/jquery.gritter.min.js"></script> 
<script src="/mywifi/Public/js/main/matrix.interface.js"></script> 
<script src="/mywifi/Public/js/main/matrix.chat.js"></script> 
<script src="/mywifi/Public/js/main/jquery.validate.js"></script> 
<script src="/mywifi/Public/js/main/matrix.form_validation.js"></script> 
<script src="/mywifi/Public/js/main/jquery.wizard.js"></script> 
<script src="/mywifi/Public/js/main/jquery.uniform.js"></script> 
<script src="/mywifi/Public/js/main/select2.min.js"></script> 
<script src="/mywifi/Public/js/main/matrix.popover.js"></script> 
<script src="/mywifi/Public/js/main/jquery.dataTables.min.js"></script> 
<script src="/mywifi/Public/js/main/matrix.tables.js"></script> 

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>

</body>
</html>