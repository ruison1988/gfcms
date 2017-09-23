<?php defined('HOM_NAME') || exit('Access Denied'); defined('FRAME_PATH') || exit;

/**
 * 导航模块 (最多支持两级)
 * @return array
 */
function kp_block_navigate($conf) {
	global $run;




	$nav_arr = $run->kv->xget('navigate');
	foreach($nav_arr as &$v) {
		if($v['cid']) {
			$v['url'] = $run->category->category_url($v['cid'], $v['alias']);
		}

		if(!empty($v['son'])) {
			foreach($v['son'] as &$v2) {
				if($v2['cid']) {
					$v2['url'] = $run->category->category_url($v2['cid'], $v2['alias']);
				}
			}
		}
	}




	return $nav_arr;
}
?><!doctype html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo(isset($gf['titles']) ? $gf['titles'] : ''); ?></title>
	<base href="<?php echo(isset($C['hostname']) ? $C['hostname'] : ''); ?>" ></base>
	<meta name="keywords" content="<?php echo(isset($gf['seo_keywords']) ? $gf['seo_keywords'] : ''); ?>" />
	<meta name="description" content="<?php echo(isset($gf['seo_description']) ? $gf['seo_description'] : ''); ?>" />
	<link rel="shortcut icon" type="image/x-icon" href= "<?php echo(isset($gf['webdir']) ? $gf['webdir'] : ''); ?>favicon.ico" />
	<link rel="stylesheet" type="text/css" href="http://wdt.my.com/tpl/home/default/css/global.css" />
</head>

<body>
<div class="wrap">
	<!--头部开始-->
	<div class="head cf">
		<div class="logo"><a href="<?php echo(isset($gf['weburl']) ? $gf['weburl'] : ''); ?>"><img src="http://wdt.my.com/tpl/home/default/img/logo.gif" alt="<?php echo(isset($gf['webname']) ? $gf['webname'] : ''); ?>" title="<?php echo(isset($gf['webname']) ? $gf['webname'] : ''); ?>" /></a></div>
		<div class="search">
			<form id="search_form" method="get" action="<?php echo(isset($gf['webdir']) ? $gf['webdir'] : ''); ?>index.php">
				<input type="hidden" name="u" value="search-index" />
				<select name="mid" class="s_set">
					<?php if(isset($gf['mod_name']) && is_array($gf['mod_name'])) { foreach($gf['mod_name'] as $k=>&$v) { ?><option value="<?php echo(isset($k) ? $k : ''); ?>"<?php if (isset($_GET['mid']) && $_GET['mid']==$k) { ?> selected="selected"<?php } ?>><?php echo(isset($v) ? $v : ''); ?></option><?php }} ?>
				</select>
				<input class="s_tit" type="text" name="keyword" value="<?php echo(isset($keyword) ? $keyword : ''); ?>" />
				<input class="s_sub" type="submit" value="" />
			</form>
		</div>
	</div>
	<!--头部结束-->

	<!--导航开始-->
	<?php $data = kp_block_navigate(array (
)); ?>
	<div class="nav">
		<div class="n_p"></div>
		<div class="n_c">
			<dl<?php if (empty($gf_var['topcid'])) { ?> class="on"<?php } ?>>
				<dt><a href="<?php echo(isset($gf['weburl']) ? $gf['weburl'] : ''); ?>">首页</a></dt>
			</dl>
			<?php if(isset($data) && is_array($data)) { foreach($data as &$v) { ?>
			<dl<?php if ($gf_var['topcid'] == $v['cid']) { ?> class="on"<?php } ?>>
				<dt><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" target="<?php echo(isset($v['target']) ? $v['target'] : ''); ?>"><?php echo(isset($v['name']) ? $v['name'] : ''); ?></a></dt>
				<dd>
					<?php if(isset($v['son']) && is_array($v['son'])) { foreach($v['son'] as &$v2) { ?><a href="<?php echo(isset($v2['url']) ? $v2['url'] : ''); ?>" target="<?php echo(isset($v2['target']) ? $v2['target'] : ''); ?>"><?php echo(isset($v2['name']) ? $v2['name'] : ''); ?></a><?php }} ?>
				</dd>
			</dl>
			<?php }} ?>
		</div>
		<div class="n_n"></div>
		<dl class="n_hover"></dl>
	</div>
	<?php unset($data); ?>
	<!--导航结束-->


	<!--两列开始-->
	<div class="cont cf">
		<div class="m_l">
			<div class="b1_top">
				<div class="b1_tit">
					<div class="ct_p"></div>
					<div class="ct_c">
						<a href="<?php echo(isset($gf['weburl']) ? $gf['weburl'] : ''); ?>">首页</a> &#187; <a href="javascript:;">404错误</a>
					</div>
					<div class="ct_n"></div>
				</div>
				<div class="b1_cont art_show cf">
					<div class="content">
						<p>有点尴尬，您访问的页面没找到！</p>
					</div>
				</div>
			</div>
		</div>

		<div class="m_r">
						<div class="sidebar">
				<h2 class="b2_tit">
					<b>联系我们</b>
				</h2>
				<ul class="b2_cont">
					<b>北京xxx科技有限公司</b><br />
					联系人：某先生<br />
					电　话：010-88888888<br />
					手　机：18888888888<br />
					邮　箱：88888@qq.com<br />
					地　址：北京市东直门xxx大厦
				</ul>
			</div>

		</div>
	</div>
	<!--两列结束-->


	<!--底部开始-->
	<div class="foot">
		<p>
			Copyright &#169; 2012-2014 <a href="<?php echo(isset($gf['weburl']) ? $gf['weburl'] : ''); ?>"><?php echo(isset($gf['webname']) ? $gf['webname'] : ''); ?></a> Inc. 保留所有权利。

			<!-- 感谢您使用GFCMS,功夫因您更精彩!为支持GFCMS的发展,请保留GFCMS的链接信息. -->
			Powered by <a href="http://www.gfcms.com" target="_blank" title="GFCMS">GFCMS <?php echo(isset($_ENV['_config']['version']) ? $_ENV['_config']['version'] : ''); ?></a>
		</p>
		<p><?php  echo '页面耗时'.runtime().'秒, 内存占用'.runmem().', 访问数据库'.$_ENV['_sqlnum'].'次';  ?></p>
		<p><?php echo(isset($gf['beian']) ? $gf['beian'] : ''); ?></p>
		<p><?php echo(isset($gf['tongji']) ? $gf['tongji'] : ''); ?></p>
	</div>
	<!--底部结束-->
</div>


<script type="text/javascript" src="http://wdt.my.com/tpl/home/default/js/jquery.js"></script>
<script type="text/javascript" src="http://wdt.my.com/tpl/home/default/js/main.js"></script>
</body>
</html>
