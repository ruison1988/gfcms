<?php

class TTUtil{
	/*生成的配置信息*/
	protected static $project_configs = array();
	protected static $configs = array();
	protected static $post_data = array();
	protected static $userInfo = array();

	public static function test(){
		return 'BBBBBBBBBBB';
	
	}

	/**
	 * 初始化传入参数
	 */
	public static function setInput($arr){
		self::$post_data = array_merge(self::$post_data, $arr);
	
	}

	/**
	 * 获取参数
	 */
	public static function getInput($name){
		if(!isset(self::$post_data[$name]) || self::$post_data[$name] == ''){
			self::$post_data[$name] = '';
		}
		return self::$post_data[$name] ? self::$post_data[$name] : '';
	
	}

	/**
	 *获取任意config/目录下面的*.yml配置
	 *@param string $); 配置项键值
	 *@return mix 配置
	 *@example cfg('project.file_upload_max_day') 将返回 int(5)
	*/
	public static function cfg($name, $default = NULL){
		if(!preg_match('/\.+/', $name)){
			if(!isset(self::$configs[$name])){
				//$ret = TTDB::query("select {$type} from Tbl_Config where FKey='{$name}'");
				$ret = TTDB::query("select FKey,FValue,FText from Tbl_Config");
				if(!$ret) return $default;
				foreach ($ret as $k=>$v){
					$key = $v['FKey'];
					self::$configs[$key] = $v['FValue'] != '' ? $v['FValue'] : $v['FText'];
				}
			}
			return self::$configs[$name] ? json_decode(self::$configs[$name], true) : $default;
		}else{
			$name = explode('.', $name);
			if(!isset(self::$project_configs[$name[0]])){
				self::$project_configs[$name[0]] = TMYamlCacher::getInstance()->execute(ROOT_PATH . "config/" . $name[0] . '.yml');
			}
			$res = self::$project_configs[$name[0]];
			$tmp = count($name);
			for($i = 1; $i < $tmp; $i++){
				if(!isset($res[$name[$i]])) return $default;
				$res = $res[$name[$i]];
			}
			return $res;
		}
	
	}

	/*登陆*/
	public static function login(){
		$uin = TTUtil::getQQ();
		
		if(!$uin){
			return array(
					'code' => -99,
					'message' => '尚未登陆'
			);
		}else{
			return array(
					'code' => 0,
					'message' => 'success',
					'data' => array(
							'qq' => $uin
					)
			);
		}
	
	}

