<?php

/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 
function excute() {
	
	//在一个try-catch块中包含所有代码，来捕获所有可能的异常!  
	try {
		
		$params = $_REQUEST;
		
		$controller = $params['controller'];
	
		//获取action并把它正确的格式化，使它所有的字母都是小写的，并追加一个'Action'  
		$action = $params['action'] . 'Action';
	
		//检查controller是否存在。如果不存在，抛出异常
		$pathfile = "./src/controllers/{$controller}.php";
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
	
		//执行action   
		$data = $controller-> $action();
	    
	} catch (Exception $e) {

	}
}

excute();

exit();

?>
