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
defined('FRAME_PATH') || exit;

/**
 * 内容列表模块
 * @param int cid 分类ID 如果不填：自动识别 (不推荐用于读取频道分类，影响性能)
 * @param int mid 模型ID (当cid为0时，设置mid才能生效，否则程序自动识别)
 * @param string dateformat 时间格式
 * @param int titlenum 标题长度
 * @param int intronum 简介长度
 * @param string orderby 排序方式
 * @param int orderway 降序(-1),升序(1)
 * @param int start 开始位置
 * @param int limit 显示几条
 * @return array
 */
function kp_block_list($conf) {
	global $run;




	$cid = isset($conf['cid']) ? intval($conf['cid']) : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
	$mid = _int($conf, 'mid', 2);
	$dateformat = empty($conf['dateformat']) ? 'Y-m-d H:i:s' : $conf['dateformat'];
	$titlenum = _int($conf, 'titlenum');
	$intronum = _int($conf, 'intronum');
	$orderby = isset($conf['orderby']) && in_array($conf['orderby'], array('id', 'dateline')) ? $conf['orderby'] : 'id';
	$orderway = isset($conf['orderway']) && $conf['orderway'] == 1 ? 1 : -1;
	$start = _int($conf, 'start');
	$limit = _int($conf, 'limit', 10);

	// 读取分类内容
	if($cid == 0) {
		$cate_name = 'No Title';
		$cate_url = 'javascript:;';

		$table_arr = &$run->_cfg['table_arr'];
		$table = isset($table_arr[$mid]) ? $table_arr[$mid] : 'article';

		$where = array();
	}else{
		$cate_arr = $run->category->get_cache($cid);
		if(empty($cate_arr)) return;
		$cate_name = $cate_arr['name'];
		$cate_url = $run->category->category_url($cid, $cate_arr['alias']);
		$table = &$cate_arr['table'];

		if(!empty($cate_arr['son_cids']) && is_array($cate_arr['son_cids'])) {
			$where = array('cid' => array("IN" => $cate_arr['son_cids'])); // 影响数据库性能
		}else{
			$where = array('cid' => $cid);
		}
	}

	// 初始模型表名
	$run->cms_content->table = 'cms_'.$table;

	// 读取内容列表
	$list_arr = $run->cms_content->find_fetch($where, array($orderby => $orderway), $start, $limit);
	foreach($list_arr as &$v) {
		$run->cms_content->format($v, $mid, $dateformat, $titlenum, $intronum);
	}




	return array('cate_name'=> $cate_name, 'cate_url'=> $cate_url, 'list'=> $list_arr);
}
defined('FRAME_PATH') || exit;

/**
 * 内容属性列表模块
 * @param int flag 属性ID (默认为0) [0=图片 1=推荐 2=热点 3=头条 4=精选 5=幻灯]
 * @param int cid 分类ID 如果不填：自动识别 (不推荐用于读取频道分类，影响性能)
 * @param int mid 模型ID (当cid为0时，设置mid才能生效，否则程序自动识别)
 * @param string dateformat 时间格式
 * @param int titlenum 标题长度
 * @param int intronum 简介长度
 * @param string orderby 排序方式
 * @param int orderway 降序(-1),升序(1)
 * @param int start 开始位置
 * @param int limit 显示几条
 * @return array
 */
