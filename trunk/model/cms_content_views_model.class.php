<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class cms_content_views extends model {
	function __construct() {
		$this->table = '';			// 表名 (可以是 cms_article_views cms_product_views cms_photo_views 等)
		$this->pri = array('id');	// 主键
		$this->maxid = 'id';		// 自增字段
	}
}
