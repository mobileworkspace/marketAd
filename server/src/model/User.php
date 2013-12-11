<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('../data/db.php');

class User {
 	
 	public function get($username,$userpass) {
 		
 		$mysql = new Mysql();
 		
 		if($mysql->open()){
		
 			$sql = 'select * from customer where name = "' . $username . '" and passwd = "' . $userpass . '"';
			$queryId = $mysql->query($sql);
			if ( $queryId==0 ) {
				throw new Exception('access database fail.');
			}
			
			$data = $mysql->fetchRow();
			
			if ($data == false) {
				return null;
			}
			
			return $data;
 		}
 		
		return null;
	}
 }
 
?>
