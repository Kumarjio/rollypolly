<?php require_once('../Connections/connMain.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO activities (activity_id, `uid`, activity_date) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['activity_id'], "int"),
                       GetSQLValueString($_POST['uid'], "text"),
                       GetSQLValueString($_POST['activity_date'], "date"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

mysql_select_db($database_connMain, $connMain);
$query_Recordset1 = "SELECT * FROM activities";
$Recordset1 = mysql_query($query_Recordset1, $connMain) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Activity_id:</td>
      <td><input type="text" name="activity_id" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Uid:</td>
      <td><input type="text" name="uid" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Activity_date:</td>
      <td><input type="text" name="activity_date" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td>activity_id</td>
    <td>uid</td>
    <td>activity_date</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Recordset1['activity_id']; ?></td>
      <td><?php echo $row_Recordset1['uid']; ?></td>
      <td><?php echo $row_Recordset1['activity_date']; ?></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
