<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class cms_content_data extends model {
	function __construct() {
		$this->table = '';			// 表名 (可以是 cms_article_data cms_product_data cms_photo_data 等)
		$this->pri = array('id');	// 主键
		$this->maxid = 'id';		// 自增字段
	}
}
