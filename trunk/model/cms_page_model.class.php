<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class cms_page extends model {
	function __construct() {
		$this->table = 'cms_page';	// 表名
		$this->pri = array('cid');	// 主键
	}
}
