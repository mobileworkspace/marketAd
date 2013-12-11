<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('/../model/User.php');

class UserController{
 	
 	private $_params;
	
	public function __construct($params)
	{
		$this->_params = $params;
	}
	
	public function loginAction(){
		$user = new User();
		$data = $user->get($this->_params['login_username'],$this->_params['login_password']);
		
		if ($data==false) {
			header('Location: ../web/NewUser.php');
		}else{
			header('Location: ../web/login.php');
		}
		
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
