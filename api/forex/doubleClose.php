<?php require_once('../../Connections/connForexmastery2.php'); ?>
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

$limit = 15;
if (!empty($_GET['limit'])) {
  $limit = $_GET['limit'];
}

$colname_rsView = "5,15,30,60,240";
if (isset($_GET['timeperiod'])) {
  $colname_rsView = $_GET['timeperiod'];
}
mysql_select_db($database_connForexmastery2, $connForexmastery2);
$query_rsView = sprintf("SELECT * FROM `double` WHERE timeperiod IN ($colname_rsView) ORDER BY totalvalue DESC LIMIT $limit");
$rsView = mysql_query($query_rsView, $connForexmastery2) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
if ($totalRows_rsView > 0) { // Show if recordset not empty
do {
  echo $row_rsView['id'].','.$row_rsView['symbol'].','.$row_rsView['timeperiod'].','.$row_rsView['strategy1'].','.$row_rsView['strategy2'].','.$row_rsView['totalvalue'].','.$row_rsView['maxloss']."|";
  } while ($row_rsView = mysql_fetch_assoc($rsView));
} // Show if recordset not empty
mysql_free_result($rsView);