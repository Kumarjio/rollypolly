<?php
function pr($d)
{
	echo '<pre>';
	print_r($d);
	echo '</pre>';	
}


$promotepiece = array('Q', 'R', 'B', 'N');
function oppositewins($p='B')
{
	return ($p === 'B') ? 1 : 2;
}

function getlegalmoves($standard, $fen)
{
	$standard->resetGame($fen);
	global $promotepiece;
	$toMove = $standard->toMove();
	if ($standard->gameOver()) {
		return array('success' => 1, 'result' => $standard->gameOver());
	}

	if ($standard->inCheckMate()) {
		return array('success' => 1, 'result' => oppositewins($toMove));
	}

	if ($standard->inDraw()) {
		return array('success' => 1, 'result' => 3);
	}

	if ($standard->inStaleMate()) {
		return array('success' => 1, 'result' => 4);
	}

	if ($standard->inRepetitionDraw()) {
		return array('success' => 1, 'result' => 5);
	}

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
					$promotemove = 0;
					if (strtoupper($loc) == 'P') {
						$promotemove = $standard->isPromoteMove($sq, $m);
					}

					if (!empty($promotemove)) {
						foreach ($promotepiece as $piece) {
							$legalmove = $standard->_convertSquareToSAN($sq, $m, $piece);
							$parseMove = $standard->_parseMove($legalmove);
							$validMove = $standard->_validMove($parseMove);
							if ($validMove === true) {
								$standard->resetGame($fen);
								$standard->moveSAN($legalmove);
								$renderFen = $standard->renderFen();
								$list[] = array('move' => $legalmove, 'fen' => $renderFen);
								$standard->resetGame($fen);
							}
						}
					} else {
						$legalmove = $standard->_convertSquareToSAN($sq, $m);
						$parseMove = $standard->_parseMove($legalmove);
						$validMove = $standard->_validMove($parseMove);
						if ($validMove === true) {
							$standard->resetGame($fen);
							$standard->moveSAN($legalmove);
							$renderFen = $standard->renderFen();
							$list[] = array('move' => $legalmove, 'fen' => $renderFen);
							$standard->resetGame($fen);
						}
					}
				}
			}
		}
	}

	return $list;
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
function insertgamesallmoves($standard, $fen, $pid, $fen_id)
{
	$standard->resetGame($fen);
	$list = getlegalmoves($standard);
	foreach ($list as $move) {
		$cid = '';
		if (!empty($pid)) {
			$sql = "insert into gamesallmoves (`move_number` ,`fen_id` ,`fen` ,`move_by` ,`result` ,`move_w_1` ,`move_b_1` ,`move_w_2` ,`move_b_2` ,`move_w_3` ,`move_b_3` ,`move_w_4` ,`move_b_4` ,`move_w_5` ,`move_b_5` ,`move_w_6` ,`move_b_6` ,`move_w_7` ,`move_b_7` ,`move_w_8` ,`move_b_8` ,`move_w_9` ,`move_b_9` ,`move_w_10` ,`move_b_10` ,`move_w_11` ,`move_b_11` ,`move_w_12` ,`move_b_12` ,`move_w_13` ,`move_b_13` ,`move_w_14` ,`move_b_14` ,`move_w_15` ,`move_b_15` ,`move_w_16` ,`move_b_16` ,`move_w_17` ,`move_b_17` ,`move_w_18` ,`move_b_18` ,`move_w_19` ,`move_b_19` ,`move_w_20` ,`move_b_20`) select `move_number` ,`fen_id` ,`fen` ,`move_by` ,`result` ,`move_w_1` ,`move_b_1` ,`move_w_2` ,`move_b_2` ,`move_w_3` ,`move_b_3` ,`move_w_4` ,`move_b_4` ,`move_w_5` ,`move_b_5` ,`move_w_6` ,`move_b_6` ,`move_w_7` ,`move_b_7` ,`move_w_8` ,`move_b_8` ,`move_w_9` ,`move_b_9` ,`move_w_10` ,`move_b_10` ,`move_w_11` ,`move_b_11` ,`move_w_12` ,`move_b_12` ,`move_w_13` ,`move_b_13` ,`move_w_14` ,`move_b_14` ,`move_w_15` ,`move_b_15` ,`move_w_16` ,`move_b_16` ,`move_w_17` ,`move_b_17` ,`move_w_18` ,`move_b_18` ,`move_w_19` ,`move_b_19` ,`move_w_20` ,`move_b_20` from gamesallmoves WHERE id = ".$pid;
			mysql_query($sql) or die(mysql_error());
			$cid = mysql_insert_id();
		}

		$standard->resetGame($fen);
		$toMove = $standard->toMove();
		$standard->moveSAN($move);
		$newfen = $standard->renderFen();
		if (empty($cid)) {
			$movenumber = 1;
			$field = 'move_'.strtolower($toMove).'_'.$movenumber;
			echo $sql = "INSERT INTO gamesallmoves set fen_id = '".$fen_id."', move_by = '".$toMove."', result = '".$standard->gameOver()."', ".$field." = '".mysql_escape_string($move)."', fen = '".mysql_escape_string($newfen)."', move_number = ".$movenumber.", pid = ".$pid;
		
		} else {
			$sql = "select * from gamesallmoves where id = '".$pid."'";
			$rs = mysql_query($sql) or die(mysql_error());
			$rec = mysql_fetch_array($rs);
			$movenumber = $rec['move_number'];
			if ($toMove === 'W') {
				$movenumber = $movenumber + 1;
			}

			$field = 'move_'.strtolower($toMove).'_'.$movenumber;
			echo $sql = "UPDATE gamesallmoves set move_by = '".$toMove."', result = '".$standard->gameOver()."', ".$field." = '".mysql_escape_string($move)."', fen = '".mysql_escape_string($newfen)."', move_number = ".$movenumber.", pid = ".$pid." where id = ".$cid;
		}
		echo '<br>';
		mysql_query($sql) or die(mysql_error());
	}
}

