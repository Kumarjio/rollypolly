<?php require_once('Connections/conn.php'); ?>
<?php
if (empty($_GET['side']) || !in_array($_GET['side'], array('W', 'B'))) {
	echo 'Please choose side to play: W/B';
	exit;
}

if ($_GET['side'] === 'B') {
	$fromMove = 'W';	
} else if ($_GET['side'] === 'W') {
	$fromMove = 'B';	
} else {
	echo 'Please choose side to play: W/B';
	exit;
}


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

$colname_rsView = "-1";
if (isset($_GET['fen'])) {
  $colname_rsView = $_GET['fen'];
}
$colid_rsView = "-1";
if (isset($_GET['id'])) {
  $colid_rsView = $_GET['id'];
}
mysql_select_db($database_conn, $conn);
$query_rsView = sprintf("SELECT * FROM games WHERE fen = %s OR id = %s", GetSQLValueString($colname_rsView, "text"),GetSQLValueString($colid_rsView, "int"));
$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');

echo $query_rsView.'<br>';
pr($row_rsView);

$standard = new Games_Chess_Standard;
$fen = $row_rsView['fen'];
$standard->resetGame($fen);
$toMove = $standard->toMove();
echo $toMove;
echo '<br>';
$result = $standard->gameOver();
echo $result;
echo '<br>';
$result = !empty($result) ? $result : 'In Progress';
$legalMovesOriginal = getlegalmoves($standard, $fen);
echo $fen;
echo '<br>';
pr($legalMovesOriginal);
exit;

$standard = process($standard, $fromMove, $toMove, $legalMovesOriginal, $row_rsView, $fen);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Play Game</title>
</head>

<body>
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td><strong>To Move</strong></td>
		<td><?php echo $toMove; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Fen</strong></td>
		<td><?php echo $renderFen; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Result</strong></td>
		<td><?php echo $result; ?>&nbsp;</td>
	</tr>
	<tr>
		<td><strong>Legal Moves Original</strong></td>
		<td><strong>Fen</strong></td>
	</tr>
	<?php foreach ($legalMovesOriginal as $k => $v) { ?>
	<tr>
		<td align="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?fen=<?php echo $v['fen']; ?>&pid="><?php echo $v['move']; ?></a>&nbsp;</td>
		<td><?php echo $v['fen']; ?>&nbsp;</td>
	</tr>
	<?php } ?>
</table>
</body>
</html>
<?php
mysql_free_result($rsView);
?>
