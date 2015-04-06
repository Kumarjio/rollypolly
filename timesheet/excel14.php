<<?php require_once('../Connections/conn.php'); ?>
<?php
session_start();
//$include_path=".;c:\wamp\www\procentris2\includes\pear";
$include_path=".:".$_SERVER['DOCUMENT_ROOT']."/utilities/procentris/includes/pear"; 
ini_set("include_path", $include_path); //".:../:./include:../include");
require_once 'Spreadsheet/Excel/Writer.php';

?>
<?php
mysql_select_db($database_conn, $conn);
$query_rsUser = "SELECT * FROM procentris_users";
$rsUser = mysql_query($query_rsUser, $conn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>
<?php
function getCategory($u, $fromdate, $todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	$query = "select DISTINCT pt.category, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.category = pl.list_id and pl.list_id = '".$_GET['list_id']."'";
	$rs = mysql_query($query) or die("error".mysql_error());
	while($rec = mysql_fetch_array($rs)) {
		$categories[$rec['category']] = $rec['list'];
	}
	return $categories;
}
function getProject($u, $categoryKey, $fromdate, $todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	$query = "select DISTINCT pt.project, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.project = pl.list_id";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$projects[$rec['project']] = $rec['list'];
	}
	return $projects;
}
function getTask($u,$categoryKey,$projectKey,$fromdate,$todate) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	if(!$projectKey) $projectKey = '%';
	$query = "select DISTINCT pt.tasks, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.project like '".$projectKey."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.tasks = pl.list_id";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$tasks[$rec['tasks']] = $rec['list'];
	}
	return $tasks;
}

function getTime($u,$categoryKey,$projectKey,$taskKey,$date) {
	if(!$fromdate) $fromdate="1900-01-01";
	if(!$todate) $todate="3000-12-31";
	if(!$categoryKey) $categoryKey = '%';
	if(!$projectKey) $projectKey = '%';
	if(!$taskKey) $taskKey = '%';
	$query = "select sum(pt.timetaken) as tm from procentris_timesheet as pt where pt.user_id like '".$u."' and pt.category like '".$categoryKey."' and pt.project like '".$projectKey."' and pt.tasks like '".$taskKey."' and pt.cdate = '".$date."'";
	$rs = mysql_query($query) or die("error");
	while($rec = mysql_fetch_array($rs)) {
		$timetaken = $rec['tm'];
	}
	return $timetaken;
}

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
?>
<?php
$topArray = array('A','B','C','D','E','F','G','H','I','J','K');
if($_GET['mydate']) {
		$time = strtotime($_GET['mydate']);
	} else {
		$time = time();
	}
	$arr = getDays($time);
  $fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
  $todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
  
  // creating spreadsheet
$workbook = new Spreadsheet_Excel_Writer();

$formatTop =& $workbook->addFormat(array('Size' => 14,
                                      'Align' => 'left',
                                      'Color' => 'black',
									  'FgColor' => 'white',
									  'Border' => 0));
$format =& $workbook->addFormat(array('Size' => 14,
                                      'Align' => 'left',
                                      'Color' => 'black',
									  'FgColor' => 'white',
									  'Border' => 1));
$format1 =& $workbook->addFormat(array('Size' => 11,
                                      'Align' => 'left',
                                      'Color' => 'black',
									  'FgColor' => 'white',
									  'Border' => 1));
$formatbold =& $workbook->addFormat(array('Size' => 11,
                                      'Align' => 'left',
                                      'Color' => 'black',
									  'FgColor' => 'white',
									  'Bold' => 1,
									  'Border' => 1));						
$formatboldcenter =& $workbook->addFormat(array('Size' => 11,
                                      'Align' => 'center',
                                      'Color' => 'black',
									  'FgColor' => 'white',
									  'Bold' => 1,
									  'Border' => 1));			
$formatborder =& $workbook->addFormat(array('Border' => 1));									 
									  
