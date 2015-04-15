<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conn = "localhost";
$database_conn = "consultl_dhtmlchess";
$username_conn = "consultl_user";
$password_conn = "passwords123";
$conn = mysql_connect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>