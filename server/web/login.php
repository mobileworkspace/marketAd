<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

session_start();  

set_include_path('./'.PATH_SEPARATOR.dirname(__FILE__));
require_once('../src/data/config.php');
require_once('../src/data/ApiCaller.php'); 

$apicaller = new ApiCaller(Config::$app_id, Config::$app_key, Config::$seo_url);  
 
$result = $apicaller->callApi(array(
    'controller' => $_POST['controller'],
    'action' => $_POST['action'],    
    'username' => $_POST['login_username'],  
    'userpass' => $_POST['login_password']
));  

if ( $result['success']==true ) { //登录成功
 
	$_SESSION['user'] = $result['data'];
	
	header('Location: ./NewUser.php');
	
}else{
	
	print "登录失败，原因：".$result['errormsg'];
	header('Location: ../index.php');
}

exit();  

?>