function createposition($fen)
{
	$sql = "select * from positions where position = '".mysql_escape_string($fen)."'";
	$rs = mysql_query($sql) or die(mysql_error());
	if (mysql_num_rows($rs) == 0) {
		mysql_query("insert into positions set position = '".mysql_escape_string($fen)."'") or die(mysql_error());
		return mysql_insert_id();
	} else {
		$rec = mysql_fetch_array($rs);
		return $rec['fen_id'];
	}
}

function process($standard, $fromMove, $toMove, $legalMovesOriginal, $row_rsView, $fen)
{
	if ($fromMove == 'B') {
		$table = 'games';
	} else {
		$table = 'gamesblack';
	}
	global $database_conn, $conn;
	if ($toMove == $fromMove) {
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
			echo $query = sprintf("SELECT * FROM $table WHERE pid = %s and move = %s and fen = %s and moveby = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"));
			echo '<br>';
			$rs = mysql_query($query, $conn) or die(mysql_error());
			$totalRows = mysql_num_rows($rs);
			if ($totalRows == 0) {
				echo $query = sprintf("INSERT INTO $table SET pid = %s, move = %s, fen = %s, moveby = %s, result = %s, process = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"));
				echo '<br>';
				$rs = mysql_query($query, $conn) or die(mysql_error());
			}
		}
	
		$query = sprintf("UPDATE $table SET process = 1 WHERE id = %s", GetSQLValueString($row_rsView['id'], "int"));
		$rs = mysql_query($query, $conn) or die(mysql_error());
	} else {
		mysql_select_db($database_conn, $conn);
		echo $query = sprintf("SELECT * FROM $table WHERE pid = %s", GetSQLValueString($row_rsView['id'], "int"));
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
			echo $query = sprintf("INSERT INTO $table SET pid = %s, move = %s, fen = %s, moveby = %s, result = %s, process = %s", GetSQLValueString($row_rsView['id'], "int"), GetSQLValueString($move2, "text"), GetSQLValueString($renderFen, "text"), GetSQLValueString($toMove, "text"), GetSQLValueString($result, "text"), GetSQLValueString($process, "int"));
			echo '<br>';
			$rs = mysql_query($query, $conn) or die(mysql_error());
		}		
		$query = sprintf("UPDATE $table SET process = 1 WHERE id = %s", GetSQLValueString($row_rsView['id'], "int"));
		$rs = mysql_query($query, $conn) or die(mysql_error());
	}
	return $standard;
}


function clean($mv, $standard)
{
	//echo '<hr>';
	if ($mv == 'O-O' || $mv == 'O-O-O') {
		return $mv;	
	}
	$tmp = $standard->_parseMove($mv);
	//pr($tmp);
	foreach ($tmp as $k => $v) {
		$mv = '';
		if ($v['piece'] == 'P') {
			$mv = $v['takesfrom'].$v['takes'].$v['square'];
			break;
		} else {
			$mv = $v['piece'].$v['takesfrom'].$v['takes'].$v['square'];
		}
	}
	//echo $mv;
	//echo '<br>';
	return $mv;
}

function getkey($fen='')
{
	$key = 'key_'.md5($fen);
	return $key;
}


function getside($toMove)
{
	if ($toMove == 'W')
		$side = 'White';
	else if ($toMove == 'B')
		$side = 'Black';
	else
		$side = 'White';
	return $side;	
}
function replacemove($side, $arr='')
{
	global $database_conn, $conn;
	mysql_select_db($database_conn, $conn);
	$query = sprintf("REPLACE finalgames SET side = %s, ponder = %s, move = %s, fen = %s, fenpost = %s, moveby = %s, result = %s, process = %s, played_by = 'Stockfish'", GetSQLValueString($side, "text"), GetSQLValueString($arr['ponder'], "text"), GetSQLValueString($arr['show'], "text"), GetSQLValueString($arr['fen'], "text"), GetSQLValueString($arr['fenpost'], "text"), GetSQLValueString($arr['toMove'], "text"), GetSQLValueString($arr['result'], "text"), GetSQLValueString($arr['process'], "int"));
	$rs = mysql_query($query, $conn) or die(mysql_error());
}

function getnewfen($standard, $fen, $move)
{
	$from = substr($move, 0, 2);
	$to = substr($move, 2, 2);
	$move2 = $standard->_convertSquareToSAN($from, $to);
	$show = $move2;
	$standard->moveSquare($from, $to);
	$renderFen = $standard->renderFen();
	return $renderFen;	
}
?>