	/*登录即注册*/
	public static function loginIsRegister(){
		$from = (int)self::getInput("from");
		$utype = (int)self::getInput("utype");
		$uin = self::getQQ();
		if(!$uin) return;
		$counter = TTCounter::dayQuery(TTCounter::LOGIN_IS_REGISTER_DAY, $uin);
		if($counter['retcode'] == 0 && $counter['cur_value'] != 0){
			return array('code'=>0,'message'=>'今天已经更新登录即注册信息！cur_value:'.$counter['cur_value']);
		}
		$suffix = self::getSuffixById($uin);
		$row = array(
				"FIp" => TMUtil::getClientIp(),
				"FFirstLoginTime" => date('Y-m-d H:i:s'),
				"FFirstLoginDate" => date('Y-m-d')
		);
		//若有用户资料则同时更新进去
		if(self::getInput("WXUnionId"))	$row['FWXUnionId'] = self::getInput("WXUnionId");
		if(self::getInput("nick")) $row['FNick'] = self::getInput("nick");
		if(self::getInput("avatar")) $row['FAvatar'] = self::getInput("avatar");
		
		$rowName = self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		$ret = TTDB::update("Tbl_User_{$suffix}", "{$rowName}='{$uin}'", $row);
		//尚未注册
		if(!$ret){
			$row['FStatus'] = 1;
			//微信登录
			if(self::isQQ($uin)){
				$row['FQQ'] = $uin;
			}else{
				$row['FUnionId'] = $uin;
				//没有用户信息则阻止注册操作
				$nick = self::getInput("nick");
				$avatar = self::getInput("avatar");
				$WXUnionId = self::getInput("WXUnionId");
				if(!$nick){
					return array('code'=>-92,'message'=>'微信尚未授权登录！');
				}
				$row['FNick'] = $nick;
				$row['FAvatar'] = $avatar;
				$row['FWXUnionId'] = $WXUnionId;
			}
			$ret = TTDB::insert("Tbl_User_{$suffix}", $row);
			if(!$ret) return array('code'=>-93,'message'=>'登录即注册插入失败！');
			TTCounter::allAdd(TTCounter::LOGIN_IS_REGISTER, $uin, 1, 1, 1);
			$msg = '登录即注册插入成功！';
		}else{
			$msg = '登录即注册更新成功！';
			//已绑定的老用户 金豆水流截取最新的记录 特殊处理
			$userInfo = self::getUserInfo();
			if($userInfo['FBind'] != 0 && $userInfo['FUnionId']){
				//是否采用最新金豆流水记录方式
				$retCounter = TTCounter::allQuery(TTCounter::SCORE_DETAIL_CHANGE,$uin);
				if($retCounter['retcode'] == 0 && $retCounter['cur_value'] == 0){
					//将微信端当前时间之前的金豆流水冻结
					$now = date('Y-m-d H:i:s');
					$suffixWX = self::getSuffixById($userInfo['FUnionId']);
					$userIdWX = self::isQQ($uin) ? $userInfo['FUserIdBind'] : $userInfo['FUserId'];
					$ret = TTDB::update("Tbl_ScoreDetail_{$suffixWX}", "FUserId = {$userIdWX} and FTime < '{$now}'", array('FStatus'=>2));
					if(!$ret){	return array('code'=>-94,'message'=>'请重新登录');	}
					//微信端添加一条平台更新金豆流水记录以显示最新余额
					$sql = "select FScore from Tbl_UserScore_{$suffixWX} where FUserId = {$userIdWX} limit 1";
					$retScore = TTDB::query($sql);
					if(!isset($retScore[0]['FScore'])){	return array('code'=>-95,'message'=>'请重新登录');	}
					$ret = TTDB::insert("Tbl_ScoreDetail_{$suffixWX}", array(
							'FUserId' => $userIdWX,
							'FStrategy' => '平台更新',
							'FStrategyType' => 2,
							'FLeftScore' => $retScore[0]['FScore'],
							'FScore' => 0,
							'FStatus' => 1,
							'FTime' => date('Y-m-d H:i:s'),
							'FDate' => date('Y-m-d'),
							'FIp' => TMUtil::getClientIp()
					));
					if(!$ret) return array('code'=>-96,'message'=>'积分记流水失败');
					TTCounter::allAdd(TTCounter::SCORE_DETAIL_CHANGE,$uin);
				}
			}
		}
		TTCounter::dayAdd(TTCounter::LOGIN_IS_REGISTER_DAY, $uin, 1, 1, 1);
		return array('code'=>0,'message'=>$msg);
	}

	/*活动时间*/
	public static function active(){
		$project_time = TTUtil::cfg('project.project_time');
		$now_time = date('Y-m-d H:i:s');
		if($now_time < $project_time['start']){
			return array('code'=>-1,'message'=>'活动尚未开始');
		}
		if($now_time > $project_time['over']){
			return array('code'=>-2,'message'=>'活动已结束');
		}
		return array('code'=>0,'message'=>'success');
	}

	/*加锁*/
	public static function lock($action_name, $lock_expire = 1){
		if(TMEnvConfig::getEnv() == 'test') return true;
		$uin = TMAuthUtils::isLogin() ? TMAuthUtils::getUin() : '88888';
		$lockKey = substr(md5(TMConfig::get('tams_id') . $action_name . $uin), 8, 16);
		if(TMEnvConfig::getEnv() == 'test' || $lock_expire > 120){
			$ret = TMMemCacheMgr::getInstance()->add($lockKey, 1, $lock_expire);
		}else{
			$ret = TaeMemCounterService::counterAddByStrKey($lockKey, 1, $lock_expire, TaeMemCounterService::STRICT_MAX, 1);
			$ret = $ret['retcode'] != 0 ? false : true;
		}
		return array(
				'code' => $ret ? 0 : -105,
				'message' => $ret ? 'success' : '温馨提示：您的操作速度过快，请稍后再试！'
		);
	
	}

