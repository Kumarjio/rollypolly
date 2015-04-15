<?php
include('config.php');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.'/Applications/MAMP/htdocs/chess/libraries/PEAR');
include_once('Connections/conn.php');
require_once 'Games/Chess/Standard.php';
function pr($data)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function getlegalmoves($standard) {
	$toMove = $standard->toMove();
	$ploc = $standard->getPieceLocations($toMove);
	$toArray = $standard->toArray();
	$newloc = array();
	if ($ploc) {
		foreach ($ploc as $loc) {
			$newloc[$loc] = $toArray[$loc];
		}
	}
	$list = array();
	if (empty($newloc)) {
		return false;
	} else {
		foreach ($newloc as $sq => $loc) {
			$move = $standard->getPossibleMoves(strtoupper($loc), $sq, $toMove, true);
			if (!empty($move)) {
				foreach ($move as $m) {
					$legalmove = $standard->_convertSquareToSAN($sq, $m);
					$parseMove = $standard->_parseMove($legalmove);
					$validMove = $standard->_validMove($parseMove);
					if ($validMove === true) {
						$list[] = $legalmove;
					}
				}
			}
		}
	}

	return $list;
}

function insert($standard, $list, $fen, $pid)
{
	foreach ($list as $move) {
		$standard->resetGame($fen);
		$toMove = $standard->toMove();
		$standard->moveSAN($move);
		$newfen = $standard->renderFen();
		echo $sql = "INSERT INTO `chess`.`games` (`move`, `moveby` , `fen`, `pid`) VALUES ('".mysql_escape_string($move)."', '".$toMove."', '".mysql_escape_string($newfen)."', '".$pid."')";
		echo '<br>';
		mysql_query($sql) or die(mysql_error());
	}
}

$standard = new Games_Chess_Standard;
$sql = "select * from games where processed = 0 ORDER BY id LIMIT 100";
$rs = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($rs) == 0) {
	$standard->resetGame();
	$pid = 0;
	$startfen = $standard->renderFen();
	$list = getlegalmoves($standard);
	//pr($list);
	insert($standard, $list, $startfen, $pid);
} else {
	while($rec = mysql_fetch_array($rs)) {
		$standard->resetGame($rec['fen']);
		$pid = $rec['id'];
		$startfen = $standard->renderFen();
		$list = getlegalmoves($standard);
		//pr($list);
		insert($standard, $list, $startfen, $pid);
		mysql_query("update games set processed = 1 where id = ".$pid) or die(mysql_error());
	}
}


/*

			$promotemove = 0;
			if (strtoupper($loc) == 'P') {
				$promotemove = $standard->isPromoteMove($sq, $m);
			}
*/
?>