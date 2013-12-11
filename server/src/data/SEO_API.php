<?php

/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
session_start(); 

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('./config.php');

function excute() {
	
	//Define our id-key pairs  
	$applications = array( Config::$app_id => Config::$app_key);
	
	//在一个try-catch块中包含所有代码，来捕获所有可能的异常!  
	try {
		
		//获得在POST/GET request中的所有参数 
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
	    $params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $applications[$app_id], base64_decode($enc_request), MCRYPT_MODE_ECB)), true);  
	      
	    //check if the request is valid by checking if it's an array and looking for the controller and action  
	    if( $params == false || isset($params['controller']) == false || isset($params['action']) == false ) {  
	        throw new Exception('Request is not valid'); 
	    }  
	    
		//获取controller并把它正确的格式化使得第一个字母总是大写的
		//$controller = ucfirst(strtolower($params['controller']));
		$controller = $params['controller'];
	
		//获取action并把它正确的格式化，使它所有的字母都是小写的，并追加一个'Action'  
		$action = $params['action'] . 'Action';
	
		//检查controller是否存在。如果不存在，抛出异常
		$pathfile = "../controllers/{$controller}.php";
		if (file_exists($pathfile)) {
			include_once $pathfile;
		} else {
			throw new Exception('Controller is invalid.');
		}
		
		//记录controller的名称
		$controller_name = $controller;
		
		//创建一个新的controller实例，并把从request中获取的参数传给它  
		$controller = new $controller($params);
	
		//检查controller中是否存在action。如果不存在，抛出异常。
		if (method_exists($controller, $action) === false) {
			throw new Exception('Action is invalid.');
		}
	
		if ( !(strcmp($controller_name,'UserController')==0 && 
		         (strcmp($action,'loginAction')==0 || strcmp($action,'registerAction')==0 )
		      )
		   ) {
	    	
	    	if ( $_SESSION['user']==null ) {
//			echo '请先登录系统';
				header('Location: index.php');
				exit();
			}
	    }
	
		//执行action   
		$data = $controller-> $action();
		if ($data==null){
			$result['data'] = '';
			$result['success'] = false;
		}else{
			$result['data'] = $data;
			$result['success'] = true;
		}
	
	} catch (Exception $e) {
		//捕获任何一次異常并且报告问题  
		$result = array ();
		$result['success'] = false;
		$result['errormsg'] = $e->getMessage();
	}
	
	echo @json_encode($result);
}

excute();

exit();

?>
