<?php

/**
 *运用程序 计数服务
 *@Author yanghui
 *@package system
 *@version 1.0
*/
class TTCounter{
	const IS_JOIN					= 1; //用户是否已经在页面登陆过
	const JOINS						= 2; //活动参与人数
	const SCORE						= 3; //总金豆积分
	const UPLOAD					= 5; //上传
	const GET_SCORE					= 6; //每天获得积分上限
	const VOTE						= 7; //投票
	const SHARE_VIDEO				= 8; //分享视频
	const AWARD						= 9; //中奖限制
	const WEIXIN_SCORE				= 10; //微信积分
	const WEIXIN_AWARD				= 11; //微信是否获奖
	const IS_CHECKIN				= 12; //签到
	const LOGIN_IS_REGISTER			= 13; //登录即注册
	const ACT_VOTE_DAY				= 14; //获积分每天活动点赞次数
	const ACT_VOTE					= 15; //活动点赞
	const ACT_SHARE_DAY				= 16; //获积分每天活动分享次数
	const ACT_SHARE					= 17; //活动分享
	const ACT_NOTICE				= 18; //活动分享
	const ACT_ADD_ADDRESS			= 19;//用户填写地址计数器
	const CHECKIN					= 20; //签到总数
	const USER_RECORD_ACT			= 21;//用户填写地址计数器
	const SCORE_DAY					= 22; //每日金豆积分
	const LOGIN_IS_REGISTER_DAY		= 23; //登录即注册每天更新
	const USER_CHECKIN_DAYS			= 24; //用户总签到天数
	const IS_QRCODE_SCORE			= 25; //二维码分享积分
	const QR_LIMIT_STR_SCENE		= 26; //微信永久二维码上限
	const IS_SUBSCRIBE_SCORE		= 27; //是否关注加分
	const GAME_DAY_SCORE			= 28; //游戏每天加分上限
	const USER_COMMENT				= 29; //用户每天评论总计数器
	const USER_COMMENT_ADD_SCORE	= 30; //用户每天评论加金豆计数器
	const SCORE_DETAIL_CHANGE		= 31; //是否采用最新金豆流水记录方式
	const UNIONID_CHECK				= 32; //微信Unionid检查
	
	const USER_DAY_ADD_ACTS			= 33; //用户每天参与活动加金豆活动数计数器
	const USER_DAY_ADD_FORACT		= 34; //用户当天是否参与该活动计数器

	const WX_USER_INDEX_SHARE		= 35;//微信端用户分享首页次数
	const WX_USER_DETAIL_SHARE		= 36;//微信端用户分享首页次数

	const ACT_VOTE_DAY_LIMIT		= 37; //每天活动点赞总次数
	const WX_USER_INFO_DAY			= 38; //微信用户资料每天更新
	
	
	/*是否是线上*/
	public static function getIsOnline(){
		return TMEnvConfig::getEnv() != 'test';
	
	}
	/*基础型 每天的计数器查询*/
	public static function dayQuery($counter_id, $key = '10000', $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterQuery($counter_id, $key, $extra_key);
	
	}
	/*基础型 总数的计数器查询*/
	public static function allQuery($counter_id, $key = '10000', $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterQuery($counter_id, $key, $extra_key);
	
	}
	/*这里每天的计数器封装方法少了个参数，不会返回当前值*/
	/*基础型 每天的计数器增加*/
	public static function dayAdd($counter_id, $key = '10000', $add_val = 1, $strict_type = 0, $strict_val = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterAdd($counter_id, $key, $add_val, $strict_type, $strict_val);
	
	}

	/**
	 * <p>Title: 基础型 总数的计数器增加</p>
	 * <p>Description: </p>
	 * @param unknown $counter_id	计数器类型id（范围1-100）,101-200属于sdk内部使用
	 * @param string $key			计数主键值，int类型，一般为QQ号码
	 * @param number $add_val		增加值(为负值，表示减计数)
	 * @param number $strict_type	不是必填项，限制类型：
									0: TaeCounterService::STRICT_NONE 表示无限制，此时strict_val无意义；
									1: TaeCounterService::STRICT_MAX  表示最大值限制，此时strict_val是允许的最大值，如为减计数（即$add_val<0），则表示最小值限制，此时strcit_val是允许的最小值。
	 * @param number $strict_val	不是必填项，限制值：根据strict_type的取值做不同解释
	 * @return Ambigous <multitype:, multitype:NULL number string >
	 */
	public static function allAdd($counter_id, $key = '10000', $add_val = 1, $strict_type = 0, $strict_val = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterAdd($counter_id, $key, $add_val, 0, $strict_type, $strict_val);
	
	}
	/*基础型 当天计数设置计数*/
	public static function daySet($counter_id, $key = '10000', $set_val){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterSet($counter_id, $key, $set_val);
	
	}
	/*基础型 总数计数设置计数*/
	public static function allSet($counter_id, $key = '10000', $set_val, $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterSet($counter_id, $key, $set_val, $extra_key);
	
	}
	
	/*扩展型 每天的计数器查询*/
	public static function dayQueryExt($counter_id, $key = '10000', $extra_strkey = '', $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterQueryExt($counter_id, $key, $extra_key, $extra_strkey . '');
	
	}
	/**
	 * <p>Title: 扩展型 总数计数器查询</p>
	 * <p>Description: 查询通用计数（增加16字节的字符串作为扩展key字段）</p>
	 * @param unknown $counter_id 计数器类型id（范围1-100）,101-200属于sdk内部使用
	 * @param string $key 计数主键值，int类型，一般为QQ号码
	 * @param string $extra_strkey 不是必填项，string类型，附加key，默认为空；最大16个字节
	 * @param number $extra_key 不是必填项，int类型，附加key，默认为0
	 * @return Ambigous <multitype:, multitype:NULL number string >
	 */
	public static function allQueryExt($counter_id, $key = '10000', $extra_strkey = '', $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterQueryExt($counter_id, $key, $extra_key, $extra_strkey . '');
	
	}
	/*扩展型 每天的计数器增加*/
	//strict_type 0:无限制 strict_val无意义  1:限制最大值 strict_val有意义
	public static function dayAddExt($counter_id, $key = '10000', $extra_strkey = '', $add_val = 1, $strict_type = 0, $strict_val = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterAddExt($counter_id, $key, $add_val, $extra_strkey . '', $strict_type, $strict_val);
	
	}
	/*扩展型 总数计数器增加*/
	public static function allAddExt($counter_id, $key = '10000', $extra_strkey, $add_val = 1, $strict_type = 0, $strict_val = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterAddExt($counter_id, $key, $add_val, $extra_key, $extra_strkey . '', $strict_type, $strict_val);
	
	}
	/*扩展型 当天计数设置计数 */
	public static function daySetExt($counter_id, $key = '10000', $set_val, $extra_strkey = ""){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::dayCounterSetExt($counter_id, $key, $set_val, $extra_strkey . '');
	
	}
	/*扩展型 总数计数设置计数*/
	public static function allSetExt($counter_id, $key = '10000', $set_val, $extra_strkey = "", $extra_key = 0){
		//针对微信号的映射处理
		if(!preg_match("/^\d+$/", $key)){
			$key = TTWeixin::getMapKey($key);
		}
		return TaeCounterService::counterSetExt($counter_id, $key, $set_val, $extra_key, $extra_strkey . '');
	
	}


}