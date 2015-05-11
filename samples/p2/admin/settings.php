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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE settings SET maxRecordPerPage=%s WHERE setting_id=%s",
                       GetSQLValueString($_POST['maxRecordPerPage'], "int"),
                       GetSQLValueString($_POST['setting_id'], "int"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($updateSQL, $connP2) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_connP2, $connP2);
$query_rsSettings = "SELECT * FROM settings WHERE setting_id = 1";
$rsSettings = mysql_query($query_rsSettings, $connP2) or die(mysql_error());
$row_rsSettings = mysql_fetch_assoc($rsSettings);
$totalRows_rsSettings = mysql_num_rows($rsSettings);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Settings</title>
</head>

<body>
<h1>Settings</h1>
<p><a href="index.php">Back to Home Page</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table>
        <tr valign="baseline">
            <td nowrap align="right">Max Record Per Page:</td>
            <td><input type="text" name="maxRecordPerPage" value="<?php echo htmlentities($row_rsSettings['maxRecordPerPage'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
        </tr>
        <tr valign="baseline">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" value="Update record"></td>
        </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="setting_id" value="<?php echo $row_rsSettings['setting_id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsSettings);
?>
