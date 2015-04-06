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
	if(!trim($_POST['title'])) {
		$error = "Please add title. ";
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
  $insertSQL = sprintf("INSERT INTO home_product_management (user_id, title, created) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['created'], "date"));

  mysql_select_db($database_connHm, $connHm);
  $Result1 = mysql_query($insertSQL, $connHm) or die(mysql_error());
}

$colname_rsView = "-1";
if (isset($_SESSION['user_id'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsView = sprintf("SELECT * FROM home_product_management WHERE user_id = %s ORDER BY title ASC", $colname_rsView);
$rsView = mysql_query($query_rsView, $connHm) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/mumbaionline.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Create New Place</title>
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
            <h1 class="title"><a href="#">Create New Place</a></h1>
		    <p class="byline"><small>Posted on August 6th, 2009 by <a href="#">Admin</a></small></p>
		    <div class="entry">
              <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
			  <?php echo $error; ?>
		  <table align="center">
			<tr valign="baseline">
			  <td nowrap align="right">Title:</td>
			  <td><input type="text" name="title" value="" size="32"></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap align="right">&nbsp;</td>
			  <td><input type="submit" value="Add New Place" name="submit"></td>
			</tr>
		  </table>
		  <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
		  <input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s'); ?>">
		  <input type="hidden" name="MM_insert" value="form1">
		</form>
		<?php include('menu.php'); ?>
	        </div>
	    </div>
              <?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
		<div class="post">
            <h1 class="title"><a href="#">View All My Place</a></h1>
		    <p class="byline"><small>Posted on August 6th, 2009 by <a href="#">Admin</a></small></p>
		    <div class="entry">
              <table border="0" cellpadding="5" cellspacing="0">
                <tr>
                  <td><strong>Title</strong></td>
                  <td><strong>Create On </strong></td>
                  <td><strong>Actions </strong></td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td><a href="hpm_add_items.php?pid=0&id=<?php echo $row_rsView['id']; ?>"><?php echo $row_rsView['title']; ?></a></td>
                    <td><?php echo $row_rsView['created']; ?></td>
                    <td><a href="hpm_add_items.php?pid=0&id=<?php echo $row_rsView['id']; ?>" title="Add Item"><img border="0" src="images/add_item.png" /></a> | <a href="hpm_manage_items.php?id=<?php echo $row_rsView['id']; ?>" title="Manage Items"><img border="0" src="images/sales_manager-173070.jpeg" height="16" width="16" /></a></td>
                  </tr>
                  <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
                    </table>
		  </div>
	    </div>
                <?php } // Show if recordset not empty ?>
<!-- InstanceEndEditable -->
<?php include(DOCROOT.'/Templates/foot.php'); ?>
</body><!-- InstanceEnd -->
</html>
<?php
mysql_free_result($rsView);
?>
