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
	header('Location: index.php');
}

exit();  

?>
