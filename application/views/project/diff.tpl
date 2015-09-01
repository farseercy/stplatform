<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
<style type="text/css">
    .layout_leftnav {
        position:absolute;
        top:60px;
        left:0;
        bottom:35px;
        width:180px;
    }
    .layout_allmain{
        position:absolute;
        top:60px;
        right:0;
        bottom:35px;
        left:0;
        background:#fff;
    }
    .layout_rightmain {
        position:absolute;
        top:60px;
        right:0;
        bottom:35px;
        left:180px;
        background:#fff;
    }
    .pan_leftnav {
        position:relative;
        top:60px;
        left:0;
        bottom:0;
        width:180px;
    }
    .pan_rightmain {
        position:absolute;
        top:120px;
        right:0;
        bottom:35px;
        left:180px;
        background:#fff;
    }    
</style>
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
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
<script type="text/javascript">
    function change(src)
    {
        //alert(src);
        document.getElementById("iframe").src = src;
    }
</script>
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
            <a class="btn btn-large edit-case" id="perf" href="<{$baseurl}>index.php/proPerf/exchangeLevel/<{$curPro}>/<{$curModule}>" target="_blank">性能测试</a>
            <a class="btn btn-large edit-case" id="diff" >DIFF测试</a>
            <a class="btn btn-large edit-case" id="conf" href="<{$baseurl}>index.php/proConf/exchangeLevel/<{$curPro}>/<{$curModule}>" target="_blank">项目配置</a>
            	<select class="module" id="module" name="module" style="width:20%; float:right;">
                	<option selected="<{$curPro}>" ><{$curPro}></option>
				 	<{foreach from=$arrPro item=i key=key}>
                    	<option value="<{$i.name}>"><{$i.name}> </option>
                    <{/foreach}>
				 </select>
           <!-- </div> --> 
        </div>     
    </div>
    <div class="pan_leftnav">
        <div>
            <!-- 左侧功能列表 -->
            <ul>
                <li><a href="#" onclick="change('<{$baseurl}>index.php/proDiff/one_click')"><h2>一键diff</h2></a></li>
                <li><a href="#" onclick="change('<{$baseurl}>index.php/proDiff/conf_ignore')"><h2>配置字段</h2></a></li>
                <li><a href="#" onclick="change('<{$baseurl}>index.php/proDiff/diff_ret')"><h2>查看结果</h2></a></li>
            </ul>
        </div>


    </div>
    <div class="pan_rightmain">
        <iframe id="iframe" frameborder="0" style="width:100%;height:100%" src='<{$baseurl}>index.php/proDiff/welcome'>
        </iframe> 
    </div>
<{/block}>




        <!--
        <div class="content" style="padding-top: 3px; padding-left:0.1%; width: 99%; float: left;">  
            <div class="panel">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
                            <tbody>
                                <tr>
                                    <td width="90%" align="left" >                                        
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
                 <div class="list" style="padding-top: 3px; padding-left:0.1%; width: 100%;float: left;">

                 </div>

            </div>
        </div>     --> 