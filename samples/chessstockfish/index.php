<?php require_once('Connections/conn.php'); ?>
<?php
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');
if (!empty($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'fen') {
	$standard = new Games_Chess_Standard;
	$standard->resetGame();

	$moves = $_POST['moves'];
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
		//echo 'White: '.$white.', black: '.$black.'<br>';
		$standard->moveSAN($black);
		//pr($standard->getMoveList());
	}
	$fen = $standard->renderFen();
	$moves = $standard->getMoveList();
	$toMove = $standard->toMove();
}


if (!empty($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'fenplay') {
	$standard = new Games_Chess_Standard;
	$standard->resetGame($_POST['fen']);
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
		$standard->moveSquare($from, $to);
		$renderFen = $standard->renderFen();
		$result = '';
		$process = 1;
		$legalMoves = getlegalmoves($standard, $renderFen);
		if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
			$result = $legalMoves['result'];
			$process = 1;
		}//end if
		$query = sprintf("INSERT INTO finalgames SET move = %s, fen = %s, fenpost = %s, moveby = %s, result = %s, process = %s, played_by = 'Stockfish', owner = %s", GetSQLValueString($move2, "text"), GetSQLValueString($fen, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"), GetSQLValueString($toMove, "text"));
		$rs = mysql_query($query, $conn) or die(mysql_error());
	} else {
		$renderFen = $result[1]['fenpost'];
		$move2 = $result[1]['move'];
		$legalMoves = getlegalmoves($standard, $renderFen);
	}
	if (empty($legalMoves['success'])) {
		foreach ($legalMoves as $k => $v) {
			$process = 0;
			$legalMoves2 = getlegalmoves($standard, $v['fen']);
			if (!empty($legalMoves2['success']) && $legalMoves2['success'] == 1) {
				$result = $legalMoves2['result'];
				$process = 1;
			}
			$query = sprintf("INSERT INTO finalgames SET move = %s, fen = %s, fenpost = %s, moveby = %s, result = %s, process = %s, owner = %s", GetSQLValueString($v['move'], "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($v['fen'], "text"), GetSQLValueString($moveby, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"), GetSQLValueString($toMove, "text"));
			$rs = @mysql_query($query, $conn);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Main Page</title>
<style type="text/css">
body {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;	
}
</style>
</head>

<body>
<h2>Complete</h2>
<p><a href="white.php">Complete White</a> | Developmental White</p>
<p><a href="chess.php">View Complete White</a></p>
<p><a href="search.php">Search Complete White</a></p>
<h2>Combinations</h2>
<p><a href="gamespos.php">Analyse Combinations</a></p>
<p><a href="gamespos_view.php">View Combinations</a></p>
<h2>Find Fen</h2>
<?php if (!empty($fen)) {
	echo $fen;
	pr($moves);
	echo 'To Move: '.$toMove.', and Move is: '.$move2;
}
?>
<form id="form1" name="form1" method="post" action="">
	<p>
		<label for="moves"></label>
		<textarea name="moves" id="moves" cols="45" rows="5"><?php echo !empty($_POST['moves']) ? $_POST['moves'] : NULL; ?></textarea>
	</p>
	<p>
		<input type="submit" name="submit" id="submit" value="Get Fen" />
		<input name="MM_Insert" type="hidden" id="MM_Insert" value="fen" />
	</p>
</form>
<form id="form2" name="form2" method="post" action="">
	<p>
		<label for="moves"></label>
		<textarea name="fen" id="fen" cols="45" rows="5"><?php echo !empty($fen) ? $fen : NULL; ?></textarea>
	</p>
	<p>
		<input type="submit" name="submit" id="submit" value="Get Move" />
		<input name="MM_Insert" type="hidden" id="MM_Insert" value="fenplay" />
	</p>
</form>
<p>&nbsp;</p>
</body>
</html>