function kp_block_list_flag($conf) {
	global $run;




	$flag = _int($conf, 'flag');
	$cid = isset($conf['cid']) ? intval($conf['cid']) : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
	$mid = _int($conf, 'mid', 2);
	$dateformat = empty($conf['dateformat']) ? 'Y-m-d H:i:s' : $conf['dateformat'];
	$titlenum = _int($conf, 'titlenum');
	$intronum = _int($conf, 'intronum');
	$orderway = isset($conf['orderway']) && $conf['orderway'] == 1 ? 1 : -1;
	$start = _int($conf, 'start');
	$limit = _int($conf, 'limit', 10);

	// 读取分类内容
	if($cid == 0) {
		$table_arr = &$run->_cfg['table_arr'];
		$table = isset($table_arr[$mid]) ? $table_arr[$mid] : 'article';

		$where = array('flag' => $flag);
	}else{
		$cate_arr = $run->category->get_cache($cid);
		$table = &$cate_arr['table'];

		if(!empty($cate_arr['son_cids']) && is_array($cate_arr['son_cids'])) {
			$where = array('flag' => $flag, 'cid' => array("IN" => $cate_arr['son_cids'])); // 影响数据库性能
		}else{
			$where = array('flag' => $flag, 'cid' => $cid);
		}
	}

	// 初始模型表名
	$run->cms_content_flag->table = 'cms_'.$table.'_flag';

	// 读取内容列表
	$key_arr = $run->cms_content_flag->find_fetch($where, array('id' => $orderway), $start, $limit);
	$keys = array();
	foreach($key_arr as $v) {
		$keys[] = $v['id'];
	}

	// 读取内容列表
	$run->cms_content->table = 'cms_'.$table;
	$list_arr = $run->cms_content->mget($keys);
	foreach($list_arr as &$v) {
		$run->cms_content->format($v, $mid, $dateformat, $titlenum, $intronum);
	}




	return array('list'=> $list_arr);
}
defined('FRAME_PATH') || exit;

/**
 * 遍历内容列表模块
 * @param int cid 频道分类ID 如果不填：自动识别
 * @param int mid 模型ID (当cid为0时，设置mid才能生效，否则程序自动识别)
 * @param string dateformat 时间格式
 * @param int titlenum 标题长度
 * @param int intronum 简介长度
 * @param string orderby 排序方式
 * @param int orderway 降序(-1),升序(1) 默认：-1
 * @param int limit 显示几条
 * @return array
 */
