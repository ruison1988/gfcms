<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class view_control extends control{
	public function index() {
		$_ENV['_config']['cache']['l2_cache'] = 0;

		$id = (int)R('id');
		$cid = (int)R('cid');

		$_var = $this->category->get_cache($cid);
		empty($_var) && core::error404();

		$mviews = &$this->models->cms_content_views;
		$mviews->table = 'cms_'.$_var['table'].'_views';

		$data = $mviews->get($id);
		if(!$data) $data = array('id'=>$id, 'cid'=>$cid, 'views'=>0);
		$data['views']++;
		echo 'var views='.$data['views'].';';
		$mviews->set($id, $data);
		exit;
	}
}
