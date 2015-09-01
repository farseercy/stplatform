<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
<script type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.js">
	$(document).ready(function(){
	});	
	function addGet(){
	
	}
</script>
<{/block}>
<{block name="layout"}>
	<div class="layout_header">
        <div class="header">
            <div class="h_logo"><a href="#" title="测试平台"><img src="<{$baseurl}>/public/images/qaup_logo.png" width="130" height = "40" alt=""/> </a>
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
            <a class="btn btn-large edit-case" id='func' value='功能测试' >修改接口</a>
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
                                    <form name="form" id="form" method="post" enctype="multipart/form-data"  action="">
                                	<td width="20%" align="right" >GET参数个数&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >
                                    	<input style="width:10%;" type="text" class="getNum" id="getNum" name="getNum"   value="<{$getNum}>">
                                        &nbsp;&nbsp;&nbsp;&nbsp;POST参数个数&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input style="width:10%;" type="text" class="postNum" id="postNum" name="postNum"   value="<{$postNum}>">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="btn btn-small change" type="submit" name='change' value='确认修改'  onclick="changeNum()">
                                    </td>
                                    </form>
                               	</tr>
                                <form name="form_1" id="form" method="post" enctype="multipart/form-data"  action="">
                               	<tr>
                                	<td width="20%" align="right" >接口名&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="80%" align="left" >
                                    <input style="width:40%;" type="text" name="interfaceName" id="interfaceName"  value="<{$curInterface}>" >
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >接口路径&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="80%" align="left" >
                                    <input style="width:40%;" type="text" name="path" id="path"  value="">
                                    </td>
                               	</tr>
                                <tr>
                                	<td width="20%" align="right" >GET参数名&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr>
                                 <{assign var=count value=0}> 
			    				 <{foreach from=$arrGet item=i key=key}> 
                    				<tr>
                                		<td width="20%" align="right" >Param_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	                                    <td align="left" >
    	                                	<input style="width:10%;" type="text" name="get_<{$count}>" id="get_<{$count}>"  value="<{$i.key}>">
                                            &nbsp;&nbsp;&nbsp;&nbsp;Val_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:40%;" type="text" name="gval_<{$count}>" id="gval_<{$count}>"  value="<{$i.val}>">
                    	                </td>
                        	       	</tr>
                               	 <{assign var=count value=$count+1}>			
			            		 <{/foreach}>
                                 <tr>
                                		<td width="20%" align="right" ></td>
	                                    <td align="left" >
                                  			<input class="btn btn-small change" type="submit" name='change' value='添加GET参数'  onclick="addGet()">
                                  		</td>
                        	       	</tr>
                                 <{foreach from=$arrPost item=i key=key}>
                                    <{if $count == $totalNum}>
			            				<{break}>
			            			<{/if}> 
                    				<tr>
                                		<td width="20%" align="right" >Param_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	                                    <td align="left" >
    	                                	<input style="width:10%;" type="text" name="get_<{$count}>" id="get_<{$count}>"  value="">
                                            &nbsp;&nbsp;&nbsp;&nbsp;Val_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:40%;" type="text" name="gval_<{$count}>" id="gval_<{$count}>"  value="">
                    	                </td>
                        	       	</tr>
                               		<{assign var=count value=$count+1}>			
			            		<{/foreach}>
                                <tr>
                                	<td width="20%" align="right" >POST参数名&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td align="left" >：</td>
                               	</tr>                              
                                <{assign var=count value=0}> 
			    				 <{foreach from=$arrPost item=i key=key}> 
                    				<tr>
                                		<td width="20%" align="right" >Param_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	                                    <td align="left" >
    	                                	<input style="width:10%;" type="text" name="post_<{$count}>" id="post_<{$count}>"  value="<{$i.key}>">
                                            &nbsp;&nbsp;&nbsp;&nbsp;Val_<{$count}>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input style="width:40%;" type="text" name="pval_<{$count}>" id="pval_<{$count}>"  value="<{$i.val}>">
                    	                </td>
                        	       	</tr>
                               		<{assign var=count value=$count+1}>			
			            		<{/foreach}> 
                                
							</tbody>
                            <tfoot>
							<tr>
								<td width=15%></td>
								<td align="right" style="padding:15px 5% 15px 25px;">
									<input class="btn btn-small save" type="submit" name='save' value='确认接口配置' onclick="createInterface()">
								</td>
							</tr>
							</tfoot>
                 			</table>
                            </form>
                   	 	</div>
            </div> 
        </div>  
    </div> 
<{/block}>