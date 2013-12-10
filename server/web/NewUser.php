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
	'controller' => 'todo',
	'action' => 'create',
	'title' => $_POST['title'],
	'due_date' => $_POST['due_date'],
	'description' => $_POST['description'],
	'username' => $_SESSION['username'],
	'userpass' => $_SESSION['userpass']
));

//根据结果进行重定向
if ( $result ) {
	header('Location: xxx.php');
}else {
	header('Location: xxx.php');
}


exit();


?>
