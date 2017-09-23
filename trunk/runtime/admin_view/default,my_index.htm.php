<?php defined('HOM_NAME') || exit('Access Denied'); 
?><!doctype html>
<head>
<base href="<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>" ></base>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="http://wdt.my.com/tpl/admin/default/css/global.css" type="text/css" rel="stylesheet" />
<script src="http://wdt.my.com/tpl/admin/default/js/jquery.js" type="text/javascript"></script>
<script src="http://wdt.my.com/tpl/admin/default/js/global.js" type="text/javascript"></script>
<script type="text/javascript">
	var pKey = "<?php echo(isset($_pkey) ? $_pkey : ''); ?>", urlKey = "<?php echo(isset($_ukey) ? $_ukey : ''); ?>", place = "<?php echo(isset($_place) ? $_place : ''); ?>";
</script>
<title><?php echo(isset($_title) ? $_title : ''); ?></title>
</head>

<body>

<div class="m">
	<div class="p">
		<div class="cc cf" style="padding-top:0">
			<div id="homel" class="conthome">
				<div class="sort">
					<h1>常用功能</h1>
					<dl class="sc cf" id="used">
						<?php if(isset($used_array) && is_array($used_array)) { foreach($used_array as &$v) { ?><dd><a urlKey="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" title="<?php echo(isset($v['name']) ? $v['name'] : ''); ?>"><img src="<?php echo(isset($C['tplpath']) ? $C['tplpath'] : ''); echo(isset($v['imgsrc']) ? $v['imgsrc'] : ''); ?>"><?php echo(isset($v['name']) ? $v['name'] : ''); ?></a></dd><?php }} ?>
					</dl>
				</div>
				<div class="sort">
					<h1>我的信息</h1>
					<ul class="sc">
						<li><b>登录帐号</b><?php echo(isset($_user['username']) ? $_user['username'] : ''); ?>（<?php echo(isset($_group['groupname']) ? $_group['groupname'] : ''); ?>）</li>
						<li><b>内容条数</b><?php echo(isset($_user['contents']) ? $_user['contents'] : ''); ?></li>
						<li><b>评论条数</b><?php echo(isset($_user['comments']) ? $_user['comments'] : ''); ?></li>
						<li><b>登陆次数</b><?php echo(isset($_user['logins']) ? $_user['logins'] : ''); ?></li>
						<li><b>本次登录</b><?php echo(isset($_user['logindate']) ? $_user['logindate'] : ''); ?>（<?php echo(isset($_user['loginip']) ? $_user['loginip'] : ''); ?>）</li>
						<li><b>上次登录</b><?php echo(isset($_user['lastdate']) ? $_user['lastdate'] : ''); ?>（<?php echo(isset($_user['lastip']) ? $_user['lastip'] : ''); ?>）</li>
						<li><b>注册时间</b><?php echo(isset($_user['regdate']) ? $_user['regdate'] : ''); ?>（<?php echo(isset($_user['regip']) ? $_user['regip'] : ''); ?>）</li>
						<li><b>浏览器核</b><span id="browser"></span></li>
					</ul>
				</div>
				<div class="sort">
					<h1>服务器配置</h1>
					<ul class="sc">
						<li><b>操作系统</b><?php echo(isset($info['os']) ? $info['os'] : ''); ?></li>
						<li><b>环境软件</b><?php echo(isset($info['software']) ? $info['software'] : ''); ?></li>
						<li><b>MySQLi</b><?php echo(isset($info['mysqli']) ? $info['mysqli'] : ''); ?></li>
						<li><b>上传限制</b><?php echo(isset($info['filesize']) ? $info['filesize'] : ''); ?></li>
						<li><b>执行限制</b><?php echo(isset($info['exectime']) ? $info['exectime'] : ''); ?>秒</li>
						<li><b>安全模式</b><?php echo(isset($info['safe_mode']) ? $info['safe_mode'] : ''); ?></li>
						<li><b>远程访问</b><?php echo(isset($info['url_fopen']) ? $info['url_fopen'] : ''); ?></li>
						<li><b>其它支持</b><?php echo(isset($info['other']) ? $info['other'] : ''); ?></li>
					</ul>
				</div>
			</div>

			<div id="homer" class="conthome">
				<div class="sort">
					<h1>官方动态</h1>
					<ul class="sc" id="gfcms_news"></ul>
				</div>
				<div class="sort">
					<h1>综合统计</h1>
					<ul class="sc">
						<li><b>分类总数</b><?php echo(isset($stat['category']) ? $stat['category'] : ''); ?></li>
						<li><b>文章内容</b><?php echo(isset($stat['article']) ? $stat['article'] : ''); ?></li>
						<li><b>文章评论</b><?php echo(isset($stat['article_comment']) ? $stat['article_comment'] : ''); ?></li>
						<li><b>产品内容</b><?php echo(isset($stat['product']) ? $stat['product'] : ''); ?></li>
						<li><b>产品评论</b><?php echo(isset($stat['product_comment']) ? $stat['product_comment'] : ''); ?></li>
						<li><b>图集内容</b><?php echo(isset($stat['photo']) ? $stat['photo'] : ''); ?></li>
						<li><b>图集评论</b><?php echo(isset($stat['photo_comment']) ? $stat['photo_comment'] : ''); ?></li>
						<li><b>可用空间</b><?php echo(isset($stat['space']) ? $stat['space'] : ''); ?></li>
					</ul>
				</div>
				<div class="sort">
					<h1>GFCMS 系统</h1>
					<ul class="sc">
						<li><b>程序版本</b>GFCMS <?php echo(isset($C['version']) ? $C['version'] : ''); ?> Release <?php echo(isset($C['release']) ? $C['release'] : ''); ?></li>
						<li><b>授权检测</b>免费版</li>
						<li><b>程序设计</b><a href="http://user.qzone.qq.com/578001722" target="_blank">孙叫兽</a></li>
						<li><b>界面设计</b><a href="http://user.qzone.qq.com/578001722" target="_blank">孙叫兽</a></li>
						<li><b>用户体验</b><a href="http://user.qzone.qq.com/578001722" target="_blank">宋立新</a></li>
						<li><b>特别感谢</b>
							<a href="www.dafashij.com" target="_blank" title="www.dafashij.com 创始人,GFCMS安全顾问">Yanghui</a> 
							<a href="javascript:;" target="_blank" title="php程序员,帮助GFCMS改善用户体验支持">立新</a> 
							<a href="javascript:;" target="_blank" title="技术助理">如泉</a> 
							<a href="http://ueditor.baidu.com/" target="_blank" title="GFCMS内置UEditor编辑器">UEditor团队</a>
						</li>
						<li><b>版权所有</b><a href="http://www.gongfu.com" target="_blank">功夫科技</a></li>
						<li><b>相关网站</b>
							<a href="http://www.gfcms.com" target="_blank">功夫CMS</a> 
							<a href="http://www.seo.net.cn" target="_blank">SEO培训</a> 
							<a href="http://www.gongfu.com" target="_blank">网络营销</a> 
							<a href="http://www.2010888.com" target="_blank">网络品牌</a> 
							<a href="http://www.9959.com.cn" target="_blank">微博营销</a> 
							<a href="http://www.eczn.com" target="_blank">电子商务</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(window).resize(setHomeWidth);

