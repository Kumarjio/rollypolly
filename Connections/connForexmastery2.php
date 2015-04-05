<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connForexmastery2 = "remote-mysql3.servage.net";
$database_connForexmastery2 = "forexmastery2";
$username_connForexmastery2 = "forexmastery2";
$password_connForexmastery2 = "passwords123";
$connForexmastery2 = mysql_connect($hostname_connForexmastery2, $username_connForexmastery2, $password_connForexmastery2) or trigger_error(mysql_error(),E_USER_ERROR); 
?>