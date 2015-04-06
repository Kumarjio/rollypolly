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
$colname_rsHome = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsHome = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsHome = sprintf("SELECT * FROM procentris_list, procentris_user_client, procentris_users WHERE procentris_list.pid = 0 AND procentris_list.list_id = procentris_user_client.list_id AND procentris_users.user_id = procentris_user_client.user_id AND procentris_users.username = '%s' ORDER BY procentris_list.list ASC", $colname_rsHome);
$rsHome = mysql_query($query_rsHome, $conn) or die(mysql_error());
$row_rsHome = mysql_fetch_assoc($rsHome);
$totalRows_rsHome = mysql_num_rows($rsHome);

$colname_rsUser = "0";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsUser = sprintf("SELECT * FROM procentris_users WHERE username = '%s'", $colname_rsUser);
$rsUser = mysql_query($query_rsUser, $conn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>View Clients</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript">
function toggleLayer(whichLayer, iState) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = iState? "":"none";
	}
}
function toggleLayer2(whichLayer) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	}
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
<h3>View Clients </h3>
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
  <tr valign="top">
    <td>
        <?php do { ?>
        <a href="add.php?pid=<?php echo $row_rsHome['list_id']; ?>&list_id=<?php echo $row_rsHome['list_id']; ?>"><?php echo $row_rsHome['list']; ?></a><br>
        <?php } while ($row_rsHome = mysql_fetch_assoc($rsHome)); ?>
      </td>
    <td align="right"><a href="javascript:;" onClick="javascript:toggleLayer2('todaysmenu');">View Today's Timesheet</a><br>
<div id="todaysmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getTodaysTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>
<br>
<a href="javascript:;" onClick="javascript:toggleLayer2('weeksmenu');">View Week's Timesheet</a><br>
<div id="weeksmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getWeeksTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>
<br>
<a href="javascript:;" onClick="javascript:toggleLayer2('lastweeksmenu');">View Last Week's Timesheet</a><br>
<div id="lastweeksmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
$time = time()-(60*60*24*7);
$returnArray = getWeeksTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div></td>
  </tr>
</table>
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
mysql_free_result($rsHome);

mysql_free_result($rsUser);
?>
