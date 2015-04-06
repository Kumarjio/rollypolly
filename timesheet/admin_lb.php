<?php require_once('../Connections/conn.php'); ?>
<?php session_start(); ?>
<?php
mysql_select_db($database_conn, $conn);
$query_rsList = "SELECT * FROM procentris_leavebalance ORDER BY name ASC";
$rsList = mysql_query($query_rsList, $conn) or die(mysql_error());
$row_rsList = mysql_fetch_assoc($rsList);
$totalRows_rsList = mysql_num_rows($rsList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Leave Balance</title>
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
<h3>Leave Balance</h3>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td><strong>User ID </strong></td>
    <td><strong>Name</strong></td>
    <td><strong>PL</strong></td>
    <td><strong>CL</strong></td>
    <td><strong>SL</strong></td>
    <td><strong>Comp Off</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_rsList['user_id']; ?></td>
    <td><?php echo $row_rsList['name']; ?></td>
    <td><?php echo $row_rsList['pl']; ?></td>
    <td><?php echo $row_rsList['cl']; ?></td>
    <td><?php echo $row_rsList['sl']; ?></td>
    <td><?php echo $row_rsList['compoff']; ?></td>
  </tr>
  <?php } while ($row_rsList = mysql_fetch_assoc($rsList)); ?>
</table>
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
mysql_free_result($rsList);
?>
