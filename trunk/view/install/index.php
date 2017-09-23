<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */
 
version_compare(PHP_VERSION, '5.2.0', '>') || die('require PHP > 5.2.0 !');

define('ROOT_PATH', dirname($_SERVER['DOCUMENT_ROOT']).'/');	//根目录
define('VIEW_NAME', 'view');	//视图目录
define('HOM_NAME', 'home');	//前台APP名称
define('ADM_NAME', 'admin');	//后台APP名称

define('INST_PATH', ROOT_PATH.VIEW_NAME.'/install/');
define('FRAME_PATH', ROOT_PATH.'framework/');

date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=UTF-8');
// error_reporting(0);	//报错提示开关

// 保护锁
if(is_file(ROOT_PATH.'config/config.inc.php')) {
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
	include INST_PATH.'tpl/lock.php';
	exit;
}
include FRAME_PATH.'base/base.func.php';
include INST_PATH.'function.php';

$do = isset($_GET['do']) && in_array($_GET['do'], array('license', 'check_env', 'check_db', 'complete')) ? $_GET['do'] : 'license';

if($do == 'license') {
	include INST_PATH.'tpl/header.php';
	include INST_PATH.'tpl/license.php';
	include INST_PATH.'tpl/footer.php';
}elseif($do == 'check_env') {
	include INST_PATH.'tpl/header.php';
	include INST_PATH.'tpl/check_env.php';
	include INST_PATH.'tpl/footer.php';
}elseif($do == 'check_db') {
	include INST_PATH.'tpl/header.php';
	include INST_PATH.'tpl/check_db.php';
	include INST_PATH.'tpl/footer.php';
}elseif($do == 'complete') {
	include INST_PATH.'tpl/header.php';
	echo '<div id="cont" class="content"></div><div class="button"></div>';
	include INST_PATH.'tpl/footer.php';

	if(!isset($_POST['dbhost'])) {
		js_back('<u>非法访问！</u>');
	}

	$dbhost = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
	$dbuser = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
	$dbpw = isset($_POST['dbpw']) ? trim($_POST['dbpw']) : '';
	$dbname = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
	$charset = 'UTF8';
	$tablepre = isset($_POST['dbpre']) ? trim($_POST['dbpre']) : '';
	$hostname = isset($_POST['hostname']) ? trim($_POST['hostname']) : '';
	$adm_user = isset($_POST['adm_user']) ? trim($_POST['adm_user']) : '';
	$adm_pass = isset($_POST['adm_pass']) ? trim(str_replace(' ', '', $_POST['adm_pass'])) : '';

	if(empty($dbhost)) {
		js_back('<u>数据库主机不能为空！</u>');
	}elseif(empty($dbuser)) {
		js_back('<u>数据库用户名不能为空！</u>');
	}elseif(!preg_match('/^\w+$/', $dbname)) {
		js_back('<u>数据库名不正确！</u>');
	}elseif(empty($tablepre)) {
		js_back('<u>数据库表前辍不能为空！</u>');
	}elseif(!preg_match('/^\w+$/', $tablepre)) {
		js_back('<u>数据库表前辍不正确！</u>');
	}elseif(empty($hostname)) {
		js_back('<u>域名不能为空！(本地开发可填写localhost)</u>');
	}elseif(empty($adm_user)) {
		js_back('<u>创始人用户名不能为空！</u>');
	}elseif(strlen($adm_pass) < 5) {
		js_back('<u>密码不能小于5位数！</u>');
	}

	// 连接数据库
	if(!function_exists('mysqli_connect')) {
		js_back('函数 mysqli_connect() 不存在，请检查 php.ini 是否加载了 mysqli 模块！');
	}
	$link = mysqli_connect($dbhost, $dbuser, $dbpw);
	if(!$link) {
		js_back('mysqli 主机、账号或密码不正确！<br><u>'.mysqli_error().'</u>');
	}

	try{
		mysqli_select_db($link, $dbname);
		if(mysqli_errno($link) == 1049) {
			mysqli_query($link, "CREATE DATABASE $dbname DEFAULT CHARACTER SET UTF8");
			if(!mysqli_select_db($link, $dbname)) {
				js_back('自动创建数据库失败！您的mysqli账号是否有权限创建数据库？<br><u>'.mysqli_error().'</u>');
			}
		}
		// 为防止意外，让用户自己做选择
		if(empty($_POST['cover'])) {
			$query = mysqli_query($link, "SHOW TABLES FROM $dbname");
			while($row = mysqli_fetch_row($query)) {
				if(preg_match("#^{$tablepre}#", $row[0])) {
					js_back('<u>发现有相同表前缀，请返回选择“覆盖安装”或“修改表前缀”。</u>');
				}
			}
		}

		// 设置编码
		mysqli_query($link, "SET names utf8, sql_mode=''");
	}catch(Exception $e) {
		js_back('<u>未知错误！</u><br><u>'.mysqli_error().'</u>');
	}

	// 创建数据表
	$file = INST_PATH.'data/mysqli.sql';
	if(!is_file($file)) {
		js_back('mysqli.sql 文件 <u>丢失</u>');
	}
	$s = file_get_contents($file);
	$sqls = split_sql($s, $tablepre);
	foreach($sqls as $sql) {
		$sql = str_replace("\n", '', trim($sql));
		$ret = mysqli_query($link, $sql);
		if(substr($sql, 0, 6) == 'CREATE') {
			$name = preg_replace("/CREATE TABLE ([`a-z0-9_]+) .*/is", "\\1", $sql);

			if($ret) {
				js_show('创建数据表 '.$name.' ... <i>成功</i>');
			}else{
				js_back('创建数据表 '.$name.' ... <u>失败</u> (您的数据库没有写权限？)<br><u>'.mysqli_error().'</u>');
			}
		}

		if(!$ret) {
			js_back('创建数据表失败</u> (您的数据库没有权限？)<br><u>'.mysqli_error().'</u>');
		}
	}

	// 创建基本数据
	$file = INST_PATH.'data/mysqli_data.sql';
	if(!is_file($file)) {
		js_back('mysqli_data.sql 文件 <u>丢失</u>');
	}
	$s = file_get_contents($file);
	$sqls = split_sql($s, $tablepre);
	$ret = true;
	foreach($sqls as $sql) {
		$sql = str_replace("\n", '', trim($sql));
		mysqli_query($link, $sql) || $ret = false;
	}
	js_show('创建基本数据 ... '.($ret ? '<i>成功</i>' : '<u>失败</u>'));
	if(!$ret) exit;

	// 创建创始人
	$salt = random(16, 3, '0123456789abcdefghijklmnopqrstuvwxyz~!@#$%^&*()_+<>,.'); // 增加破解难度
	$password = md5(md5($adm_pass).$salt);
	$ip = ip2long(ip());
	$time = time();
	$ret = mysqli_query($link, "INSERT INTO `{$tablepre}user` (`uid`, `username`, `password`, `salt`, `groupid`, `email`, `homepage`, `intro`, `regip`, `regdate`, `loginip`, `logindate`, `lastip`, `lastdate`, `contents`, `comments`, `logins`) VALUES (1, '{$adm_user}', '{$password}', '{$salt}', 1, '', '', '', {$ip}, {$time}, 0, 0, 0, 0, 0, 0, 0);");
	js_show('创建创始人 ... '.($ret ? '<i>成功</i>' : '<u>失败</u>'));
	if(!$ret) exit;

	// 初始网站设置
	$webdomain = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
	$webdir = get_webdir();
	$weburl = 'http://'.$webdomain.$webdir;
	$cfg = array(
		'webname' => '功夫CMS',
		'webdomain' => $webdomain,
		'webdir' => $webdir,
		'webmail' => 'admin@qq.com',
		'tongji' => '<script type="text/javascript">var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cscript src=\'" + _bdhmProtocol + "hm.baidu.com/h.js%3F948dba1e5d873b9c1f1c77078c521c89\' type=\'text/javascript\'%3E%3C/script%3E"));</script>',
		'beian' => '京ICP备20121225号',
		'seo_title' => '让建站变的更简单！',
		'seo_keywords' => '功夫CMS,GFCMS',
		'seo_description' => '功夫CMS，让建站变的更简单！',

		'link_show' => '{cate_alias}/{id}.html',
		'link_show_type' => 2,
		'link_show_end' => '.html',
		'link_cate_page_pre' => '/page_',
		'link_cate_page_end' => '.html',
		'link_cate_end' => '/',
		'link_tag_pre' => 'tag/',
		'link_tag_end' => '.html',
		'link_comment_pre' => 'comment/',
		'link_comment_end' => '.html',
		'link_index_end' => '.html',

		'up_img_ext' => 'jpg,jpeg,gif,png',
		'up_img_max_size' => '3074',
		'up_file_ext' => 'zip,gz,rar,iso,xsl,doc,ppt,wps',
		'up_file_max_size' => '10240',
		'thumb_article_w' => 163,
		'thumb_article_h' => 124,
		'thumb_product_w' => 150,
		'thumb_product_h' => 150,
		'thumb_photo_w' => 150,
		'thumb_photo_h' => 150,
		'thumb_type' => 2,
		'thumb_quality' => 90,
		'watermark_pos' => 9,
		'watermark_pct' => 90,
	);
	$settings = addslashes(json_encode($cfg));
	$ret = mysqli_query($link, "INSERT INTO {$tablepre}kv SET k='cfg',v='{$settings}',expiry='0'");
	js_show('初始网站设置 ... '.($ret ? '<i>成功</i>' : '<u>失败</u>'));
	if(!$ret) exit;

	// 清空缓存
	$runtime = ROOT_PATH.'runtime/';
	$file = $runtime.'_runtime.php';
	if(is_file($file)) {
		$ret = unlink($runtime.'_runtime.php');
		js_show('清除 runtime/_runtime.php ... <i>完成</i>');
	}
	$tpmdir = array('_control', '_model', '_view');
	foreach($tpmdir as $dir) {
		$ret = _rmdir($runtime.'gfcms'.$dir);
		js_show('清除 runtime/gfcms'.$dir.' ... <i>完成</i>');
	}
	foreach($tpmdir as $dir) {
		if($dir == '_model') continue;
		$ret = _rmdir($runtime.'gfcms_admin'.$dir);
		js_show('清除 runtime/gfcms_admin'.$dir.' ... <i>完成</i>');
	}

	// 初始插件配置
	$file = INST_PATH.'plugin.sample.php';
	if(!is_file($file)) {
		js_back('plugin.sample.php 文件 <u>丢失</u>');
	}
	$ret = file_put_contents(ROOT_PATH.'config/plugin.inc.php', file_get_contents($file));
	js_show('设置 config/plugin.inc.php ... '.($ret ? '<i>成功</i>' : '<u>失败</u>'));
	if(!$ret) exit;

	// 生成配置文件
	$file = INST_PATH.'config.sample.php';
	if(!is_file($file)) {
		js_back('config.sample.php 文件 <u>丢失</u>');
	}
	$auth_key = random(32, 3);
	$cookie_pre = 'tw'.random(5, 3).'_';

	$s = file_get_contents($file);
	$s = preg_replace("/'auth_key' => '\w*',/", "'auth_key' => '".addslashes($auth_key)."',", $s);
	$s = preg_replace("/'cookie_pre' => '\w*',/", "'cookie_pre' => '".addslashes($cookie_pre)."',", $s);
	$s = preg_replace("/'host' => '\w*',/", "'host' => '".addslashes($dbhost)."',", $s);
	$s = preg_replace("/'user' => '\w*',/", "'user' => '".addslashes($dbuser)."',", $s);
	$s = preg_replace("/'password' => '\w*',/", "'password' => '".addslashes($dbpw)."',", $s);
	$s = preg_replace("/'name' => '\w*',/", "'name' => '".addslashes($dbname)."',", $s);
	$s = preg_replace("/'tablepre' => '\w*',/", "'tablepre' => '".addslashes($tablepre)."',", $s);
	$s = preg_replace("/'pre' => '\w*',/", "'pre' => '".addslashes($tablepre)."',", $s);
	$s = preg_replace("/'hostname' => '.*',/", "'hostname' => '".addslashes($hostname)."',", $s);

	$ret = file_put_contents(ROOT_PATH.'config/config.inc.php', $s);
	js_show('设置 config/config.inc.php ... '.($ret ? '<i>成功</i>' : '<u>失败</u>'));
	if(!$ret) exit;

	// 安装结束提示
	$s = '<div class="end"><h3>恭喜！您的网站已安装完成啦！</h3><p>';
	$s .= '首页地址：<a href="'.$weburl.'" target="_blank">'.$weburl.'</a><br>';
	$s .= '后台地址：<a href="'.$weburl.'admin/" target="_blank">'.$weburl.'admin/</a><br>';
	$s .= '用户名：'.$adm_user.'　<br>密　码：'.$adm_pass.'<br>';
	$s .= '亲，请牢记以上信息，您可以登陆后台修改密码及网站设置。^_^</p></div>';
	js_show($s);

	// 统计一下安装数
	//echo '<script type="text/javascript" src="http://www.gfcms.com/app/?install='.$webdomain.'"></script>';
}
