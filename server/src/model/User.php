<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class User {
 	
 	public function get($username,$userpass) {
 		
 		$mysql = new Mysql();
 		
 		if($mysql->open()){
		
			$queryId = $mysql->query('select * from customer where name = "' . 
			                         $username . 'and passwd = "' . $userpass);
			if ( $queryId==0 ) {
				throw new Exception('access database fail.');
			}
			
			return $mysql.fetchRow();
 		}
 		
		return null;
	}
 }
 
?>
