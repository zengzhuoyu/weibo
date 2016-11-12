<?php 
	define('APP_NAME','Index');//定义项目名称
	define('APP_PATH','./Index/');//定义项目路径

	//开启的话每一遍执行项目都走核心文件，不走编译文件
	//走编译文件的话，修改代码并不会出现及时的效果，但速度比较快
	define('APP_DEBUG',true);

	require './ThinkPHP/ThinkPHP.php';//引入tp核心文件
 ?>