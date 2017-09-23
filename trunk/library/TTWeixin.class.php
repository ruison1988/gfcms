<?php
//微信
/**
 * ——————————————————————————————————————————————————————————-
 * 请认真检查APPID以及APPSECRET是否正确
 * ———————————————————————————————————————————————————————————
 */
class TTWeixin{
	//风铃微信测试号
	const APPID_TEST = 'wx96d7d50ef0cf7847';
	const APPSECRET_TEST = '0e1bc213b2c82420f2b284185736d919';
	const CURURL_TEST = 'http://s-test.act.qq.com';
	//天天中大奖
	const APPID = 'wxfd1da3e08ff7df17';
	const APPSECRET = 'c9403133b2a877e20babbbfd35b22de3';
	const CURURL = 'http://ttzdj.qq.com';
	
	const TOKEN = '4d373eef08ff5026';
	const NEED_INFO = true; //需要用户信息
	const LOGIN_SAVE = true; //登录即保存
	public static $debug = true;
	private static $debugMsg = '';
	private static $utype = 0; // 2为微信浏览器 3为微信服务器
	//wechat实例
	private static $_wechat;
	private static $_curUserInfo = array();
	
	public static function getAppId(){
		return TMEnvConfig::getEnv() != 'production' ? self::APPID_TEST : self::APPID;
	}
	public static function getAppSecret(){
		return TMEnvConfig::getEnv() != 'production' ? self::APPSECRET_TEST : self::APPSECRET;
	}
	public static function getCurUrl(){
		return TMEnvConfig::getEnv() != 'production' ? self::CURURL_TEST : self::CURURL;
	}
	public static function getDebugInfo(){
		return self::$debug ? self::$debugMsg : '';
	}
	//获取wechat实例
	public static function getWechat(){
		if(!self::$_wechat) self::$_wechat = new YYWechat(array(
				'token' => self::TOKEN,
				'appid' => self::getAppId(),
				'appsecret' => self::getAppSecret()
		));
		return self::$_wechat;
	
	}

	public static function getJsapiConfig($url){
		//$ticket = self::getWechat()->getJsApiTicket(false);
		$ticket = self::getWechat()->getJsTicket();
		if(!$ticket) return false;
		$timestamp = time();
		$nonceStr = self::createNonceStr();
		$url = $url ? $url : self::curUrl();
		$str = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$arr = array();
		$arr['signature'] = sha1($str);
		$arr['appId'] = self::getAppId();
		$arr['timestamp'] = $timestamp;
		$arr['nonceStr'] = $nonceStr;
		return $arr;
	
	}

	private function createNonceStr($length = 16){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for($i = 0; $i < $length; $i++){
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	
	}
	//获取当前用户UIN，没有授权则返回跳转授权地址
	public static function checkLogin($save = NULL, $info = NULL, $callback = NULL){
		$save = $save === NULL ? self::LOGIN_SAVE : $save;
		$info = $info === NULL ? self::NEED_INFO : $info;
		$ret = self::user($save, $info);
		if($ret['code'] != 0){
			self::$debugMsg = $ret['message']; 
			//没有openid则返回授权地址去授权
			if(!self::$_curUserInfo['FOpenId']){
				//获取授权地址，拼接上当前方法的地址作为回流。（默认用获取用户信息的授权方式）
				$url = self::curUrl($callback);
				return self::getWechat()->getOauthRedirect($url, '', self::NEED_INFO ? 'snsapi_userinfo' : 'snsapi_base');
			}
		}
		return $ret;
	
	}
	//设置是否来自微信 2为微信浏览器 3为微信服务器
	public static function setUType($type){
		self::$utype = $type;
	}
	//是否来自微信  2为微信浏览器 3为微信服务器
	public static function getUType(){
		//判断接口的来源端
		if(TTUtil::getInput('from') == 2){
			self::$utype = 2;
		}
		//本地测试通过改变浏览器 user agent 来模拟微信端
		else if(!self::$utype && !strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'micromessenger') === false){
			self::$utype = 2;
		}
		return self::$utype;
	
	}
	//获取当前链接  用户授权完毕的以后的回调地址，获取到code以后的传入地址
	public static function curUrl($callback = NULL){
		$pageURL = 'http';
		if($_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		$callback = $callback ? $callback : $_SERVER["REQUEST_URI"];
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $callback;
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"] . $callback;
		}
		return $pageURL;
	
	}
	//获取当前用户信息
	public static function user($save = false, $info = false){
		//已经认证，直接返回
		if(isset(self::$_curUserInfo['FOpenId'])) return array('code'=>0,'message'=>'登录成功！');
		//cookie判断
		$ret = self::getUserByCookie($info);
		if($ret) return array('code'=>0,'message'=>'登录成功！');
		//认证方式获取
		$ret = self::getUserByOauth($save, $info);
		return $ret;
	
	}
	//获取当前用户id
	public static function getCurUserid(){
		$ret = self::user();
		if($ret['code'] != 0) return 0;
		return self::$_curUserInfo['FMapKey'];
	
	}
	//获取XML中的openid
	public static function getUserByXML(){
		//已经认证，直接返回
		if(isset(self::$_curUserInfo['FOpenId'])) return self::$_curUserInfo;
		//从XML中获取openid
		$openid = self::getWechat()->getRevFrom();
		$openid = $openid ? $openid : '';
		//判断openid的合法性
		if(!$openid || !self::isOpenid($openid)) return false;
		$mapkey = self::getMapKey($openid);
		self::$_curUserInfo = array(
				'FMapKey' => $mapkey,
				'FOpenId' => $openid
		);
		
		return self::$_curUserInfo;
	
	}
	//判断是否是合法openid
	public static function isOpenid($openid){
		return preg_match("/^[0-9a-zA-Z_\-]{26,32}$/", $openid) ? true : false;
	
	}
	/*获取openid的映射值*/
	public static function getMapKey($openid = ''){
		//if(TMEnvConfig::getEnv() == 'test') return '11111';
		$tm = new TMService();
		TaeCore::taeInit(TaeConstants::UIN, '888888');
		$mapKey = '';
		$openid = $openid ? $openid : self::$_curUserInfo['FOpenId'];
		if(!$openid) return '10000';
		$mapKey = TaeAccountMappingService::batchQuery(array(
				0 => $openid
		), TaeAccountMappingService::WX_ACCOUNT, 1);
		if(!empty($mapKey) && $mapKey['retcode'] == 0){
			$mapKey = array_values($mapKey['account_mapping_list']);
			$mapKey = $mapKey[0];
		}else{
			return '10000';
		}
		return $mapKey . '';
	
	}
	
}