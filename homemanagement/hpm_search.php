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
$colid_rsView = "-1";
if (isset($_SESSION['user_id'])) {
  $colid_rsView = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
$colname_rsView = "-1";
if (isset($_GET['kw'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_GET['kw'] : addslashes($_GET['kw']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsView = sprintf("SELECT * FROM home_product_management_details as a LEFT JOIN home_product_management as b ON a.id = b.id WHERE a.user_id = %s AND a.name like '%%%s%%' ORDER BY b.title, a.name ASC", $colid_rsView,$colname_rsView);
$rsView = mysql_query($query_rsView, $connHm) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?>
<?php  $lastTFM_nest = "";?>
<?php
include('../Classes/Home.php');
$Home = new Home;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/mumbaionline.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Search Items</title>
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
            <h1 class="title"><a href="#">Search Items</a></h1>
		    <p class="byline"><small>Posted on August 6th, 2009 by <a href="#">Admin</a></small></p>
		    <div class="entry">
			<form name="form1" action="" method="get">
				Keyword: <input type="text" value="<?php echo $_GET['kw']; ?>" name="kw" id="kw" />
			    <input type="submit" name="Submit" value="Search" />
			</form>
            <?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
				<?php do { ?>
					<?php $TFM_nest = $row_rsView['title'];
if ($lastTFM_nest != $TFM_nest) { 
	$lastTFM_nest = $TFM_nest; ?>
					<b><?php echo $row_rsView['title']; ?></b><br />
					<?php } //End of Basic-UltraDev Simulated Nested Repeat?>
						<?php 	
						$Home->catLinkDisplay = '';
						$Home->catLink = array();
						$Home->categoryParentLink($row_rsView['detail_id'], $row_rsView['id']);
						echo $Home->catLinkDisplay;
						echo "<br />"; 
					?>
				<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
			<?php } // Show if recordset not empty ?>
			<?php include('menu.php'); ?>
	        </div>
	    </div>                
<!-- InstanceEndEditable -->
<?php include(DOCROOT.'/Templates/foot.php'); ?>
</body><!-- InstanceEnd --></html>
<?php
mysql_free_result($rsView);
?>