	/*获取当前登陆的QQ号码*/
	public static function getQQ($from = ''){
		if($from === ''){
			$from = (int)self::getInput("from");
		}
		$utype = (int)self::getInput("utype");
		$uin = (string)self::getInput("uin");
		if($from != 2){
			$uin = TMAuthUtils::isLogin() ? TMAuthUtils::getUin() : '';
		}
		//将微信openid转为16位数字
		//$uin = $openid ? TTWeixin::getMapKey($openid) : '';
		return $uin ? $uin : 0;
	
	}
	/*获取用户的绑定帐号*/
	public static function getQQBind(){
		if(!self::$userInfo){
			self::getUserInfo();
		}
		$uin = self::getQQ();
		$colNameBind = !self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		return self::$userInfo[$colNameBind];
	
	}
	
	
	/*根据传入id获取当前用户的表后缀*/
	public static function getSuffixById($uin){
		//获取表名
		if(preg_match("/^\d+$/", $uin)){
			$suffix = substr($uin, -2) % 20;
		}else{
			preg_match_all('/[a-zA-Z]/i', $uin, $arr);
			$suffix = strtoupper(end($arr[0]));
		}
		return $suffix;
	}
	/*根据数字获取微信用户的表后缀*/
	public static function getSuffixByNum($num){
		$num = (int)$num;
		$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		return $arr[$num-1];
	}
	/*根据数字获取微信用户的表后缀*/
	public static function getNumBySuffix($suffix){
		$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$suffix = strtoupper($suffix);
		$num = array_search($suffix, $arr) + 1;
		$num = $num < 10 ? '0'.$num : $num;
		return $num;
	}
	
	public static function isQQ($uin){
		if(preg_match("/^\d+$/", $uin)){
			return true;
		}
		return false;
	}
	
	/*获取用户数据*/
	public static function getUserInfo($reload = false){
		if(!self::$userInfo || $reload){
			$uin = TTUtil::getQQ();
			$suffix = self::getSuffixById($uin);
			$colName = self::isQQ($uin) ? 'FQQ' : 'FUnionId';
			$colNameBind = !self::isQQ($uin) ? 'FQQ' : 'FUnionId';
			$sql = "select * from Tbl_User_{$suffix} where {$colName}='{$uin}' and FStatus=1";
			$ret = TTDB::query($sql);
			if(!$ret) return false;
			self::$userInfo = $ret[0];
			self::$userInfo['suffix'] = self::getSuffixById($uin);
			$uinBind = self::$userInfo[$colNameBind];
			self::$userInfo['suffixBind'] = self::getSuffixById($uinBind);
			if(self::$userInfo['FBind']){
				$userIdBind = explode('_', $ret[0]['FBindId']);
				self::$userInfo['FUserIdBind'] = $userIdBind[1];
				
				/*
				$uinBind = self::$userInfo[$colNameBind];
				$suffixBind = self::getSuffixById($uinBind);
				$sql = "select * from Tbl_User_{$suffixBind} where {$colNameBind}='{$uinBind}' and FStatus=1";
				$ret = TTDB::query($sql);
				if(!$ret) return false;
				self::$userInfoBind = $ret[0];
				*/
			}
		}
		return self::$userInfo;
	
	}
	/*获取用户数据*/
	public static function getUserInfoByUin($uin = NULL){
		if(!$uin) return 0;
		$suffix = self::getSuffixById($uin);
		$colName = self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		$colNameBind = !self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		$sql = "select * from Tbl_User_{$suffix} where {$colName}='{$uin}' and FStatus=1";
		$ret = TTDB::query($sql);
		if(!$ret) return false;
		$ret[0]['suffix'] = self::getSuffixById($uin);
		$uinBind = $ret[0][$colNameBind];
		$ret[0]['suffixBind'] = self::getSuffixById($uinBind);
		if($ret[0]['FBind']){
			$userIdBind = explode('_', $ret[0]['FBindId']);
			$ret[0]['FUserIdBind'] = $userIdBind[1];
		}
		return $ret[0];
	
	}
	
