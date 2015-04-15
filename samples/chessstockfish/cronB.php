<?php require_once('Connections/conn.php'); ?>
<?php
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');

$chess = new Chess;
$rs = $chess->findpos('finalgames_B');
if ($rs[0] == 0) {
	echo 'no moves to play';
	exit;	
}
$fen = !empty($rs[1]['fenpost']) ? $rs[1]['fenpost'] : NULL;
$standard = new Games_Chess_Standard;
$standard->resetGame($fen);
$toMove = $standard->toMove();
$fen = $standard->renderFen();
echo 'fen: '.$fen.'<br>';
$moveby = 'W';
if ($toMove == 'W') {
	$moveby = 'B';
}
pr($rs);
if ($toMove == 'B') {
	$legalMoves = getlegalmoves($standard, $fen);
	pr($legalMoves);
	if (empty($legalMoves['success'])) {
		$result = $chess->findfen($fen, 'finalgames_B');
		if ($result[0] == 0) {
			$move = $chess->find($fen);
			$from = substr($move, 0, 2);
			$to = substr($move, 2, 2);
			$move2 = $standard->_convertSquareToSAN($from, $to);
			$show = $move2;
			$standard->moveSquare($from, $to);
			$renderFen = $standard->renderFen();
			$result = '';
			$process = 0;
			echo $query = sprintf("INSERT INTO finalgames_B SET move = %s, fen = %s, fenpost = %s, moveby = %s, played_by = 'Stockfish'", GetSQLValueString($move2, "text"), GetSQLValueString($fen, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"));
			echo '<br>';
			$res = @mysql_query($query, $conn);
			if ($rs[0] > 0) {
				echo $query = sprintf("UPDATE finalgames_B SET process = 1 WHERE id = %s", GetSQLValueString($rs[1]['id'], "int"));
				echo '<br>';
				$res = @mysql_query($query, $conn);
			}
		}
	} else {
		echo $query = sprintf("UPDATE finalgames_B SET result = %s, process = 1 WHERE id = %s", GetSQLValueString($legalMoves['result'], "text"), GetSQLValueString($rs[1]['id'], "int"));
		echo '<br>';
		$res = @mysql_query($query, $conn);
	}
} else {
	$legalMoves = getlegalmoves($standard, $fen);
	pr($legalMoves);
	if (empty($legalMoves['success'])) {
		foreach ($legalMoves as $k => $v) {
			$process = 0;
			echo $query = sprintf("INSERT INTO finalgames_B SET move = %s, fen = %s, fenpost = %s, moveby = %s, process = 0", GetSQLValueString($v['move'], "text"), GetSQLValueString($fen, "text"), GetSQLValueString($v['fen'], "text"), GetSQLValueString($toMove, "text"));
			echo '<br>';
			$res = @mysql_query($query, $conn);
		}
		if ($rs[0] > 0) {
			echo $query = sprintf("UPDATE finalgames_B SET process = 1 WHERE id = %s", GetSQLValueString($rs[1]['id'], "int"));
			echo '<br>';
			$res = @mysql_query($query, $conn);
		}
	} else {
		echo $query = sprintf("UPDATE finalgames_B SET result = %s, process = 1 WHERE id = %s", GetSQLValueString($legalMoves['result'], "text"), GetSQLValueString($rs[1]['id'], "int"));
		echo '<br>';
		$res = @mysql_query($query, $conn);
	}
}
?>
<meta http-equiv="refresh" content="5" />