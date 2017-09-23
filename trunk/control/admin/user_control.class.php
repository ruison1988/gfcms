<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class user_control extends admin_control {
	// 用户管理
	public function index() {
		// hook admin_user_control_index_after.php
		$this->display();
	}

	// hook admin_user_control_after.php
}
