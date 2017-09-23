<?php

class TTRedis{
	protected $redis;
	public function TTRedis(){
		if(TMEnvConfig::getEnv() == 'test') return;
		$this->redis = TMPHPRedisClientFactory::getClient();
	}

	//添加值到redis
	//zAdd(key, score, member)：向名称为key的zset中添加元素member，score用于排序。如果该元素已经存在，则根据score更新该元素的顺序。
	public function zAddValue($key, $score, $member){
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		if(empty($score)){
			$score = 0;
		}
		if(empty($member)){
			return array(
					'code' => -98,
					'message' => '元素名为空'
			);
		}
		
		if(TMEnvConfig::getEnv() == 'test') return array('code'=>0, 'data'=>1,'message'=>'success');		
		//向redis里添加值
		$result = $this->redis->zAdd($key, $score, $member);
		if($result){
			return array(
					'code'=>0,
					'data'=>$result,
					'message'=>'success'
			);
		}else{
			return array(
					'code'=>-1,
					'data'=>$result,
					'message'=>'failed'
			);
		}
	}
	
	
	//取值
	//zRevRange(key, start, end,withscores)：返回名称为key的zset（元素已按score从大到小排序）中的index从start到end的所有元素.withscores: 是否输出socre的值，默认false，不输出
	public function zRevRangeList($key, $start = 0, $end = 9, $withscores = true){
		if(TMEnvConfig::getEnv() == 'test') return;
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		if(TMEnvConfig::getEnv() == 'test') return array('code'=>0, 'data'=>1,'message'=>'success');
		$result = $this->redis->zRevRange($key, $start, $end, $withscores);
		if($result){
			return array(
					'code'=>0,
					'data'=>$result,
					'message'=>'success'
			);
		}else{
			return array(
					'code'=>-1,
					'data'=>$result,
					'message'=>'failed'
			);
		}
	
	}

	//zRevRank(key, val);
	//返回名称为key的zset（元素已按score从小到大排序）中val元素的rank（即index，从0开始），若没有val元素，返回“null”。zRevRank 是从大到小排序
	public function zRevRankValue($key, $member){
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		if(empty($member)){
			return array(
					'code' => -98,
					'message' => '成员member值为空'
			);
		}

		if(TMEnvConfig::getEnv() == 'test') return array('code'=>0, 'data'=>1,'message'=>'success');
		$result = $this->redis->zRevRank($key,$member);
		if($result || $result===0){
			return array(
					'code'=>0,
					'data'=>$result,
					'message'=>'success'
			);
		}else{
			return array(
					'code'=>-1,
					'data'=>$result,
					'message'=>'failed'
			);
		}
	}
	
	//删除有序集中的某个值 zDelete, zRem  zRem(key, member) ：删除名称为key的zset中的元素member
	public function zDeleteValue($key, $member){
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		if(empty($member)){
			return array(
					'code' => -98,
					'message' => '成员member值为空'
			);
		}
		if(TMEnvConfig::getEnv() == 'test') return array('code'=>0, 'data'=>1,'message'=>'success');
		$result = $this->redis->zDelete($key,$member);
		if($result){
			return array(
					'code'=>0,
					'data'=>$result,
					'message'=>'success'
			);
		}else{
			return array(
					'code'=>-1,
					'data'=>$result,
					'message'=>'failed'
			);
		}
	}
	/*
	protected $redisHost = "10.224.130.24";
	protected $redisPort = 6379;
	protected $redisPass = "tenredis2356";
	protected $redis;
	protected $data = array();

	public function TTRedis(){
		if(TMEnvConfig::getEnv() == 'production'){
			//正式环境的配置  上线之前配置
			$this->redisHost = "";
			$this->redisPort = 6379;
			$this->redisPass = "tenredis2356";
		}
		
		$this->redis = new Redis();
		$connectRe = $this->redis->connect($this->redisHost, $this->redisPort);
		if(!$connectRe){
			$this->data['connect_server'] = false;
		}else{
			$this->data['connect_server'] = true;
		}
		$authRe = $this->redis->auth($this->redisPass);
		if(!$authRe){
			$this->data['auth_server'] = false;
		}else{
			$this->data['auth_server'] = true;
		}
	
	}

	//写入有序队列
	//zAdd(key, score, member)：向名称为key的zset中添加元素member，score用于排序。如果该元素已经存在，则根据score更新该元素的顺序。
	public function zAddValue($key, $score, $member){
		if(!$this->data['connect_server']){
			return array(
					'code' => -100,
					'message' => '服务连接失败'
			);
		}
		if(!$this->data['auth_server']){
			return array(
					'code' => -101,
					'message' => '服务认证失败'
			);
		}
		
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		if(empty($score)){
			$score = 0;
		}
		if(empty($member)){
			return array(
					'code' => -98,
					'message' => '元素名为空'
			);
		}
		
		//向redis里添加值
		$result = $this->redis->zAdd($key, $score, $member);
		return $result;
	
	}

	//读取有序队列里
	//zRange(key, start, end,withscores)：返回名称为key的zset（元素已按score从小到大排序）中的index从start到end的所有元素
	public function zRangeList($key, $start = 0, $end = 10, $withscores = true){
		if(!$this->data['connect_server']){
			return array(
					'code' => -100,
					'message' => '服务连接失败'
			);
		}
		if(!$this->data['auth_server']){
			return array(
					'code' => -101,
					'message' => '服务认证失败'
			);
		}
		
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		//向redis里添加值
		$result = $this->redis->zRange($key, $start, $end, $withscores);
		return $result;
	
	}

	//倒序读取有序队列里
	//zRevRange(key, start, end,withscores)：返回名称为key的zset（元素已按score从大到小排序）中的index从start到end的所有元素.withscores: 是否输出socre的值，默认false，不输出
	public function zRevRangeList($key, $start = 0, $end = 10, $withscores = true){
		if(!$this->data['connect_server']){
			return array(
					'code' => -100,
					'message' => '服务连接失败'
			);
		}
		if(!$this->data['auth_server']){
			return array(
					'code' => -101,
					'message' => '服务认证失败'
			);
		}
		
		if(empty($key)){
			return array(
					'code' => -99,
					'message' => '队列key值为空'
			);
		}
		//向redis里添加值
		$result = $this->redis->zRevRange($key, $start, $end, $withscores);
		return $result;
	
	}
*/

}
