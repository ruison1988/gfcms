<?php
/**
 * 微博组件
 *@author  YangHui
 *@package ApplicationComponent
*/
class TTBlog{
	protected static $_blog_name = '';//微博账号
	protected static $_blog_key  = '';//app_id
	protected static $_blog_password = '';//app_password
	protected static $_param     = array();
	/*返回提示信息*/
	public static function getCommonMsg($retCode) {
		$msg = array(
			1 => '温馨提示：微博内容字数超过限制！',
			2 => '温馨提示：您发送微博的频率过快，请稍后再试！',
			3 => '温馨提示：您未开通微博，请先开通微博！',
			4 => '服务器内部错误',
			5 => '温馨提示：尚未登录！',
			6 => '温馨提示：您未开通微博，请先开通微博！',
			7 => '网络繁忙，请稍后再试！',
			'101' => '温馨提示：该账号未实名认证',
			'102' => '温馨提示：图片错误',
			'103' => '温馨提示：微博发送失败，可能是频率过快或内容包含敏感字！',
			);
        return $msg[$retCode] ? $msg[$retCode] : '温馨提示：发送微博失败，可能是您尚未开通微博或服务器繁忙！';
    }
	/*返回错误信息*/
	public static function getErrMsg($innerErrCode)  {
        $msg = array(
			4  => '温馨提示：您发送的微博内容中存在过多脏话！',
			5  => '禁止访问',
			6  => '微博不存在',
			7  => '温馨提示：您发送的微博字数过长！',
			9  => '温馨提示：您发送的内容包含垃圾信息：广告，恶意链接、黑名单号码等！',
			10 => '温馨提示：您发送微博的频率过快，请稍后再试！',
			11 => '温馨提示：源消息已被删除！',
			12 => '温馨提示：源消息审核中！',
			13 => '温馨提示：您不能发送重复内容的微博！');
        return $msg[$innerErrCode] ? $msg[$innerErrCode] : '温馨提示：发送微博失败，可能是您尚未开通微博或服务器繁忙！';
    }
	/*
	 *是否有微博
	 *@return bool 是否有微博
	*/
	public static function hasBlog(){
		if(TMEnvConfig::getEnv() == 'test') return true;
		$url = 'user/info';
		$param = array();
		$param['format'] = 'json';
		$param['appid'] = self::$_blog_key;
		$param['app_password'] = self::$_blog_password;
		$res = json_decode(TaeCore::weiboCall($url, $param),true);
		return $res['data']['name'] ? true : false;
	}
	/*
	 *获取微博个人信息
	 *@return array 返回的个人信息
	*/
	public static function userInfo(){
		if(TMEnvConfig::getEnv() == 'test'){
			return array(
				'nick'=>'一个很长的昵称好多个字',
				'name'=>'your_account',
				'head'=>'http://app.qlogo.cn/mbloghead/8df62426cf2873508634'
			);
		}
		$url = 'user/info';
		$param = array();
		$param['format'] = 'json';
		$param['appid'] = self::$_blog_key;
		$param['app_password'] = self::$_blog_password;
		$res = json_decode(TaeCore::weiboCall($url, $param),true);
		return $res['data'];
	}
	/*
	 *收听微博
	*/
	public static function listenBlog($name = NULL){
		if(TMEnvConfig::getEnv() == 'test'){
			return array('code'=>0,'message'=>'温馨提示：收听微博成功！');
		}
		if(!self::hasBlog()){
			return array('code'=>-200,'温馨提示：您尚未开通微博！');
		}
		$url = 'friends/add';
		$param = array();
		$param['name'] = $name ? $name : self::$_blog_name;
		TTUtil::track(6000133);
		$res = self::send($url,$param,true);
		if($res['code']!=0){
			return array('code'=>0,'message'=>'温馨提示：您已收听过该微博！');
		}
		TTUtil::track(6000134);
		return array('code'=>0,'message'=>'温馨提示：收听微博成功！');
	}
	/*
	 *是否已经收听该微博
	 *@return bool 是否已经收听该微博
	*/
	public static function isListened(){
		if(TMEnvConfig::getEnv() == 'test'){
			return true;
		}
		if(!self::hasBlog()) return false;
		$url = 'friends/check';
		$param = array();
		$param['names'] = self::$_blog_name;
		$param['flag']  = 1;
		$res = self::send($url,$param);
		return $res;
	}
	/*
	 *发送微博(文本)
	 *@param string $content 需要发送的文本内容
	*/
	public static function sendText($content){
		if(TMEnvConfig::getEnv() == 'test'){
			if(!$content) return array('code'=>-95,'message'=>'温馨提示：没有填写微博内容！');
			return array(
				'code'=>0,'message'=>'温馨提示：发送微博成功！',
				'data'=>array('id'=>'278757090131854')
			);
		}
		$url = 't/add';
		$param = array();
		$param['content'] = $content;
		$param['clientip'] = TMUtil::getClientIp();
		TTUtil::track(6000131);
		$res = self::send($url,$param,true);
		if($res['code']!=0){
			return $res;
		}else{
			self::recordHistory($content);
		}
		TTUtil::track(6000132);
		return array('code'=>0,'message'=>'温馨提示：发送微博成功！','data'=>array('id'=>$res['id']));
	}
	//记前15条分享记录
	private static function recordHistory($content,$pic=''){
		$max = 15;
		$counterId = 79;
		$ret = TaeCounterService::dayCounterAdd($counterId,TTUtil::getQQ(),1,TaeCounterService::STRICT_MAX,$max);
		if($ret['retcode'] == '0'){
			$row = array(
				'FDesId' => TTUtil::getQQ(),
				'FTitle' => $content,
				'FNick' => $pic,
				'FTime' => date('Y-m-d H:i:s'),
				'FDate' => date('Y-m-d'),
				'FMemo' => TMUtil::getClientIp(),
			);
			TTDB::insert('Tbl_Comment',$row);
		}
	}
	/*
	 * 发送本地图片
	 *@param string $contetn 发送的内容
	 *@param string $pic 本地图片路径（完整），以@开头
	*/
	public static function sendPic($content,$pic){
		if(TMEnvConfig::getEnv() == 'test'){
			if(!$content) return array('code'=>-96,'message'=>'温馨提示：没有填写微博内容！');
			return array(
				'code'=>0,'message'=>'温馨提示：发送微博成功！',
				'data'=>array('id'=>'278757090131854')
			);
		}
		$url = 't/add_pic';
		$param = array();
		$param['content'] = $content;
		$param['clientip'] = TMUtil::getClientIp();
		$param['pic'] = $pic;
		TTUtil::track(6000131);
		$res = self::send($url,$param,true,true,false);
		if($res['code']!=0){
			return $res;
		}else{
			self::recordHistory($content,$pic);
		}
		TTUtil::track(6000132);
		return array('code'=>0,'message'=>'温馨提示：发送微博成功！','data'=>array('id'=>$res['id']));
	}
	/*
	 * 发送远程图片
	 *@param string $contetn 发送的内容
	 *@param string $pic URL
	*/
	public static function sendPicByUrl($content,$pic){
		if(TMEnvConfig::getEnv() == 'test'){
			if(!$content) return array('code'=>-95,'message'=>'温馨提示：没有填写微博内容！');
			return array(
				'code'=>0,'message'=>'温馨提示：发送微博成功！',
				'data'=>array('id'=>'278757090131854')
			);
		}
		$url = 't/add_pic_url';
		$param = array();
		$param['content'] = $content;
		$param['clientip'] = TMUtil::getClientIp();
		$param['pic_url'] = $pic;
		TTUtil::track(6000131);
		$res = self::send($url,$param,true,true);
		if($res['code']!=0){
			return $res;
		}else{
			self::recordHistory($content,$pic);
		}
		TTUtil::track(6000132);
		return array('code'=>0,'message'=>'温馨提示：发送微博成功！','data'=>array('id'=>$res['id']));
	}
	/*
	 * 分享视频，其中视频为视频的链接地址，weiboCall会自动解析
	 *@param string $contetn 发送的内容
	 *@param string $video URL
	*/
	public static function sendVideo($content,$video){
		if(TMEnvConfig::getEnv() == 'test'){
			if(!$content) return array('code'=>-95,'message'=>'温馨提示：没有填写微博内容！');
			return array(
				'code'=>0,'message'=>'温馨提示：发送微博成功！',
				'data'=>array('id'=>'278757090131854')
			);
		}
		$url = 't/add_video';
		$param = array();
		$param['content'] = $content;
		$param['clientip'] = TMUtil::getClientIp();
		$param['url'] = $video;
		TTUtil::track(6000131);
		$res = self::send($url,$param,true);
		if($res['code']!=0){
			return $res;
		}else{
			self::recordHistory($content,$video);
		}
		TTUtil::track(6000132);
		return array('code'=>0,'message'=>'温馨提示：发送微博成功！','data'=>array('id'=>$res['id']));
	}
	/*
	* 上传微博头像
	*@param string $pic 上传微博的头像(本地地址)
	*@notice $pic参数不需要加上@
	*/
	public static function updateHead($pic){
		$url = 'user/update_head';
		$param = array();
		$param['pic'] = '@'.$pic;
		$res = self::send($url,$param,true,true);
		return $res['code'] == 0 ? true : false;
	}
	
