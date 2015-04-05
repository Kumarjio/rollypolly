<?php require_once(dirname(dirname(dirname(__FILE__))).'/Connections/connMain.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
function save($user) {
	global $database_connMain, $connMain;
  $insertSQL = sprintf("select * from google_auth WHERE `uid` = %s",
                       GetSQLValueString($user['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain);
  $rec = mysql_fetch_array($Result1);
  if (!empty($rec)) {
  $insertSQL = sprintf("UPDATE google_auth set email = %s, gender = %s, name = %s, `uid` = %s, link = %s, picture = %s WHERE `uid`=%s",
                       GetSQLValueString($user['email'], "text"),
                       GetSQLValueString($user['gender'], "text"),
                       GetSQLValueString($user['name'], "text"),
                       GetSQLValueString($user['id'], "text"),
                       GetSQLValueString($user['link'], "text"),
                       GetSQLValueString($user['picture'], "text"),
                       GetSQLValueString($user['id'], "text"));
  } else {
    
  $insertSQL = sprintf("Insert into google_auth set email = %s, gender = %s, name = %s, `uid` = %s, link = %s, picture = %s",
                       GetSQLValueString($user['email'], "text"),
                       GetSQLValueString($user['gender'], "text"),
                       GetSQLValueString($user['name'], "text"),
                       GetSQLValueString($user['id'], "text"),
                       GetSQLValueString($user['link'], "text"),
                       GetSQLValueString($user['picture'], "text"));
  }

  mysql_select_db($database_connMain, $connMain);
  $Result1 = @mysql_query($insertSQL, $connMain);
  if (empty($Result1))
  	throw new Exception(mysql_error());
}

?>