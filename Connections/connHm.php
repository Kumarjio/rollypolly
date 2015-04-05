<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connHm = "localhost";
$database_connHm = "consultl_socialnetwork";
$username_connHm = "consultl_user";
$password_connHm = "passwords123";
$conn = mysql_connect($hostname_connHm, $username_connHm, $password_connHm) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_connHm, $connHm);
?>