$worksheet =& $workbook->addWorksheet();
if ($totalRows_rsUser > 0) { // Show if recordset not empty 
	$row = 0; // first row
	// row 1
  $excel = "\r\n";
  	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatTop);
	}
	$row++;
	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatTop);
	}
  // row 2
  $excel .= "\t\t";
  $excel .= "Timesheet for ".$arr['monday']['mday']." ".date('S',$arr['monday'][0])." ".substr($arr['monday']['month'],0,3)." - ".$arr['sunday']['mday']." ".date('S',$arr['sunday'][0])." ".substr($arr['sunday']['month'],0,3)."\r\n\t\r\n";
  
	$worksheet->writeString($row, 2, "Timesheet for ".$arr['monday']['mday']." ".date('S',$arr['monday'][0])." ".substr($arr['monday']['month'],0,3)." - ".$arr['sunday']['mday']." ".date('S',$arr['sunday'][0])." ".substr($arr['sunday']['month'],0,3), $formatTop);
	$worksheet->mergeCells($row,2,$row,9);
	$row++;
	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatTop);
	}
  // row 4
  $row++;
do {
	
	// define User ID
		$user_id = $row_rsUser['user_id'];
	// End User ID

$category = getCategory($user_id,$fromdate,$todate);
if($category) {
	// add additional Row:
		for($x=0;$x<11;$x++) {
			$worksheet->writeString($row, $x, "", $formatTop);
		}
		$row++;
	// end additional Row
	foreach($arr as $key => $value) {
		$totalv[$key] = 0;
	}
	$colname_rsTimesheet = "-1";
	if (isset($user_id)) {
	  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
	}
	mysql_select_db($database_conn, $conn);
	$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
	$rsTimesheet = mysql_query($query_rsTimesheet, $conn) or die(mysql_error());
	$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
	$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);

	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatTop);
	}
	$excel .= $row_rsUser['name']."\r\n";
	$worksheet->writeString($row, 0, $row_rsUser['name'], $formatTop);
	$worksheet->mergeCells($row,0,$row,5);
  
  // row2
  $row++;
  for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatborder);
	}
  $excel .= "\t\tDate\t";
  $worksheet->writeString($row, 2, "Date", $formatbold);
  $tmp = 3;
  foreach($arr as $key => $value) { 
		$excel .= $arr[$key]['mday']."\t"; 
		
		$worksheet->writeNumber($row, $tmp, $arr[$key]['mday'], $formatboldcenter);
		$tmp++;
	} 
	$tmp = 0;
	$row++;
	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatborder);
	}
	$excel .= "\r\n";
	
	// row 3
	$excel .= "Clients\t"; 
	$excel .= "Projects\t";
	$excel .= "Tasks\t";
		
	$worksheet->writeString($row, 0, "Clients", $formatbold);
	$worksheet->writeString($row, 1, "Projects", $formatbold);
	$worksheet->writeString($row, 2, "Tasks", $formatbold);
	
	$tmp = 3;
	foreach($arr as $key => $value) { 
		//$excel .= ucfirst($key)."/".$arr[$key]['mday']." ".$arr[$key]['month']."\t"; 
		$excel .= substr($arr[$key]['weekday'],0,3)."\t";
		$worksheet->writeString($row, $tmp, substr($arr[$key]['weekday'],0,3), $formatboldcenter);
		$tmp++; 
	} 
	$excel .= "Total\r\n"; 
	$worksheet->writeString($row, $tmp, "Total", $formatbold);
	$tmp = 0;
	$row++;
	for($x=0;$x<11;$x++) {
		$worksheet->writeString($row, $x, "", $formatborder);
	}
	
	  foreach($category as $categoryKey=>$categoryValue) {
		$categorycnt[$categoryValue]=1;
		$project = getProject($user_id,$categoryKey,$fromdate,$todate);
		if($project) {
			foreach($project as $projectKey=>$projectValue) {
				$projectcnt[$projectValue]=1;
				$task = getTask($user_id,$categoryKey,$projectKey,$fromdate,$todate);
				if($task) {
					$j=0;
					foreach($task as $taskKey => $taskValue) {
						if($categorycnt[$categoryValue]==1) { 
							$excel .= $categoryValue;
							$worksheet->writeString($row, $tmp, $categoryValue, $format1); 
						} 
					$excel .= "\t";
					$tmp++;
					
					$categorycnt[$categoryValue]++;
					if($projectcnt[$projectValue]==1) { 
							$excel .= $projectValue;
							$worksheet->writeString($row, $tmp, $projectValue, $format1); 
					} 
					$excel .= "\t";
					$tmp++;
					
					$projectcnt[$projectValue]++;
					$excel .= $taskValue;
					$worksheet->writeString($row, $tmp, $taskValue, $format1);
					
					$excel .= "\t";					
					$tmp++;
			$i=0;
			$totalh[$j] = 0;
			foreach($arr as $key => $value) { 
				$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']); 
				$excel .= number_format($timetaken[$i],2);
				$val = number_format($timetaken[$i],2);
				if($val=="" || $val=="0.00") {
					$val = "";
				}
				$format1->setNumFormat('0.00');
				$worksheet->writeNumber($row, $tmp, $val, $format1);
				if($val=="" || $val=="0.00") {
					$worksheet->writeString($row, $tmp, "", $format1);
				}
				
				$excel .= "\t";				
				$tmp++;
				
				$totalv[$key] += $timetaken[$i]; 
				$totalh[$j] += $timetaken[$i]; 
				$i++; 
			} 
			$excel .= number_format($totalh[$j],2);
			$val = number_format($totalh[$j],2);
			if($val=="" || $val=="0.00") {
				$val = "0.00";
			}
			$format1->setNumFormat('0.00');
			$worksheet->writeNumber($row, $tmp, $val, $format1);
			//$worksheet->writeFormula($row, $tmp, "=SUM(D".($row+1).":J".($row+1).")",$format1);
			$excel .= "\r\n";		
			$tmp=0;
			$row++;
			for($x=0;$x<11;$x++) {
				$worksheet->writeString($row, $x, "", $formatborder);
			}
			$j++; 
				 } // for task ends 
			} // if task ends 
		} // foreach project ends
	} // if project ends 
 } // foreach $category ends
 
 $excel .= "\t"; 
 $excel .= "\t"; 
 $excel .= "Total:\t";
  $worksheet->writeString($row, 2, "Total:", $format1);
  $tmp = 3;
  $grandtotal = 0;
 foreach($arr as $key => $value) {  
	$grandtotal += $totalv[$key]; 
	$excel .= number_format($totalv[$key],2);
	$val = number_format($totalv[$key],2);
	if($val=="" || $val=="0.00") {
		$val = "0.00";
	}
	$format1->setNumFormat('0.00');
	$worksheet->writeNumber($row, $tmp, $val, $format1);
	$excel .= "\t";
	$tmp++;
	} 
	$excel .= $grandtotal;
	$grandtotal = number_format($grandtotal,2);
	$format1->setNumFormat('0.00');
	$worksheet->writeNumber($row, $tmp, $grandtotal, $format1);
	
	$worksheet->writeNumber($row, $tmp+1, $grandtotal, $format1);
	//$worksheet->writeFormula($row, $tmp, "=SUM(D".($row+1).":J".($row+1).")",$format1);
	$excel .= "\r\n";
	
	$row++;
 		} // if cateogry ends
	} while ($row_rsUser = mysql_fetch_assoc($rsUser));
} // Show if recordset not empty

	$filename = $user_id."-".date("Y-m-d",$time).".xls";

$workbook->send($filename);
$workbook->close();

mysql_free_result($rsTimesheet);

mysql_free_result($rsUser);
?>?>