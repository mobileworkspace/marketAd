<?php

/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 *
 ** 功 能: 连接 MYSQL数据库  然后对 MYSQL 进行各种操作                                                  
* ------------------------------------------------------------------------------------------------------ *
* 方 法:    open           -- 连接数据库                                                         
*          query          -- 数据查询                                                               
*          setFetchMode   -- 设置取得记录的模式                                                         
*          fetchRow       -- 从记录集中取出一条记录                                            
*          fetchAll       -- 从记录集中取出所有记录                                      
*          getValue       -- 返回记录中指定字段的数据                                                    
*          getQueryId     -- 返回查询号                                                        
*          affectedRows   -- 返回影响的记录数                                               
*          recordCount    -- 返回查询记录的总数          
*          getQueryTimes  -- 返回查询的次数                                     
*          sqlInsert      -- Insert插入数据函数                                         
*          lastInsertID   -- 返回最后一次插入的自增ID                                                               
*          sqlUpdate      -- Update更新数据的函数  
*          sqlDelete      -- 删除指定条件的行    
*          checkSql       -- SQL语句的过滤, 过滤一些特殊语法  
*          getErrNo       -- 返回上一个 MySQL 操作中的错误信息的数字编码             
*          halt           -- 处理所有出错信息                      
*          close          -- 关闭非永久的数据库连接    
*          destory        -- 释放类，关闭非永久的数据库连接                          
* ------------------------------------------------------------------------------------------------------ *
*/

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('/./config.php');


class Mysql {

	private $server;
	private $database;
	private $user;
	private $password;
	private $character;
	private $linkMode = 1;
	private $fetchMode = MYSQL_ASSOC;
	
	private $link_id = 0;
	
	private $query_id = 0;
	private $query_times = 0;
	private $result = array ();
	
	private $err_no = 0;
	private $err_msg;
	

	//====================================== 
	// 函数: open() 
	// 功能: 连接数据库 
	// 参数: 无
	// 返回: 0:失败 
	// 说明: 默认使用类中变量的初始值 
	//====================================== 
	public function open() {

		$this->server = Config::$db_server;
		$this->user = Config::$db_username;
		$this->password = Config::$db_password;
		$this->database = Config::$db_name;
		
		$this->linkMode = Config::$db_linkMode;
		$this->character = Config::$db_character;
		
		$this->link_id = $this->linkMode ? mysql_pconnect($this->server, $this->user, $this->password, $this->database) : 
		                                   mysql_connect($this->server, $this->user, $this->password, $this->database);
		
		if (!$this->link_id) {
			$this->halt('数据库连接失败！请检查各项参数！');
			return false;
		}
		
		if (!mysql_select_db($this->database, $this->link_id)) {
			$this->halt('无法选择数据库');
			return false;
		}
			
		$this->query('SET NAMES ' . $this->character);
		
		return true;
	}
	
	
	//====================================== 
	// 函数: query($sql) 
	// 功能: 数据查询 
	// 参数: $sql 要查询的SQL语句 
	// 返回: 0:失败 
	//====================================== 
	public function query($sql) {
		$this->query_times++;
		$this->query_id = mysql_query($sql, $this->link_id);
		if (!$this->query_id) {
			$this->halt('<font color=red>' . $sql . '</font> 语句执行不成功！');
			return 0;
		}
		return $this->query_id;
	}
	
	//====================================== 
	// 函数: setFetchMode($mode) 
	// 功能: 设置取得记录的模式 
	// 参数: $mode 模式 MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH 
	// 返回: 0:失败 
	//====================================== 
	public function setFetchMode($mode) {
		if ($mode == MYSQL_ASSOC || $mode == MYSQL_NUM || $mode == MYSQL_BOTH) {
			$this->fetchMode = $mode;
			return 1;
		} else {
			$this->halt('错误的模式.');
			return 0;
		}
	}
	
	//====================================== 
	// 函数: fetchRow() 
	// 功能: 从记录集中取出一条记录 
	// 返回: 0: 出错 record: 一条记录 
	//====================================== 
	public function fetchRow() {
		$this->record = mysql_fetch_array($this->query_id, $this->fetchMode);
		return $this->record;
	}
	
	//====================================== 
	// 函数: fetchAll() 
	// 功能: 从记录集中取出所有记录 
	// 返回: 记录集数组 
	//====================================== 
	public function fetchAll() {
		$arr[] = array ();
		while ($this->record = mysql_fetch_array($this->query_id, $this->fetchMode)){
			$arr[] = $this->record;
		}
		mysql_free_result($this->query_id);
		
		return $arr;
	}
	
	//====================================== 
	// 函数: getValue() 
	// 功能: 返回记录中指定字段的数据 
	// 参数: $field 字段名或字段索引 
	// 返回: 指定字段的值 
	//====================================== 
	public function getValue($filed) {
		return $this->record[$filed];
	}
	
	//====================================== 
	// 函数: getQueryId() 
	// 功能: 返回查询号 
	//====================================== 
	public function getQueryId() {
		return $this->query_id;
	}
	
	//====================================== 
	// 函数: affectedRows() 
	// 功能: 返回影响的记录数 
	//====================================== 
	public function affectedRows() {
		return mysql_affected_rows($this->link_id);
	}
	
	//====================================== 
	// 函数: recordCount() 
	// 功能: 返回查询记录的总数 
	// 参数: 无 
	// 返回: 记录总数 
	//====================================== 
	public function recordCount() {
		return mysql_num_rows($this->query_id);
	}
	
	//====================================== 
	// 函数: getQueryTimes() 
	// 功能: 返回查询的次数 
	// 参数: 无 
	// 返回: 查询的次数 
	//====================================== 
	public function getQueryTimes() {
		return $this->query_times;
	}
	
