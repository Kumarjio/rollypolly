<?php
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once('Connections/conn.php');
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');
$standard = new Games_Chess_Standard;
$fen = !empty($_GET['fen']) ? $_GET['fen'] : '';
$standard->resetGame($fen);
$toMove = $standard->toMove();
$renderFen = $standard->renderFen();
if (empty($fen)) {
	//start of the game, find the first move:
	$chess = new Chess;
	$move = $chess->find($renderFen);
	$from = substr($move, 0, 2);
	$to = substr($move, 2, 2);
	$move2 = $standard->_convertSquareToSAN($from, $to);
	$standard->moveSquare($from, $to);
	$renderFen = $standard->renderFen();
	mysql_select_db($database_conn, $conn);
	$query_rsView = sprintf("SELECT * FROM games WHERE move = %s and fen = %s and moveby = %s", GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"));
	$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());
	$totalRows_rsView = mysql_num_rows($rsView);
	if ($totalRows_rsView == 0) {
		$query = sprintf("INSERT INTO games SET pid = 0, move = %s, fen = %s, moveby = %s, result = ''", GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"));
		$rs = mysql_query($query, $conn) or die(mysql_error());
	}
}
echo 'done';
exit;