<?php require_once('Connections/conn.php'); ?>
<?php

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');
require_once('Cache/Lite.php');
$fen = isset($_GET['fen']) ? $_GET['fen'] : NULL;
$standard = new Games_Chess_Standard;
$standard->resetGame($fen);
$fen = $standard->renderFen();
$toMove = $standard->toMove();

$legalMoves = getlegalmoves($standard, $fen);
$result = '';
if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
	$result = $legalMoves['result'];
}//end if
if (!empty($fen) && !empty($_GET['analyse'])) {
	$file = 'http://localhost:82/chessstockfish/autoCache.php?fen='.urlencode($fen);
	echo $file;
	$content = file_get_contents($file);
	$analyseData = json_decode($content, 1);
}

	$prefen = !empty($_GET['prefen']) ? $_GET['prefen'] : NULL;
	$id = getkey($prefen);
	
	$options = array(
		'cacheDir' => realpath('cache/').'/',
		'lifeTime' => (60*60*24*365*10)
	);
	
	$Cache_Lite = new Cache_Lite($options);
	$data = $Cache_Lite->get($id);
	if (!empty($data)) {
		$array = json_decode($data, 1);
		pr($array);
	}
if (!empty($fen) && !empty($_GET['save'])) {
	$arr = array();
	$arr['fenpost'] = $fen;
	$arr['fen'] = !empty($_GET['prefen']) ? $_GET['prefen'] : NULL;
	$arr['toMove'] = !empty($_GET['toMove']) ? $_GET['toMove'] : NULL;
	$arr['show'] = !empty($_GET['move']) ? $_GET['move'] : NULL;
	$legalMoves = getlegalmoves($standard, $arr['fenpost']);
	$result = '';
	$process = 0;
	if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
		$result = $legalMoves['result'];
		$process = 1;
	}
	$arr['result'] = $result;
	$arr['process'] = $process;
	$arr['ponder'] = NULL;
	$arr['time'] = '0 secs';
	if ($data = $Cache_Lite->get($id)) {
		$side = $arr['toMove'];
		replacemove($side, $data);
	} else {
		$arr = json_encode($arr);
   		$Cache_Lite->save($arr);
		$side = $arr['toMove'];
		replacemove($side, $arr);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Game</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/chess.css"></link>
	<script type="text/javascript" src="js/ChessFen.js"></script>
	<style type="text/css" media="screen">
	body{
		font-family:"Trebuchet MS";
		font-size:0.9em;
		margin:0px;
	}

	#boardContainer{
		width:760px;

		padding:5px;
	}
	pre{
		font-family:Courier New, Courier New, Courier, monospace;
		color: #000;	
		font-size:0.75em;
		border:1px solid #317082;
		border-left:5px solid #317082;		
		padding:2px;
	}
	img{
		border:0px;
	}
	input{
		font-size:11px;
	}

	</style>
<script type="text/javascript">
	
	function displayFen(fenString)
	{
		try{
			chessObj.loadFen(fenString,'boardDiv');
			document.getElementById('fenInput').value = fenString;
			document.getElementById('fenString').innerHTML = fenString;
		}catch(e){
			alert('Invalid FEN');	
		}
	}
	
	
	</script>
</head>

<body>
<h1><a href="game.php">Chess</a></h1>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td valign="top">

<?php if (isset($_GET['move'])) { ?>
<p>Last Move: <?php echo $_GET['move']; ?></p>
<?php } ?>
<ul>
<?php 
$urls = array();
if (empty($legalMoves['success'])) {
	foreach ($legalMoves as $k => $v) {
		if (!empty($analyseData['show']) && $analyseData['show'] == $v['move']) {
			$standard->moveSan($v['move']);
			?>
			<li><h3><a href="game.php?move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>"><?php echo $v['move']; ?></a> | <a href="game.php?analyse=1&move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>">Analyse</a> (<a href="game.php?auto=1&analyse=1&move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>#show">Auto</a>) | <a href="game.php?&save=1&move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>&prefen=<?php echo urlencode($fen); ?>&toMove=<?php echo $toMove; ?>">Save</a> - Ponder: <a href="game.php?move=<?php echo $analyseData['ponder']; ?>&fen=<?php echo urlencode(getnewfen($standard, $v['fen'], $analyseData['ponder'])); ?>"><?php echo $analyseData['ponder']; ?></a> - <a href="game.php?&analyse=1&move=<?php echo $analyseData['ponder']; ?>&fen=<?php echo urlencode(getnewfen($standard, $v['fen'], $analyseData['ponder'])); ?>">Analysis</a> (<a href="game.php?auto=1&analyse=1&move=<?php echo $analyseData['ponder']; ?>&fen=<?php echo urlencode(getnewfen($standard, $v['fen'], $analyseData['ponder'])); ?>#show">Auto</a>)</h3>
					
				<?php 
				$urls[] = 'game.php?&analyse=1&move='.$analyseData['ponder'].'&fen='.urlencode(getnewfen($standard, $v['fen'], $analyseData['ponder']));
				if (isset($_GET['auto'])) {
					?>
					<meta http-equiv="refresh" content="5;URL=game.php?auto=1&analyse=1&move=<?php echo $analyseData['ponder']; ?>&fen=<?php echo urlencode(getnewfen($standard, $v['fen'], $analyseData['ponder'])); ?>#show" />
					<?php
				}
				?>
			</li>
			<?php	
		} else {
		?>
		<li><a href="game.php?move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>"><?php echo $v['move']; ?></a> | <a href="game.php?&analyse=1&move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>">Analyse</a> | <a href="game.php?&save=1&move=<?php echo $v['move']; ?>&fen=<?php echo urlencode($v['fen']); ?>&prefen=<?php echo urlencode($fen); ?>&toMove=<?php echo $toMove; ?>">Save</a></li>
		<?php
			$urls[] = 'game.php?&analyse=1&move='.$v['move'].'&fen='.urlencode($v['fen']);
		}
	}
}
?>
</ul>
		</td>
		<td valign="top">

<div id="mainContainer">
	<div id="boardContainer">
		<p><b>My own FEN </b> ( <span id="fenString" style="font-size:0.9em;font-style:italic"></span> )</p>
		<div><table><tr><td>Type FEN in the field below:<a name="show"></a></td></tr>
		<tr><td><input type="text" id="fenInput" style="width:360px" onchange="displayFen(this.value)"></td></tr></table></div>
		<div id="boardDiv"></div>
	</div>
</div>

<script type="text/javascript">
var chessObj = new DHTMLGoodies.ChessFen( { pieceType:'alpha',squareSize:60 });
displayFen('<?php echo $fen; ?>');




</script>
		</td>
	</tr>
</table>
</body>
</html>