<?
include_once('../Connections/connHm.php');
include_once('start.php');
?>
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
if($_GET['saveString']) {
	$items = explode(",",$_GET['saveString']);
	for($no=0;$no<count($items);$no++){
		$tokens = explode("-",$items[$no]);
		$sql = "Update home_product_management_details set pid = '".$tokens[1]."' where detail_id = '".$tokens[0]."'";
		mysql_query($sql) or die('contact line '.__LINE__);
	}
}
echo 'Details Updated Successfully.';
exit;

/* Input to this file - $_GET['saveString']; */
/*
if(!isset($_GET['saveString']))die("no input");
echo "Message from saveNodes.php\n";


$items = explode(",",$_GET['saveString']);
for($no=0;$no<count($items);$no++){
	$tokens = explode("-",$items[$no]);

	echo "ID: ".$tokens[0]. " is sub of ".$tokens[1]."\n";	// Just for testing
	// Example of sql
	
	// mysql_query("update nodes set parentID='".$tokens[1]."',position='$no' where ID='".$tokens[0]."'") or die(mysql_error());
	// for a table like this:
	
	
	
	//nodes
	//---------------------
	//ID int
	//title varchar(255)
	//position int
	//parentID int
	
	
	
}

*/


?>