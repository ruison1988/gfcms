<?php
/***
 * 微视话题拉取
*/
class TTWeiVideo{
	//存储表，必须是Tbl_File表的格式
    public $table = 'Tbl_File';
	//拉取话题
	public $tag   = 'PK华少';
	//过滤a标签
	public $filterATag = true;
	//开始拉取
    public function run(){
		$recordResult = TTDB::query("select count(1) as num from ".$this->table);
		if($recordResult[0]['num'] < 1) {
			//第一次全部拉取完,向下拉取
			$this->allVideoList();
		}else{
			//拉取增加，向上拉取
			$this->addVideoList();
		}
	}	
	//添加所有视频
	protected function allVideoList(){		
		$recordResult = TTDB::query("select count(1) as num from ".$this->table);
		$pageflag     = $recordResult[0]['num'] < 1 ? 0 : 2;
		$lastRecode   = TTDB::query("select FFileName,FUserId from ".$this->table." order by FUserId ASC limit 1");
		$reqnum       = 20;
		$lastid       = $lastRecode[0]['FFileName'] ? $lastRecode[0]['FFileName'] : 0;
		$pagetime     = $lastRecode[0]['FUserId'] ? $lastRecode[0]['FUserId'] : 0;
        $data         = 'type=6&v=w&tag='.$this->tag.'&start=0&reqnum='.$reqnum.'&pageflag='.$pageflag.'&lastid='.$lastid.'&pagetime='.$pagetime;//数据
		$returnReslt  = $this->insertVideoFile($data);
		if( 1 == $returnReslt ) $this->allVideoList();
	}
	//在现有基础上添加视频
	protected function addVideoList(){
		$lastRecode  = TTDB::query("select FFileName,FUserId from ".$this->table." order by FUserId DESC limit 1");
		$reqnum      = 20;
		$pageflag    = 1;
		$lastid      = $lastRecode[0]['FFileName'] ? $lastRecode[0]['FFileName'] : 0;
		$pagetime    = $lastRecode[0]['FUserId'] ? $lastRecode[0]['FUserId'] : 0;
        $data        = 'type=6&v=w&tag='.$this->tag.'&start=0&reqnum='.$reqnum.'&pageflag='.$pageflag.'&lastid='.$lastid.'&pagetime='.$pagetime;//数据
 		$returnReslt = $this->insertVideoFile($data);
		if( 1 == $returnReslt ) $this->addVideoList();
	}
	//插入拉取的微视数据
	protected function insertVideoFile($data){
        $randArray = array("10.133.42.226","10.133.42.227");
		$randKey   = array_rand($randArray);
		$data      = $data;
		$host      = array("Host:inner.wsi.qq.com");//host
		//测试的时候使用clidewei提供的ip，上线后改成正式ip
        $url       = 'http://'.$randArray[$randKey].'/weishi/tag/tagTimeline.php/';
		$ch        = curl_init();
		//申请的id和pd，附加到cookie中
		$cookie    = 'wsiid=guangping_minisite;wsipd=rdgq(*)uhji)';
		$res       = curl_setopt ($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$host);
    	$str  = curl_exec ($ch);
		curl_close($ch);
        $str  = json_decode($str,true);
        $info = $str['data']['info'];		
        foreach ($info as $info1) {
           $digcount  = $info1['digcount'];//被赞次数
           $uid       = $info1['uid'];//微视id
           $id        = $info1['id']; //微博唯一id
           $name      = $info1['name']; //发表人账户名
           $timestamp = $info1['timestamp']; //发表时间
           $head      = isset($info1['head'])?$info1['head']:''; //发表者头像url
		   $text      = $info1['text']; //消息文本
           $playCount = $info1['playCount']; //播放次数
           $picurl    = $info1['videos'][0]['picurl']; //缩略图
           $player    = $info1['videos'][0]['player']; //播放器地址
           $realurl   = $info1['videos'][0]['realurl']; //视频原地址
           $vid       = $info1['videos'][0]['vid']; //视频id
           $title     = $info1['videos'][0]['title']; //视频标题
           $tags      = $info1['tags'][0]['name']; //tag名称
		   if($this->filterATag){
			  $text = preg_replace(array("/<a[^>]*>/i","/<\/a>/i"),'',$text);
		   }
           //初始明细表
		   $values = array(
                'FUserId'    => $timestamp ? $timestamp : '',
                'FNick'      => $name      ? $name      : '',
                //'FType'      => 1,
                'FVoteCount' => 0,
                'FScore'     => 0,
                'FEnable'    => 2,
				'FText'      => $text    ? $text      : '',
                'FFileName'  => $id      ? $id        : '',
				'FMiniUrl'   => $head    ? $head.'/0' : '',
				'FUrl'       => $picurl  ? $picurl    : '',
				'FDesc'      => $realurl ? $realurl   : '',
                'FTime'      => date('Y-m-d H:i:s'),
                'FDate'      => date('Y-m-d'),
                //'FMemo'      => TMUtil::getClientIp()
            );
            $result = TTDB::insert($this->table, $values);
        }
		return $str['data']['hasNext'];
	}

}