<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connAds = "localhost";
$database_connAds = "consultl_ads";
$username_connAds = "consultl_user";
$password_connAds = "passwords123";
$connAds = mysql_connect($hostname_connAds, $username_connAds, $password_connAds) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_connAds, $connAds) or die('could not select db');
$dsn_connAds = 'mysql:dbname='.$database_connAds.';host='.$hostname_connAds;

//adodb try
define('BASE_DIR', dirname(dirname(__FILE__)));
$site_path = BASE_DIR;//'/home135/sub004/sc29722-KLXJ';
include($site_path.'/libraries/adodb/adodb.inc.php');

$ADODB_CACHE_DIR = $site_path.'/cache/ADODB_cache';
$connAdsAdodb = ADONewConnection('mysql');
$connAdsAdodb->Connect($hostname_connAds, $username_connAds, $password_connAds, $database_connAds);
//$connAdodb->LogSQL();consultl_user, passwords123
?>