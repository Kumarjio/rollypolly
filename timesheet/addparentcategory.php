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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if(trim($_POST['list'])=="") {
		$_POST["MM_insert"] = "";
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
  $insertSQL = sprintf("INSERT INTO procentris_list (list, list_type, pid, user_id, `level`, color) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['list'], "text"),
                       GetSQLValueString($_POST['list_type'], "text"),
                       GetSQLValueString($_POST['pid'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['level'], "int"),
                       GetSQLValueString($_POST['color'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE procentris_list SET list=%s, color=%s WHERE list_id=%s",
                       GetSQLValueString($_POST['list'], "text"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['list_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
}

mysql_select_db($database_conn, $conn);
$query_rsHome = "SELECT * FROM procentris_list WHERE pid = 0 ORDER BY list ASC";
$rsHome = mysql_query($query_rsHome, $conn) or die(mysql_error());
$row_rsHome = mysql_fetch_assoc($rsHome);
$totalRows_rsHome = mysql_num_rows($rsHome);

$colname_rsEdit = "0";
if (isset($_GET['list_id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_GET['list_id'] : addslashes($_GET['list_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsEdit = sprintf("SELECT * FROM procentris_list WHERE list_id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $conn) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

$colname_rsUser = "1";
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
<title>Manage Clients</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" src="colorpicker.js"></script>
<script language="javascript">
function init(){
	var inp1 = document.getElementById('input1');
	var inp2 = document.getElementById('color');

	if(inp1) attachColorPicker(inp1, true);
	if(inp2) attachColorPicker(inp2);
}
window.onload = init;
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
<h3>Add Client </h3>
<p><a href="steps.php">Back</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table>
        <tr valign="baseline">
          <td nowrap align="right">Client:</td>
          <td><input type="text" name="list" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Color:</td>
          <td><input type="text" name="color" id="color" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Insert New Client"></td>
        </tr>
      </table>
      <input type="hidden" name="list_type" value="Category">
      <input type="hidden" name="pid" value="0">
      <input type="hidden" name="user_id" value="<?php echo $row_rsUser['user_id']; ?>">
      <input type="hidden" name="level" value="1">
      <input type="hidden" name="MM_insert" value="form1">
    </form>
    <p>&nbsp;</p>
    <?php if ($totalRows_rsHome > 0) { // Show if recordset not empty ?>
    <h3>View Clients</h3>
    <table border="1">
      <tr>
        <td><strong>Client</strong></td>
        <td><strong>Color</strong></td>
        <td><strong>Edit</strong></td>
      </tr>
      <?php do { ?>
      <tr bgcolor="<?php echo $row_rsHome['color']; ?>">
        <td><?php echo $row_rsHome['list']; ?></td>
        <td><?php echo $row_rsHome['color']; ?>&nbsp;</td>
        <td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?list_id=<?php echo $row_rsHome['list_id']; ?>#edit">Edit</a></td>
      </tr>
      <?php } while ($row_rsHome = mysql_fetch_assoc($rsHome)); ?>
    </table>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
    <h3>Edit Client<a name="edit"></a></h3>
    <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
      <table>
        <tr valign="baseline">
          <td nowrap align="right">Client:</td>
          <td><input type="text" name="list" value="<?php echo $row_rsEdit['list']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Color:</td>
          <td><input type="text" name="color" id="color" value="<?php echo $row_rsEdit['color']; ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" value="Update">
              <input name="list_id" type="hidden" id="list_id" value="<?php echo $row_rsEdit['list_id']; ?>"></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form2">
    </form>
    <?php } // Show if recordset not empty ?>
    <p>&nbsp; </p>
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

mysql_free_result($rsEdit);

mysql_free_result($rsUser);
?>
