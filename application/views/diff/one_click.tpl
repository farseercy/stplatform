<!--include common javascript && css -->
<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#one_click_form").validate();
	});
	</script>
<{/block}>

<{block name="layout"}>
	<!-- <form method="post" action="<{$baseurl}>index.php/proDiff/start_diff" onsubmit="return checkParams(offline_env.value);"> -->
	<form id="one_click_form" method="post" action="<{$baseurl}>index.php/proDiff/start_diff">
<table>

<tr>
	<table border="2">
		<tr>
			  <td align="right" width="30%">线上机器名及log路径：</td>
			  <td align="left"><input style="width:60%;" type="text" id="log_rec"  value=""  class="required"></td>
			</tr>
			<tr>
			  <td align="right" width="30%">log筛选正则表达式：</td>
			  <td align="left"> <input style="width:60%;" type="text" id="log_regex"  value=""  class="required"></td>
			</tr>
			<tr>
			  <td align="right" width="30%">请求串所在位置：</td>
			  <td align="left"><input style="width:60%;" type="text" id="log_pos"  value=""  class="required"></td>
			</tr>
			<tr>
				以phpui为例：<br>
				线上机器名及log路径：tc-map-wpng01.tc.baidu.com:/home/map/lighttpd/log/lighttpd.log<br>
				log筛选正则表达式：phpui2/<br>
				请求串所在位置：8（以空格隔开的位置）<br>

			</tr>
	</table>
</tr>
<tr>
	<p></p>
	<table border="2">
		<tr>
			  <td align="right" width="30%">线下环境：</td>
			  <td align="left"><input style="width:60%;" type="text" id="offline_env"  value=""  class="required"></td>
			</tr>
			<tr>
			  <td align="right" width="30%">线上环境：</td>
			  <td align="left"><input style="width:60%;" type="text" id="online_env"  value="yf-map-mirror-wpng.vm.baidu.com:8000"></td>
			</tr>
	</table>
</tr>

</table>
		<p></p>
		<input type="submit" value="一键diff" name="submit" id="submit" />
	</form>

<{/block}>