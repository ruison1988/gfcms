<?php
/*
 *数据库操作类
 *都是一些很简单的方法，curd什么的
 *@Author HuangKeTao
*/
class TTDB{
	protected static $_db = null;//数据库实例
	protected static $_dbAlias = 'default';//数据库别名
	/*设置别名*/
	public static function setAlias($alias){
		if(!$alias)
			self::$_dbAlias = 'default';
		else
			self::$_dbAlias = $alias;
	}
	/*获取别名*/
	public static function getAlias(){
		return self::$_dbAlias ? self::$_dbAlias : 'default';
	}

	//数据库操作方法
	public static function getDB() {
   		$dbAlias = self::getAlias();
        if(empty(self::$_db[$dbAlias])) {
            self::$_db[$dbAlias] = new TMService($dbAlias);
        }
        return self::$_db[$dbAlias];
    }
	/*创建，其中$row为数组，键值为对应table的filed*/
	public static function insert($table,$row,$needTime = 1){
		$date = array(
				'FTime'=>date('Y-m-d H:i:s'),
				'FDate'=>date('Y-m-d')
		);
		if( $needTime && (!isset($row['FTime']) || !isset($row['FDate'])) )	$row = array_merge($row,$date);
		$db = self::getDB();
		$db->insert($row,$table);
		$rs = $db->getInsertId();
		return $rs==-1?false:$rs;//成功返回记录行数 失败返回false
	}
	/*更新*/
	public static function update($table,$where,$row,$needTime = 1){
		$date = array(
				'FTime'=>date('Y-m-d H:i:s'),
				'FDate'=>date('Y-m-d')
		);
		if( $needTime && (!isset($row['FTime']) || !isset($row['FDate'])) )	$row = array_merge($row,$date);

		$db = self::getDB();
		$db->update($row, $where, $table);
		$rs = $db->getAffectedRowNum();
		return $rs>0?$rs:false;
	}
	/*查询*/
	public static function query($sql){
		$db = self::getDB();
		return $db->query($sql,MYSQLI_ASSOC);
	}
	/*查询*/
	public static function select($table,$select='*',$conditions=array()){
		$db = self::getDB();
		return $db->select($conditions,$select,$table);
	}
	/*查询返回值
		array(1) {
		  [0]=>array(7) {["FId"]=>string(1) "1"
						["FQQ"]=>string(0) ""
						["FCode"]=>string(8) "AC_Bxxxx"
						["FType"]=>string(3) "123"
						["FStatus"]=>string(3) "234"
						["FApplyTime"]=>string(19) "0000-00-00 00:00:00"
						["FMemo"]=>NULL
		  }
		  [1]=>array(7) {["FId"]=>string(1) "2"
						["FQQ"]=>string(0) ""
						["FCode"]=>string(5) "BB_CC"
						["FType"]=>string(3) "321"
						["FStatus"]=>string(3) "343"
						["FApplyTime"]=>string(19) "0000-00-00 00:00:00"
						["FMemo"]=>NULL
		  }
		}
	*/
	//执行sql
	public static function execute($sql){
		$db = self::getDB();
		$db->query($sql);
		return $db->getAffectedRowNum();
	}
}
?>