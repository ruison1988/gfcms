<?php
/**
 *分页组件
 *@Author yanghui
 *@package ApplicationComponent
 *@version 1.0
*/
class TTPage{
	protected $_controller = null;
	public $max_show = 6;//每页显示的最多页数链接
	public $page_size = 8;//每页显示数
	public $use_table;//使用表
	public $page;
	public $fields = '*';
	public $limit_num;//限制取多少位
	
	protected $_sort_manner = array();//排序方式 格式如 array('FTime' => 'DESC');
	protected $_ands = array();//增加条件 格式如 array('FDate>"2011-02-02"');
	
	public $getPagination = true;//获取页面分页
	public $cacheExpire = 300;//缓存时间(保持原状)
	public $totalCache = true;//总数缓存(保持原状)
	
	public function __construct($controller){
		$this->_controller = $controller;
	}
	/*
	 *设置排序类型
	 *@param array $option
	 *@example 支持多个，例如
	 *setSortManner(array('FFileId'=>'ASC'));
	 *setSortManner(array('FTime'=>'DESC'));
	 *生成的sql的order是“order by FFileId ASC , FTime DESC”
	*/
	public function setSortManner($option){
		if(!$option || !is_array($option))
			return ;
		$this->_sort_manner = array_merge($this->_sort_manner,$option);
	}
	/*
	 *获取设置过的排序
	*/
	public function getSortManner(){
		return $this->_sort_manner ? $this->_sort_manner : array('FTime' => 'DESC');
	}
	/*
	 *设置条件
	 *@param string $option 不需要and  例如 FId>10，可以多次设置
	*/
	public function setAnds($option){
		if(!$option || !is_string($option) || in_array($option,$this->_ands))
			return ;
		$this->_ands[] = $option;
	}
	/*
	 *获取AND条件
	*/
	public function getAnds(){
		return $this->_ands ? $this->_ands : array('1=1');
	}
	/*
	 *获取当前页数
	*/
	public function getPage(){
		if(!$this->_controller){
			return 1;
		}
		return $this->page ? $this->page : $this->_controller->getRequest()->getGetParameter('page');
	}
	/*
	 *根据已经设置好的条件执行sql
	 *@retuen array 返回数组列表和分页
	*/
	public function run(){
		if(!$this->use_table)
			return array('code' => -8,'message'=>'分页的时候数据表没有设置！');
		$rs = array('data' => array(), 'pagination' => array());
		$ands = $this->getAnds();//构造条件
		$page = (int)$this->getPage();//当前分页
		$page = $page ? $page : 1;
		$_hitCache = false;//命中缓存
		$pageSize = $this->page_size;
		$limitNum = $this->limit_num ? $this->limit_num : $this->page_size;
		$sort_manner = $this->getSortManner();//排序种类，默认是数组的第一个[不能为FFileId]
		$_sort_manner = array();
		foreach($sort_manner as $k => $v){
			$_sort_manner[] = $k.' '.$v;
		}
		
		$sort = ' ORDER BY '.implode(',',$_sort_manner);
		$limit = ' LIMIT '.$pageSize*($page-1).','.$limitNum;//limit 参数
		$sql = 'SELECT '.$this->fields.' FROM '.$this->use_table.' WHERE '.implode(' AND ',$ands).$sort.$limit;//需要执行的sql合成
		//$dataCacheKey = substr(md5($sql),8,8);//缓存键值
		$countSql = 'SELECT COUNT(*) total FROM '.$this->use_table.' WHERE '.implode(' AND ',$ands);
		$cache_key = 'file_total_'.substr(md5($countSql),8,8);
		if($this->getPagination){//获取分页
			$total = $this->totalCache ? TTCache::get($cache_key) : 0;
			if(!$this->totalCache || !$total){
				$total = TTDB::query($countSql);
				$total = isset($total[0]['total']) && $total[0]['total'] ? $total[0]['total'] : 1;
				if($this->totalCache){
					TTCache::set($cache_key,$total,$this->cacheExpire);
				}
			}
			$totalPages = $total ? ceil($total/$pageSize) : 1;//总页数
			$rs['pagination'] = $this->page($page,$totalPages);//获取分页信息
			$rs['pagination']['total'] = $total ? $total : 0;//总数
			$rs['pagination']['page']  = $page;//当前分页
		}
		$rs['data'] =TTDB::query($sql);//执行sql
		return $rs;//返回结果
	}
	//获取URL
	//@param array $unsets 需要删除的参数
	public function getUrl($unsets = array('page')){
		$url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");//当前url获取
		$url = ltrim($url,'/');
		$url = str_replace(array('"',"'",":",'<','>','%3C','%3E','%20',' '),'',$url);//可能出现的安全限制
        $parse = parse_url($url);//解析url
        if(isset($parse['query'])) {
			parse_str($parse['query'],$params);//参数解析
			foreach($unsets as $unset){//删除需要被删除的键值
				if($params[$unset]) unset($params[$unset]);
			}
			foreach($params as $key => $val){//获取建值(安全的)
				if($this->_controller){
					$params[$key] = $this->_controller->getRequest()->getGetParameter($key);
				}else{
					$params[$key] = '';
				}
				
			}
			$url = $parse['path'].'?'.http_build_query($params);
        }
		$url = str_replace('&amp;','&',$url);//可能出现的安全限制
		$url = TMConfig::get('base_url').$url;//base_url
		return $url;
	}
	/**
	 * 第一页 前一页 12345 后一页 最后一页
	 *@param int $page 当前页数
	 *@param int $pages 最大页数
	 *@return array 分页结果
	*/
	public function page($page,$pages){
		$page = $page > $pages ? $pages : $page;
		$url  = $this->getUrl();
		$show = $this->max_show;//显示 1 2 3 4 5 6
		$mix  = ceil($show/2);//可以显示出1的最大页数
		$max  = $pages - $mix;//可以显示出最大页数的最小分页数
		
		$url = substr($url,-1) == '?' ? $url : $url.'&';//是否需要加上&
		$p['pages'] = $pages;
		$p['first'] = $url.'page=1';
		$p['last']  = $url.'page='.$pages;
		$_pre = $page == 1 ? 1 : $page - 1;
		$p['pre']   = $url.'page='.$_pre;
		$_nxt = $page == $pages ? $pages : $page + 1;
		$p['nxt']   = $url.'page='.$_nxt;
		if($pages<=$show){
			for($i=1;$i<=$pages;$i++){
				$p['main'][$i] = $url.'page='.$i;
			}
		}else if($page <= $mix){
			for($i=1;$i<=$show;$i++){
				$p['main'][$i] = $url.'page='.$i;
			}
		}else if($page >= $max){
			$start = $pages - $show + 1;
			for($i = $start ; $i <= $pages ; $i ++){
				$p['main'][$i] = $url.'page='.$i;
			}
		}else{
			$_start = $page - $mix + 1;
			$_end   = $page + $mix - 1;
			$show%2==0 && $_end++;
			for($i = $_start ; $i <= $_end ; $i ++){
				if($i<1) continue;
				$p['main'][$i] = $url.'page='.$i;
			}
		}
		return $p;
	}
}
?>