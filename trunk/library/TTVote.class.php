<?php
/**
 * 投票
*/
class TTVote{
	public static function onStart(){
		$ret = TTUtil::check(1,1,'vote');
		if($ret['code']!=0){
			return $ret;
		}
		/*$vote = TTCounter::dayQuery(TTCounter::VOTE,$qq);
		if($vote['cur_value']>=TTUtil::cfg('project.max_vote')){
			return array('code'=>-17,'message'=>'温馨提示：您今天转椅的次数已达上限，明天再来吧！');
		}*/
		return array('code'=>0,'message'=>'success');
	}
	public static function onEnd(){
		//$qq = TTUtil::getQQ();
		//TTCounter::dayAdd(TTCounter::VOTE,$qq);
	}
	
}