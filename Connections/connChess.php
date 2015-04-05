<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connChess = "localhost";
$database_connChess = "consultl_chess";
$username_connChess = "consultl_user";
$password_connChess = "passwords123";
$connChess = mysql_connect($hostname_connChess, $username_connChess, $password_connChess) or trigger_error(mysql_error(),E_USER_ERROR);  
mysql_select_db($database_connChess, $connChess) or die("error in selecting db");
?>