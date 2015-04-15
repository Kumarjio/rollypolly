<?php
include('config.php');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.'/Applications/MAMP/htdocs/chess/libraries/PEAR');
include_once('Connections/conn.php');
require_once 'Games/Chess/Standard.php';

//rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
$standard = new Games_Chess_Standard;
$standard->resetGame();
echo $standard->renderFen();
echo '<br>';
$standard->moveSAN('f4');
$standard->moveSAN('e5');
$list = getlegalmoves($standard);
pr($list);
$standard->moveSAN('g4');
$standard->moveSAN('Qh4');
echo $standard->renderFen();
echo '<br>';
$list = getlegalmoves($standard);
pr($list);
exit;
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