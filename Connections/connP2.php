<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connP2 = "localhost";
$database_connP2 = "consultl_p2";
$username_connP2 = "user";
$password_connP2 = "password";
$connP2 = mysql_connect($hostname_connP2, $username_connP2, $password_connP2) or die(mysql_error());
mysql_select_db($database_connP2, $connP2);
?>