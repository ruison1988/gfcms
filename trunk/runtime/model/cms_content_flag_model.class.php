<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class cms_content_flag extends model {
	function __construct() {
		$this->table = '';					// 表名 (可以是 cms_article_flag cms_product_flag cms_photo_flag 等)
		$this->pri = array('flag', 'id');	// 主键
	}
}
