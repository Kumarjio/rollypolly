<?php require_once('../../Connections/connMain.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO records (record_id, module_id, user_id, city_id, title, details, lat, lon, address, showAddress) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['record_id'], "text"),
                       GetSQLValueString($_POST['module_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "text"),
                       GetSQLValueString($_POST['city_id'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['details'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lon'], "double"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString(isset($_POST['showAddress']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Title:</td>
      <td><input type="text" name="title" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Address:</td>
      <td><input type="text" name="address" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">ShowAddress:</td>
      <td><input type="checkbox" name="showAddress" value="" checked></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="record_id" value="">
  <input type="hidden" name="module_id" value="">
  <input type="hidden" name="user_id" value="">
  <input type="hidden" name="city_id" value="">
  <input type="hidden" name="details" value="">
  <input type="hidden" name="lat" value="">
  <input type="hidden" name="lon" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>