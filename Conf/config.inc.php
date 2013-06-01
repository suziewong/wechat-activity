<?php
return array(
	//'配置项'=>'配置值'
	// 添加数据库配置信息
	'URL_MODEL'=>  0, 			// 0普通模式，1.pathinfo[利于SEO] 如果你的环境不支持PATHINFO 请设置为3
	'DB_TYPE'   => 'mysql', 	// 数据库类型
	'DB_HOST'   => '', // 服务器地址
	'DB_NAME'   => '', 		// 数据库名
	'DB_USER'   => '', 	// 用户名
	'DB_PWD'    => '', 	// 密码
	'DB_PORT'   => 3306, 		// 端口
	'DB_PREFIX' => 'xx_', 	// 数据库表前缀



	
	'TMPL_ACTION_ERROR' => 'Public:error',			//默认错误跳转对应的模板文件	
	'TMPL_ACTION_SUCCESS' => 'Public:success',		//默认成功跳转对应的模板文件
	'TMPL_PARSE_STRING'  =>array(
    	'__PUBLIC__' => __ROOT__.'/Common', 		// 更改默认的__PUBLIC__ 替换规则
     	'__ADMIN__' => __ROOT__.'/Common/', 	// 更改默认的__PUBLIC__ 替换规则
     	'__CSS__'=>  __ROOT__.'/Common/Css/',
     	'__JS__' => __ROOT__.'/Common/Js/', 		// 增加新的JS类库路径替换规则
     	'__UPLOAD__' => __ROOT__.'/Common/Uploads', // 增加新的上传路径替换规则
     	'__PIC__'=> __ROOT__.'/Common/images/',
	)
);
?>