$(function() {
	$("#used a").click(function(){
		parent.oneTab($(this).attr("urlKey"));
	}).attr("href", "javascript:;");

	setHomeWidth();
	getBrowser();

	setStyle("img:hover", ".2s ease-in-out 0s 3", "a_s", "rotate(0deg)", "rotate(-6deg)", "rotate(0deg)", "rotate(6deg)", "rotate(0deg)");

	$("body").append('<?php echo(isset($response_info) ? $response_info : ''); ?>');
});

function setStyle(name, val, func, a, b, c, d, e) {
	var arr = ["webkit", "ms", "moz", "o"];
	var s = s2 = "";
	for(var v in arr) {
		s += "@-"+arr[v]+"-keyframes "+func+"{";
		s += "0%{-"+arr[v]+"-transform:"+a+"}";
		s += "25%{-"+arr[v]+"-transform:"+b+"}";
		s += "50%{-"+arr[v]+"-transform:"+c+"}";
		s += "75%{-"+arr[v]+"-transform:"+d+"}";
		s += "100%{-"+arr[v]+"-transform:"+e+"}";
		s += "}";
		s2 += "-"+arr[v]+"-animation:"+func+" "+val+";"
	}
	$("head").append('<style type="text/css">'+s+name+'{'+s2+'}</style>');
}

function setHomeWidth() {
	$("#homel,#homer").width(($(".cc").width()-8)/2);
}

function appInfo() {
	var browser = {
			msie: false, firefox: false, opera: false, safari: false,
			chrome: false, netscape: false, appname: '未知', version: ''
		},
		userAgent = window.navigator.userAgent.toLowerCase();
	if (/(msie|firefox|opera|chrome|netscape)\D+(\d[\d.]*)/.test(userAgent)){
		browser[RegExp.$1] = true;
		browser.appname = RegExp.$1;
		browser.version = RegExp.$2;
	}else if(/version\D+(\d[\d.]*).*safari/.test(userAgent)){
		browser.safari = true;
		browser.appname = 'safari';
		browser.version = RegExp.$2;
	}
	return browser;
}

function getBrowser() {
	var myos = appInfo();
	$("#browser").html(myos.appname +" "+myos.version+(myos.msie && myos.version<9 ? ' <strong style="color:red">【您的浏览器不支持圆角效果，建议使用IE9以上版本或chrome】</strong>' : ''));
}
</script>
</body>
</html>
