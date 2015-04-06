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
function catlink_categories2($cat_id,$q) {
	$query = "select * from procentris_list where list_id = '".$cat_id."'";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = $rec['list'];
		array_unshift($q,$category);
		catlink_categories2($pid,$q);	
	} else {
		foreach($q as $value) {
			$displayLink2 .= $value.' -> ';
		}
		$displayLink2 = substr($displayLink2,0,-4);
		echo $displayLink2;
	}
}
?>
<?php
$colname_rsDeleteList = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsDeleteList = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsDeleteList = sprintf("SELECT * FROM procentris_list, procentris_users WHERE procentris_list.user_id = procentris_users.user_id AND procentris_users.username = '%s' AND procentris_list.deleted = 1", $colname_rsDeleteList);
$rsDeleteList = mysql_query($query_rsDeleteList, $conn) or die(mysql_error());
$row_rsDeleteList = mysql_fetch_assoc($rsDeleteList);
$totalRows_rsDeleteList = mysql_num_rows($rsDeleteList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Deleted Task List</title>
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
<h3>My Deleted Task List</h3>
<?php if ($totalRows_rsDeleteList > 0) { // Show if recordset not empty ?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <td><strong>List Name</strong></td>
        <td><strong>Parent</strong></td>
        <td><strong>List Type </strong></td>
        <td><strong>Undelete</strong></td>
    </tr>
    <?php do { ?>
    <tr>
        <td><?php echo $row_rsDeleteList['list']; ?></td>
        <td>
	<?php
	$q = array();
	catlink_categories2($row_rsDeleteList['list_id'],$q);
	?>&nbsp;</td>
        <td><?php echo $row_rsDeleteList['list_type']; ?></td>
        <td><a href="undelete_task.php?del_id=<?php echo $row_rsDeleteList['list_id']; ?>">Undelete</a></td>
    </tr>
    <?php } while ($row_rsDeleteList = mysql_fetch_assoc($rsDeleteList)); ?>
</table>
<?php } // Show if recordset not empty ?>
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
mysql_free_result($rsDeleteList);
?>
