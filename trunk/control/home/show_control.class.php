<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class show_control extends control{
	public $_cfg = array();	// 全站参数
	public $_var = array();	// 内容页参数

	public function index() {
		// hook show_control_index_before.php

		$_GET['cid'] = (int)R('cid');
		$_GET['id'] = (int)R('id');
		//获取数据库对象变量(魔术方法动态加载方式)->获取字段内容(读缓存方式)
		$this->_var = $this->category->get_cache($_GET['cid']);
		/*
			Array(
				[cid] => 2,
				[mid] => 2,
				[type] => 0,
				[upid] => 0,
				[name] => '静态图',
				[alias] => 'jpg',
				[intro] => '',
				[cate_tpl] => 'article_list.htm',
				[show_tpl] => 'article_show.htm',
				[count] => 1,
				[orderby] => 0,
				[seo_title] => '',
				[seo_keywords] => '',
				[seo_description] => '',
				[place] => Array(
						[0] => Array(
								[cid] => 2
								[name] => '静态图',
								[url] => '/index.php?cate--cid-2.html',
							)

					)

				[topcid] => 2,
				[table] => 'article',
			)
		*/
		empty($this->_var) && core::error404();

		$this->_cfg = $this->runtime->xget();

		// 初始模型表名
		$this->cms_content->table = 'cms_'.$this->_var['table'];

		// 读取内容
		$_show = $this->cms_content->read($_GET['id']);
		if(empty($_show['cid']) || $_show['cid'] != $_GET['cid']) core::error404();

		// SEO 相关
		$this->_cfg['titles'] = $_show['title'].(empty($_show['seo_title']) ? '' : '/'.$_show['seo_title']);
		$this->_cfg['seo_keywords'] = empty($_show['seo_keywords']) ? $_show['title'] : $_show['seo_keywords'];
		$this->_cfg['seo_description'] = empty($_show['seo_description']) ? $_show['intro']: $_show['seo_description'];

		$this->assign('gf', $this->_cfg);
		$this->assign('gf_var', $this->_var);

		$GLOBALS['run'] = &$this;
		$GLOBALS['_show'] = &$_show;

		// hook show_control_index_after.php

		$_ENV['_theme'] = &$this->_cfg['theme'];
		$this->display($this->_var['show_tpl']);
	}

	// hook show_control_after.php
}
