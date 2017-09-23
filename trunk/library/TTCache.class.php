<?php
/*
 *缓存Memcache
*/
class TTCache{
	/*设置缓存*/
	public function set($name,$value,$expire=0){
		$name = TMConfig::get('tams_id')."_".$name;
		return TMMemCacheMgr::getInstance()->set($name, $value, $expire);
	}
	/*获取缓存*/
	public function get($name){
		$name =TMConfig::get('tams_id')."_".$name;
		return TMMemCacheMgr::getInstance()->get($name);
	}
}
?>