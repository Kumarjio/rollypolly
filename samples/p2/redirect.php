<?php require_once('../../Connections/connP2.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if (empty($_GET['url'])) {
	header("Location: /");
	exit;
}
$clicked_time = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
if ((isset($_GET["id"]))) {
  $insertSQL = sprintf("INSERT INTO tracking (main_image_id, image_detail_id, clicked_time, ip) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_GET['id'], "int"),
                       GetSQLValueString($_GET['did'], "int"),
                       GetSQLValueString($clicked_time, "date"),
                       GetSQLValueString($ip, "text"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($insertSQL, $connP2) or die(mysql_error());
}
$url = urldecode($_GET['url']);

header("Location: $url");
exit;
?>