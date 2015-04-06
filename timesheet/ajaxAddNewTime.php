<?php require_once('../Connections/conn.php'); ?>
<?php
function getDays($time='') {
	if(!$time) {
		$time = mktime(0,0,0,date('m'),date('d'),date('Y'));
	}
	$today = getdate($time);
	//print_r($today);
	switch($today['wday']) {
		case 1: // monday
			$monday = $time+(60*60*24*0); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time+(60*60*24*1); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*2); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*3); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*4); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*5); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*6); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 2: // tuesday
			$monday = $time-(60*60*24*1); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time+(60*60*24*0); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*1); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*2); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*3); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*4); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*5); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 3: // wednesday
			$monday = $time-(60*60*24*2); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*1); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time+(60*60*24*0); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*1); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*2); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*3); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*4); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 4: // thursday
			$monday = $time-(60*60*24*3); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*2); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*1); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time+(60*60*24*0); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*1); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*2); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*3); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 5: // friday
			$monday = $time-(60*60*24*4); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*3); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*2); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*1); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time+(60*60*24*0); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*1); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*2); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 6: // saturday
			$monday = $time-(60*60*24*5); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*4); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*3); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*2); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time-(60*60*24*1); 
			$arr['friday'] = getdate($friday);
			$saturday = $time+(60*60*24*0); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*1); 
			$arr['sunday'] = getdate($sunday);
			break;
		case 0: // sunday
			$monday = $time-(60*60*24*6); 
			$arr['monday'] = getdate($monday);
			$tuesday = $time-(60*60*24*5); 
			$arr['tuesday'] = getdate($tuesday);
			$wednesday = $time-(60*60*24*4); 
			$arr['wednesday'] = getdate($wednesday);
			$thursday = $time-(60*60*24*3); 
			$arr['thursday'] = getdate($thursday);
			$friday = $time-(60*60*24*2); 
			$arr['friday'] = getdate($friday);
			$saturday = $time-(60*60*24*1); 
			$arr['saturday'] = getdate($saturday);
			$sunday = $time+(60*60*24*0); 
			$arr['sunday'] = getdate($sunday);
			break;
	}
	return $arr;
}

function catlink_categories1($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."'";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = '<a href="add.php?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		array_unshift($q,$category);
		catlink_categories1($pid,$q);	
	} else {
		$cat_id = 0;
		$pid = 0;
		$category = '<a href="addhome.php?s='.$s.'&list_id='.$cat_id.'&pid='.$pid.'">Home</a>';
		array_unshift($q,$category);
		foreach($q as $value) {
			$displayLink .= $value.' -> ';
		}
		$displayLink = substr($displayLink,0,-4);
	}
}

