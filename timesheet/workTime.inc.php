<a href="javascript:;" onClick="javascript:toggleLayer2('todaysmenu');">View Today's Timesheet</a><br>
<div id="todaysmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getTodaysTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>
<br>
<a href="javascript:;" onClick="javascript:toggleLayer2('weeksmenu');">View Week's Timesheet</a><br>
<div id="weeksmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getWeeksTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>
<br>
<a href="javascript:;" onClick="javascript:toggleLayer2('lastweeksmenu');">View Last Week's Timesheet</a><br>
<div id="lastweeksmenu" style="display:none; ">
	<?php
// get array
include_once('f_workinghours.php');
$time = time()-(60*60*24*7);
$returnArray = getWeeksTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>