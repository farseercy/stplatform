<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script>	
    	function checkChange()	{
    		var interface = document.getElementById("interface").value;
    		alert(interface);
			document.form.action="<{$baseurl}>index.php/addFuncCase/exchangeLevel/<{$curModule}>/"+interface;
			document.form.submit();
    	};
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
            <a class="btn btn-large edit-case" id='func' value='功能测试' >修改case_<{$caseid}></a>
            	<select class="module" id="module" name="module" style="width:20%; float:right;">
                	<option value="<{$curModule}>"><{$curModule}> 模块</option>
				 </select>
           <!-- </div> --> 
        </div>     
    </div> 
    
    <div class="container">
        <div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 99%; heigth: 100%;float: left;">  
            <div class="panel">
                <!-- Case列表图区 -->
                    	<div class="list" style="padding-top: 3px; padding-left:0.1%; width: 78%;float: left;">
                    		<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tbody>
                               	<tr>
                                	<td width="20%" align="right" >选择接口&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="80%" align="left" >
                                    <input style="width:30%;" type="text" class="params" id="params"  value="<{$curInterface}>" readonly="readonly"></td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >Case描述&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input style="width:50%;" type="text" class="params" id="params"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >前提操作&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="null" class="check null" onclick="" checked="true">无
                                        <input type="checkbox" id="http" class="check http" onclick="">http请求
                                        <input type="checkbox" id="null" class="check null" onclick="">mysql
                                        <input type="checkbox" id="null" class="check null" onclick="">redis
                                        <input type="checkbox" id="null" class="check null" onclick="">mongo
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td align="left" >
                                    	<input style="width:50%;" type="text" class="params" id="params"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >GET参数&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr>
                                <{foreach from=$getParams item=i key=key}>
                                <{if $i != ""}> 
                    				<tr>
                                		<td width="20%" align="right" >KEY &nbsp;&nbsp;&nbsp;&nbsp;</td>
	                                    <td align="left" >
    	                                	<input style="width:15%;" type="text" class="getKey<{$i}>" id="getKey<{$i}>"  value="<{$i}>" readonly="readonly">
        	    	                        &nbsp;&nbsp;&nbsp;&nbsp;VALUE &nbsp;&nbsp;&nbsp;&nbsp;
            	                        	<input style="width:40%;" type="text" class="getVal<{$i}>" id="getVal<{$i}>"  value="">
                    	                </td>
                        	       	</tr>
                                <{else}>
                                	<tr>
                                		<td width="20%" align="right" ></td>
	                                    <td align="left" >无</td>
    	                           	</tr>
                                <{/if}>
                                <{/foreach}>
                                <tr>
                                	<td width="20%" align="right" >POST参数&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr>                              
                                <{foreach from=$postParams item=i key=key}>
                                <{if $i != ""}> 
            	        			<tr>
        	                        	<td width="20%" align="right" >KEY &nbsp;&nbsp;&nbsp;&nbsp;</td>
                	                    <td align="left" >
                    	                	<input style="width:15%;" type="text" class="params" id="params"  value="<{$i}>" readonly="readonly">
                        		            &nbsp;&nbsp;&nbsp;&nbsp;VALUE &nbsp;&nbsp;&nbsp;&nbsp;
                                	    	<input style="width:40%;" type="text" class="params" id="params"  value="">
                                    	</td>
	                               	</tr>
                                <{else}>
                                	<tr>
                                		<td width="20%" align="right" ></td>
	                                    <td align="left" >无</td>
    	                           	</tr>
                                <{/if}>
                                <{/foreach}>
                                
                                <tr>
                                	<td width="20%" align="right" >结果校验&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="null" class="check null" onclick="" checked="true">JSON
                                        <input type="checkbox" id="null" class="check null" onclick="" >PB
                                        <input type="checkbox" id="null" class="check null" onclick="" >mcpack
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td width="80%" align="left" >
                                    	<input style="width:40%;" type="text" class="params" id="params"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >数据库变化&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="null" class="check null" onclick="">mysql
                                        <input type="checkbox" id="null" class="check null" onclick="">redis
                                        <input type="checkbox" id="null" class="check null" onclick="">mongo
                                    </td>
                               	</tr>
							</tbody>
                 			</table>
                   	 	</div>
            </div> 
        </div>  
    </div> 
<{/block}>