<?php
/**
 * 抽奖
*/
class TTLottery{

	/*开始*/
	public static function onStart(){
		$ret = TTUtil::check(1,1,'lottery');
		if($ret['code']!=0) { return $ret; }
		$time = TTUtil::cfg('project.lottery_time',TTUtil::cfg('project.project_time'));
		$now  = date('Y-m-d H:i:s');
		if($time && $time['start'] > $now){
			return array('code'=>-21,'message'=>'抽奖活动将于'.substr($time['start'],0,10).'开始，敬请期待哦~');
		}else if($time && $now > $time['over']){
			return array('code'=>-22,'message'=>'抽奖活动已结束，感谢您的参与！');
		}
		$score = TTCounter::allQuery(TTCounter::SCORE,TTUtil::getQQ());
		if($score['cur_value']<1){
			return array('code'=>-17,'message'=>'您今天的抽奖机会已用完，明天再来试试吧！');
		}
		$ip = TMUtil::getClientIp();
		$ret = TTCounter::dayQuery(TTCounter::AWARD,$ip);
		if($ret['cur_value'] >= TTUtil::cfg('project.max_ip_award_perday',2)){
			return array('code'=>0,'message'=>'success','max'=>true);
		}
		return array('code'=>0,'message'=>'success');
	}
	/*结束*/
	public static function onEnd(){
		TTUtil::addScore(-1,'lottery');
	}
	
}