<?php require_once('Connections/conn.php'); ?>
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

mysql_select_db($database_conn, $conn);
$query_rsView = "SELECT * FROM gamespos WHERE pid = 0";
$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>View</title>
</head>

<body>
<h1>View Games</h1>
<table border="1" cellpadding="5" cellspacing="0">
	<tr>
		<td><strong>ID</strong></td>
		<td><strong>Fen</strong></td>
		<td><strong>Move By</strong></td>
		<td><strong>Side</strong></td>
		<td><strong>View</strong></td>
	</tr>
	<?php do { ?>
		<tr>
			<td><?php echo $row_rsView['id']; ?></td>
			<td><?php echo $row_rsView['fen']; ?></td>
			<td><?php echo $row_rsView['moveby']; ?></td>
			<td><?php echo $row_rsView['side']; ?></td>
			<td><a href="chess_gamespos.php?pid=<?php echo $row_rsView['id']; ?>">View</a></td>
		</tr>
		<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rsView);
?>
