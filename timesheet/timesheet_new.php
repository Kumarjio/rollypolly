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
if($_POST['list']) {
	if(trim($_POST['list'])!="") {
		$query = "insert into procentris_list(list, pid, level, list_type, user_id) values('".addslashes(stripslashes($_POST['list']))."', '".$_POST['pid']."', '".$_POST['level']."', '".$_POST['list_type']."', '".$_POST['user_id']."')";
		mysql_query($query) or die("could not insert");
		$message = 'New Category Added Successfully';
		$success = 1;
	} else {
		// category name not selected, so give the error.
		$message = 'Please enter Category Name.';
	}
}
if($_SESSION['MM_Username']) {
	$rs = mysql_query("select * from procentris_users where username = '".$_SESSION['MM_Username']."'") or die("error");
	$rec = mysql_fetch_array($rs);
	$_GET['user_id'] = $rec['user_id'];
	$_SESSION['MM_UserId'] = $rec['user_id'];
	$user_id = $rec['user_id'];
}
function getCategory($u, $fromdate, $todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	$query = "select DISTINCT pt.category, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.category = pl.list_id";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$categories[$rec['category']] = $rec['list'];
	}
	return $categories;
}
function getProject($u, $categoryKey, $fromdate, $todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	$query = "select DISTINCT pt.project, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.project = pl.list_id";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$projects[$rec['project']] = $rec['list'];
	}
	return $projects;
}
function getTask($u,$categoryKey,$projectKey,$fromdate,$todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	if(!$projectKey) $projectKey = '%';
	$query = "select DISTINCT pt.tasks, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.project like '".$projectKey."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.tasks = pl.list_id";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$tasks[$rec['tasks']] = $rec['list'];
	}
	return $tasks;
}

function getTime($u,$categoryKey,$projectKey,$taskKey,$date) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	if(!$projectKey) $projectKey = '%';
	if(!$taskKey) $taskKey = '%';
	$query = "select sum(pt.timetaken) as tm from procentris_timesheet as pt where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.project like '".$projectKey."' and pt.tasks like '".$taskKey."' and pt.cdate = '".$date."'";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$timetaken = $rec['tm'];
	}
	return $timetaken;
}

function getDays($time='') {
	if(!$time) {
		$time = mktime(0,0,0,date('m'),date('d'),date('Y'));
	}
	$today = getdate($time);
	//print_r($today);
	switch($today['wday']) {
		case 1: // monday
			$monday = $time+(60*60*24*0); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time+(60*60*24*1); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*2); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*3); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*4); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*5); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*6); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 2: // tuesday
			$monday = $time-(60*60*24*1); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time+(60*60*24*0); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*1); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*2); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*3); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*4); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*5); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 3: // wednesday
			$monday = $time-(60*60*24*2); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*1); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*0); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*1); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*2); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*3); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*4); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 4: // thursday
			$monday = $time-(60*60*24*3); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*2); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*1); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*0); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*1); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*2); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*3); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 5: // friday
			$monday = $time-(60*60*24*4); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*3); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*2); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*1); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*0); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*1); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*2); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 6: // saturday
			$monday = $time-(60*60*24*5); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*4); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*3); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*2); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time-(60*60*24*1); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*0); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*1); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 0: // sunday
			$monday = $time-(60*60*24*6); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*5); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*4); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*3); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time-(60*60*24*2); 
			$arr['friday'] = getdate($friday);
			$saturday = $time-(60*60*24*1); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*0); 
			$arr['sunday'] = getdate($sunday);
			break;
	}
	return $arr;
}
?>
<?php
$colname_rsTimesheet = "-1";
if (isset($user_id)) {
  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
}
mysql_select_db($database_conn, $conn);
$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
$rsTimesheet = mysql_query($query_rsTimesheet, $conn) or die(mysql_error());
$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);

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
<title>Timesheet</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="js.js"></script>
<script language="javascript">
window.onload = function() {
	if("<?php echo $_POST['showCategory']; ?>") {
		toggleLayer2("<?php echo $_POST['showCategory']; ?>");
	}
	if("<?php echo $_POST['showProject']; ?>") {
		toggleLayer2("<?php echo $_POST['showProject']; ?>");
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
<h3>Timesheet</h3>
<form name="form1" method="get" action="">
   Date: 
   <input name="mydate" type="text" id="mydate" value="<?php if($_GET['mydate']) echo $_GET['mydate']; else echo date('Y-m-d'); ?>" size="15">
   <input type="submit" name="Submit" value="Search">
  <input name="user_id" type="hidden" id="user_id" value="<?php echo $_GET['user_id']; ?>">
</form>
<a href="excel3.php?user_id=<?php echo $_GET['user_id']; ?>&mydate=<?php if($_GET['mydate']) echo $_GET['mydate']; else echo date('Y-m-d'); ?>">Formatted Excel Sheet for <?php if($_GET['mydate']) echo $_GET['mydate']; else echo date('Y-m-d'); ?></a>
<?php
if($_GET['mydate']) {
		$time = strtotime($_GET['mydate']);
	} else {
		$time = time();
	}
	$arr = getDays($time);
  $fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
  $todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td valign="top">
<?php
// get array
include_once('f_workinghours.php');

// end array
$result = getTimeSheetNew2($time,$_GET['user_id']);
echo $result;
?></td>
    <td valign="top" align="right"><?php include('workTime.inc.php'); ?></td>
  </tr>
</table>

<p>&nbsp; </p>
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
mysql_free_result($rsTimesheet);

mysql_free_result($rsUser);
?>
