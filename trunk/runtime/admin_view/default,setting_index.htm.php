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
			<form action="http://<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>/index.php?u=setting-index-ajax-1" method="post">
				<div class="tb_t">基本设置</div>
				<table class="tb">
					<tr>
						<th class="th">站点标题</th>
						<td class="col"><?php echo(isset($input['webname']) ? $input['webname'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">站点域名</th>
						<td class="col"><?php echo(isset($input['webdomain']) ? $input['webdomain'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">所在目录</th>
						<td class="col"><?php echo(isset($input['webdir']) ? $input['webdir'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">邮箱</th>
						<td class="col"><?php echo(isset($input['webmail']) ? $input['webmail'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">统计代码</th>
						<td class="col"><?php echo(isset($input['tongji']) ? $input['tongji'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">备案号</th>
						<td class="col"><?php echo(isset($input['beian']) ? $input['beian'] : ''); ?></td>
					</tr>
					<tr>
						<th class="th">关闭全站评论</th>
						<td class="col"><input type="checkbox" name="dis_comment" value="1" <?php if ($input['dis_comment']) { ?> checked="checked"<?php } ?>title="关闭全站的评论功能"></td>
					</tr>

					


				</table>
				<div class="tb_b"><input type="submit" value="&#20445;&#23384;" class="but1" /></div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
gfAjax.submit("form:first");
</script>
</body>
</html>