	//====================================== 
	// 函数: sqlInsert() 
	// 功能: Insert插入数据函数 
	// 参数: $taname 要插入数据的表名 
	// 参数: $row 要插入的内容 (数组) 
	// 返回: 记录总数 
	// 返回: 插入语句 
	//====================================== 
	function sqlInsert($tbname, $row) {
		foreach ($row as $key => $value) {
			$sqlfield .= $key . ',';
			$sqlvalue .= $value . ',';
		}
		return 'INSERT INTO ' . $tbname . '(' . substr($sqlfield, 0, -1) . ') VALUES (' . substr($sqlvalue, 0, -1) . ')';
	}
	
	//====================================== 
	// 函数: lastInsertID() 
	// 功能: 返回最后一次插入的自增ID 
	// 参数: 无 
	//====================================== 
	public function lastInsertID() {
		return mysql_insert_id();
	}
	
	//====================================== 
	// 函数: sqlUpdate() 
	// 功能: Update更新数据的函数 
	// 参数: $taname 要插入数据的表名 
	// 参数: $row 要插入的内容 (数组) 
	// 参数: $where 要插入的内容 的条件 
	// 返回: Update语句 
	//====================================== 
	function sqlUpdate($tbname, $row, $where) {
		foreach ($row as $key => $value) {
			$sqlud .= $key . '= ' . $value . ',';
		}
		return 'UPDATE ' . $tbname . ' SET ' . substr($sqlud, 0, -1) . ' WHERE ' . $where;
	}
	
	//====================================== 
	// 函数: sqlDelete() 
	// 功能: 删除指定条件的行 
	// 参数: $taname 要插入数据的表名 
	// 参数: $where 要插入的内容 的条件 
	// 返回: DELETE语句 
	//====================================== 
	function sqlDelete($tbname, $where) {
		return 'DELETE FROM ' . $tbname . ' WHERE ' . $where;
	}
	
	
	//====================================== 
	//函数：checkSql SQL语句的过滤 
	//功能：过滤一些特殊语法 
	//参数：$db_string 查询的SQL语句 
	//参数：$querytype 查询的类型 
	//====================================== 
	function checkSql($db_string, $querytype = 'select') {
/*		
		$clean = '';
		$old_pos = 0;
		$pos = -1;
		
		//如果是普通查询语句，直接过滤一些特殊语法 
		if ($querytype == 'select') {
			$notallow1 = '[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}';
			//$notallow2 = '--|/\*'; 
			if (eregi($notallow1, $db_string)) {
				exit ('<font size="5" color="red">Safe Alert: Request Error step 1 !</font>');
			}
		}
		//完整的SQL检查 
		while (true) {
			$pos = strpos($db_string, '\'', $pos +1);
			if ($pos === false) {
				break;
			}
			$clean .= substr($db_string, $old_pos, $pos - $old_pos);
			while (true) {
				$pos1 = strpos($db_string, '\'', $pos +1);
				$pos2 = strpos($db_string, '\\', $pos +1);
				if ($pos1 === false) {
					break;
				}
				elseif ($pos2 == false || $pos2 > $pos1) {
					$pos = $pos1;
					break;
				}
				$pos = $pos2 +1;
			}
			$clean .= '$s$';
			$old_pos = $pos +1;
		}
		$clean .= substr($db_string, $old_pos);
		$clean = trim(strtolower(preg_replace(array (
			'~\s+~s'
		), array (
			' '
		), $clean)));
		//老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它 
		if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0) {
			$fail = true;
		}
		//发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们 
		elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false) {
			$fail = true;
		}
		//这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库 
		elseif (strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0) {
			$fail = true;
		}
		elseif (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])
		~s', $clean) != 0) {
			$fail = true;
		}
		elseif (strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])
		~s', $clean) != 0) {
			$fail = true;
		}
		elseif (strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^
		[a-z])~s', $clean) != 0) {
			$fail = true;
		}
		//老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息 
		elseif (preg_match('~\([^)]*?select~s', $clean) != 0) {
			$fail = true;
		}
		if (!empty ($fail)) {
			exit ('<font size="5" color="red">Safe Alert: Request Error step 2!</font>');
		} else {
			return $db_string;
		}
*/		
	}
	
	//====================================== 
	// 函数: getErrNo() 
	// 功能: 返回上一个 MySQL 操作中的错误信息的数字编码 
	// 参数: 无
	// 返回: 错误信息的数字编码
	//=====================================
    function getErrNo(){ 
        $this->errno = @mysql_errno($this->connection_id); 
        return $this->errno; 
    } 

	//====================================== 
	// 函数: halt($err_msg) 
	// 功能: 处理所有出错信息 
	// 参数: $err_msg 自定义的出错信息 
	//===================================== 
	public function halt($err_msg = '') {
		if ($err_msg == '') {
			$this->errno = mysql_errno();
			$this->error = mysql_error();
			echo '<b>mysql error:<b><br>';
			echo $this->errno . ':' . $this->error . '<br>';
		} else {
			echo '<b>mysql error:<b><br>';
			echo $err_msg . '<br>';
		}
		
		exit ();
	}
	
	//====================================== 
	//函数：close() 
	//功能：关闭非永久的数据库连接 
	//参数：无 
	//====================================== 
	public function close() {
		$link_id = $link_id ? $link_id : $this->link_id;
		mysql_close($link_id);
	}
	
	//====================================== 
	//函数：destory 
	//功能：释放类，关闭非永久的数据库连接 
	//====================================== 
	public function destory() {
		$this->close();
	}
}

?>
	