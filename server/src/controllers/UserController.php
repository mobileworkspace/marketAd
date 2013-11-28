<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class UserController{
 	
 	private $_params;
	
	public function __construct($params)
	{
		$this->_params = $params;
	}
	
	public function loginAction($username,$userpass){
		$user = new User();
		$user.get($username,$userpass);
	}
	
	public function createAction()
	{
		//create a new User

	}
	
	public function readAction()
	{
		
		
	}
	
	public function updateAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
	
 }
 
?>
