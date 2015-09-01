<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config["plat_info_array"] = array(
		'title' => '标题',
		'username' => 'MapmoServer',
		'homeTitle' => '服务端项目测试平台',

		'maintabarray' => array(
            '0' =>array('label' => '环境部署', 'id' => 'deploy'),
			'1' =>array('label' => '准入测试', 'id' => 'admittanceTest'),
			'2' =>array('label' => '功能测试', 'id' => 'functionTest'),
			'3' =>array('label' => '性能测试', 'id' => 'performanceTest'),
			//'4' =>array('label' => 'phpuiDiff', 'id' => 'phpuiDiff'),
            ),
		
		'performanceConf' => array(
            //'0' =>array('label' => '创建人','id' => 'ID_User','val'=>''),
            '0' =>array('label' => '所属项目', 'id' => 'ID_Pro','val'=>''),
            '1' =>array('label' => '测试机器', 'id' => 'ID_HostName','val'=>''),
			'2' =>array('label' => '端口号', 'id' => 'ID_HostPort','val'=>''),
            '3' =>array('label' => '每秒请求量','id' => 'ID_Vel','val'=>''),
            '4' =>array('label' => '执行时间min','id' => 'ID_Period','val'=>'20'),
			'5' =>array('label' => '访问Url','id' => 'ID_Url','val'=>'mcenter/n?'),
            '6' =>array('label' => '请求类型','id' => 'ID_Type','val1'=>'get','val2'=>'post'),
            '7' =>array('label' => '词表选择','id' => 'ID_File'),
            
            ),
        'admittanceTest' => array(
            '0' =>array('label' => '项目名', 'id' => 'ID_Pro','val'=>''),
            '1' =>array('label' => 'case描述','id' => 'ID_Purpose','val'=>''),
            '2' =>array('label' => 'URL', 'id' => 'ID_Url','val'=>''),
            '3' =>array('label' => 'post参数 ','id' => 'ID_Post','val'=>''),
            '4' =>array('label' => '上传文件 ','id' => 'ID_File','val'=>''),
			'5' =>array('label' => '校验json结果','id' => 'ID_Test','val'=>''),
            
            ),
         'deployModule' => array(
            '0'=>array('label' => '长连接整体部署环境','id' => 'd_longlink','val'=>'14','param'=>'Port_for_Client;Port_for_Manager;Manager_for_Server;redis_IP;mysql_IP;mysql_PORT;lighttpd_IP;lighttpd_PORT;fshare_IP;fshare_PORT'),
			'1'=>array('label' => 'moblie集群lighttpd和apps','id' => 'd_mobile_lighttpd','val'=>'18','param'=>'offlinePHPPort;offlineLighttpdPort;CloudPortPrefix;LongLinkAddr;LongLinkPort'),
			'2'=>array('label' => 'ccexp集群lighttpd和apps','id' => 'd_lighttpd_ccexp','val'=>'20','param'=>'offlinePHPPort;offlineLighttpdPort;LongLinkAddr;LongLinkPort;MysqlIP;MysqlPort;CCofflineLighttpdPort;CCIP'),
            '3'=>array('label' => 'ccexp和ccdn模板部署','id' => 'd_ccexp_ccdn','val'=>'23','param'=>'Mod_ccexp_ccdn","LongLinkAddr;LongLinkPort;CloudPortPrefix;MysqlIP;MysqlPort'),
            '4'=>array('label' => 'ccapi模板部署','id' => 'd_ccapi','val'=>'24','param'=>'offlineLighttpdPort;MysqlIP_Port;MongoIP_Port'),
            ),
        'dropdownarray' => array(
            '0' =>'action1',
            '1' =>'action2',
            '2' =>'action3'
            ),
         'wordlist' => array(
            '0' =>array('selectID' => 'SelType1', 'ParamName' => 'pName1','Scopes'=>'Scopes1','Scopee'=>'Scopee1'), 
            '1' =>array('selectID' => 'SelType2', 'ParamName' => 'pName2','Scopes'=>'Scopes2','Scopee'=>'Scopee2'), 
            '2' =>array('selectID' => 'SelType3', 'ParamName' => 'pName3','Scopes'=>'Scopes3','Scopee'=>'Scopee3'), 
            '3' =>array('selectID' => 'SelType4', 'ParamName' => 'pName4','Scopes'=>'Scopes4','Scopee'=>'Scopee4'),  
            '4' =>array('selectID' => 'SelType5', 'ParamName' => 'pName5','Scopes'=>'Scopes5','Scopee'=>'Scopee5'),
            '5' =>array('selectID' => 'SelType6', 'ParamName' => 'pName6','Scopes'=>'Scopes6','Scopee'=>'Scopee6'), 
            '6' =>array('selectID' => 'SelType7', 'ParamName' => 'pName7','Scopes'=>'Scopes7','Scopee'=>'Scopee7'),
            '7' =>array('selectID' => 'SelType8', 'ParamName' => 'pName8','Scopes'=>'Scopes8','Scopee'=>'Scopee8'), 
            '8' =>array('selectID' => 'SelType9', 'ParamName' => 'pName9','Scopes'=>'Scopes9','Scopee'=>'Scopee9'), 
            '9' =>array('selectID' => 'SelType10', 'ParamName' => 'pName10','Scopes'=>'Scopes10','Scopee'=>'Scopee10'), 
            '10' =>array('selectID' => 'SelType11', 'ParamName' => 'pName11','Scopes'=>'Scopes11','Scopee'=>'Scopee11'), 
            '11' =>array('selectID' => 'SelType12', 'ParamName' => 'pName12','Scopes'=>'Scopes12','Scopee'=>'Scopee12'), 
            '12' =>array('selectID' => 'SelType13', 'ParamName' => 'pName13','Scopes'=>'Scopes13','Scopee'=>'Scopee13'), 
            '13' =>array('selectID' => 'SelType14', 'ParamName' => 'pName14','Scopes'=>'Scopes14','Scopee'=>'Scopee14'),  
            '14' =>array('selectID' => 'SelType15', 'ParamName' => 'pName15','Scopes'=>'Scopes15','Scopee'=>'Scopee15'),
            '15' =>array('selectID' => 'SelType16', 'ParamName' => 'pName16','Scopes'=>'Scopes16','Scopee'=>'Scopee16'), 
            '16' =>array('selectID' => 'SelType17', 'ParamName' => 'pName17','Scopes'=>'Scopes17','Scopee'=>'Scopee17'),
            '17' =>array('selectID' => 'SelType18', 'ParamName' => 'pName18','Scopes'=>'Scopes18','Scopee'=>'Scopee18'), 
            '18' =>array('selectID' => 'SelType19', 'ParamName' => 'pName19','Scopes'=>'Scopes19','Scopee'=>'Scopee19'), 
            '19' =>array('selectID' => 'SelType20', 'ParamName' => 'pName20','Scopes'=>'Scopes20','Scopee'=>'Scopee20'),          
            ),
         'layerinfo' => array(
            '0' => array(
            	'values' => array(
            		'0' => array('name' => '模板部署'),
          			'1' => array('name' => '部署脚本列表'),
            	),
            ),
            '1' => array(
            	'values' => array(
            		'0' => array('name' => 'case配置'),
          			'1' => array('name' => 'case执行'),
            	),
            ),
            '2' => array(
            	'values' => array(
            		'0' => array('name' => '自动化case配置'),
          			'1' => array('name' => 'case列表'),
                    '2' => array('name' => 'jenkins展示'),
            	),
            ),
            '3' => array(
            	'values' => array(
                    '0' => array('name' => 'case配置'),
            		'1' => array('name' => 'case执行'),
            		'2' => array('name' => '词表生成'),
          			'3' => array('name' => '词表列表'),
           			
            	),
            ),
         	'4' => array(
         			'values' => array(
         					'0' => array('name' => '一键diff'),
         					'1' => array('name' => '忽略字段配置'),
         					'2' => array('name' => 'diff结果'),
         			),
         		),
	       ),
         'layers' => array(
            
            ),
        'currentTab' => 0,
        'currentLayer' => 0,      
		'layer_array'=> array(
		),
		'selectlayertab' => 0,//默认选中产品效果层tab
        'selectlayeritemtab' => 0,//默认选中每一层的第一个监控分类
		'itemrouter' => '0/0',//记录左侧层次选择的路由s
		'wordListFile'=>'Emp',
		'testResult'=>'',
		'mysqlResult'=>'',
		'redisResult'=>'',
		'Url'=>'',
		'postParam'=>'',
		'offset'=>0,
		'curPro'=>'',
		'curModule'=>'',
            
);