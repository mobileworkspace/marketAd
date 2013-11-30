<?php

/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
session_start(); 

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('/../src/data/config.php');

function excute() {
	
	//Define our id-key pairs  
	$applications = array( Config.app_id => Config.app_key);
	
	//��һ��try-catch���а������д��룬���������п��ܵ��쳣!  
	try {
		
		//�����POST/GET request�е����в���  
		$params = $_POST;
		
		//get the encrypted request  
	    $enc_request = $_POST['enc_request'];  
	      
	    //get the provided app id  
	    $app_id = $_POST['app_id'];  
	      
	    //check first if the app id exists in the list of applications  
	    if( !isset($applications[$app_id]) ) {  
	        throw new Exception('Application does not exist!');  
	    }  
	      
	    //decrypt the request  
	    $params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB)));  
	      
	    //check if the request is valid by checking if it's an array and looking for the controller and action  
	    if( $params == false || isset($params->controller) == false || isset($params->action) == false ) {  
	        throw new Exception('Request is not valid'); 
	    }  
	    
		//��ȡcontroller��������ȷ�ĸ�ʽ��ʹ�õ�һ����ĸ���Ǵ�д��  
		$controller = ucfirst(strtolower($params['controller']));
	
		//��ȡaction��������ȷ�ĸ�ʽ����ʹ�����е���ĸ����Сд�ģ���׷��һ��'Action'  
		$action = $params['action'] . 'Action';
	
		//���controller�Ƿ���ڡ���������ڣ��׳��쳣  
		if (file_exists("controllers/{$controller}.php")) {
			include_once "controllers/{$controller}.php";
		} else {
			throw new Exception('Controller is invalid.');
		}
		
		//��¼controller������
		$controller_name = $controller;
		
		//����һ���µ�controllerʵ�������Ѵ�request�л�ȡ�Ĳ���������  
		$controller = new $controller ($params);
	
		//���controller���Ƿ����action����������ڣ��׳��쳣��  
		if (method_exists($controller, $action) === false) {
			throw new Exception('Action is invalid.');
		}
	
		if ( !(strcmp($controller_name,'UserController')==0 && 
		         (strcmp($action,'loginAction')==0 || strcmp($action,'registerAction')==0 )
		      )
		   ) {
	    	
	    	if ( $_SESSION['user']==null ) {
	//			echo '���ȵ�¼ϵͳ';
				header('Location: index.php');
				exit();
			}
	    }
	
		//ִ��action  
		$result['data'] = $controller-> $action ();
		$result['success'] = true;
	
	} catch (Exception $e) {
		//�����κ�һ�ή������ұ�������  
		$result = array ();
		$result['success'] = false;
		$result['errormsg'] = $e->getMessage();
	}
	
	return $result['data'];
}

excute();

exit();

?>
