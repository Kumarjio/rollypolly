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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE home_product_management_details SET name=%s, description=%s WHERE detail_id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['detail_id'], "int"));

  mysql_select_db($database_connHm, $connHm);
  $Result1 = mysql_query($updateSQL, $connHm) or die(mysql_error());

  $updateGoTo = "hpm_add_items.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsEdit = "-1";
if (isset($_GET['detail_id'])) {
  $colname_rsEdit = (get_magic_quotes_gpc()) ? $_GET['detail_id'] : addslashes($_GET['detail_id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsEdit = sprintf("SELECT * FROM home_product_management_details WHERE detail_id = %s", $colname_rsEdit);
$rsEdit = mysql_query($query_rsEdit, $connHm) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/mumbaionline.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Edit Item</title>
<!-- InstanceEndEditable -->
<link href="../default.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="../js/script.js"></script>
<script src="../js/jquery-1.2.6.js" type="text/javascript"></script>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body>
<?php include(DOCROOT.'/Templates/head.php'); ?>
<!-- InstanceBeginEditable name="EditRegion3" -->
		  <div class="post">
            <h1 class="title"><a href="#">Edit Item</a></h1>
		    <p class="byline"><small>Posted on August 9th, 2009 by <a href="#">Admin</a></small></p>
		    <div class="entry">
		      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		        <table>
                  <tr valign="baseline">
                    <td align="right" valign="top" nowrap="nowrap">Name:</td>
                    <td valign="top"><input type="text" name="name" value="<?php echo $row_rsEdit['name']; ?>" id="name" size="32" /></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" valign="top" nowrap="nowrap">Description:</td>
                    <td valign="top"><textarea name="description" cols="25" rows="5" id="description"><?php echo $row_rsEdit['description']; ?></textarea></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" valign="top" nowrap="nowrap">&nbsp;</td>
                    <td valign="top"><input type="submit" value="Update Item" name="submit" />
                    <input name="detail_id" type="hidden" id="detail_id" value="<?php echo $row_rsEdit['detail_id']; ?>" /></td>
                  </tr>
                </table>
                <input type="hidden" name="MM_update" value="form1">
              </form>
		      <p>&nbsp;</p>
	        </div>
	    </div>
<!-- InstanceEndEditable -->
<?php include(DOCROOT.'/Templates/foot.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsEdit);
?>
