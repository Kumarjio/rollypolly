<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connGroupjole = "localhost";
$database_connGroupjole = "consultl_groupjole";
$username_connGroupjole = "consultl_user";
$password_connGroupjole = "passwords123";
$connGroupjole = mysql_connect($hostname_connGroupjole, $username_connGroupjole, $password_connGroupjole) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_connGroupjole, $connGroupjole) or die('could not select db');
$dsn_connGroupjole = 'mysql:dbname='.$database_connGroupjole.';host='.$hostname_connGroupjole;

//adodb try
define('BASE_DIR', dirname(dirname(dirname(__FILE__))));
$site_path = BASE_DIR;//'/home135/sub004/sc29722-KLXJ';
include($site_path.'/libraries/adodb/adodb.inc.php');

$ADODB_CACHE_DIR = $site_path.'/cache/ADODB_cache';
$connGroupjoleAdodb = ADONewConnection('mysql');
$connGroupjoleAdodb->Connect($hostname_connGroupjole, $username_connGroupjole, $password_connGroupjole, $database_connGroupjole);
//$connAdodb->LogSQL();consultl_user, passwords123
?>