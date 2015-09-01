<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <script>	
    	function checkChange()	{
    		var interface = document.getElementById("interface").value;
    		//alert(interface);
			document.form.action="<{$baseurl}>index.php/addFuncCase/exchangeLevel/<{$curModule}>/"+interface;
			document.form.submit();
    	};
		function createCase()	{
		    var create=1;
    		var interface = document.getElementById("interface").value;
			if(document.getElementById("desc").value==''){
				alert('请输入case描述！');
				create=0;
			}
			else if(document.getElementById("test").value==''){
				alert('请输入所需校验结果！');
				create=0;
			}
			if(create==1){
			//document.form.action="<{$baseurl}>index.php/addFuncCase/createCase/<{$curModule}>";
				document.form_1.action="<{$baseurl}>index.php/addFuncCase/exchangeLevel/<{$curModule}>/"+interface+"/1";
				document.form_1.submit();
			}
    	};
		function testCase()	{
		    var create=1;
    		var interface = document.getElementById("interface").value;
			if(document.getElementById("ip_port").value==''){
				alert('请输入IP地址！');
				create=0;
			}
			else if(document.getElementById("path").value==''){
				alert('请输入请求路径');
				create=0;
			}
			if(create==1){
			//document.form.action="<{$baseurl}>index.php/addFuncCase/createCase/<{$curModule}>";
				document.form_1.action="<{$baseurl}>index.php/addFuncCase/exchangeLevel/<{$curModule}>/"+interface+"/2";
				document.form_1.submit();
			}
    	};
		function checkJson(self)	{
    		var r_json = document.getElementById("r_json");
   			var r_pb = document.getElementById("r_pb");
			var r_mcpack = document.getElementById("r_mcpack");
			r_json.checked = false;
   			r_pb.checked = false;
			r_mcpack.checked = false;
   			self.checked = true;
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
            <a class="btn btn-large edit-case" id='func' value='功能测试' >添加功能case</a>
            	<select class="module" id="module" name="module" style="width:20%; float:right;">
                	<option value="<{$curModule}>"><{$curModule}> 模块</option>
                	<!--<{foreach from=$arrModule item=i key=key}>
                    	<option value="<{$i.name}>"><{$i.name}> 模块</option>
                    <{/foreach}>-->
				 </select>
           <!-- </div> --> 
        </div>     
    </div> 
    
    <div class="container">
        <div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 99%; heigth: 100%;float: left;">  
            <div class="panel">
                <!-- Case列表图区 -->
                    	<div class="list" style="padding-top: 3px; padding-left:0.1%; width: 100%;float: left;">
                    		<table style="BORDER-RIGHT: #f3f6f6 2px dotted; BORDER-TOP: #f3f6f6 2px dotted; BORDER-LEFT: #f3f6f6 2px dotted; BORDER-BOTTOM: #f3f6f6 2px dotted; BORDER-COLLAPSE: collapse" borderColor=#f3f6f6 height=90 cellPadding=1 width=100% align=center border=1 > 
							<tbody>
                               	<tr>
                                <form name="form" method="post" enctype="multipart/form-data"  action="">
                                	<td width="20%" align="right" >选择接口&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="80%" align="left" >
                                    <select name="interface" id="interface" style="width:30%; float:left;" onchange="checkChange()">
                                    	<option value="<{$curInterface}>"><{$curInterface}></option>
                						<{foreach from=$arrInterface item=i key=key}>
                    						<option value="<{$i}>"><{$i}></option>
                    					<{/foreach}>
                                    </td>
                                </form>
                               	</tr>
                                <form name="form_1" method="post" enctype="multipart/form-data"  action="">
                                <tr>
                                	<td width="20%" align="right" >用例级别&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="80%" align="left" >
                                    <select name="level" id="level" style="width:10%; float:left;" >
                                    	<option value="0">0</option>
                						<option value="1">1</option>
                                        <option value="2">2</option>
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >请求路径&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input style="width:50%;" type="text" name="path" id="path"  value="<{$path}>">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >用例描述&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input style="width:50%;" type="text" name="desc" id="desc"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >前提操作&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="c_http" name="c_http" class="check c_http" onclick="">http请求
                                        <input type="checkbox" id="c_mysql" name="c_mysql" class="check c_mysql" onclick="">mysql
                                        <input type="checkbox" id="c_redis" name="c_redis" class="check c_redis" onclick="">redis
                                        <input type="checkbox" id="c_mongo" name="c_mongo" class="check c_mongo" onclick="">mongo
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td align="left" >
                                    	curl <input style="width:35%;" type="text" name="operate" id="operate"  value="">
                                        -d <input style="width:20%;" type="text" name="operate" id="operate"  value="">
                                        -H <input style="width:20%;" type="text" name="operate" id="operate"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td align="left" >
                                    	数据库<input style="width:50%;" type="text" name="sqlop" id="sqlop"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >GET参数&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr>
                                <{assign var=count value=0}> 
                                <{foreach from=$arrGet item=i key=key}>
                                <{if $i != ""}> 
                    				<tr>
                                		<td width="20%" align="right" >KEY &nbsp;&nbsp;&nbsp;&nbsp;</td>
	                                    <td align="left" >
    	                                	<input style="width:10%;" type="text" name="gkey_<{$count}>" id="gkey_<{$count}>"  value="<{$i.key}>">
                        		            &nbsp;&nbsp;&nbsp;&nbsp;VALUE &nbsp;&nbsp;&nbsp;&nbsp;
                                	    	<input style="width:30%;" type="text" name="gval_<{$count}>" id="gval_<{$count}>"  value="<{$i.val}>">
                    	                </td>
                        	       	</tr>
                                <{else}>
                                	<tr>
                                		<td width="20%" align="right" ></td>
	                                    <td align="left" >无</td>
    	                           	</tr>
                                <{/if}>
                                <{assign var=count value=$count+1}>	
                                <{/foreach}>
                                <input style="width:30%;display:none;" type="text" name="gNum" id="gNum" value="<{$count}>">
                                <tr>
                                	<td width="20%" align="right" >POST参数&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr> 
                                <{assign var=count value=0}>                              
                                <{foreach from=$arrPost item=i key=key}>
                                <{if $i != ""}> 
            	        			<tr>
        	                        	<td width="20%" align="right" >KEY &nbsp;&nbsp;&nbsp;&nbsp;</td>
                	                    <td align="left" >
                    	                	<input style="width:10%;" type="text" name="pkey_<{$count}>" id="pkey_<{$count}>"  value="<{$i.key}>">
                        		            &nbsp;&nbsp;&nbsp;&nbsp;VALUE &nbsp;&nbsp;&nbsp;&nbsp;
                                	    	<input style="width:30%;" type="text" name="pval_<{$count}>" id="pval_<{$count}>"  value="<{$i.val}>">
                                    	</td>
	                               	</tr>
                                <{else}>
                                	<tr>
                                		<td width="20%" align="right" ></td>
	                                    <td align="left" >无</td>
    	                           	</tr>
                                <{/if}>
                                <{assign var=count value=$count+1}>	
                                <{/foreach}>
                                <input style="width:30%;display:none;" type="text" name="pNum" id="pNum" value="<{$count}>" >
                                <tr>
                                	<td width="20%" align="right" >结果校验&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="r_json" name="r_json" class="check r_json"  onclick="checkJson(this)" checked="true">JSON
                                        <input type="checkbox" id="r_pb" name="r_pb" class="check r_pb" onclick="checkJson(this)" >PB
                                        <input type="checkbox" id="r_mcpack" name="r_mcpack" class="check r_mcpack" onclick="checkJson(this)" >mcpack
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td width="80%" align="left" >
                                       <!-- <input style="width:50%;" type="text" name="test" id="test"  value="<{$return}>">>-->
                                       <textarea rows="3" cols="80" id="testArea" name="testArea" value="test"> <{$return}></textarea>
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >数据库校验&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input type="checkbox" id="r_mysql" name="r_mysql" class="check r_mysql" onclick="">mysql
                                        <input type="checkbox" id="r_redis" name="r_redis" class="check r_redis" onclick="">redis
                                        <input type="checkbox" id="r_mongo" name="r_mongo" class="check r_mongo" onclick="">mongo
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" ></td>
                                    <td width="80%" align="left" >
                                    	<input style="width:50%;" type="text" name="sqltest" id="sqltest"  value="">
                                    </td>
                               	</tr>
							</tbody>
                            <tfoot>
							<tr>
								<td width=15%></td>
								<td align="right" style="padding:10px 5% 10px 15px;">
                                <a value="请求IP:PORT"> 请求IP:PORT&nbsp;&nbsp;</a> 
                                <input style="width:20%;" type="text" name="ip_port" id="ip_port"  value="">
                                <input class="btn btn-small test" type="submit" name='test' value='测试Case' onclick="testCase()">
                                
                                
								</td>
							</tr>
                            <tr>
								<td width=15%></td>
								<td align="right" style="padding:10px 5% 10px 15px;">
                                <input class="btn btn-small save" type="submit" name='save' value='确认配置' onclick="createCase()">
								</td>
							</tr>
							</tfoot>
                            </form>
                 			</table>
                           
                   	 	</div>
            </div> 
        </div>  
    </div> 
<{/block}>