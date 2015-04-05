<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connP2 = "localhost";
$database_connP2 = "consultl_p2";
$username_connP2 = "consultl_user";
$password_connP2 = "passwords123";
$connP2 = mysql_connect($hostname_connP2, $username_connP2, $password_connP2) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_connP2, $connP2);
?>