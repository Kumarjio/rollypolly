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
if($_POST['Submit']) {
	mysql_query("delete from procentris_user_client where user_id = '".$_GET['user_id']."'") or die("error");
	if($_POST['list_id'][0]=="-1") {
	
	} else {
		foreach($_POST['list_id'] as $value) {
			mysql_query("insert into procentris_user_client set user_id = '".$_GET['user_id']."', list_id = '".$value."'") or die("error");
		}
	}
}
$client = array();
?>
<?php
$colname_rstakeuser = "1";
if (isset($_GET['user_id'])) {
  $colname_rstakeuser = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_conn, $conn);
$query_rstakeuser = sprintf("SELECT * FROM procentris_users WHERE user_id = %s", $colname_rstakeuser);
$rstakeuser = mysql_query($query_rstakeuser, $conn) or die(mysql_error());
$row_rstakeuser = mysql_fetch_assoc($rstakeuser);
$totalRows_rstakeuser = mysql_num_rows($rstakeuser);

$colname_rsRel = "1";
if (isset($_GET['user_id'])) {
  $colname_rsRel = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsRel = sprintf("SELECT * FROM procentris_user_client WHERE user_id = %s", $colname_rsRel);
$rsRel = mysql_query($query_rsRel, $conn) or die(mysql_error());
$row_rsRel = mysql_fetch_assoc($rsRel);
$totalRows_rsRel = mysql_num_rows($rsRel);

$colname_rsShow = "-2";
if (isset($_GET['user_id'])) {
  $colname_rsShow = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsShow = sprintf("SELECT procentris_list.list FROM procentris_user_client, procentris_list WHERE procentris_list.list_id = procentris_user_client.list_id AND procentris_user_client.user_id = %s", $colname_rsShow);
$rsShow = mysql_query($query_rsShow, $conn) or die(mysql_error());
$row_rsShow = mysql_fetch_assoc($rsShow);
$totalRows_rsShow = mysql_num_rows($rsShow);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add Client</title>
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
<h3>Add Client To User
  <?php echo $row_rstakeuser['name']; ?></h3>
<p><a href="allusers.php">Back To User Management</a> </p>
<?php if ($totalRows_rsRel > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php $client[] = $row_rsRel['list_id']; ?>
<?php } while ($row_rsRel = mysql_fetch_assoc($rsRel)); ?>
<?php } // Show if recordset not empty ?>
<form name="form1" method="post" action="">
  <table border="1" cellspacing="0" cellpadding="5">
    <tr valign="top">
      <td><strong>Client</strong><br>
	    <?php if ($totalRows_rsShow > 0) { // Show if recordset empty ?>
		<ol>
        <?php do { ?>
        <li><?php echo $row_rsShow['list']; ?></li>
        <?php } while ($row_rsShow = mysql_fetch_assoc($rsShow)); ?>
		</ol>
        <?php } // Show if recordset empty ?> 
	  </td>
      <td><select name="list_id[]" size="10" multiple id="list_id[]">
	  <option value="-1">Select Client</option>
	  <?php
	  $query = "SELECT * FROM procentris_list WHERE pid = '0' ORDER BY list ASC";
	  $rs = mysql_query($query) or die("error");
	  while($rec = mysql_fetch_array($rs)) {
	  	?>
		<option value="<?php echo $rec['list_id']; ?>"<?php if(in_array($rec['list_id'],$client)) { echo ' selected'; } ?>><?php echo $rec['list']; ?></option>
		<?php
	  }
	  ?>
      </select></td>
    </tr>
    <tr valign="top">
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add Client">        </td>
    </tr>
  </table>
</form>
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
mysql_free_result($rstakeuser);

mysql_free_result($rsRel);

mysql_free_result($rsShow);
?>