	//转发微博
	public static function shareBlog($id,$content){
		//return array('code'=>0,'message'=>'转播成功');
		if(TMEnvConfig::getEnv() == 'test'){
			if(!$content || !$id) return array('code'=>-95,'message'=>'内容缺失！');
			return array(
				'code'=>0,'message'=>'温馨提示：转播成功！',
				'data'=>array('id'=>'278757090131854')
			);
		}
		$url = 't/re_add';
		$param = array();
		$param['reid'] = $id;
		$param['content'] = $content;
		TTUtil::track(6000135);
		$res = self::send($url,$param,true,true);
		TTUtil::track(6000136);
		return $res;
	}
	/*
	 * 解析返回的结果
	 *@param array $res 解析发送请求的返回值
	*/
	protected static function parseRes($res){
		$info = json_decode($res,true);
		
		/*if (isset($info['errcode']) && $info['errcode'])
			throw new CException(CBase::_('blog',-1,$this->getErrMsg($info['errcode'])));*/
		if ($info['ret']){
			$info['ret'] = $info['ret'] == 4 ? ($info['errcode'] == 14 ? '101' : ($info['errcode'] == 49 ? '102' : '103' ) ) : $info['ret'];
			return array('code'=>-2,'message'=>self::getCommonMsg($info['ret']));
		}
		$ret = isset($info['data']) ? $info['data'] : $info;
		$ret['code'] = 0;
		return $ret;
	} 
	/*
	 * 发送请求，weiboCall
	 *@param string $url 微博开放接口url
	 *@param array $param 参数
	 *@param bool $isPost 是否是post方式，反之为get的方式
	 *@param bool $needArrayParam 是否需要传入array格式的参数
	 *@param bool $query 是否需要对参数转化为string
	 *@return array 返回send之后的结果
	*/
	protected static function send($url, $param = array(), $isPost = false, $needArrayParam = false , $query = false){
		$param['format'] = 'json';
		$param['busi_ip'] = $_SERVER['LOCAL_ADDR'];
		$param['clientip'] = $param['clientip'] ? $param['clientip'] : TMUtil::getClientIp();
		$param['busi_name'] = 'weibo_control';
		$param['app_password'] = self::$_blog_password;
		$param['appid'] = self::$_blog_key;
		if($query)
			$param = http_build_query($param);
		$res = TaeCore::weiboCall($url, $param,$isPost,$needArrayParam);
		return self::parseRes($res);
	}
	/*获取简单信息*/
	public static function get_simple_info($ids){
		//return array(array('mcount'=>5,'count'=>66,'id'=>'255284048170684'));
		if(TTUtil::getQQ() == 0)//未登录
		{
			TaeCore::taeInit(TaeConstants::UIN, '888888');//模拟登陆
		}
		$url = 't/list';
		$param = array();
		$param['ids'] = $ids;
		$res = self::send($url,$param,false);
		return isset($res['info']) ? $res['info'] : array();
		 
	}
	/*获取评论*/
	public static function get_friends($type,$startindex=0,$reqnum=20){
		$maps = array(
			'intimate' => 'friends/get_intimate_friends',//常用
			'fans'     => 'friends/fanslist',//粉丝
			'idols'    => 'friends/idollist',
			'mutual'   => 'friends/mutual_list',
		);
		if(!isset($maps[$type])) return array('code'=>-23,'message'=>'不存在该好友列表！');
		$url = $maps[$type];
		$param = array();
		$param['reqnum']     = $reqnum;
		$param['startindex'] = $startindex;
		if($type == 'mutual'){
			$userinfo = self::userInfo();
			$param['name'] = $userinfo['name'];
		}
		$res = self::send($url,$param,false);
		
		$ret = isset($res['info']) ? $res['info'] : array();
		$list = array();
		foreach($ret as $k => $v){
			$list[] = array('name'=>$v['name'],'nick'=>$v['nick'],'url'=>isset($v['head'])?$v['head']:$v['headurl']);
		}
		return array(
			'code' => 0,
			'message'  => 'success',
			'data' => $list,
			'page' => array(
				'hasnext' => isset($res['hasnext']) ? $res['hasnext'] : 1,
				'nextstartpos' => isset($res['nextstartpos']) ? $res['nextstartpos'] : 0,
				'type' => $type,
			)
		);
	}
}