function kp_block_listeach($conf) {
	global $run;




	$cid = isset($conf['cid']) ? intval($conf['cid']) : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
	$mid = _int($conf, 'mid', 2);
	$dateformat = empty($conf['dateformat']) ? 'Y-m-d H:i:s' : $conf['dateformat'];
	$titlenum = _int($conf, 'titlenum');
	$intronum = _int($conf, 'intronum');
	$orderby = isset($conf['orderby']) && in_array($conf['orderby'], array('id', 'dateline')) ? $conf['orderby'] : 'id';
	$orderway = isset($conf['orderway']) && $conf['orderway'] == 1 ? 1 : -1;
	$limit = _int($conf, 'limit', 10);

	if($cid == 0) {
		$cid_arr = $run->category->get_cids_by_mid($mid);

		$table_arr = &$run->_cfg['table_arr'];
		$table = isset($table_arr[$mid]) ? $table_arr[$mid] : 'article';
	}else{
		$_var = $run->category->get_cache($cid);
		if(isset($_var['son_list'])) {
			$cid_arr = $_var['son_list'];
			$table = $_var['table'];
		}else{
			return array();
		}
	}

	// 初始模型表名
	$run->cms_content->table = 'cms_'.$table;

	// 读取内容列表
	$ret = array();
	foreach($cid_arr as $_cid => $cids) {
		// 读取分类内容
		$cate_arr = $run->category->get_cache($_cid);
		$ret[$_cid]['cate_name'] = $cate_arr['name'];
		$ret[$_cid]['cate_url'] = $run->category->category_url($cate_arr['cid'], $cate_arr['alias']);

		if(!$cids) continue;

		// 读取分类列表
		if(is_array($cids)) {
			$where = array('cid' => array("IN" => $cids)); // 影响数据库性能，不推荐这样建分类
		}else{
			$where = array('cid' => $_cid);
		}

		$ret[$_cid]['list'] = $run->cms_content->find_fetch($where, array($orderby => $orderway), 0, $limit);
		foreach($ret[$_cid]['list'] as &$v) {
			$run->cms_content->format($v, $mid, $dateformat, $titlenum, $intronum);
		}
	}




	return $ret;
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


	<!--banner开始-->
	<div class="banner">
		<ul>
			<li><a href="www.gfcms.com" title="GFCMS官方网站" target="_blank"><img src="http://wdt.my.com/tpl/home/default/img/banner/b1.jpg" alt="GFCMS官方网站"></a></li>
			<li><a href="www.gfcms.com" title="GFCMS官方网站" target="_blank"><img src="http://wdt.my.com/tpl/home/default/img/banner/b2.jpg" alt="GFCMS官方网站"></a></li>
			<li><a href="www.gfcms.com" title="GFCMS官方网站" target="_blank"><img src="http://wdt.my.com/tpl/home/default/img/banner/b3.jpg" alt="GFCMS官方网站"></a></li>
		</ul>
	</div>
	<!--banner结束-->

	<!--两列开始-->
	<div class="cont cf">
		<div class="c2_l b1_top">
			<div class="b1_tit">
				<div class="ct_p"></div>
				<div class="ct_c">程序简介</div>
				<div class="ct_n"></div>
				<a class="more" href="">更多</a>
			</div>
			<div class="b1_cont">
				<div class="info_l"><img src="http://wdt.my.com/tpl/home/default/img/brief.jpg"></div>
				<div class="info_r">
					<p>GFCMS由中国首家网络营销策划机构《功夫科技》推出的一款亿级负载的轻量级CMS（根据官方测试，负载能力远超discuz、phpwind等论坛程序）。程序采用面向对象(OOP)+面向切面(AOP)的设计思想，基于MVC设计模式开发。</p>
					<p>GFCMS的特点：1.体积小功能强; 2.速度快性能高; 3.高安全够稳定; 4.完全符合SEO; 5.好插件扩展强; 6.用户体验良好; 7.模板引擎易用</p>
				</div>
			</div>
		</div>

		<?php $data = kp_block_list(array (
  'mid' => '4',
  'limit' => '2',
  'orderby' => 'time',
  'titlenum' => '28',
)); ?>
		<div class="c2_r b1_top">
			<div class="b1_tit">
				<a class="more" href="<?php echo(isset($data['cate_url']) ? $data['cate_url'] : ''); ?>">更多</a>
				<div class="ct_p"></div>
				<div class="ct_c">客户案例</div>
				<div class="ct_n"></div>
			</div>
			<div class="b1_cont caselist">
				<?php if(isset($data['list']) && is_array($data['list'])) { foreach($data['list'] as &$v) { ?>
				<p><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>"><img src="<?php echo(isset($v['pic']) ? $v['pic'] : ''); ?>"><b><?php echo(isset($v['subject']) ? $v['subject'] : ''); ?></b><i><?php echo(isset($v['intro']) ? $v['intro'] : ''); ?></i></a></p>
				<?php }} ?>
			</div>
		</div>
		<?php unset($data); ?>
	</div>
	<!--两列结束-->

	<!--产品开始-->
	<?php $data = kp_block_list(array (
  'mid' => '3',
  'limit' => '10',
  'orderby' => 'time',
  'titlenum' => '28',
)); ?>
	<div class="cont cf">
		<h2 class="b2_tit">
			<a class="more" href="<?php echo(isset($data['cate_url']) ? $data['cate_url'] : ''); ?>">更多</a>
			<b>产品展示</b>
		</h2>
		<div class="b2_cont piclist">
			<a class="p_prev" href="javascript:;"></a>
			<div class="p_cont">
				<ul class="cf">
					<?php if(isset($data['list']) && is_array($data['list'])) { foreach($data['list'] as &$v) { ?>
					<li><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" title="<?php echo(isset($v['title']) ? $v['title'] : ''); ?>&#10;发表于:<?php echo(isset($v['date']) ? $v['date'] : ''); ?>" target="_blank"><img src="<?php echo(isset($v['pic']) ? $v['pic'] : ''); ?>" /><u><?php echo(isset($v['subject']) ? $v['subject'] : ''); ?></u></a></li>
					<?php }} ?>
				</ul>
			</div>
			<a class="p_next" href="javascript:;"></a>
		</div>
	</div>
	<?php unset($data); ?>
	<!--产品结束-->

	<!--三列开始-->
	<div class="cont c3">
		<div class="c3_m cf">
			<?php $data = kp_block_list_flag(array (
  'flag' => '1',
  'limit' => '8',
  'orderby' => 'time',
  'titlenum' => '28',
)); ?>
			<div class="c3_l">
				<h2 class="b2_tit">
					<b>推荐</b>
				</h2>
				<ul class="b2_cont lists cf">
					<?php if(isset($data['list']) && is_array($data['list'])) { foreach($data['list'] as &$v) { ?><li><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" title="<?php echo(isset($v['title']) ? $v['title'] : ''); ?>&#10;发表于:<?php echo(isset($v['date']) ? $v['date'] : ''); ?>" target="_blank"><?php echo(isset($v['subject']) ? $v['subject'] : ''); ?></a></li><?php }} ?>
				</ul>
			</div>
			<?php unset($data); ?>

			<?php $data = kp_block_list_flag(array (
  'flag' => '2',
  'limit' => '8',
  'orderby' => 'time',
  'titlenum' => '28',
)); ?>
			<div class="c3_l">
				<h2 class="b2_tit">
					<b>热点</b>
				</h2>
				<ul class="b2_cont lists cf">
					<?php if(isset($data['list']) && is_array($data['list'])) { foreach($data['list'] as &$v) { ?><li><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" title="<?php echo(isset($v['title']) ? $v['title'] : ''); ?>&#10;发表于:<?php echo(isset($v['date']) ? $v['date'] : ''); ?>" target="_blank"><?php echo(isset($v['subject']) ? $v['subject'] : ''); ?></a></li><?php }} ?>
				</ul>
			</div>
			<?php unset($data); ?>

			<?php $data = kp_block_list_flag(array (
  'flag' => '3',
  'limit' => '8',
  'orderby' => 'time',
  'titlenum' => '28',
)); ?>
			<div class="c3_l">
				<h2 class="b2_tit">
					<b>头条</b>
				</h2>
				<ul class="b2_cont lists cf">
					<?php if(isset($data['list']) && is_array($data['list'])) { foreach($data['list'] as &$v) { ?><li><a href="<?php echo(isset($v['url']) ? $v['url'] : ''); ?>" title="<?php echo(isset($v['title']) ? $v['title'] : ''); ?>&#10;发表于:<?php echo(isset($v['date']) ? $v['date'] : ''); ?>" target="_blank"><?php echo(isset($v['subject']) ? $v['subject'] : ''); ?></a></li><?php }} ?>
				</ul>
			</div>
			<?php unset($data); ?>

			<?php $data = kp_block_listeach(array (
  'limit' => '8',
)); ?>
				<?php if(isset($data) && is_array($data)) { foreach($data as &$v) { ?>
				<div class="c3_l">
					<h2 class="b2_tit">
						<a class="more" href="<?php echo(isset($v['cate_url']) ? $v['cate_url'] : ''); ?>">更多</a>
						<b><?php echo(isset($v['cate_name']) ? $v['cate_name'] : ''); ?></b>
					</h2>
					<ul class="b2_cont lists cf">
						<?php if(isset($v['list']) && is_array($v['list'])) { foreach($v['list'] as &$lv) { ?>
						<li><span><?php echo(isset($lv['date']) ? $lv['date'] : ''); ?></span><a href="<?php echo(isset($lv['url']) ? $lv['url'] : ''); ?>" target="_blank"><?php echo(isset($lv['subject']) ? $lv['subject'] : ''); ?></a></li>
						<?php }} ?>
					</ul>
				</div>
				<?php }} ?>
			<?php unset($data); ?>
		</div>
	</div>
	<!--三列结束-->

	<!--友情链接开始-->
	
	<!--友情链接结束-->


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
