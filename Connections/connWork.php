<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connWork = "localhost";
$database_connWork = "consultl_work";
$username_connWork = "consultl_user";
$password_connWork = "passwords123";
$connWork = mysql_connect($hostname_connWork, $username_connWork, $password_connWork) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_connWork, $connWork) or die('could not select db');
$dsn_connWork = 'mysql:dbname='.$database_connWork.';host='.$hostname_connWork;

//adodb try
define('BASE_DIR', dirname(dirname(__FILE__)));
$site_path = BASE_DIR;//'/home135/sub004/sc29722-KLXJ';
include($site_path.'/libraries/adodb/adodb.inc.php');

$ADODB_CACHE_DIR = $site_path.'/cache/ADODB_cache';
$connWorkAdodb = ADONewConnection('mysql');
$connWorkAdodb->Connect($hostname_connWork, $username_connWork, $password_connWork, $database_connWork);
//$connAdodb->LogSQL();consultl_user, passwords123
?>