<?php
$time1 = microtime(true);
require_once('Connections/conn.php');
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');
require_once('Cache/Lite.php');

$moves = !empty($_REQUEST['moves']) ? trim($_REQUEST['moves']) : NULL;
$fen = !empty($_REQUEST['fen']) ? trim($_REQUEST['fen']) : NULL;

$side = !empty($_REQUEST['side']) ? trim($_REQUEST['side']) : NULL;
if (empty($moves) && empty($fen)) {
	echo 'please add some moves or fen';
	exit;	
}

$standard = new Games_Chess_Standard;
$standard->resetGame($fen);

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

$id = getkey($fen);

$options = array(
    'cacheDir' => realpath('cache/').'/',
    'lifeTime' => (60*60*24*365*10)
);

$Cache_Lite = new Cache_Lite($options);
$refresh = isset($_GET['refresh']) ? true : false;
if ($data = $Cache_Lite->get($id) && !$refresh) { // cache hit !
   	$data = json_decode($data, 1);
	if (empty($side)) $side = getside($data['toMove']);
	$data['process'] = 0;
	replacemove($side, $data);
	$time2 = microtime(true);
	$data['time'] = $time2 - $time1.' secs';
	$data['id'] = $id;
	$data = json_encode($data);
} else { // page has to be (re)constructed in $data
	$chess = new Chess;
	$data = $chess->process($standard, $fen);
	if (empty($side)) $side = getside($data['toMove']);
	$data['process'] = 0;
	replacemove($side, $data);
	$time2 = microtime(true);
	$data['time'] = $time2 - $time1.' secs';
	$data['id'] = $id;
	$data = json_encode($data);
   	$Cache_Lite->save($data);
}
if (isset($_GET['jsoncallback'])) {
echo $_GET['jsoncallback'] . '(' . $data . ');';
} else echo $data;
exit;
?>