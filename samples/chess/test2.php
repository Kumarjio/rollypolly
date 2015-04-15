<?php
ob_start();
ini_set('memory_limit','500M');
ini_set('max_execution_time','-1');
include('config.php');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.'/Applications/MAMP/htdocs/chess/libraries/PEAR');
include_once('Connections/conn.php');
require_once 'Games/Chess/Standard.php';

//rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
$standard = new Games_Chess_Standard;
$standard->blankBoard();
$standard->addPiece('W', 'K', 'g5');
$standard->addPiece('W', 'Q', 'f5');
$standard->addPiece('B', 'K', 'g8');
//$standard->_move = 'B';
$initial = $standard->renderFen();
$fen_id = createposition($initial);
//$list = getlegalmoves($standard);

$rs = mysql_query("select * from gamesallmoves where fen_id = '".$fen_id."' order by id limit 1") or die(mysql_error());
if (mysql_num_rows($rs) == 0) {
	echo 'inserting first: ';
	insertgamesallmoves($standard, $initial, 0, $fen_id);
} else {
	echo 'inserting followup: ';
	$rs = mysql_query("select * from gamesallmoves where (result = '' or result is null) AND processed = 0 AND fen_id = '".$fen_id."' order by id limit 100") or die(mysql_error());
	if (mysql_num_rows($rs) == 0) {
		echo 'data completed';
	} else {
		while ($rec = mysql_fetch_array($rs)) {
			insertgamesallmoves($standard, $rec['fen'], $rec['id'], $fen_id);
			mysql_query("update gamesallmoves set processed = 1 where id = '".$rec['id']."'") or die(mysql_error());
			//flush();
			//sleep(1);
		}
	}
}
?>
<meta http-equiv="refresh" content="2" />