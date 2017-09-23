<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */
define('DEBUG', 2);	//调试模式，分三种：0 关闭调试; 1 开启调试; 2 开发调试   注意：开启调试会暴露绝对路径和表前缀

define('ROOT_PATH', '../');	//根目录
//define('ROOT_PATH', dirname($_SERVER['DOCUMENT_ROOT']).'/');	//根目录
define('VIEW_NAME', 'view');	//视图目录
define('HOM_NAME', 'home');	//前台APP名称
define('ADM_NAME', 'admin');	//后台APP名称
define('TPL_PATH', ROOT_PATH.VIEW_NAME.'/tpl/');	//模板目录
define('HOM_PATH', TPL_PATH.HOM_NAME.'/');	//前台目录
define('ADM_PATH', TPL_PATH.ADM_NAME.'/');	//后台目录
//判断是否已安装
if(!is_file(ROOT_PATH.'config/config.inc.php')) exit('<html><body><script>location="install/index.php"</script></body></html>');
define('FRAME_PATH', ROOT_PATH.'framework/');	//框架目录
require FRAME_PATH.'framework.php';
