<?php
/**
 * (C)2012-2016 gfcms.com GongFu Co.ltd.
 * Author: RickSun <442352433@qq.com>
 */

defined('ROOT_PATH') or exit;

class links_control extends admin_control{
	// 管理链接
	public function index() {
		$links = $this->kv->xget('gf_links');
		$this->assign('links', $links);

		$this->display();
	}

	// 增加/编辑链接
	public function set() {
		$name = htmlspecialchars(R('name', 'P'));
		$url = htmlspecialchars(R('url', 'P'));
		isset($_POST['key']) && $key = (int) R('key', 'P');

		!$name && E(1, '网站名称不能为空！', 'name');
		!$url && E(1, '网站 URL不能为空！', 'url');

		$arr = $this->kv->xget('gf_links');
		$row = array('name' => $name, 'url' => $url);

		// key 有值为编辑
		if(isset($key)) {
			$arr[$key] = $row;
			$this->kv->set('gf_links', $arr);
			E(0, '保存成功！');
		}else{
			$arr[] = $row;
			$this->kv->set('gf_links', $arr);
			end($arr);
			$key = key($arr);
			E(0, '保存成功！', $key);
		}
	}

	// 删除链接
	public function del() {
		$key = (int) R('key', 'P');

		$arr = $this->kv->xget('gf_links');
		unset($arr[$key]);
		$this->kv->set('gf_links', $arr);

		E(0, '删除完成！');
	}

	// 链接排序
	public function sort() {
		$keys = R('keys', 'P');

		$arr = $this->kv->xget('gf_links');
		if(!empty($keys) && is_array($keys)) {
			$newarr = array();
			foreach($keys as $k) {
				strlen($k) && $newarr[] = $arr[$k];
			}
			$this->kv->set('gf_links', $newarr);
		}

		E(0, '修改排序完成！');
	}
}
