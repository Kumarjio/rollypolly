<?php require_once('../../../Connections/connP2.php'); ?>
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

require_once('../config.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$target_dir = IMAGEUPLOADDIR;
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (empty($_FILES["fileToUpload"]["name"])) {
    $error = "Please choose the file to upload.";
    unset($_POST["MM_insert"]);
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $filename = basename($_FILES["fileToUpload"]["name"]);
  $_POST['fileName'] = $filename;
  $target_file = $target_dir . $filename;
  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if(empty($check)) {
      $error = "File is not an image.";
      unset($_POST["MM_insert"]);
  }
  // Check if file already exists
  if (file_exists($target_file)) {
      $error = "Sorry, file already exists.";
      unset($_POST["MM_insert"]);
  }
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
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO main_image (fileName) VALUES (%s)",
                       GetSQLValueString($_POST['fileName'], "text"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($insertSQL, $connP2) or die(mysql_error());

  $insertGoTo = "main.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM main_image WHERE id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($deleteSQL, $connP2) or die(mysql_error());
}

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $target_file = $target_dir . urldecode($_GET['filename']);
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  $insertGoTo = "main.php";
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}

mysql_select_db($database_connP2, $connP2);
$query_rsView = "SELECT * FROM main_image";
$rsView = mysql_query($query_rsView, $connP2) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Main Image Upload</title>
<style type="text/css">

.imglist {
  max-height: 70px;
}
</style>
</head>

<body>
<h1>Main Image Page</h1>
<?php if (!empty($error)) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<label for="fileToUpload">File:</label>
<input type="file" name="fileToUpload" id="fileToUpload">
<input type="submit" name="submit" id="subm it" value="Submit">
<input type="hidden" name="fileName" id="fileName">
<input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <h3>View All Images</h3>
  <table border="1" cellspacing="1" cellpadding="5">
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top"><strong>Image Name</strong></td>
      <td valign="top"><strong>Delete</strong></td>
      <td valign="top"><strong>Choose Area</strong></td>
      <td valign="top"><strong>Choose Smooth Area</strong></td>
    </tr>
      <?php do { ?>
    <tr>
      <td valign="top"><a href="area.php?id=<?php echo $row_rsView['id']; ?>"><img src="<?php echo IMAGEDIR.$row_rsView['fileName']; ?>" class="imglist" /></a></td>
        <td valign="top"><?php echo $row_rsView['fileName']; ?></td>
        <td valign="top"><a href="main.php?delete_id=<?php echo $row_rsView['id']; ?>&filename=<?php echo urlencode($row_rsView['fileName']); ?>" onClick="var a = confirm('Do you really want to delete this record?'); return a;">Delete</a></td>
        <td valign="top"><a href="area.php?id=<?php echo $row_rsView['id']; ?>">Choose Area</a></td>
        <td valign="top"><a href="areaSmooth.php?id=<?php echo $row_rsView['id']; ?>">Choose Smooth Area</a></td>
    </tr>
        <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsView);
?>
