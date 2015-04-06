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
function getName($id) {
	$rs = mysql_query("select * from procentris_list where list_id = '".$id."'") or die('error');
	$rec = mysql_fetch_array($rs);
	return $rec['list'];
}
if($_POST) {
	foreach($_POST['timesheet_id'] as $key => $value1) {		
		$value = trim($_POST['timetaken'][$key]);
		$value = number_format($value,2);
		if(is_numeric($value)) {
			if(preg_match("/(.*)\.([0-9]{0,2})$/",$value,$array)) {
				if($array[2]=="25" || $array[2]=="50" || $array[2]=="75" || $array[2]=="00" || $array[2]=="" || $array[2]=="5" || $array[2]=="0") {			
					//echo $array[0];
					//echo " allowed";
					$query = "update procentris_timesheet set timetaken = '".$value."', cday = '".$_POST['cday'][$key]."', cmonth = '".$_POST['cmonth'][$key]."', cyear = '".$_POST['cyear'][$key]."', cdate = '".$_POST['cyear'][$key]."-".$_POST['cmonth'][$key]."-".$_POST['cday'][$key]."' where timesheet_id = '".$key."'"; 
					mysql_query($query) or die('error');
					if($value == "0.00" || $value == 0 || $value == "") {
						$query = "delete from procentris_timesheet where timesheet_id = '".$key."'"; 
						mysql_query($query) or die('error');
					}
					//$error = "<p class=error>Record Updated Successfully.</p>";
				} else {
					//echo $array[0];
					$_POST["MM_insert"] = "";
					$error .= "<p class=error>Please Correct the entry. Entry should be like ".$array[1].", ".$array[1].".00, ".$array[1].".25, ".$array[1].".50, ".$array[1].".75 and not like ".$array[0]."</p>";
					$failure = 1;
				}	
			} else {
				//echo $_POST['timetaken'];echo " allowed";
				$failure = 1;
			}
		} else {
			$_POST["MM_insert"] = "";
			$error .= "<p class=error>Time Taken should be numeric.</p>";
			$failure = 1;
		}
	}
	if($failure != 1) {
		header("Location: timesheet_one.php?user_id=".$_GET['user_id']."&mydate=".$_GET['mydate']);
		exit;
	}
}
?>
<?php
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
mysql_select_db($database_conn, $conn);
$query_rsEdit = sprintf("SELECT * FROM procentris_timesheet WHERE procentris_timesheet.user_id = %s AND procentris_timesheet.category = %s AND procentris_timesheet.project = %s AND procentris_timesheet.tasks = %s AND procentris_timesheet.cday = '%s' AND procentris_timesheet.cmonth = '%s' AND procentris_timesheet.cyear = '%s'", $coluserid_rsEdit,$colcat_rsEdit,$colproject_rsEdit,$coltask_rsEdit,$colday_rsEdit,$colmonth_rsEdit,$colyear_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Timing</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
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
<h3>Edit Timesheet</h3>
<p><a href="timesheet_one.php?user_id=<?php echo $_GET['user_id']; ?>&mydate=<?php echo $_GET['mydate']; ?>">Back To Timesheet </a></p>
<?php echo $error; ?>
<form name="form1" method="post" action="">
  <table border="1" cellpadding="5" cellspacing="0">
    <tr valign="top">
      <td><strong>Category</strong></td>
      <td><strong>Project</strong></td>
      <td><strong>Task</strong></td>
      <td><strong>Day</strong></td>
      <td><strong>Month</strong></td>
      <td><strong>Year</strong></td>
      <td><strong>TimeTaken</strong></td>
    </tr>
    <?php do { ?>
    <tr valign="top">
      <td><?php echo getName($row_rsEdit['category']); ?></td>
      <td><?php echo getName($row_rsEdit['project']); ?></td>
      <td><?php echo getName($row_rsEdit['tasks']); ?></td>
      <td><input type="text" name="cday[<?php echo $row_rsEdit['timesheet_id']; ?>]" value="<?php echo $row_rsEdit['cday']; ?>"></td>
      <td><input type="text" name="cmonth[<?php echo $row_rsEdit['timesheet_id']; ?>]" value="<?php echo $row_rsEdit['cmonth']; ?>"></td>
      <td><input type="text" name="cyear[<?php echo $row_rsEdit['timesheet_id']; ?>]" value="<?php echo $row_rsEdit['cyear']; ?>"></td>
      <td><input type="text" name="timetaken[<?php echo $row_rsEdit['timesheet_id']; ?>]" value="<?php echo $row_rsEdit['timetaken']; ?>"><input type="hidden" name="timesheet_id[<?php echo $row_rsEdit['timesheet_id']; ?>]" value="<?php echo $row_rsEdit['timesheet_id']; ?>"></td>
    </tr>
    <?php } while ($row_rsEdit = mysql_fetch_assoc($rsEdit)); ?>
    <tr>
      <td colspan="7"><input type="submit" name="Submit" value="Update"></td>
    </tr>
  </table>
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