function catlink_categories3($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."'";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
	$rec = mysql_fetch_array($rs);
	if($rec['level']>=3) {
		$query .= " and user_id = '".$_SESSION['MM_UserId']."'";
		$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
		$rec = mysql_fetch_array($rs);
	}
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		if($rec['level']>=3) {
			$category = $rec['list'];
		} else {
			$category = '<a href="add.php?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		}
		array_unshift($q,$category);
		catlink_categories3($pid,$q);	
	} else {
		$cat_id = 0;
		$pid = 0;
		$category = '<a href="addhome.php?s='.$s.'&list_id='.$cat_id.'&pid='.$pid.'">Home</a>';
		array_unshift($q,$category);
		foreach($q as $value) {
			$displayLink .= $value.' -> ';
		}
		$displayLink = substr($displayLink,0,-4);
	}
}
$rs = mysql_query("select * from procentris_list where list_id = '".$pid."' and user_id = '".$_SESSION['MM_UserId']."'") or die("Error in line no ".__LINE__." of File ".__FILE__." with error: ".mysql_error());
if(mysql_num_rows($rs)>0) {
	$rec = mysql_fetch_array($rs);
	$level_parent = $rec['level'];
	$level_child = $level_parent + 1;	
}
if(!$level_child) $level_child = 2;
if($level_child==1) {
	$list_type = "Category";
} else if($level_child==2) {
	$list_type = "Project";
} else if($level_child==3) {
	$list_type = "Task";
} 
// display location string
$q = array();
if($list_id) {
	if($level_child==2) {
		catlink_categories1($list_id,$q);
	} else {
		catlink_categories3($list_id,$q);
	}
} else {
	$displayLink = "<a href='addhome.php'>Home</a>";
}
?>
<?php
$colname_rsList = "-1";
if (isset($_GET['list_id'])) {
  $colname_rsList = (get_magic_quotes_gpc()) ? $_GET['list_id'] : addslashes($_GET['list_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsList = sprintf("SELECT a.list as task, b.list as project, c.list as cat, a.list_id as task_id, b.list_id as project_id, c.list_id as cat_id FROM procentris_list as a, procentris_list as b, procentris_list as c WHERE a.pid = b.list_id and b.pid = c.list_id and a.list_id = %s", $colname_rsList);
$rsList = mysql_query($query_rsList, $conn) or die(mysql_error());
$row_rsList = mysql_fetch_assoc($rsList);
$totalRows_rsList = mysql_num_rows($rsList);
?>
Date: 
<select name="cdate">  
  <?php
  $Week = getDays(time());
  
  $pre = $Week['monday'][0]-(60*60*24);
  $Week2 = getDays($pre);
  	$nows = getdate();
if($nows['wday']==2 || $nows['wday']==1) {
	foreach($Week2 as $key => $value) {
		echo "<option value='".$Week2[$key]['year']."-".$Week2[$key]['mon']."-".$Week2[$key]['mday']."'";
		if(date('Y-m-j')==$Week2[$key]['year']."-".$Week2[$key]['mon']."-".$Week2[$key]['mday']) {
			echo " selected";
		}
		echo ">".$Week2[$key]['year']."-".$Week2[$key]['mon']."-".$Week2[$key]['mday']."</option>";
		$displayArray[$Week2[$key]['year']."-".$Week2[$key]['mon']."-".$Week2[$key]['mday']] = $Week2[$key]['mday'];
	}
}
	foreach($Week as $key => $value) {
		echo "<option value='".$Week[$key]['year']."-".$Week[$key]['mon']."-".$Week[$key]['mday']."'";
		if(date('Y-m-j')==$Week[$key]['year']."-".$Week[$key]['mon']."-".$Week[$key]['mday']) {
			echo " selected";
		}
		echo ">".$Week[$key]['year']."-".$Week[$key]['mon']."-".$Week[$key]['mday']."</option>";
		$displayArray[$Week[$key]['year']."-".$Week[$key]['mon']."-".$Week[$key]['mday']] = $Week[$key]['mday'];
	}
	?>
  </select>
  <?php
  //print_r($displayArray);
  if($displayArray) {
  	foreach($displayArray as $key=>$value) { ?>
  <a href="javascript:chooseobject('<?php echo $key; ?>');"><?php echo $value; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
  	<?php 
		}
	}
  ?>
<!-- <input name="cdate" type="text" id="cdate" value="<?php echo date('Y-m-d'); ?>"> --> 
  </p>
  <p><strong>Time Worked:</strong>    
    <input name="timetaken" type="text" id="timetaken" value="<?php echo $_POST['timetaken']; ?>">
  </p>
  <p>
    <input type="button" name="Submit" value="Add Time" onClick="var str = getFormElements(document.formAction); doAjax('ajaxAddNewTimeSubmit.php','POST','user_id=<?php echo $_GET['user_id']; ?>',str,'newTimeSubmit');doAjax('ajax_timesheet_new.php','GET','user_id=<?php echo $_GET['user_id']; ?>&mydate='+document.formAction.mydate.value,'','divTimesheetNew');">
    <input name="user_id" type="hidden" id="user_id" value="<?php echo $_GET['user_id']; ?>">
    <input name="cday" type="hidden" id="cday" value="<?php echo date('d'); ?>">    
    <input name="cmonth" type="hidden" id="cmonth" value="<?php echo date('m'); ?>">
    <input name="cyear" type="hidden" id="cyear" value="<?php echo date('Y'); ?>">
</p>
  <input type="hidden" name="MM_insert" value="form1">
<?php
mysql_free_result($rsList);
?>
