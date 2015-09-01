<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
     <script type="text/javascript" >
    $(document).ready(function() {
    	$("#pro").change(function(){
            //alert($('.pro').val());
            document.form.action="<{$baseurl}>index.php/proFunc/exchangeLevel/"+$('.pro').val();
	    	document.form.submit();
    	});
		$("#module").change(function(){
            //alert($('.pro').val());
            document.form.action="<{$baseurl}>index.php/proFunc/exchangeLevel/<{$curPro}>/"+$('.module').val();
	    	document.form.submit();
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
            <a class="btn btn-large edit-case" id='func' href="<{$baseurl}>index.php/proFunc/exchangeLevel/<{$curPro}>/<{$curModule}>" target="_blank">功能测试</a>
            <a class="btn btn-large edit-case" id="perf" >性能测试</a>
            <a class="btn btn-large edit-case" id="diff" href="<{$baseurl}>index.php/proDiff/exchangeLevel/<{$curPro}>/<{$curModule}>" target="_blank">DIFF测试</a>
            <a class="btn btn-large edit-case" id="conf" href="<{$baseurl}>index.php/proConf/exchangeLevel/<{$curPro}>/<{$curModule}>" target="_blank">项目配置</a>
            	<select class="pro" id="pro" name="pro" style="width:20%; float:right;">
                	<option selected="<{$curPro}>" ><{$curPro}> 项目</option>
				 	<{foreach from=$arrPro item=i key=key}>
                    	<option value="<{$i.name}>"><{$i.name}> 项目</option>
                    <{/foreach}>
				 </select>
           <!-- </div> --> 
        </div>     
    </div> 
    
    <div class="container">
        <form name="form" method="post" enctype="multipart/form-data" action="" >
        <div class="content" style="padding-top: 3px; padding-left:0.1%; width: 99%; float: left;">  
            <div class="panel">
                <!-- Case列表图区 -->
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tbody>
								<tr>
                                	<td width="90%" align="left" >
                                    	<a class="btn btn-small edit-case" id="addCase" value="执行Case">执行Case</a>
                                    	<a class="btn btn-small edit-case" id="addCase" value="导出测试设计">导出测试设计</a>
                                    	<a class="btn btn-small edit-case" id="addCase" value="导出自动化">导出自动化</a>
                                        <select class="module" id="module" name="module" style="width:16%; float:right;">
                							<option selected="<{$curModule}>" ><{$curModule}> 模块</option>
				 							<{foreach from=$arrModule item=i key=key}>
                    						<option value="<{$i}>"><{$i}>  模块</option>
                    						<{/foreach}>
				 						</select>
                                    </td>
                               </tr>
							</tbody>
                 		</table>
               	 		<!-- Case列表图区 -->
                    	<div class="list" style="padding-top: 3px; padding-left:0.1%; width: 100%;float: left;">
                    		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tbody>
								<tr>
                                	<td width="90%" align="left" ></td>
                               </tr>
                               <tr>
                                	<td width="90%" align="left" >请求IP:PORT</td>
                               </tr>
							</tbody>
                 			</table>
                   	 	</div>
                		<iframe id="iframe" width=100% frameBorder=0 height=100px   src=''></iframe> 
            		</div> 
        </div>
        </form>        
    </div> 
<{/block}>