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
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$target_dir = SUBIMAGEUPLOADDIR.$id.'/';
$target_http_dir = SUBIMAGEDIR.$id.'/';

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $_POST['extraFields'] = json_encode($_POST['data']);
  if (!empty($_FILES["fileUpload"]["name"])) {
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755);
      chmod($target_dir, 0755);
    }

    $filename = 'f_'.time().'_'.basename($_FILES["fileUpload"]["name"]);
    $_POST['imageFile'] = $filename;
    $target_file = $target_dir . $filename;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileUpload"]["tmp_name"]);
    if(empty($check)) {
        $error = "File is not an image.";
        unset($_POST["MM_update"]);
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $error = "Sorry, file already exists.";
        unset($_POST["MM_update"]);
    }
    // Check file size
    /*if ($_FILES["fileUpload"]["size"] > 500000) {
        $error = "Sorry, your file is too large.";
        unset($_POST["MM_update"]);
    }*/
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        unset($_POST["MM_update"]);
    }
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && !empty($_FILES["fileUpload"]["name"])) {
  move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE image_details SET coordinates=%s, imageFile=%s, extraFields=%s WHERE detail_id=%s",
                       GetSQLValueString($_POST['coordinates'], "text"),
                       GetSQLValueString($_POST['imageFile'], "text"),
                       GetSQLValueString($_POST['extraFields'], "text"),
                       GetSQLValueString($_POST['detail_id'], "int"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($updateSQL, $connP2) or die(mysql_error());

  $updateGoTo = "area.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsArea = "-1";
if (isset($_GET['detail_id'])) {
  $colname_rsArea = $_GET['detail_id'];
}
mysql_select_db($database_connP2, $connP2);
$query_rsArea = sprintf("SELECT * FROM image_details WHERE detail_id = %s", GetSQLValueString($colname_rsArea, "int"));
$rsArea = mysql_query($query_rsArea, $connP2) or die(mysql_error());
$row_rsArea = mysql_fetch_assoc($rsArea);
$totalRows_rsArea = mysql_num_rows($rsArea);

$colname_rsMain = "-1";
if (isset($_GET['id'])) {
  $colname_rsMain = $_GET['id'];
}
mysql_select_db($database_connP2, $connP2);
$query_rsMain = sprintf("SELECT * FROM main_image WHERE id = %s", GetSQLValueString($colname_rsMain, "int"));
$rsMain = mysql_query($query_rsMain, $connP2) or die(mysql_error());
$row_rsMain = mysql_fetch_assoc($rsMain);
$totalRows_rsMain = mysql_num_rows($rsMain);

$data = json_decode($row_rsArea['extraFields'], 1);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Update Area Details</title>
<style type="text/css">

.imglist {
  max-height: 70px;
}
</style>
</head>

<body>
<h1>Edit Area Details</h1>
<?php if (!empty($error)) echo $error; ?>
<p><a href="area.php?id=<?php echo $colname_rsMain; ?>">Back To Area Selection</a> | <a href="main.php">Back To Main Page </a></p>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
  <table border="0" cellspacing="1" cellpadding="5">
    <tr>
      <td align="right"><strong>Coordinates</strong></td>
      <td><input name="coordinates" type="text" id="coordinates" value="<?php echo $row_rsArea['coordinates']; ?>"></td>
    </tr>
    <tr>
      <td align="right"><strong>Image:</strong></td>
      <td><input type="file" name="fileUpload" id="fileUpload">
      <br><?php if (!empty($row_rsArea['imageFile'])) { ?><img src="<?php echo $target_http_dir.$row_rsArea['imageFile']; ?>" class="imglist" /><?php } ?></td>
    </tr>
    <tr>
      <td align="right"><strong>Item Description:</strong></td>
      <td><input name="data[itemDescription]" type="text" id="data_itemDescription" size="50" value="<?php echo !empty($data['itemDescription']) ? $data['itemDescription'] : ''; ?>"></td>
    </tr>
    <tr>
      <td align="right"><strong>Model Number:</strong></td>
      <td><input name="data[modelNumber]" type="text" id="data_modelNumber" size="50" value="<?php echo !empty($data['modelNumber']) ? $data['modelNumber'] : ''; ?>"></td>
    </tr>
    <tr>
      <td align="right"><strong>Manufacturing Part Number:</strong></td>
      <td><input name="data[manufacturingPartNumber]" type="text" id="data_manufacturingPartNumber" size="50" value="<?php echo !empty($data['manufacturingPartNumber']) ? $data['manufacturingPartNumber'] : ''; ?>"></td>
    </tr>
    <tr>
        <td align="right"><strong>Manufacturing Description:</strong></td>
        <td><input name="data[manufacturingDescription]" type="text" id="data_manufacturingDescription" size="50" value="<?php echo !empty($data['manufacturingDescription']) ? $data['manufacturingDescription'] : ''; ?>"></td>
    </tr>
    <tr>
        <td align="right"><strong>URL:</strong></td>
        <td><input name="data[url]" type="text" id="data_url" size="50" value="<?php echo !empty($data['url']) ? $data['url'] : ''; ?>"></td>
    </tr>
    <tr>
        <td align="right">&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Submit">
            <input name="extraFields" type="hidden" id="extraFields" value="<?php echo $row_rsArea['extraFields']; ?>">
            <input name="detail_id" type="hidden" id="detail_id" value="<?php echo $row_rsArea['detail_id']; ?>"><input type="hidden" name="imageFile" id="imageFile" value="<?php echo $row_rsArea['imageFile']; ?>" />
            </td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsArea);

mysql_free_result($rsMain);
?>