	/*行为监测*/
	public static function track($code){
		$uin = self::getQQ();
		$uin = !$uin ? '10000' : $uin;
		if(!self::isQQ($uin)){
			$uin = TTWeixin::getMapKey($uin);
		}
		return TMTrackUtils::trackAction($uin, $code);
	}
	//增加积分
	public static function addScore($score, $strategy, $uin = NULL){
		$userInfo = array();
		if($uin){
			$userInfo = self::getUserInfoByUin($uin);
		}else{
			$uin = $uin ? $uin : TTUtil::getQQ();
			$userInfo = self::getUserInfo();
		}
		if(!$userInfo) return array('code'=>-80,'message'=>'用户资料丢失');
		$colName = self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		$colNameBind = !self::isQQ($uin) ? 'FQQ' : 'FUnionId';
		$userId = $userInfo['FUserId'];
		$suffix = TTUtil::getSuffixById($uin);
		//绑定的对象
		$uinBind = $userInfo[$colNameBind];
		$userIdBind = $userInfo['FUserIdBind'];
		$suffixBind = TTUtil::getSuffixById($uinBind);
		$totalScore = 0;
		if($score == 0) return array('code'=>-81,'message'=>'要加的积分为空');
		$actCode = self::getInput('act_id');
		$strategyType = $score >= 0 ? '1' : '0';
		//扣分限制
		$counter = TTCounter::allQuery(TTCounter::SCORE, $uin);
		if($counter['retcode'] == 0){
			$leftScore = $counter['cur_value'] + $score;
		}else{
			return array('code'=>-82,'message'=>'当前用户计数器积分查询失败');
		}
		
		if($leftScore < 0) return array('code'=>-83,'message'=>"积分不能再减 counter['cur_value']:{$counter['cur_value']} + score:{$score}");
		//每日的加分控制 加分前 微信端不受总分限制
		$from = (int)self::getInput('from');
		if($score >= 0 && $from != 2){
			$curScore = $counter['cur_value'] + $score;
			$scoreDayMax = 0;
			$scoreDayMax = (int)self::cfg('checkinScore5', 50) * 2;
			$scoreDayMax += (int)self::cfg('voteScore', 10) * (int)self::cfg('voteDayTimes', 5);
			$scoreDayMax += (int)self::cfg('actShareScore', 10) * (int)self::cfg('actShareDayTimes', 5);
			$scoreDayMax += (int)self::cfg('bindAddScore', 100);
			$counter = TTCounter::dayAdd(TTCounter::SCORE_DAY, $uin,$score,1,$scoreDayMax);
			if($counter['retcode']!=0){
				return array('code'=>-84,'message'=>'积分已达每日上限');
			}
		}
		//记流水 绑定过的用户统一记录到微信端 未绑定则各记各的
		if($userInfo['FBind'] && self::isQQ($uin)){
			$autoSuffix = $suffixBind;
			$autoUserId = $userIdBind;
		}else{
			$autoSuffix = $suffix;
			$autoUserId = $userId;
		}
		$ret = TTDB::insert("Tbl_ScoreDetail_{$autoSuffix}", array(
				'FUserId' => $autoUserId,
				'FActId' => $actCode,
				'FStrategy' => $strategy,
				'FStrategyType' => $strategyType,
				'FLeftScore' => $leftScore,
				'FScore' => abs($score),
				'FStatus' => 1,
				'FTime' => date('Y-m-d H:i:s'),
				'FDate' => date('Y-m-d'),
				'FIp' => TMUtil::getClientIp()
		));
		if(!$ret) return array('code'=>-85,'message'=>'积分记流水失败');
		//更新积分
		$s = $score >= 0 ? '+' : '-';
		$sql = 'FScore=FScore' . $s . abs($score);
		$sql = "update Tbl_UserScore_{$suffix} set {$sql} where FUserId={$userId}";
		$ret = TTDB::execute($sql);
		if(!$ret){
			$row = array(
					'FUserId' => $userId,
					'FScore' => $score,
					'FStatus' => 1
			);
			$ret = TTDB::insert("Tbl_UserScore_{$suffix}", $row);
			if(!$ret) return array('code'=>-86,'message'=>'更新插入当前用户积分表失败');
			$totalScore = $score;
		}else{
			$ret = TTDB::query("select FScore from Tbl_UserScore_{$suffix} where FUserId={$userId}");
			if(!$ret) return array('code'=>-87,'message'=>'查询当前用户积分表失败');
			$totalScore = $ret[0]['FScore'];
		}
		//总分计数器
		$counter = TTCounter::allAdd(TTCounter::SCORE, $uin, $score);
		if($counter['retcode'] != 0) return array('code'=>-88,'message'=>'当前用户计数器积分增加失败');
		
		
		//redis排行
		$redis = new TTRedis();
		$redisKey = self::cfg("project.redis_score_key","scoreTopList");
		if($userInfo['FBind']){
			//数据库同步
			$sql = "update Tbl_UserScore_{$suffixBind} set FScore={$totalScore} where FUserId={$userIdBind}";
			$ret = TTDB::execute($sql);
			if(!$ret){
				$row = array(
						'FUserId' => $userIdBind,
						'FScore' => $score,
						'FStatus' => 1
				);
				$ret = TTDB::insert("Tbl_UserScore_{$suffixBind}", $row);
				if(!$ret) return array('code'=>-89,'message'=>'更新插入绑定用户积分表失败');
			}
			//计数器同步
			$counterBind = TTCounter::allSet(TTCounter::SCORE, $uinBind, $totalScore);
			if($counterBind['retcode'] != 0) return array('code'=>-810,'message'=>'绑定用户计数器积分同步失败');
			
			$from = (int)self::getInput("from");
			//微信端来源 QQUserId,QQ表后缀_WXUserId,WX表后缀
			if(self::isQQ($uin)){
				$bindUin = $userId.",".$suffix."_".$userIdBind.",".$suffixBind;
			}else{
				$bindUin = $userIdBind.",".$suffixBind."_".$userId.",".$suffix;
			}
			$redisRet = $redis->zAddValue($redisKey, $totalScore, $bindUin);
			if($redisRet['code'] == 0){
				$redis->zDeleteValue($redisKey, $uin);
				$redis->zDeleteValue($redisKey, $uinBind);
			}
		}else{
			$redis->zAddValue($redisKey, $totalScore, $uin);
		}
		return array('code'=>0,'message'=>'积分处理成功！','totalScore'=>$counter['cur_value']);
	
	}

