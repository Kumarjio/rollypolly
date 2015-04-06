<?php require_once('../Connections/connHm.php'); ?>
<?php
if(!$_COOKIE['user_id']) { 
	echo 'Please login first.';
	exit;
} 
include_once('start.php');
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if(!trim($_POST['title'])) {
		echo 'Please enter title.';
		exit;
	}
	$_POST['site_id'] = $_SESSION['site'][$_SESSION['siteurl']]['site_id'];
	$_POST['user_id'] = $_COOKIE['user_id'];
}
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO home_product_management (site_id, user_id, title, created) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['site_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['created'], "date"));

  mysql_select_db($database_connHm, $connHm);
  $Result1 = mysql_query($insertSQL, $connHm) or die(mysql_error());
}
echo "New Place Created Successfully.";
?>