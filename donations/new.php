<?php require_once('../Connections/connWork.php'); ?>
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

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (empty($_FILES["fileToUpload"]["name"])) {
    $error = "Please choose the file to upload.";
    unset($_POST["MM_insert"]);
  }
}

$target_dir = 'images/'.$_SESSION['MM_UserId'].'/';
$target_dirThumbs = 'images/'.$_SESSION['MM_UserId'].'/thumbs/';
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755);
    chmod($target_dir, 0755);
  }
  if (!is_dir($target_dirThumbs)) {
    mkdir($target_dirThumbs, 0755);
    chmod($target_dirThumbs, 0755);
  }
  $filename = 'f_'.time().'_'.basename($_FILES["fileToUpload"]["name"]);
  $_POST['fileToUpload'] = $filename;
  $target_file = $target_dir . $filename;
  $target_fileThumbs = $target_dirThumbs . $filename;
  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if(empty($check)) {
      $error = "File is not an image.";
      unset($_POST["MM_insert"]);
  }
  // Check if file already exists
  //if (file_exists($target_file)) {
      //$error = "Sorry, file already exists.";
      //unset($_POST["MM_insert"]);
  //}
  // Check file size
  /*if ($_FILES["fileToUpload"]["size"] > 500000) {
      $error = "Sorry, your file is too large.";
      unset($_POST["MM_insert"]);
  }*/
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      unset($_POST["MM_insert"]);
  }
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
  include('SimpleImage.php');
  $image = new SimpleImage();
  $image->load($target_file);
  $image->resize(230,153);
  $image->save($target_fileThumbs);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO donations (user_id, donation_title, donation_desc, donation_needed, donation_category_id, donation_image, donation_paypal_email) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['donation_title'], "text"),
                       GetSQLValueString($_POST['donation_desc'], "text"),
                       GetSQLValueString($_POST['donation_needed'], "double"),
                       GetSQLValueString($_POST['donation_category_id'], "int"),
                       GetSQLValueString($_POST['fileToUpload'], "text"),
                       GetSQLValueString($_POST['donation_paypal_email'], "text"));

  mysql_select_db($database_connWork, $connWork);
  $Result1 = mysql_query($insertSQL, $connWork) or die(mysql_error());

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertGoTo = "newConfirm.php?did=".mysql_insert_id();
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}

mysql_select_db($database_connWork, $connWork);
$query_rsCategory = "SELECT * FROM donations_category ORDER BY category ASC";
$rsCategory = mysql_query($query_rsCategory, $connWork) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_UserId'])) {
  $colname_rsUser = $_SESSION['MM_UserId'];
}
mysql_select_db($database_connWork, $connWork);
$query_rsUser = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $connWork) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>New Request</title>
</head>

<body>
<h1>New Fund Request</h1>
<?php if (!empty($error)) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Fund Title:</td>
      <td><input type="text" name="donation_title" value="" size="32" required></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Why you need Fund?</td>
      <td><textarea name="donation_desc" cols="50" rows="5" required></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">How much funds you need:</td>
      <td>$ 
        <input type="text" name="donation_needed" value="" size="32" required></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Category:</td>
      <td><select name="donation_category_id" required>
        <option value="">Select</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsCategory['category_id']?>"><?php echo $row_rsCategory['category']?></option>
        <?php
} while ($row_rsCategory = mysql_fetch_assoc($rsCategory));
  $rows = mysql_num_rows($rsCategory);
  if($rows > 0) {
      mysql_data_seek($rsCategory, 0);
	  $row_rsCategory = mysql_fetch_assoc($rsCategory);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Image:</td>
      <td><input type="file" name="fileToUpload" id="fileToUpload" required></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Paypal Email Address:</td>
      <td><input name="donation_paypal_email" type="text" id="donation_paypal_email" value="<?php echo $row_rsUser['paypal_email']; ?>"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['MM_UserId']; ?>">
  <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($rsCategory);

mysql_free_result($rsUser);
?>
