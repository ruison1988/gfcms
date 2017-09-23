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
	<div class="head">
		<dl>
			<dd class="on"><?php echo(isset($_title) ? $_title : ''); ?></dd>
			<dd>在线获取</dd>
		</dl>
	</div>
	<div class="p c2">
		<div class="cc" id="plugin">
			<?php if (empty($plugins['enable']) && empty($plugins['disable']) && empty($plugins['not_install'])) { ?>
				<div class="sky warning bnote" style="margin-top:8px"><i></i><b>暂无插件</b></div>
			<?php }else{ ?>

			<?php if(isset($plugins['enable']) && is_array($plugins['enable'])) { foreach($plugins['enable'] as $k=>&$v) { ?>
				<div class="plu cf">
					<div class="plu_l">
						<p class="plu_ico"<?php if (isset($v['is_show'])) { ?> style="background:url(../<?php echo(isset($core) ? $core : ''); ?>/plugin/<?php echo(isset($k) ? $k : ''); ?>/show.jpg)"<?php } ?>></p>
					</div>
					<div class="plu_c">
						<p class="plu_cont plu_b"><?php echo(isset($v['name']) ? $v['name'] : ''); ?>(<?php echo(isset($k) ? $k : ''); ?>)</p>
						<p class="plu_bottom" plugin="<?php echo(isset($k) ? $k : ''); ?>" version="<?php echo(isset($v['version']) ? $v['version'] : ''); ?>">
							<a class="but2 disabled">停用</a>
							<?php if (!empty($v['setting'])) { ?><a class="but2" href="javascript:parent.oneTab('<?php echo(isset($v['setting']) ? $v['setting'] : ''); ?>');">设置</a><?php } ?>
						</p>
					</div>
					<div class="plu_r">
						<p class="plu_cont plu_i"><?php echo(isset($v['brief']) ? $v['brief'] : ''); ?></p>
						<p class="plu_bottom"><b>版本号：<?php echo(isset($v['version']) ? $v['version'] : ''); ?></b> <b>作者: <a href="<?php echo(isset($v['authorurl']) ? $v['authorurl'] : ''); ?>" target="_blank"><?php echo(isset($v['author']) ? $v['author'] : ''); ?></a></b> <b>最近更新：<?php echo(isset($v['update']) ? $v['update'] : ''); ?></b></p>
					</div>
				</div>
			<?php }} ?>

			<?php if(isset($plugins['disable']) && is_array($plugins['disable'])) { foreach($plugins['disable'] as $k=>&$v) { ?>
				<div class="plu cf">
					<div class="plu_l">
						<p class="plu_ico"<?php if (isset($v['is_show'])) { ?> style="background:url(../<?php echo(isset($core) ? $core : ''); ?>/plugin/<?php echo(isset($k) ? $k : ''); ?>/show.jpg)"<?php } ?>></p>
					</div>
					<div class="plu_c">
						<p class="plu_cont plu_b"><?php echo(isset($v['name']) ? $v['name'] : ''); ?>(<?php echo(isset($k) ? $k : ''); ?>)</p>
						<p class="plu_bottom" plugin="<?php echo(isset($k) ? $k : ''); ?>" version="<?php echo(isset($v['version']) ? $v['version'] : ''); ?>">
							<?php if (!empty($v['setting']) && !empty($v['setting_ok'])) { ?>
							<a class="but2" href="javascript:parent.oneTab('<?php echo(isset($v['setting']) ? $v['setting'] : ''); ?>');">设置</a>
							<?php }else{ ?>
							<a class="but2 enable">启用</a>
							<?php } ?>
							<a class="but2 del">删除</a>
						</p>
					</div>
					<div class="plu_r">
						<p class="plu_cont plu_i"><?php echo(isset($v['brief']) ? $v['brief'] : ''); ?></p>
						<p class="plu_bottom"><b>版本号：<?php echo(isset($v['version']) ? $v['version'] : ''); ?></b> <b>作者: <a href="<?php echo(isset($v['authorurl']) ? $v['authorurl'] : ''); ?>" target="_blank"><?php echo(isset($v['author']) ? $v['author'] : ''); ?></a></b> <b>最近更新：<?php echo(isset($v['update']) ? $v['update'] : ''); ?></b></p>
					</div>
				</div>
			<?php }} ?>

			<?php if(isset($plugins['not_install']) && is_array($plugins['not_install'])) { foreach($plugins['not_install'] as $k=>&$v) { ?>
				<div class="plu cf">
					<div class="plu_l">
						<p class="plu_ico"<?php if (isset($v['is_show'])) { ?> style="background:url(../<?php echo(isset($core) ? $core : ''); ?>/plugin/<?php echo(isset($k) ? $k : ''); ?>/show.jpg)"<?php } ?>></p>
					</div>
					<div class="plu_c">
						<p class="plu_cont plu_b"><?php echo(isset($v['name']) ? $v['name'] : ''); ?>(<?php echo(isset($k) ? $k : ''); ?>)</p>
						<p class="plu_bottom" plugin="<?php echo(isset($k) ? $k : ''); ?>" version="<?php echo(isset($v['version']) ? $v['version'] : ''); ?>">
							<a class="but2 install">安装</a>
							<a class="but2 del">删除</a>
						</p>
					</div>
					<div class="plu_r">
						<p class="plu_cont plu_i"><?php echo(isset($v['brief']) ? $v['brief'] : ''); ?></p>
						<p class="plu_bottom"><b>版本号：<?php echo(isset($v['version']) ? $v['version'] : ''); ?></b> <b>作者: <a href="<?php echo(isset($v['authorurl']) ? $v['authorurl'] : ''); ?>" target="_blank"><?php echo(isset($v['author']) ? $v['author'] : ''); ?></a></b> <b>最近更新：<?php echo(isset($v['update']) ? $v['update'] : ''); ?></b></p>
					</div>
				</div>
			<?php }} ?>

			<?php } ?>
		</div>
		<div class="cc un" id="gfcms_app">
			<div class="sky warning bnote"><i></i><b>玩命加载中<font id="loading">...</font></b></div>
		</div>
	</div>
