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
		if($rec['level']>=3) {
			$category = $rec['list'];
		} else {
			$category = '<a href="add.php?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		}
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
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$arr = explode("-",$_POST['cdate']);
	$_POST['cday'] = $arr[2];
	$_POST['cmonth'] = $arr[1];
	$_POST['cyear'] = $arr[0];
	if(is_numeric($_POST['timetaken'])) {
		if(preg_match("/(.*)\.([0-9]{0,2})$/",$_POST['timetaken'],$array)) {
			if($array[2]=="25" || $array[2]=="50" || $array[2]=="75" || $array[2]=="00" || $array[2]=="" || $array[2]=="5" || $array[2]=="0") {			
				//echo $array[0];
				//echo " allowed";
			} else {
				//echo $array[0];
				$_POST["MM_insert"] = "";
				$error = "<p class=error>Please Correct the entry. Entry should be like ".$array[1].", ".$array[1].".00, ".$array[1].".25, ".$array[1].".50, ".$array[1].".75 and not like ".$array[0]."</p>";
			}	
		} else {
			//echo $_POST['timetaken'];echo " allowed";
		}
	} else {
		$_POST["MM_insert"] = "";
		$error = "<p class=error>Time Taken should be numeric.</p>";
	}
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
  $insertSQL = sprintf("INSERT INTO procentris_timesheet (user_id, category, project, tasks, timetaken, cdate, cday, cmonth, cyear) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['category'], "int"),
                       GetSQLValueString($_POST['project'], "int"),
                       GetSQLValueString($_POST['tasks'], "int"),
                       GetSQLValueString($_POST['timetaken'], "double"),
                       GetSQLValueString($_POST['cdate'], "date"),
                       GetSQLValueString($_POST['cday'], "int"),
                       GetSQLValueString($_POST['cmonth'], "int"),
                       GetSQLValueString($_POST['cyear'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsUser = sprintf("SELECT * FROM procentris_users WHERE username = '%s'", $colname_rsUser);
$rsUser = mysql_query($query_rsUser, $conn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsList = "19";
if (isset($_GET['list_id'])) {
  $colname_rsList = (get_magic_quotes_gpc()) ? $_GET['list_id'] : addslashes($_GET['list_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsList = sprintf("SELECT a.list as task, b.list as project, c.list as cat, a.list_id as task_id, b.list_id as project_id, c.list_id as cat_id FROM procentris_list as a, procentris_list as b, procentris_list as c WHERE a.pid = b.list_id and b.pid = c.list_id and a.list_id = %s", $colname_rsList);
$rsList = mysql_query($query_rsList, $conn) or die(mysql_error());
$row_rsList = mysql_fetch_assoc($rsList);
$totalRows_rsList = mysql_num_rows($rsList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add Time</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.error {
	font-weight: bold;
	color: #FF0000;
	text-decoration: underline;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
<script language="javascript">
window.onload = function() {
	document.form1.timetaken.focus();
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
<h3>Add Work Time</h3>
<p><?php echo $displayLink; ?></p>
<?php echo $error; ?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('timetaken','','RisNum');return document.MM_returnValue">
  <p><strong>Category:</strong> <?php echo $row_rsList['cat']; ?></p>
  <p><strong>Project:</strong> <?php echo $row_rsList['project']; ?></p>
  <p><strong>Task: </strong><?php echo $row_rsList['task']; ?></p>
  <p><strong>Date:</strong><input name="cdate" type="text" id="cdate" value="<?php echo date('Y-m-d'); ?>">
  </p>
  <p><strong>Time Worked:</strong>    
    <input name="timetaken" type="text" id="timetaken" value="<?php echo $_POST['timetaken']; ?>">
  </p>
  <p>
    <input type="submit" name="Submit" value="Add Time">
    <input name="user_id" type="hidden" id="user_id" value="<?php echo $row_rsUser['user_id']; ?>">
    <input name="category" type="hidden" id="category" value="<?php echo $row_rsList['cat_id']; ?>">
    <input name="project" type="hidden" id="project" value="<?php echo $row_rsList['project_id']; ?>">
    <input name="tasks" type="hidden" id="tasks" value="<?php echo $row_rsList['task_id']; ?>">
    <input name="cday" type="hidden" id="cday" value="<?php echo date('d'); ?>">
	
    
    <input name="cmonth" type="hidden" id="cmonth" value="<?php echo date('m'); ?>">
    <input name="cyear" type="hidden" id="cyear" value="<?php echo date('Y'); ?>">
</p>
  <input type="hidden" name="MM_insert" value="form1">
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
mysql_free_result($rsUser);

mysql_free_result($rsList);
?>
