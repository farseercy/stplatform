<{extends file="application/views/basicframe/basicframe.tpl"}>
<{block name="header"}>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
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
        <form name="form" method="post" enctype="multipart/form-data" action="" >
        <div class="left-account" style="padding-top: 3px; padding-left:0.1%; width: 49%; float: left;">  
            <div class="panel">
                <div class="panel-header" >项目列表</div>
                	<{foreach from=$arrPro item=i key=key}>
                    	<p><a class="" id="perf" href="<{$baseurl}>index.php/proFunc/exchangeLevel/<{$i.name}>" target="_blank"><{$i.name}></a></p>
                    <{/foreach}>		       	
                <iframe id="iframe" width=100% frameBorder=0 height=100px   src=''></iframe> 
            </div> 
        </div>
        <div class="right-account" style="padding-top: 3px; padding-left:0.1%; width: 49%; float: left;">  
            <div class="panel">
                <div class="panel-header" >模块列表</div>	
                	<{foreach from=$arrModule item=i key=key}>
                    	<p><a class="" id="perf" href="<{$baseurl}>index.php/moduleFunc/exchangeLevel/<{$i.name}>/0" target="_blank"><{$i.name}></a></p>
                	<{/foreach}>		       	
                <iframe id="iframe" width=100% frameBorder=0 height=100px   src=''></iframe> 
            </div> 
        </div>
        </form>        
    </div> 
<{/block}>