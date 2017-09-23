<?php
$_ENV['_config'] = array(
	'plugin_disable' => 0,			// 禁止掉所有插件
	'zone' => 'Asia/Shanghai',		// 时区
	'gzip' => 1,	// 开启 GZIP 压缩
	'auth_key' => '',	// 加密KEY

	'parseurl' => 0,			// 是否开启前台伪静态

	'cookie_pre' => 'gf_',
	'cookie_path' => '/',
	'cookie_domain' => '',

	// 数据库配置，type 为默认的数据库类型，可以支持多种数据库: mysql|pdo_mysql|pdo_sqlite|postgresql|mongodb
	'db' => array(
		'type' => 'mysqli',
		// 主数据库
		'master' => array(
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'name' => '2_0',
			'charset' => 'utf8',
			'tablepre' => 'pre_',
			'engine'=>'MyISAM',
		),
		// 从数据库(可以是从数据库服务器群，如果不设置将使用主数据库)
		/*
		'slaves' => array(
			array(
				'host' => 'localhost',
				'user' => 'root',
				'password' => '',
				'name' => '2_0',
				'charset' => 'utf8',
				'engine'=>'MyISAM',
			),
		),
		*/
	),

	'cache' => array(
		'enable'=>0,
		'l2_cache'=>1,
		'type'=>'memcache',
		'pre' => 'gf_',
		'memcache'=>array (
			'multi'=>1,
			'host'=>'127.0.0.1',
			'port'=>'11211',
		)
	),
	
	'hostname' => 'http://wdt.my.com/',	// 当前域名
	'projects' => array('home','admin'),	//所有项目(第一个为默认项目)

	'url_suffix' => '.html',
	'version' => '1.0',			// 版本号
	'release' => '20160801',		// 发布日期
);
