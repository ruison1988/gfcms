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
		<div class="cc">
			<div class="tb_t">清除缓存</div>
			<ul class="marginbot">
				<li><label><input type="checkbox" checked="checked" id="dbcache"> <span title="清空 runtime 数据表">数据库缓存</span></label></li>
				<li><label><input type="checkbox" checked="checked" id="filecache"> <span title="删除 runtime 目录中的文件缓存">文件缓存</span></label></li>
			</ul>
			<div class="tb_b" style="padding-left:0"><input type="submit" value="确定" class="but1" /></div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(".but1").click(function(){
	var v1 = $("#dbcache")[0].checked ? 1 : 0;
	var v2 = $("#filecache")[0].checked ? 1 : 0;
	gfAjax.postd("index.php?u=tool-index-ajax-1", {"dbcache":v1, "filecache":v2}, function(data){
		gfAjax.alert(data);
	});
});
</script>
</body>
</html>