</div>
<script type="text/javascript">
setPlurWidth();
$(window).resize(setPlurWidth);
$("#plugin .plu_c .plu_bottom a").each(function() {
	if(!this.href) this.href = "javascript:;";
});

//检测插件是否需要升级
setTimeout(function() {
	var plu_arr = new Array();
	$("#plugin>.plu>.plu_c>.plu_bottom").each(function(i) {
		if(i >= 20) return;
		plu_arr[i] = $(this).attr("plugin") + "-" + $(this).attr("version");
	});
	$.getScript("http://www.gfcms.com/app/?go=plugin_upgrade&s="+plu_arr.join(","));
}, 1000)

//停用
$(".disabled").click(function(){
	gfAjax.postd("index.php?u=plugin-disabled-ajax-1", {"dir" : $(this).parent().attr("plugin")}, function(data){
		gfAjax.alert(data);
		if(window.gfData.err==0) setTimeout(function(){window.location.reload();}, 1000);
	});
});

//启用
$(".enable").click(function(){
	enable($(this).parent().attr("plugin"));
});

//安装
$(".install").click(function(){
	gfAjax.postd("index.php?u=plugin-install-ajax-1", {"dir" : $(this).parent().attr("plugin")}, function(data){
		gfAjax.alert(data);
		if(window.gfData.err==0) setTimeout(function(){window.location.reload();}, 1000);
	});
});

//删除
$(".del").click(function(){
	var dir = $(this).parent().attr("plugin");
	gfAjax.confirm("删除不可恢复，确定删除“<font color='red'>"+$(this).parent().parent().children(".plu_b").html()+"</font>”？", function(){
		gfAjax.postd("index.php?u=plugin-delete-ajax-1", {"dir" : dir}, function(data){
			window.gfErr = true;
			gfAjax.alert(data);
			$(".ajaxtips u").click(function(){window.location.reload();});
			if(window.gfData.err==0) setTimeout(function(){window.location.reload();}, 1000);
		});
	});
});

//加载效果
var int;
function loading() {
	var dot = '';
	int = setInterval(function(){
		dot += '.';
		if(dot.length > 6) dot = '.';
		$("#loading").html(dot);
	}, 50);
}

//在线获取
$(".head dd:eq(1)").one("click", function() {
	loading();
	$.getScript("http://www.gfcms.com/app/?go=plugin");
});

//在线安装
function install_plugin(dir) {
	gfAjax.confirm("玩命下载 <font color='red'>"+dir+"</font> 中<font id='loading'>......</font>", function(){});
	loading();
	$("#okA").remove();

	$.getScript("index.php?u=plugin-install_plugin-dir-" + dir, function(){
		clearInterval(int);
		if(typeof err == 'number' && !err && $("#install_enable").length == 0) {
			$("#noA").after('<a id="install_enable" class="but3" href="javascript:;">启用</a>');
			$("#install_enable").click(function() { enable(dir); });
		}
		//只要在线安装过都重新加载一下
		$(".head dd:eq(0)").click(function(){ window.location.reload(); });
	});
}

//在线升级
function upgrade(dir) {
	gfAjax.confirm("玩命下载 <font color='red'>"+dir+"</font> 中<font id='loading'>......</font>", function(){});
	loading();
	$("#okA").remove();

	$.getScript("index.php?u=plugin-upgrade-dir-" + dir, function(){
		clearInterval(int);
		$("#noA").html("我知道了");
		setTimeout(function(){window.location.reload();}, 2000);
	});
}

//设置插件展示宽度 PS: CSS3 时代的到来就可以不使用这么笨的方法了
function setPlurWidth() {
	$(".plu_r").width(Math.max(500, $(".plu:visible").width() - $(".plu_l:visible").outerWidth(true) - $(".plu_c:visible").outerWidth(true) - 15));
}

//启用
function enable(dir) {
	gfAjax.postd("index.php?u=plugin-enable-ajax-1", {"dir" : dir}, function(data){
		gfAjax.alert(data);
		if(window.gfData.err==0) setTimeout(function(){window.location.reload();}, 1000);
	});
}
</script>
</body>
</html>
