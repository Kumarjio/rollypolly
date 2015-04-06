<?php require_once('../Connections/connHm.php'); ?>
<?php include_once('start.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if(!trim($_POST['name'])) {
		$error = "Please add item name. ";
		unset($_POST["MM_insert"]);
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
  $insertSQL = sprintf("INSERT INTO home_product_management_details (id, user_id, name, description, pid, created_dt) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['pid'], "int"),
                       GetSQLValueString($_POST['created_dt'], "date"));

  mysql_select_db($database_connHm, $connHm);
  $Result1 = mysql_query($insertSQL, $connHm) or die(mysql_error());
}

$colname_rsPlace = "-1";
if (isset($_GET['id'])) {
  $colname_rsPlace = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsPlace = sprintf("SELECT * FROM home_product_management WHERE id = %s", $colname_rsPlace);
$rsPlace = mysql_query($query_rsPlace, $connHm) or die(mysql_error());
$row_rsPlace = mysql_fetch_assoc($rsPlace);
$totalRows_rsPlace = mysql_num_rows($rsPlace);

$colpid_rsView = "0";
if (isset($_GET['pid'])) {
  $colpid_rsView = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
$colid_rsView = "-1";
if (isset($_SESSION['user_id'])) {
  $colid_rsView = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
$colname_rsView = "-1";
if (isset($_GET['id'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsView = sprintf("SELECT * FROM home_product_management_details WHERE id = %s AND user_id = %s AND pid = %s ORDER BY name ASC", $colname_rsView,$colid_rsView,$colpid_rsView);
$rsView = mysql_query($query_rsView, $connHm) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$colname_rsPid = "-1";
if (isset($_GET['pid'])) {
  $colname_rsPid = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsPid = sprintf("SELECT * FROM home_product_management_details WHERE detail_id = %s", $colname_rsPid);
$rsPid = mysql_query($query_rsPid, $connHm) or die(mysql_error());
$row_rsPid = mysql_fetch_assoc($rsPid);
$totalRows_rsPid = mysql_num_rows($rsPid);
?>
<?php
include('../Classes/Home.php');
$Home = new Home;
$Home->categoryParentLink($_GET['pid'], $_GET['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/mumbaionline.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_rsPlace['title']; ?> :: Add Items</title>
<!-- InstanceEndEditable -->
<link href="../default.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="../js/script.js"></script>
<script src="../js/jquery-1.2.6.js" type="text/javascript"></script>
<!-- InstanceBeginEditable name="head" -->
<script language="javascript">
window.onload = function() {
	document.getElementById('name').focus();
}
</script>
<!-- InstanceEndEditable -->
</head>

<body>
<?php include(DOCROOT.'/Templates/head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
		<p><?php 
echo $Home->catLinkDisplay; ?></p>
		  <div class="post">
            <h1 class="title"><a href="#"><?php if($_GET['pid']==0) echo 'Root'; else echo $row_rsPid['name']; ?> :: Add Items</a></h1>
		    <p class="byline"><small>Posted on August 6th, 2009 by <a href="#">Admin</a></small></p>
		    <div class="entry">
              <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
			  <?php echo $error; ?>
		  <table align="center">
			<tr valign="baseline">
			  <td align="right" valign="top" nowrap>Name:</td>
			  <td valign="top"><input type="text" name="name" value="" id="name" size="32"></td>
			</tr>
			<tr valign="baseline">
			  <td align="right" valign="top" nowrap>Description:</td>
			  <td valign="top"><textarea name="description" cols="25" rows="5" id="description"></textarea></td>
		    </tr>
			<tr valign="baseline">
			  <td align="right" valign="top" nowrap>&nbsp;</td>
			  <td valign="top"><input type="submit" value="Add New Item" name="submit"></td>
			</tr>
		  </table>		  
		  	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
			<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
			<input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
			<input type="hidden" name="created_dt" value="<?php echo date('Y-m-d H:i:s'); ?>">
			<input type="hidden" name="MM_insert" value="form1">
		</form>
		<?php include('menu.php'); ?>
	        </div>
	    </div>
			<div align="right">
			<a href="hpm_manage_items.php?id=<?php echo $row_rsPlace['id']; ?>" title="Manage Items">Manage Items</a></div>
              <?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
		<div class="post">
            <h1 class="title"><a href="#"><?php echo $row_rsPlace['title']; ?> :: View Items</a></h1>
		    <p class="byline"><small>Posted on August 6th, 2009 by <a href="#">Admin</a>. List of Items Under <?php if($_GET['pid']==0) echo 'Root'; else echo $row_rsPid['name']; ?></small></p>
		    <div class="entry">
              <table border="1">
                <tr>
                  <td valign="top"><strong>Item Name </strong></td>
                  <td valign="top"><strong>Created Date </strong></td>
                  <td valign="top"><strong>Edit</strong></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td valign="top"><a href="hpm_add_items.php?id=<?php echo $row_rsPlace['id']; ?>&pid=<?php echo $row_rsView['detail_id']; ?>"><?php echo $row_rsView['name']; ?></a>
                    <?php if($row_rsView['description']) { ?><br /><?php echo $row_rsView['description']; ?><?php } ?></td>
                    <td valign="top"><?php echo $row_rsView['created_dt']; ?></td>
                    <td valign="top"><a href="hpm_edit_items.php?detail_id=<?php echo $row_rsView['detail_id']; ?>&id=<?php echo $row_rsPlace['id']; ?>&pid=<?php echo $row_rsPid['detail_id']; ?>">Edit</a></td>
                  </tr>
                  <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
                    </table>
		  </div>
	    </div>
                <?php } // Show if recordset not empty ?>
<!-- InstanceEndEditable -->
<?php include(DOCROOT.'/Templates/foot.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsPlace);

mysql_free_result($rsView);

mysql_free_result($rsPid);
?>
