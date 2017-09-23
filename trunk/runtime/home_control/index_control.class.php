<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */
defined('ROOT_PATH') or exit;

class index_control extends control{
	public $_cfg = array();	// 全站参数
	public $_var = array();	// 首页参数

	public function index() {



		$this->_cfg = $this->runtime->xget();
		$this->_cfg['titles'] = $this->_cfg['webname'].(empty($this->_cfg['seo_title']) ? '' : ' - '.$this->_cfg['seo_title']);
		$this->_var['topcid'] = 0;

		$this->assign('gf', $this->_cfg);
		$this->assign('gf_var', $this->_var);

		$GLOBALS['run'] = &$this;




		$_ENV['_theme'] = &$this->_cfg['theme'];
		$this->display();
	}



}
