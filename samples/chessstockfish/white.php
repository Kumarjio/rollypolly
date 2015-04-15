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
$query_rsView = "SELECT * FROM games WHERE process = 0 ORDER BY id ASC LIMIT 1";
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
$result = $standard->gameOver();
$result = !empty($result) ? $result : 'In Progress';
$legalMovesOriginal = getlegalmoves($standard, $fen);

if (!empty($legalMovesOriginal['success']) && $legalMovesOriginal['success'] == 1) {
	pr($legalMoves);
	pr($row_rsView);
	echo $query_rsView;
	echo '  consider this'; exit;	
}

		
if ($toMove == 'B') {
	foreach ($legalMovesOriginal as $k => $v) {
		$renderFen = $v['fen'];
		$move2 = $v['move'];
		//checking result
		$result = '';
		$process = 0;
		$standard->moveSAN($move2);
		$legalMoves = getlegalmoves($standard, $renderFen);
		if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
			$result = $legalMoves['result'];
			$process = 1;
		}//end if
		$standard->resetGame($fen);
		//end checking result

		mysql_select_db($database_conn, $conn);
		echo $query = sprintf("SELECT * FROM games WHERE pid = %s and move = %s and fen = %s and moveby = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"));
		echo '<br>';
		$rs = mysql_query($query, $conn) or die(mysql_error());
		$totalRows = mysql_num_rows($rs);
		if ($totalRows == 0) {
			echo $query = sprintf("INSERT INTO games SET pid = %s, move = %s, fen = %s, moveby = %s, result = %s, process = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"));
			echo '<br>';
			$rs = mysql_query($query, $conn) or die(mysql_error());
		}
	}

	$query = sprintf("UPDATE games SET process = 1 WHERE id = %s", GetSQLValueString($row_rsView['id'], "int"));
	$rs = mysql_query($query, $conn) or die(mysql_error());
} else {
	mysql_select_db($database_conn, $conn);
	echo $query = sprintf("SELECT * FROM games WHERE pid = %s", GetSQLValueString($row_rsView['id'], "int"));
	echo '<br>';
	$rs = mysql_query($query, $conn) or die(mysql_error());
	$totalRows = mysql_num_rows($rs);
	if ($totalRows == 0) {
		$chess = new Chess;
		$move = $chess->find($row_rsView['fen']);
		$from = substr($move, 0, 2);
		$to = substr($move, 2, 2);
		$move2 = $standard->_convertSquareToSAN($from, $to);
		$standard->moveSquare($from, $to);
		$renderFen = $standard->renderFen();
		//checking result
		$result = '';
		$process = 0;
		$legalMoves = getlegalmoves($standard, $renderFen);
		if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
			$result = $legalMoves['result'];
			$process = 1;
		}//end if
		$standard->resetGame($fen);
		//end checking result
		echo $query = sprintf("INSERT INTO games SET pid = %s, move = %s, fen = %s, moveby = %s, result = %s, process = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"));
		echo '<br>';
		$rs = mysql_query($query, $conn) or die(mysql_error());
	}		
	$query = sprintf("UPDATE games SET process = 1 WHERE id = %s", GetSQLValueString($row_rsView['id'], "int"));
	$rs = mysql_query($query, $conn) or die(mysql_error());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
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
<meta http-equiv="refresh" content="2" />
</body>
</html>
<?php
mysql_free_result($rsView);
?>
