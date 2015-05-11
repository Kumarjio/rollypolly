<?php require_once('../../Connections/connGroupjole.php'); ?>
<?php session_start(); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$return = array();
$return['params'] = $_POST;

$colname_rsView = "-1";
if (isset($_POST['facebook_id'])) {
  $colname_rsView = $_POST['facebook_id'];
}
mysql_select_db($database_connGroupjole, $connGroupjole);
$query_rsView = sprintf("SELECT * FROM users WHERE facebook_id = %s", GetSQLValueString($colname_rsView, "text"));
$rsView = mysql_query($query_rsView, $connGroupjole) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$return['totalRows_rsView'] = $totalRows_rsView;

if ($totalRows_rsView == 0) {
    $_POST['facebook_auth'] = 1;
if (!empty($_POST)) {
  $insertSQL = sprintf("INSERT INTO users (email, name, gender, facebook_auth, facebook_id, first_name, last_name) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['facebook_auth'], "int"),
                       GetSQLValueString($_POST['facebook_id'], "text"),
                       GetSQLValueString($_POST['first_name'], "text"),
                       GetSQLValueString($_POST['last_name'], "text"));

  mysql_select_db($database_connGroupjole, $connGroupjole);
  $Result1 = mysql_query($insertSQL, $connGroupjole) or die(mysql_error());

    $return['insert'] = 1;
    
    $colname_rsView = "-1";
    if (isset($_POST['facebook_id'])) {
      $colname_rsView = $_POST['facebook_id'];
    }
    mysql_select_db($database_connGroupjole, $connGroupjole);
    $query_rsView = sprintf("SELECT * FROM users WHERE facebook_id = %s", GetSQLValueString($colname_rsView, "text"));
    $rsView = mysql_query($query_rsView, $connGroupjole) or die(mysql_error());
    $row_rsView = mysql_fetch_assoc($rsView);
    $totalRows_rsView = mysql_num_rows($rsView);
}
} else {
    //$return['row_rsView'] = $row_rsView;  
}

$_SESSION['MM_Username'] = $row_rsView['facebook_id'];
$_SESSION['MM_UserGroup'] = $row_rsView['access_level'];
$_SESSION['MM_UserId'] = $row_rsView['user_id'];
$_SESSION['MM_Name'] = $row_rsView['name'];     
echo json_encode($return);
?>
<?php
mysql_free_result($rsView);
?>