	/*//验证图片是不是本人上传的
	 public static function is_my_img($url){
	 $url = preg_split("/\//", $url, -1, PREG_SPLIT_NO_EMPTY);
	 list($uin,$other) = explode('_',$url[5]);
	 if($url[4] != TMConfig::get('tams_id') || $uin != TTUtil::getQQ() || strlen($other)<20)
	 return false;
	 return true;
	 }*/
	/*根据QQ获取昵称*/
	public static function getNick($qq){
		$nick = TaeIMService::getNick(array(
				$qq
		));
		$nick = htmlspecialchars($nick['nicknamelist'][$qq]);
		return $nick;
	
	}

	/*批量获取QQ昵称*/
	public static function getNicks($qqs){
		$res = array();
		if(TMEnvConfig::getEnv() == 'test'){
			foreach($qqs as $qq){
				$res[$qq] = '一个很多字的昵称';
			}
		}else{
			$nick = TaeIMService::getNick($qqs);
			$nick = $nick['nicknamelist'];
			foreach($qqs as $qq){
				$res[$qq] = isset($nick[$qq]) ? htmlspecialchars($nick[$qq]) : '网友';
			}
		}
		return $res;
	
	}
	//非常方便的检查
	public static function check($login = 1, $active = 1, $lock = '', $verify = 1){
		if($login){
			$ret = self::login();
			if($ret['code'] != 0) return $ret;
		}
		if($active){
			$ret = self::active();
			if($ret['code'] != 0) return $ret;
		}
		if($lock){
			$ret = self::lock($lock);
			if($ret['code'] != 0) return $ret;
		}
		if($verify){
			$ret = self::verify($login);
			if($ret['code'] != 0) return $ret;
		}
		if($login){
			//登录即注册
			$ret = self::loginIsRegister();
			if($ret['code'] != 0) return $ret;
		}
		return array('code' => 0, 'message' => $ret['message']);
	
	}

