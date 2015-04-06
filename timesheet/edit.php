<?php require_once('../Connections/conn.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE procentris_list SET list=%s WHERE list_id=%s",
                       GetSQLValueString($_POST['list'], "text"),
                       GetSQLValueString($_POST['list_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsEdit = "-1";
if (isset($_GET['edit_id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_GET['edit_id'] : addslashes($_GET['edit_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsEdit = sprintf("SELECT * FROM procentris_list WHERE list_id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<?php
function catlink_categories1($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."'";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = '<a href="add.php?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		array_unshift($q,$category);
		catlink_categories1($pid,$q);	
	} else {
		$cat_id = 0;
		$pid = 0;
		$category = '<a href="addhome.php?s='.$s.'&list_id='.$cat_id.'&pid='.$pid.'">Home</a>';
		array_unshift($q,$category);
		foreach($q as $value) {
			$displayLink .= $value.' -> ';
		}
		$displayLink = substr($displayLink,0,-4);
	}
}

function catlink_categories3($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."'";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
	$rec = mysql_fetch_array($rs);
	if($rec['level']>=3) {
		$query .= " and user_id = '".$_SESSION['MM_UserId']."'";
		$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		$rec = mysql_fetch_array($rs);
	}
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = '<a href="add.php?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		array_unshift($q,$category);
		catlink_categories3($pid,$q);	
	} else {
		$cat_id = 0;
		$pid = 0;
		$category = '<a href="addhome.php?s='.$s.'&list_id='.$cat_id.'&pid='.$pid.'">Home</a>';
		array_unshift($q,$category);
		foreach($q as $value) {
			$displayLink .= $value.' -> ';
		}
		$displayLink = substr($displayLink,0,-4);
	}
}
$rs = mysql_query("select * from procentris_list where list_id = '".$pid."' and user_id = '".$_SESSION['MM_UserId']."'") or die("Error in line no ".__LINE__." of File ".__FILE__." with error: ".mysql_error());
if(mysql_num_rows($rs)>0) {
	$rec = mysql_fetch_array($rs);
	$level_parent = $rec['level'];
	$level_child = $level_parent + 1;	
}
if(!$level_child) $level_child = 2;
if($level_child==1) {
	$list_type = "Category";
} else if($level_child==2) {
	$list_type = "Project";
} else if($level_child==3) {
	$list_type = "Task";
} 
// display location string
$q = array();
if($list_id) {
	if($level_child==2) {
		catlink_categories1($list_id,$q);
	} else {
		catlink_categories3($list_id,$q);
	}
} else {
	$displayLink = "<a href='addhome.php'>Home</a>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript">
window.onload = function() {
	document.form1.list.select();
}
</script>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
body,td,th,select,input,submit,button,div,p {
	font-family: Verdana;
	font-size: 11px;
}
body {
	background-color: #B5D452;
}
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="800" border="2" align="center" cellpadding="1" cellspacing="0" bordercolor="#000000" bgcolor="#FFFFFF" height="500">
  <tr>
    <td valign="top"><table width="100%"  border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td valign="top"><img src="../images/logo_hirez3.gif" width="100" height="100"></td>
        <td valign="top"><h1>Procentris Time Reporting System </h1> </td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><a href="../index.php">Home</a><?php if(!$_SESSION['MM_Username']) { ?> | <a href="../users/register.php">Register</a> | <a href="../users/login.php">Login</a><?php } ?><?php if($_SESSION['MM_Username']) { ?> | <a href="addhome.php">Add Timesheet</a> | <a href="timesheet_new.php">Add Timesheet <span class="style1">(NEW)</span></a> | <a href="timesheet.php">Timesheet</a> | <a href="deleted_task_list.php">Deleted Task List</a> | <a href="user_leavebalance.php">Leave Balance</a> | <a href="../users/edit.php">Edit Details</a> | <a href="../users/logout.php">Logout</a><?php } ?><?php if($_SESSION['MM_UserGroup']=="Admin" || $_SESSION['MM_UserGroup']=="SUPERADMIN") { ?> | <a href="admin.php">Admin</a>          <?php } ?>
          <?php if($_SESSION['MM_Username']) { ?><br>
          You are logged in as: <?php echo $_SESSION['MM_Username']; ?><?php } ?></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
<!-- InstanceBeginEditable name="EditRegion3" -->
<h3>Edit Category</h3>
<p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a></p>
<p>  <?php echo $displayLink; ?></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">List:</td>
      <td><input type="text" name="list" value="<?php echo $row_rsEdit['list']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Update record"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="list_id" value="<?php echo $row_rsEdit['list_id']; ?>">
</form>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
</td>
      </tr>
    </table></td>
  </tr>
</table>
<?php include('../end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEdit);
?>
