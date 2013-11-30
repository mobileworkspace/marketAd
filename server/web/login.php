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
require_once('/../src/data/ApiCaller.php'); 

$apicaller = new ApiCaller(Config.app_id, Config.app_key, Config.seo_url);  
 
$result = $apicaller->callApi(array(
    'controller' => $_POST['controller'],
    'action' => $_POST['action'],    
    'username' => $_POST['login_username'],  
    'userpass' => $_POST['login_password']
));  


if ( $result['success']==true ) { //��¼�ɹ�
 
	$_SESSION['user'] = $result;
	
	header('Location: xxx.php');
	
}else{
	
	echo '��¼ʧ�ܣ�ԭ��'.$result['errormsg'];
	header('Location: /../index.php');
}

exit();  

?>
