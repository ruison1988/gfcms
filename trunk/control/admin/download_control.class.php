<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class download_control extends admin_control {

	public function index() {
		$dir = R('theme');
		if(empty($dir)) $this->installTips('主题目录名不能为空！');
		if(preg_match('/\W/', $dir)) $this->installTips('主题目录名不正确！');
		$file = HOM_PATH.$dir;
		$zipFile = HOM_PATH.$dir.'.zip';
		if(!is_file($zipFile)) {
			kp_zip::zip($file, $zipFile);
        }
		header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=".basename($zipFile));
        readfile($zipFile);
        exit;
	}

}