	/**
	 * <p>Title: 校验来源</p>
	 * <p>Description:默认不校验PC端 </p>
	 */
	public static function verify($needUin){
		if(TMEnvConfig::getEnv() == 'test'){
			return array(
					'code' => 0,
					'message' => 'success'
			);
		}
		$timestamp = (string)self::getInput("timestamp");
		$token = (string)self::getInput("token");
		if(!$timestamp || !$token){
			return array(
					'code' => -200,
					'message' => '来源校验参数丢失'
			);
		}
		$sign = TTUtil::cfg('project.signature');
		$uin = $needUin ? TTUtil::getQQ() : '';
		//时间戳加约定的密钥加uin，两次MD5加密后的字符串
		$localSign = md5(md5($timestamp . $sign . $uin));
		//print_r("sign:".$sign."  qq:".$uin."  timestamp:".$timestamp."  localSign:".$localSign."  token:".$token);exit();
		if($localSign == $token){
			return array(
					'code' => 0,
					'message' => 'success'
			);
		}else{
			return array(
					'code' => -201,
					'message' => '来源校验失败'
			);
		}
	
	}
	//获取头像
	public static function getHead($qq = NULL){
		$qq = $qq ? $qq : TTUtil::getQQ();
		if(!$qq) return false;
		$head = TaeIMService::getOneAvatar($qq);
		$head = isset($head['qqhead_url']) ? $head['qqhead_url'] : '';
		$head = str_replace('&s=40&', '&s=100&', $head);
		return $head;
	
	}
	//批量获取头像
	public static function getHeads($qqs){
		if(!$qqs) return array();
		$heads = $other = array();
		if(count($qqs) > 20){
			$other = array_slice($qqs, 20);
			$qqs = array_slice($qqs, 0, 20);
		}
		$ret = TaeIMService::getQQAvatars($qqs);
		if($ret['retcode'] != 0) return array();
		foreach($ret['headList'] as $qq => $v){
			$head = isset($v['head']) ? $v['head'] : '';
			$head = str_replace('&s=41&', '&s=100&', $head);
			$heads[$qq] = $head;
		}
		if($other){
			$heads = array_merge($heads, self::getHeads($other));
		}
		return $heads;
	
	}
	//游戏项目获得红钻
	//project.yml中red_time为红钻发放时间
	//project.yml中red_max_daily为红钻每日发放数
	//project.yml中red_max为红钻发放总数
	//使用TTCounter::RED为总数计数器
	//使用TTCounter::GOT_RED为是否领取红钻计数器
	//使用Tbl_User表中的FValue4字段记录领取活动时间
	//@return array
	public static function getRed(){
		$uin = TTUtil::getQQ();
		$time = TTUtil::cfg("project.red_time");
		if(!$time) return array(
				'code' => -1101,
				'message' => '温馨提示：红钻发放时间未设置！'
		);
		if(date('Y-m-d H:i:s') < $time['start']){
			return array(
					'code' => -31,
					'message' => '温馨提示：红钻尚未开始发放，敬请期待！'
			);
		}
		$start = strtotime(date('Y-m-d 00:00:00', strtotime($time['start']))) - 1;
		$now = $time['over'] > date('Y-m-d H:i:s') ? strtotime(date('Y-m-d 00:00:00')) : strtotime(date('Y-m-d 00:00:00', strtotime($time['over'])));
		$days = ceil(($now - $start) / (24 * 3600));
		$per_day = TTUtil::cfg('project.red_max_daily');
		if(!$per_day) return array(
				'code' => -1102,
				'message' => '温馨提示：红钻每日发放数未设置！'
		);
		$all = TTUtil::cfg('project.red_max');
		if(!$all) return array(
				'code' => -1105,
				'message' => '温馨提示：红钻发放总数未设置！'
		);
		$max = $per_day * $days > $all ? $all : $per_day * $days;
		$max = TaeCounterService::counterAddExt(TTCounter::RED, '11111', 1, 0, '', TaeCounterService::STRICT_MAX, $max);
		if($max['retcode'] != '0'){
			return array(
					'code' => -1103,
					'message' => '温馨提示：红钻今日已被领取完，明天再来吧！'
			);
		}else{
			TTCounter::allAdd(TTCounter::GOT_RED, $uin);
			TTUtil::track(6000245);
			TTDB::update('Tbl_User', 'FQQ=\'' . $uin . '\'', array(
					'FValue4' => date('Y-m-d H:i:s')
			));
			return array(
					'code' => 0,
					'message' => '温馨提示：恭喜您，成功领取红钻！'
			);
		}
	
	}


}