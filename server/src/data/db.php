<?php

/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 *
 ** �� ��: ���� MYSQL���ݿ�  Ȼ��� MYSQL ���и��ֲ���                                                  
* ------------------------------------------------------------------------------------------------------ *
* �� ��:     open           -- �������ݿ�                                                         
*          query          -- ���ݲ�ѯ                                                               
*          setFetchMode   -- ����ȡ�ü�¼��ģʽ                                                         
*          fetchRow       -- �Ӽ�¼����ȡ��һ����¼                                            
*          fetchAll       -- �Ӽ�¼����ȡ�����м�¼                                      
*          getValue       -- ���ؼ�¼��ָ���ֶε�����                                                    
*          getQueryId     -- ���ز�ѯ��                                                        
*          affectedRows   -- ����Ӱ��ļ�¼��                                               
*          recordCount    -- ���ز�ѯ��¼������          
*          getQueryTimes  -- ���ز�ѯ�Ĵ���                                     
*          sqlInsert      -- Insert�������ݺ���                                         
*          lastInsertID   -- �������һ�β��������ID                                                               
*          sqlUpdate      -- Update�������ݵĺ���  
*          sqlDelete      -- ɾ��ָ����������    
*          checkSql       -- SQL���Ĺ���, ����һЩ�����﷨  
*          getErrNo       -- ������һ�� MySQL �����еĴ�����Ϣ�����ֱ���             
*          halt           -- �������г�����Ϣ                      
*          close          -- �رշ����õ����ݿ�����    
*          destory        -- �ͷ��࣬�رշ����õ����ݿ�����                          
* ------------------------------------------------------------------------------------------------------ *
*/

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('./src/data/config.php');


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
	// ����: open() 
	// ����: �������ݿ� 
	// ����: ��
	// ����: 0:ʧ�� 
	// ˵��: Ĭ��ʹ�����б����ĳ�ʼֵ 
	//====================================== 
	public function open() {

		$this->server = Config.db_server;
		$this->user = Config.db_username;
		$this->password = Config.db_password;
		$this->database = Config.db_name;
		
		$this->linkMode = Config.db_linkMode;
		$this->character = Config.db_character;
		
		$this->link_id = $this->linkMode ? mysql_pconnect($this->server, $this->user, $this->password, $this->database) : 
		                                   mysql_connect($this->server, $this->user, $this->password, $this->database);
		
		if (!$this->link_id) {
			$this->halt('���ݿ�����ʧ�ܣ�������������');
			return 0;
		}
		
		if (!mysql_select_db($this->database, $this->link_id)) {
			$this->halt('�޷�ѡ�����ݿ�');
			return 0;
		}
			
		$this->query('SET NAMES ' . $this->character);
		
		return $this->link_id;
	}
	
	
	//====================================== 
	// ����: query($sql) 
	// ����: ���ݲ�ѯ 
	// ����: $sql Ҫ��ѯ��SQL��� 
	// ����: 0:ʧ�� 
	//====================================== 
	public function query($sql) {
		$this->query_times++;
		$this->query_id = mysql_query($sql, $this->link_id);
		if (!$this->query_id) {
			$this->halt('<font color=red>' . $sql . '</font> ���ִ�в��ɹ���');
			return 0;
		}
		return $this->query_id;
	}
	
	//====================================== 
	// ����: setFetchMode($mode) 
	// ����: ����ȡ�ü�¼��ģʽ 
	// ����: $mode ģʽ MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH 
	// ����: 0:ʧ�� 
	//====================================== 
	public function setFetchMode($mode) {
		if ($mode == MYSQL_ASSOC || $mode == MYSQL_NUM || $mode == MYSQL_BOTH) {
			$this->fetchMode = $mode;
			return 1;
		} else {
			$this->halt('�����ģʽ.');
			return 0;
		}
	}
	
	//====================================== 
	// ����: fetchRow() 
	// ����: �Ӽ�¼����ȡ��һ����¼ 
	// ����: 0: ���� record: һ����¼ 
	//====================================== 
	public function fetchRow() {
		$this->record = mysql_fetch_array($this->query_id, $this->fetchMode);
		return $this->record;
	}
	
	//====================================== 
	// ����: fetchAll() 
	// ����: �Ӽ�¼����ȡ�����м�¼ 
	// ����: ��¼������ 
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
	// ����: getValue() 
	// ����: ���ؼ�¼��ָ���ֶε����� 
	// ����: $field �ֶ������ֶ����� 
	// ����: ָ���ֶε�ֵ 
	//====================================== 
	public function getValue($filed) {
		return $this->record[$filed];
	}
	
	//====================================== 
	// ����: getQueryId() 
	// ����: ���ز�ѯ�� 
	//====================================== 
	public function getQueryId() {
		return $this->query_id;
	}
	
	//====================================== 
	// ����: affectedRows() 
	// ����: ����Ӱ��ļ�¼�� 
	//====================================== 
	public function affectedRows() {
		return mysql_affected_rows($this->link_id);
	}
	
	//====================================== 
	// ����: recordCount() 
	// ����: ���ز�ѯ��¼������ 
	// ����: �� 
	// ����: ��¼���� 
	//====================================== 
	public function recordCount() {
		return mysql_num_rows($this->query_id);
	}
	
	//====================================== 
	// ����: getQueryTimes() 
	// ����: ���ز�ѯ�Ĵ��� 
	// ����: �� 
	// ����: ��ѯ�Ĵ��� 
	//====================================== 
	public function getQueryTimes() {
		return $this->query_times;
	}
	
	//====================================== 
	// ����: sqlInsert() 
	// ����: Insert�������ݺ��� 
	// ����: $taname Ҫ�������ݵı��� 
	// ����: $row Ҫ��������� (����) 
	// ����: ��¼���� 
	// ����: ������� 
	//====================================== 
	function sqlInsert($tbname, $row) {
		foreach ($row as $key => $value) {
			$sqlfield .= $key . ',';
			$sqlvalue .= $value . ',';
		}
		return 'INSERT INTO ' . $tbname . '(' . substr($sqlfield, 0, -1) . ') VALUES (' . substr($sqlvalue, 0, -1) . ')';
	}
	
	//====================================== 
	// ����: lastInsertID() 
	// ����: �������һ�β��������ID 
	// ����: �� 
	//====================================== 
	public function lastInsertID() {
		return mysql_insert_id();
	}
	
	//====================================== 
	// ����: sqlUpdate() 
	// ����: Update�������ݵĺ��� 
	// ����: $taname Ҫ�������ݵı��� 
	// ����: $row Ҫ��������� (����) 
	// ����: $where Ҫ��������� ������ 
	// ����: Update��� 
	//====================================== 
	function sqlUpdate($tbname, $row, $where) {
		foreach ($row as $key => $value) {
			$sqlud .= $key . '= ' . $value . ',';
		}
		return 'UPDATE ' . $tbname . ' SET ' . substr($sqlud, 0, -1) . ' WHERE ' . $where;
	}
	
	//====================================== 
	// ����: sqlDelete() 
	// ����: ɾ��ָ���������� 
	// ����: $taname Ҫ�������ݵı��� 
	// ����: $where Ҫ��������� ������ 
	// ����: DELETE��� 
	//====================================== 
	function sqlDelete($tbname, $where) {
		return 'DELETE FROM ' . $tbname . ' WHERE ' . $where;
	}
	
	
	//====================================== 
	//������checkSql SQL���Ĺ��� 
	//���ܣ�����һЩ�����﷨ 
	//������$db_string ��ѯ��SQL��� 
	//������$querytype ��ѯ������ 
	//====================================== 
	function checkSql($db_string, $querytype = 'select') {
		
		$clean = '';
		$old_pos = 0;
		$pos = -1;
		
		//�������ͨ��ѯ��䣬ֱ�ӹ���һЩ�����﷨ 
		if ($querytype == 'select') {
			$notallow1 = '[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}';
			//$notallow2 = '--|/\*'; 
			if (eregi($notallow1, $db_string)) {
				exit ('<font size="5" color="red">Safe Alert: Request Error step 1 !</font>');
			}
		}
		//������SQL��� 
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
		//�ϰ汾��Mysql����֧��union�����õĳ�����Ҳ��ʹ��union������һЩ�ڿ�ʹ���������Լ���� 
		if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0) {
			$fail = true;
		}
		//�����汾�ĳ�����ܱȽ��ٰ���--,#������ע�ͣ����Ǻڿ;���ʹ������ 
		elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false) {
			$fail = true;
		}
		//��Щ�������ᱻʹ�ã����Ǻڿͻ������������ļ���down�����ݿ� 
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
		//�ϰ汾��MYSQL��֧���Ӳ�ѯ�����ǵĳ��������Ҳ�õ��٣����ǺڿͿ���ʹ��������ѯ���ݿ�������Ϣ 
		elseif (preg_match('~\([^)]*?select~s', $clean) != 0) {
			$fail = true;
		}
		if (!empty ($fail)) {
			exit ('<font size="5" color="red">Safe Alert: Request Error step 2!</font>');
		} else {
			return $db_string;
		}
	}
	
	//====================================== 
	// ����: getErrNo() 
	// ����: ������һ�� MySQL �����еĴ�����Ϣ�����ֱ��� 
	// ����: ��
	// ����: ������Ϣ�����ֱ���
	//=====================================
    function getErrNo(){ 
        $this->errno = @mysql_errno($this->connection_id); 
        return $this->errno; 
    } 

	//====================================== 
	// ����: halt($err_msg) 
	// ����: �������г�����Ϣ 
	// ����: $err_msg �Զ���ĳ�����Ϣ 
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
	//������close() 
	//���ܣ��رշ����õ����ݿ����� 
	//�������� 
	//====================================== 
	public function close() {
		$link_id = $link_id ? $link_id : $this->link_id;
		mysql_close($link_id);
	}
	
	//====================================== 
	//������destory 
	//���ܣ��ͷ��࣬�رշ����õ����ݿ����� 
	//====================================== 
	public function destory() {
		$this->close();
	}
}

?>
