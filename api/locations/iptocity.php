<?php
include_once('../../config.php');
//http://mkgalaxy.com/api/locations/iptocity.php
try {
	$g = new world_Geography();
  $ip = !empty($_GET['ip']) ? $_GET['ip'] : $_SERVER['REMOTE_ADDR'];
	$r = $g->iptocity($ip);
  $r2 = $g->get_nearby_cities($r['lat'], $r['lon']);
  $r['nearby'] = $r2;
	if (empty($r)) {
		throw new Exception('Empty Data', 2001);
	}
} catch (Exception $e) {
	$r = array('success' => 0, 'error' => 1, 'msg' => $e->getMessage(), 'code' => $e->getCode());
}
//echo json_encode($r);
$content = json_encode($r);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
?>