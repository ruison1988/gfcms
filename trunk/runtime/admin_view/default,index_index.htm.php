<?php defined('HOM_NAME') || exit('Access Denied'); 
?><!doctype html>
<head>
<title>功夫CMS 后台管理</title>
<base href="<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>" ></base>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="http://wdt.my.com/tpl/admin/default/css/admin.css" type="text/css" rel="stylesheet" />
</head>

<body scroll="no">
<div class="acp">
	<div id="box_opt"><a id="ifr_refresh" title="刷新" href="">刷新</a> <a id="full_screen" title="全屏" href="">全屏</a></div>
	<div class="top">
		<div class="logo"><a href="./"><img src="http://wdt.my.com/tpl/admin/default/img/logo.jpg" /></a></div>
		<div class="nav">
			<ul>
				<?php if(isset($_navs[0]) && is_array($_navs[0])) { foreach($_navs[0] as $pKey=>&$name) { ?><li pKey="<?php echo(isset($pKey) ? $pKey : ''); ?>" tabindex="0" role="link">
					<b><?php echo(isset($name) ? $name : ''); ?></b>
					<dl>
						<?php if(isset($_navs[2][$pKey]) && is_array($_navs[2][$pKey])) { foreach($_navs[2][$pKey] as $url=>&$arr) { ?><dd urlKey="<?php echo(isset($url) ? $url : ''); ?>" tabindex="0" role="link"><?php echo(isset($arr['name']) ? $arr['name'] : ''); ?></dd>
						<?php }} ?><dt></dt>
					</dl>
				</li>
				<?php }} ?>
			</ul>
		</div>
		<div class="uinfo">
			<p>您好, <?php echo(isset($_user['username']) ? $_user['username'] : ''); ?>（<?php echo(isset($_group['groupname']) ? $_group['groupname'] : ''); ?>）[<a href="index.php?u=index-logout">退出</a>]</p>
			<p class="ilk"><a href="../" target="_blank" title="网站首页">网站首页</a></p>
		</div>
	</div>
	<div class="unber">
		<div class="left">
			<h1 id="menutit"></h1>
			<dl id="menu"></dl>
			<div class="copyright">Powered by <a href="http://www.gfcms.com" target="_blank" title="GFCMS <?php echo(isset($C['version']) ? $C['version'] : ''); ?> Release <?php echo(isset($C['release']) ? $C['release'] : ''); ?>">GFCMS <?php echo(isset($C['version']) ? $C['version'] : ''); ?></a><br />© 2013 <a href="http://www.gongfu.com" target="_blank" title="© 2012-2016 GongFu Co.ltd.">GongFu</a> Inc.</div>
		</div>
		<div class="right">
			<div id="closeer" title="关闭其它标签页"></div>
			<div id="leftbtn" title="向上"></div>
			<div id="rightbtn" title="向下"></div>
			<div id="adder" title="新标签页"></div>
			<div id="box_tab"><ul></ul></div>
			<div id="box_place"></div>
			<div id="box_frame"></div>
		</div>
	</div>
</div>
<script src="http://wdt.my.com/tpl/admin/default/js/jquery.js" type="text/javascript"></script>
<script src="http://wdt.my.com/tpl/admin/default/js/admin.js" type="text/javascript"></script>
</body>
</html>
