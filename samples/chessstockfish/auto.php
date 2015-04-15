<?php require_once('Connections/conn.php'); ?>
<?php

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');
$time1 = microtime(true);
$moves = trim($_GET['moves']);
	$standard = new Games_Chess_Standard;
	$standard->resetGame();

	if (!empty($moves)) {
		$tmp = explode('.', $moves);
		$tmp = array_slice($tmp, 1);
		foreach ($tmp as $k => $v) {
			$mv = explode(' ', $v);
			$white = !empty($mv[0]) ? clean($mv[0], $standard) : '';
			$standard->moveSAN($white);
			//pr($standard->getMoveList());
			$black = !empty($mv[1]) ? clean($mv[1], $standard) : '';
			if ($black == '') {
				break;	
			}
			
			$standard->moveSAN($black);
			//pr($standard->getMoveList());
		}
	}
		$fen = $standard->renderFen();
		$moves = $standard->getMoveList();
		$toMove = $standard->toMove();

	$moveby = 'W';
	if ($toMove == 'W') {
		$moveby = 'B';
	}
	//check db if fen occurs:
	$chess = new Chess;
	$result = $chess->findfen($fen, 'finalgames');
	$legalMoves = array();
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
		$legalMoves = getlegalmoves($standard, $renderFen);
		if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
			$result = $legalMoves['result'];
			$process = 1;
		}//end if
		$query = sprintf("INSERT INTO finalgames SET move = %s, fen = %s, fenpost = %s, moveby = %s, result = %s, process = %s, played_by = 'Stockfish'", GetSQLValueString($move2, "text"), GetSQLValueString($fen, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"));
		$rs = @mysql_query($query, $conn);
	} else {
		$renderFen = $result[1]['fenpost'];
		$move2 = $result[1]['move'];
		$show = $move2;
		$legalMoves = getlegalmoves($standard, $renderFen);
	}
$time2 = microtime(true);
$return = array('moves' => $_GET['moves'], 'show' => $show, 'time' => $time2 - $time1.' secs');
$data = json_encode($return);
echo $_GET['jsoncallback'] . '(' . $data . ');';
exit;
?>