<?php
/*
 * Created on 2013-11-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Config {
	
	//数据库部分配置
	public static $db_server='localhost';   //数据库连接服务器
	public static $db_name='market_ad';     //数据库名称
	public static $db_username='root';      //数据库连接用户
	public static $db_password='cao042279'; //数据库连接密码
	public static $db_character='UTF8';     //数据库数据编码
	public static $db_linkMode='0';         //连接数据库模式（持久 或 临时）
	public static $fetchMode='MYSQL_ASSOC';
	
	//服务器部分配置
	public static $host='';
	public static $port=80;
	public static $seo_url='http://localhost:80/src/data/SEO_API.php';
	
	//加密部分配置
	public static $app_id='MyApp1';
	public static $app_key='2we8r6ac6c9g23dt46ba02d19c7915t2';   //randomly generated app key

}

?>
