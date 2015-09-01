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
	</script> 
    </div>
  </div>
</div>
<div class="layout_rightmain">
<div class="inner">
  <div class="page-title"><i class="i_icon"></i> 个人信息 </div>
  <div class="pd10 left">
    <div class="page-search"> 类型：
      <select class="select">
        <option>请选择</option>
      </select>
      <a href="#" class="btn btn-primary"><i class="icon16 icon16-zoom"></i> 搜索</a> </div>
      <div class="panel">
<div class="list-table">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th style="width:13px"><input type="checkbox" id="selAll" class="check" /></th>
      <th style="width:90px">编号</th>
      <th style="width:300px">标题</th>
      <th style="width:40px">级别</th>
      <th>内容</th>
      <th style="width:40px">频率</th>
      <th style="width:40px">启用</th>
      <th style="width:40px"">操作</th>
    </tr>
    <{foreach from=$cases item=case}>
    <tr>
      <td><input type="checkbox"  class="check"/></td>
      <td><{$case->num}></td>
      <td><{$case->title}></td>
      <td><{$case->clevel}></td>
      <td><{$case->content}></td>
      <td><{$case->frequency}></td>
      <td>是</td>
      <td class="i-operate"><a href="#" title="编辑">编辑</a></td>
    </tr>
    <{/foreach}>
  </table>
</div>
</div>
<div class="list-page">
<{math assign='total_page' equation="((a + b - c) / b)" a=$total_rows b=$per_page c=1 format="%d"}>
<{math assign='cur_page' equation="(a / b) + 1" a=$offset b=$per_page format="%d"}> 
  <div class="i-total">共相关Case <b><{$total_rows}></b> 条 第 <b><{$cur_page}>/<{$total_page}></b> 页  </div>
  <div class="i-list"> 
<{if ($cur_page == 1)}>
	  <span>首页</span>
	  <span>上一页</span>  
	<{if (($cur_page + 1) > $total_page)}>
	  <span>下一页</span>
	  <span>末页</span>
	<{else}>
	  <a href="<{$cur_page * $per_page}>">下一页</a> 
	  <a href="<{($total_page - 1) * $per_page}>">尾页</a>
	<{/if}>
<{elseif ($cur_page > 1)}>
	  <a href="0">首页</span>
	  <a href="<{($cur_page - 2) * $per_page}>">上一页</a>  
	<{if (($cur_page + 1) > $total_page)}>
	  <span>下一页</span>
	  <span>末页</span>
	<{else}>
	  <a href="<{$cur_page * $per_page}>">下一页</a> 
	  <a href="<{($total_page - 1) * $per_page}>">尾页</a>
	<{/if}>
<{/if}>
  </div>
  <div class="clear"></div>
</div>
  </div>
  
  </div>
</div>
<div class="layout_footer">&copy; 2013-2014 Baidu.com 百度公司版权所有</div>
</body>
</html>
