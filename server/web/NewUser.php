<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
session_start();

require_once(dirname(__FILE__).'/../src/data/config.php');
require_once(dirname(__FILE__).'/../src/data/ApiCaller.php');

$apicaller = new ApiCaller($app_id, $app_key, $seo_url); 
 
$apicaller->checkLogin();

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
