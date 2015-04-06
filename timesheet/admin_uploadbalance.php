<?php
require_once('../Connections/conn.php');
session_start();
$array = array('P.L','C.L','S.L','Comp Off','L wo P','Late Mark','Opt/Holiday','Holiday','"""Official Leave"""');
$array2 = array('P.L'=>"pl",'C.L'=>"cl",'S.L'=>"sl",'Comp Off'=>"compoff",'L wo P','Late Mark','Opt/Holiday','Holiday','"""Official Leave"""');
if($_FILES['userfile']['name']) {
	$ext = strrchr($_FILES['userfile']['name'],".");
	if($ext==".csv") {
		move_uploaded_file($_FILES['userfile']['tmp_name'],"files/".$_FILES['userfile']['name']);
		$file = file("files/".$_FILES['userfile']['name']);
		foreach($file as $key=>$line) {
			if($key>=5) {
				$var = explode(",",$line);
				if(in_array($var[0],$array)) {
					if($name != "") {
						if($array2[$var[0]]) {
							$array3[$name]['field'][$array2[$var[0]]] = $var[15];
							$query = "update procentris_leavebalance set ".$array2[$var[0]]." = '".$array3[$name]['field'][$array2[$var[0]]]."' where name = '".addslashes(stripslashes(trim(strtolower($name))))."'";
							mysql_query($query) or die('error');
						}
					}
				} else if(trim($var[0]!="")) {
					$name = trim($var[0]);
					if($name != "") {
						$query = "select * from procentris_leavebalance where name = '".addslashes(stripslashes(trim(strtolower($name))))."'";
						$rs = mysql_query($query) or die("error");
						if(mysql_num_rows($rs)==0) {
							mysql_query("insert into procentris_leavebalance set name = '".addslashes(stripslashes(trim(strtolower($name))))."'");
						}
					}
				}
			}
		}
		header("Location: steps.php");
		exit;
	} else {
		$error = 'File Should be in csv format. You have upload in '.$ext.' format.';
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Upload Balance Sheet</title>
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
<h3>Upload Leave Balance Excel Sheet in CSV Format</h3>
<?php echo $error; ?>
<form action="" method="post" enctype="multipart/form-data" name="form1">
  <p>
    <input name="userfile" type="file" id="userfile" size="45">
</p>
  <p>
    <input type="submit" name="Submit" value="Upload">
</p>
</form>
<p>&nbsp;</p>
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
