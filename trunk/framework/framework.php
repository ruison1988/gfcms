<?php
/**
 * Copyright (C) 2013-2014 www.gfcms.com All rights reserved.
 * Licensed http://www.mn2g.com/
 * Author: RickSun <442352433@qq.com>
 */

// 核心框架入口文件
defined('FRAME_PATH') || die('Error Accessing');

version_compare(PHP_VERSION, '5.2.0', '>') || die('require PHP > 5.2.0 !');

// 记录开始运行时间
$_ENV['_start_time'] = microtime(1);

// 记录内存初始使用
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $_ENV['_start_memory'] = memory_get_usage();

define('GONGFU_VERSION', '1.0.0');	//框架版本
defined('DEBUG') || define('DEBUG', 2);	//调试模式
defined('CONFIG_PATH') || define('CONFIG_PATH', ROOT_PATH.'config/');	//配置目录
defined('MODEL_PATH') || define('MODEL_PATH', ROOT_PATH.'model/');	//模型目录
defined('LOG_PATH') || define('LOG_PATH', ROOT_PATH.'log/');	//日志目录
defined('RUNTIME_PATH') || define('RUNTIME_PATH', ROOT_PATH.'runtime/');	//运行缓存目录
defined('PLUGIN_PATH') || define('PLUGIN_PATH', ROOT_PATH.'plugin/');	//插件目录
defined('RUNTIME_MODEL') || define('RUNTIME_MODEL', RUNTIME_PATH.'model/');	//模型缓存目录

include CONFIG_PATH.'config.inc.php';

if(DEBUG) {
	include FRAME_PATH.'base/base.func.php';
	include FRAME_PATH.'base/core.class.php';
	include FRAME_PATH.'base/debug.class.php';
	include FRAME_PATH.'base/log.class.php';
	include FRAME_PATH.'base/model.class.php';
	include FRAME_PATH.'base/view.class.php';
	include FRAME_PATH.'base/control.class.php';
	include FRAME_PATH.'db/db.interface.php';
	include FRAME_PATH.'db/db_mysqli.class.php';
	include FRAME_PATH.'cache/cache.interface.php';
	include FRAME_PATH.'cache/cache_memcache.class.php';
}else{
	$runfile = RUNTIME_PATH.'_runtime.php';
	if(!is_file($runfile)) {
		$s  = trim(php_strip_whitespace(FRAME_PATH.'base/base.func.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/core.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/debug.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/log.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/model.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/view.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'base/control.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'db/db.interface.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'db/db_mysqli.class.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'cache/cache.interface.php'), "<?php>\r\n");
		$s .= trim(php_strip_whitespace(FRAME_PATH.'cache/cache_memcache.class.php'), "<?php>\r\n");
		$s = str_replace('defined(\'FRAME_PATH\') || exit;', '', $s);
		file_put_contents($runfile, '<?php '.$s);
		unset($s);
	}
	include $runfile;
}
core::start();

if(DEBUG > 1 && !R('ajax', 'R')) {
	debug::sys_trace();
}
