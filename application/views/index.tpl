<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>首页-浅蓝风格demo页</title>
<link href="<{$baseurl}>public/css/style.css" rel="stylesheet" type="text/css">
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script src="<{$baseurl}>public/js/common.js" type="text/javascript"></script>
</head>
<body>
<div class="layout_header">
  <div class="header">
    <div class="h_logo"><a href="#" title="业务监控平台"><img src="<{$baseurl}>public/images/qaup_logo.png" width="130" height="40"  alt=""/></a></div>
    <div class="h_nav"> <span class="hi"><img src="<{$baseurl}>public/images/head_default.jpg" alt="id"/> 欢迎你，管理员！</span><span class="link"><a href="#"><i class="icon16 icon16-setting"></i> 设置</a><a href="#"><i class="icon16 icon16-power"></i> 注销</a></span> </div>
    <div class="clear"></div>
  </div>
</div>
<div class="layout_leftnav">
  <div class="inner">
    <div class="nav-vertical">
      <ul class="accordion">
      	<{foreach from=$groups item=group}>
        <li id="<{$group}>"> <a class="group" href="#"><{$group|replace:$prefix:""}><span></span></a>
        </li>
        <{/foreach}>
      </ul>
      <script type="text/javascript">
		$(document).ready(function() {
			// Store variables
			var accordion_head = $('.accordion > li > a'),
				accordion_body = $('.accordion li > .sub-menu');
			// Open the first tab on load
			accordion_head.first().addClass('active').next().slideDown('normal');
			// Click function
			accordion_head.on('click', function(event) {
				// Disable header links
				event.preventDefault();
				// Show and hide the tabs on click
				if ($(this).attr('class') != 'active'){
					accordion_body.slideUp('normal');
					$(this).next().stop(true,true).slideToggle('normal');
					accordion_head.removeClass('active');
					$(this).addClass('active');
				}
			});
		});
		
		$("a.group").click(function() {
			var url = "<{$baseurl}>index.php/welcome/showcurrgroup/" + $(this).parent().attr('id');
			$.get(url, function(data, status) {
				var hosts = eval(data);
				$("#host").empty();
				for (var i in hosts){
					$("#host").append("<option value='" + hosts[i].name + "'>" + hosts[i].name + "</option>");
				}
				$("#host").change();
			});
		});
</script> 
    </div>
  </div>
</div>
<div class="layout_rightmain">
<div class="inner">
  <div class="page-title"><i class="i_icon"></i> 个人信息 </div>
  <div class="pd10 left">
    <div class="page-search"> 类型：
      <select class="select" id="host">
      </select>
      <script type="text/javascript">
		$("#host").change(function(){
			var prefix = "http://mt.noah.baidu.com/monife/widgets/visualize.html?[{%22namespaces%22:[%22";
			var suffix = "%22],%22items%22:[%22CPU_IDLE%22,%22MEM_FREE_PERCENT%22,%22DISK_TOTAL_USED_PERCENT%22],%22interval%22:86400000,%22autoLoad%22:%22true%22}]";
			var url = prefix + $("#host").find("option:selected").text() + suffix;
			$("#iframe").attr("src", url);
		});
	 </script> 
      <a href="#" class="btn btn-primary"><i class="icon16 icon16-zoom"></i> 搜索</a> </div>
<div class="panel">
<div class="list-table">
	<iframe id="iframe" width=100% frameBorder=0 height=70%	src=''></iframe> 
</div>
</div>
  </div>
  
  </div>
</div>
<div class="layout_footer">&copy; 2013-2014 Baidu.com 百度公司版权所有</div>
</body>
</html>
