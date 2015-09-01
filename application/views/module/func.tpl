<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script type="text/javascript">
	$(document).ready(function() {
	    $('#delCase').click(function (){
			//保存当前用例信息
			alert("del");
	        url = "index.php/moduleFunc/exchangeLevel/<{$curModule}>/delCases";
	        var tempcase = new Object();
	        tempcase.caseid=caseid;
	        tempcase.table='tb_func';
			//tempcase.validateTime='{"start":"'+start+'", "end":"'+end+'"}';			
	        var jsonStr = JSON.stringify(tempcase);
	    	var encodeStr = encodeURIComponent(jsonStr);
	    	//alert(jsonStr);
	    	$.ajax({
				type:"POST",
				url:url,
				dataType:'jsonp',
				data:"data="+encodeStr,
				jsonp:'callback',
				cache:false,
				success:function() {
					history.go(0);
					$("#mask").remove();   
			        $(".pop-box").animate({left: 0, top: 0, opacity: "hide" }, "slow"); 
				},
				error: function(XMLHttpRequest, textStatus){
					alert(textStatus);
					 alert('删除失败，请稍后重试');
				}
				
			});
		});
		$('#runCase').click(function (){
			alert("runCases");
		});
		$('#getCase').click(function (){
			alert("getCases");
		});
		$('#getAuto').click(function (){
			alert("getAutoTest");
		});
	});
	</script>
