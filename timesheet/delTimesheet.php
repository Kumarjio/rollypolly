<?php require_once('../Connections/conn.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "Admin,SUPERADMIN";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../users/login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

  $coluserid_rsEdit = "0";
	if (isset($_GET['user_id'])) {
	  $coluserid_rsEdit = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
	}
	$colcat_rsEdit = "0";
	if (isset($_GET['category'])) {
	  $colcat_rsEdit = (get_magic_quotes_gpc()) ? $_GET['category'] : addslashes($_GET['category']);
	}
	$colproject_rsEdit = "0";
	if (isset($_GET['project'])) {
	  $colproject_rsEdit = (get_magic_quotes_gpc()) ? $_GET['project'] : addslashes($_GET['project']);
	}
	$coltask_rsEdit = "0";
	if (isset($_GET['task'])) {
	  $coltask_rsEdit = (get_magic_quotes_gpc()) ? $_GET['task'] : addslashes($_GET['task']);
	}
	$colday_rsEdit = "0";
	if (isset($_GET['d'])) {
	  $colday_rsEdit = (get_magic_quotes_gpc()) ? $_GET['d'] : addslashes($_GET['d']);
	}
	$colmonth_rsEdit = "0";
	if (isset($_GET['m'])) {
	  $colmonth_rsEdit = (get_magic_quotes_gpc()) ? $_GET['m'] : addslashes($_GET['m']);
	}
	$colyear_rsEdit = "0";
	if (isset($_GET['y'])) {
	  $colyear_rsEdit = (get_magic_quotes_gpc()) ? $_GET['y'] : addslashes($_GET['y']);
	}
$deleteSQL = sprintf("delete FROM procentris_timesheet WHERE procentris_timesheet.user_id = %s AND procentris_timesheet.category = %s AND procentris_timesheet.project = %s AND procentris_timesheet.tasks = %s AND procentris_timesheet.cday = '%s' AND procentris_timesheet.cmonth = '%s' AND procentris_timesheet.cyear = '%s'", $coluserid_rsEdit,$colcat_rsEdit,$colproject_rsEdit,$coltask_rsEdit,$colday_rsEdit,$colmonth_rsEdit,$colyear_rsEdit);

  //mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "timesheet_one.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>
