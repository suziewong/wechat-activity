<?php

//开启调试模式以后的话,model类的数据库操作都可以显示
define('APP_DEBUG',true); // 开启调试模式
//define('APP_DEBUG',false); // 关闭调试模式

define('APP_NAME','Home');
define('APP_PATH', './Home/');

//define("RUNTIME_PATH", "./Runtime/");
//定义配置文件存放路径
//define("CONF_PATH", RUNTIME_PATH . "Conf/");
define("TMPL_PATH", APP_PATH. "Template/");
define("CONF_PATH",  "./Conf/");

/*	'DEFAULT_THEME' => 'default',
	'THEME_LIST'		=> 'default,new',
    'TMPL_DETECT_THEME' => 	true, // 自动侦测模板主题*/

require './ThinkPHP/ThinkPHP.php';