<{/block}>
<{block name="layout"}>
	<div class="layout_header">
        <div class="header">
            <div class="h_logo"><a href="#" title="测试平台"><img src="<{$baseurl}>/public/images/qaup_logo.png" width="130" height = "40" alt="立体监控平台"/> </a>
            </div>
            <div class="h_nav">
                <div class="btn-dropdown">
                    <a class="btn dropdown-nav"><span class="select-txt"><{$curruser}></span><span class="select-open"></span></a>
                    <ul class="dropdown-menu">
                        <{foreach from=$platInfo.dropdownarray item=value key=key}>
                            <li><a href="#"> <{$value}> </a> </li>
                        <{/foreach}>
                    </ul>
                </div>
                <a href="#" title= ""> Help </a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 99%; float: left;">  
            <!--<div class="panel"> -->
            <a class="btn btn-large edit-case" id='func' value='功能测试' >功能测试</a>
            <a class="btn btn-large edit-case" id="perf" value='性能测试' >性能测试</a>
            	<select class="module" id="module" name="module" style="width:20%; float:right;">
                	<option value="<{$curModule}>"><{$curModule}> 模块</option>
                	<{foreach from=$arrModule item=i key=key}>
                    	<option value="<{$i.name}>"><{$i.name}> 模块</option>
                		<!--<option selected="Footprint" >Footprint 模块</option> -->
                    <{/foreach}>
				 </select>
           <!-- </div> --> 
        </div>     
    </div> 
    
    <div class="container">
        <form name="form" method="post" enctype="multipart/form-data" action="" >
        <div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 99%; heigth: 100%;float: left;">  
            <div class="panel">
                <!--接口列表-->
				<div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 15%;  heigth: 100%;float: left; border=0">  
            		<div class="panel">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tbody>
								<tr>
                                <td width="15%" align="middle" ></td>
                                <td width="100%" align="right" >
                                	<a class="btn btn-small edit-case" id="addCase" value="添加接口" href="<{$baseurl}>index.php/addInterface/exchangeLevel/<{$curModule}>/0/0" target="_blank">添加接口</a>
                                </td>
                                </tr>
                                <{foreach from=$arrInterface item=i key=key}>
                                <tr>
                                <th width="20%" align="middle" ></th>
                                <th width="70%" >
                    					<a id="addCase" value="<{$i}>"  href="<{$baseurl}>index.php/editInterface/exchangeLevel/<{$curModule}>/<{$i}>/" target="_blank"><{$i}></a>
                                </th>
                                </tr>
                                <{/foreach}>
							</tbody>
                 		</table>
						<iframe id="iframe" width=100% frameBorder=0 height=100%   src=''></iframe> 
            		</div> 
                    
            	</div>
                <!-- Case列表图区 -->
                <div class="right-account" style="padding-top: 3px; padding-left:0.1%; width: 84%;float: left;">  
            		<div class="panel">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tbody>
								<tr>
                                	<td width="90%" align="left" >
                                    	<a class="btn btn-small edit-case" id="addCase" value="添加Case" href="<{$baseurl}>index.php/addFuncCase/exchangeLevel/<{$curModule}>" target="_blank">添加Case</a>
                                    	<a class="btn btn-small delCase" id="delCase" value="删除Case">删除Case</a>
                                    	<a class="btn btn-small runCase" id="runCase" value="执行Case">执行Case</a>
                                    	<a class="btn btn-small getCase" id="getCase" value="导出测试设计">导出测试设计</a>
                                    	<a class="btn btn-small getAuto" id="getAuto" value="导出自动化">导出自动化</a>
                                        <input style="width:20%;float: right;" type="text" name="ip_port" id="ip_port"  value="">
                                        <a value="请求IP:PORT" style="float: right;" > 请求IP:PORT&nbsp;&nbsp;</a> 
                                    </td>
                               </tr>
							</tbody>
                 		</table>
               	 		<!-- Case列表图区 -->
                    	<div class="list" style="padding-top: 3px; padding-left:0.1%; width: 100%;float: left;">
                    		<table style="BORDER-RIGHT: #f3f6f6 2px dotted; BORDER-TOP: #f3f6f6 2px dotted; BORDER-LEFT: #f3f6f6 2px dotted; BORDER-BOTTOM: #f3f6f6 2px dotted; BORDER-COLLAPSE: collapse" borderColor=#f3f6f6 height=40 cellPadding=1 width=200 align=center border=1> 
							<tbody>
                            	<tr>
									<td style="width:5%"><input type="checkbox" id="cases[]" class="check" /></td>
	    							<th style="width:10%">&nbsp;&nbsp;编号</th>
				    				<th style="width:15%" >&nbsp;&nbsp;接口</th>
    								<th style="width:15%" >&nbsp;&nbsp;描述</th>	
                                    <th style="width:10%" >&nbsp;&nbsp;级别</th>				
				    				<th style="width:15%">&nbsp;&nbsp;上次执行时间</th>
    								<th style="width:15%">&nbsp;&nbsp;上次执行结果</th>
  								</tr>
                                <{math assign='total_page' equation="((a + b - c) / b)" a=$caseInfo.total_rows b=$caseInfo.per_page c=1 format="%d"}>
								<{math assign='cur_page' equation="(a / b) + 1" a=$caseInfo.offset b=$caseInfo.per_page format="%d"}>  		
								<{if ($caseInfo.total_rows != 0)}>
			  	 				<{foreach from=$cases item=i}>
		        				<tr>
		        					<td><input type="checkbox" name="cases[]" class="check"/></td>
    								<td>&nbsp;&nbsp;<a class="btn btn-small edit-case" id="edit" value="<{$i->id}>"><{$i->id}></a></td>   				
    								<td>&nbsp;&nbsp;<{$i->interface}></td>
    								<td>&nbsp;&nbsp;<{$i->desc}></td>
                                    <td>&nbsp;&nbsp;<{$i->level}></td>
    								<td>&nbsp;&nbsp;Time</td>
                                    <td>&nbsp;&nbsp;<{$i->result}></td>
    							</tr>	       
		      		 			<{/foreach}>	
		       					<{/if}>
							</tbody>
                 			</table>
                            <div class="list-page">
				<{math assign='total_page' equation="((a + b - c) / b)" a=$caseInfo.total_rows b=$caseInfo.per_page c=1 format="%d"}>
				<{math assign='cur_page' equation="(a / b) + 1" a=$caseInfo.offset b=$caseInfo.per_page format="%d"}> 
	  			<!-- 没有数据或数量不足则不显示翻页 -->
	  			<{if ($caseInfo.total_rows > $caseInfo.per_page)}>
		  			<div class="i-total">共相关Case <b><{$caseInfo.total_rows}></b> 条 第 <b><{$cur_page}>/<{$total_page}></b> 页  </div>
		  			<div class="i-list"> 
					<{if ($cur_page == 1)}>
			  			<span>首页</span>
			  			<span>上一页</span>  
						<{if (($cur_page + 1) > $total_page)}>
			  				<span>下一页</span>
			  				<span>末页</span>
						<{else}>
			  				<a href="<{$cur_url}>/<{$cur_page * $caseInfo.per_page}>">下一页</a> 
			  				<a href="<{$cur_url}>/<{($total_page - 1) * $caseInfo.per_page}>">尾页</a>
						<{/if}>
					<{elseif ($cur_page > 1)}>
					  	<a href="<{$cur_url}>/0">首页</span>
					  	<a href="<{$cur_url}>/<{($cur_page - 2) * $caseInfo.per_page}>">上一页</a>  
						<{if (($cur_page + 1) > $total_page)}>
			  				<span>下一页</span>
			  				<span>末页</span>
						<{else}>
			  				<a href="<{$cur_url}>/<{$cur_page * $caseInfo.per_page}>">下一页</a> 
			  				<a href="<{$cur_url}>/<{($total_page - 1) * $caseInfo.per_page}>">尾页</a>
						<{/if}>
					<{/if}>
		  			</div>
		  			<div class="clear"></div>
	  			<{/if}> 
	  		</div>
                   	 	</div>
            		</div> 
            	</div>
            </div> 
        </div>
        </form>        
    </div> 
<{/block}>