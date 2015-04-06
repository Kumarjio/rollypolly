<?php
require_once('../Connections/conn.php');
if(!function_exists('getCategory')) { 
	function getCategory($u, $fromdate, $todate) {
		if(!$fromdate) $fromdate="1900-01-01";
		if(!$todate) $todate="3000-12-31";
		$query = "select DISTINCT pt.category, pl.list from procentris_timesheet as pt, procentris_list as pl where pt.user_id like '".$u."' and pt.cdate between '".$fromdate."' and '".$todate."' and pt.category = pl.list_id";
		$rs = mysql_query($query) or die("error");
		while($rec = mysql_fetch_array($rs)) {
			$categories[$rec['category']] = $rec['list'];
		}
		return $categories;
	}
}
if(!function_exists('getProject')) { 
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
}
if(!function_exists('getTask')) { 
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
}

if(!function_exists('getAllCategory')) { 
	function getAllCategory($u) {
		$query = "select pl.* from procentris_list as pl, procentris_user_client as puc where puc.list_id = pl.list_id and puc.user_id = '".$u."' and pl.deleted = 0";
		$rs = mysql_query($query) or die("error".mysql_error());
		if(mysql_num_rows($rs)) {
			while($rec = mysql_fetch_array($rs)) {
				$categories[$rec['list_id']] = $rec['list'];
			}
			return $categories;
		}
	}
}
if(!function_exists('getAllProject')) { 
	function getAllProject($u, $categoryKey) {
		if(!$categoryKey) $categoryKey = '%';
		$query = "select pl.* from procentris_list as pl where pl.user_id like '".$u."' and pl.pid like '".$categoryKey."' and pl.deleted = 0";
		$rs = mysql_query($query) or die("error");
		if(mysql_num_rows($rs)) {
			while($rec = mysql_fetch_array($rs)) {
				$projects[$rec['list_id']] = $rec['list'];
			}
			return $projects;
		}
	}
}
if(!function_exists('getAllTask')) { 
	function getAllTask($u,$categoryKey,$projectKey) {
		if(!$categoryKey) $categoryKey = '%';
		if(!$projectKey) $projectKey = '%';
		$query = "select pl.* from procentris_list as pl where pl.user_id like '".$u."' and pl.pid like '".$projectKey."' and pl.deleted = 0";
		$rs = mysql_query($query) or die("error");
		if(mysql_num_rows($rs)) {
			while($rec = mysql_fetch_array($rs)) {
				$tasks[$rec['list_id']] = $rec['list'];
			}
			return $tasks;
		} 
	}
}

if(!function_exists('getTime')) { 
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
}

if(!function_exists('getDays')) { 
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
}
if(!function_exists('getWorkingHours')) { 
	function getWorkingHours($time,$user_id) {
		$colname_rsTimesheet = "-1";
		if (isset($user_id)) {
		  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
		}
		$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
		$rsTimesheet = mysql_query($query_rsTimesheet) or die(mysql_error());
		$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
		$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);
		
		$arr = getDays($time);
		$fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
		$todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
		
		$category = getCategory($user_id,$fromdate,$todate);
		if($category) {
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
								$categorycnt[$categoryValue]++;					
								$projectcnt[$projectValue]++;
								$i=0;
								$totalh[$j] = 0;
								foreach($arr as $key => $value) { 
									$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']); 				
									$totalv[$key] += $timetaken[$i]; 
									$totalh[$j] += $timetaken[$i]; 
									$i++; 
								} 
								$j++; 
							}
						}
					}
				}
			}
			foreach($arr as $key => $value) {  
				$grandtotal += $totalv[$key];
			}
			return $totalv;
		}
	}
}
/*
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getWorkingHours($time,2);
print_r($returnArray);
*/

if(!function_exists('getTodaysTime')) { 
	function getTodaysTime($time,$user_id) {
		$colname_rsTimesheet = "-1";
		if (isset($user_id)) {
		  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
		}
		$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
		$rsTimesheet = mysql_query($query_rsTimesheet) or die(mysql_error());
		$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
		$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);
		
		
		$today = getdate($time);
		$chkdate = $today['weekday'];
		$chkdate2 = strtolower($chkdate);
		
		$arr = getDays($time);
		//$fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
		//$todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
		$fromdate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		$todate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		
		$category = getCategory($user_id,$fromdate,$todate);
		if($category) {
			$result = '<table border=1 cellspacing=0 cellpadding=2>';
			$result .= '<tr><td colspan=4 align=center>'.$today['mday'].' '.$today['month'].', '.$today['year'].'</td></tr>';
			$result .= '<tr><td align=left><b>Client</b></td><td align=left><b>Project</b></td><td align=left><b>Task</b></td><td align=left><b>Time Taken</b></td></tr>';
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
									$result .= '<tr>
													<td align=left>'.$categoryValue.'</td>'; 
								} else {
									$result .= '<td align=left>&nbsp;</td>'; 
								}		
								$categorycnt[$categoryValue]++;	
								if($projectcnt[$projectValue]==1) { 
									$result .= '<td align=left>'.$projectValue.'</td>'; 
								} else {
									$result .= '<td align=left>&nbsp;</td>'; 
								}		
								$projectcnt[$projectValue]++;
								$result .= '<td align=left>'.$taskValue.'</td>';
								$i=0;
								$totalh[$j] = 0;
								foreach($arr as $key => $value) {
									if($arr[$key]['weekday']!=$chkdate) { continue; }
									$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']);
									if(number_format($timetaken[$i],2)=="0.00") {
										$taketime = "&nbsp;";
									} else {
										$taketime = number_format($timetaken[$i],2);
									}
									$result .= '<td align=right>'.$taketime.'</td>'; 				
									$totalv[$key] += $timetaken[$i]; 
									$totalh[$j] += $timetaken[$i];
									$i++; 
								} 
								$j++; 
								$result .= '</tr>';
							}
						}
					}
				}
			}
			foreach($arr as $key => $value) {  
				$grandtotal += $totalv[$key];
			}
			$result .= '<tr><td align=left>&nbsp;</td><td align=left>&nbsp;</td><td align=right><b>Total:</b></td><td align=right>'.number_format($grandtotal,2).'</td>';
			$result .= '</table>';
			return $result;
		}
	}
}
/*
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getTodaysTime($time,2);
*/

if(!function_exists('getWeeksTime')) { 
	function getWeeksTime($time,$user_id) {
		$colname_rsTimesheet = "-1";
		if (isset($user_id)) {
		  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
		}
		$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
		$rsTimesheet = mysql_query($query_rsTimesheet) or die(mysql_error());
		$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
		$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);
		
		
		$today = getdate($time);
		$chkdate = $today['weekday'];
		$chkdate2 = strtolower($chkdate);
		
		$arr = getDays($time);
		$fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
		$todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
		//$fromdate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		//$todate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		
		$category = getCategory($user_id,$fromdate,$todate);
		if($category) {
			$result = '<table border=1 cellspacing=0 cellpadding=2>';
			$result .= '<tr><td colspan=10 align=center>'.$today['mday'].' '.$today['month'].', '.$today['year'].'</td></tr>';
			$result .= '<tr><td align=left><b>Client</b></td><td align=left><b>Project</b></td><td align=left><b>Task</b></td>';
			foreach($arr as $key => $value) {
				$result .= '<td align=left><b>'.substr(ucfirst($key),0,3).'</b></td>';
			}
			$result .= '</tr>';
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
									$result .= '<tr>
													<td align=left>'.$categoryValue.'</td>'; 
								} else {
									$result .= '<td align=left>&nbsp;</td>'; 
								}		
								$categorycnt[$categoryValue]++;	
								if($projectcnt[$projectValue]==1) { 
									$result .= '<td align=left>'.$projectValue.'</td>'; 
								} else {
									$result .= '<td align=left>&nbsp;</td>'; 
								}		
								$projectcnt[$projectValue]++;
								$result .= '<td align=left>'.$taskValue.'</td>';
								$i=0;
								$totalh[$j] = 0;
								foreach($arr as $key => $value) {
									$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']);
									if(number_format($timetaken[$i],2)=="0.00") {
										$taketime = "&nbsp;";
									} else {
										$taketime = number_format($timetaken[$i],2);
									}
									$result .= '<td align=right>'.$taketime.'</td>'; 				
									$totalv[$key] += $timetaken[$i]; 
									$totalh[$j] += $timetaken[$i];
									$i++; 
								} 
								$j++; 
								$result .= '</tr>';
							}
						}
					}
				}
			}
			$result .= '<tr><td align=left>&nbsp;</td><td align=left>&nbsp;</td><td align=right><b>Total:</b></td>';
			foreach($arr as $key => $value) {  
				$grandtotal += $totalv[$key];
				$result .= '<td align=right>'.number_format($totalv[$key],2).'</td>';
			}
			$result .= '</tr>';
			$result .= '<tr><td colspan=10 align=center>TOTAL HOURS WORKED THIS WEEK: <b>'.number_format($grandtotal,2).'</b></td></tr>';
			$result .= '</table>';
			return $result;
		}
	}
}
if(!function_exists('wireframe')) {
	function wireframe($today,$arr,$returnArray,$content) {
		$result = "";
		$result .= '<table border=1 cellspacing=0 cellpadding=2>';
			$result .= '<tr><td colspan=8 align=center>'.$today['mday'].' '.$today['month'].', '.$today['year'].'</td></tr>';
			$result .= '<tr><td align=left><b>Task</b></td>';
			foreach($arr as $key => $value) {
				$result .= '<td align=left><b>'.substr(ucfirst($key),0,3).'</b></td>';
			}
			$result .= '</tr>';
			$result .= '<tr>
							<td>&nbsp;</td>
							';
			foreach($arr as $key => $value) {
				$result .= '<td align="center"><b>'.$arr[$key]['mday'].'</b></td>
				';
			}
			$result .= '
					  </tr>';
			$result .= $content;
			$result .= '</table>';
			return $result;
	}
}
if(!function_exists('getTimeSheetNew')) { 
	function getTimeSheetNew($time,$user_id) {		
		static $did1 = 0; 
	
		$colname_rsTimesheet = "-1";
		if (isset($user_id)) {
		  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
		}
		$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
		$rsTimesheet = mysql_query($query_rsTimesheet) or die(mysql_error());
		$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
		$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);
		
		$returnArray = getWorkingHours($time,$user_id);
		$today = getdate($time);
		$chkdate = $today['weekday'];
		$chkdate2 = strtolower($chkdate);
		
		$arr = getDays($time);
		$fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
		$todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
		//$fromdate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		//$todate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		
		$category = getAllCategory($user_id);
		$result = '<div id="divTimesheetNew"><br><br>';
		//$result .= "<a href=\"javascript:doAjax('ajaxAddNew.php','GET','user_id=".$user_id."&mydate=".date('Y-m-d',$time)."','','newCategory');\">ADD NEW TASK</a><br>";
		//$result .= "<div id='newCategory'></div>";
		if($category) {
			$result .= '<br>';
			$result .= '<table border=1 cellspacing=0 cellpadding=2>';
			$result .= '<tr><td colspan=10 align=center>'.$today['mday'].' '.$today['month'].', '.$today['year'].'</td></tr>';
			$result .= '<tr><td align=left><b>Client</b></td><td align=left><b>Project</b></td><td align=left><b>Task</b></td>';
			foreach($arr as $key => $value) {
				$result .= '<td align=left><b>'.substr(ucfirst($key),0,3).'</b></td>';
			}
			$result .= '</tr>';
			$result .= '<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							';
			foreach($arr as $key => $value) {
				$result .= '<td align="center"><b>'.$arr[$key]['mday'].'</b></td>
				';
			}
			$result .= '
					  </tr>';
			$result .= '<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						';
			foreach($arr as $key => $value) {
				$result .= '<td align="right"><b>'.$returnArray[$key].'</b> h</td>
				';
			}
			$result .= '
					  </tr>';
			foreach($category as $categoryKey=>$categoryValue) {
				$categorycnt[$categoryValue]=1;
				if($categorycnt[$categoryValue]==1) { 
					$result .= '<tr><td align=left><font color=blue><b>'.$categoryValue.'</b></font>';
					$did1++;
					$did = "div".$did1;
					$result .= '<form name="'.$did.'" action="" method="post">Add New Project: <input type="text" name="list" value="" size=8><input type="hidden" value="'.$categoryKey.'" name="pid"><input type="hidden" value="'.$user_id.'" name="user_id"><input type="hidden" value="2" name="level"><input type="hidden" value="Project" name="list_type"><input type="submit" name="Go" value="Go"></form>';
					$result .= '</td>';
					$result .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
				} 
				$project = getAllProject($user_id,$categoryKey);
				if($project) {					
					foreach($project as $projectKey=>$projectValue) {
						$projectcnt[$projectValue]=1;
						if($projectcnt[$projectValue]==1) {
							$result .= '<tr><td>&nbsp;</td>';  
							$result .= '<td align=left><font color=red><b>'.$projectValue.'</b></font>';
							$did1++;
							$did = "div".$did1;
							$result .= '<form name="'.$did.'" action="" method="post">Add New Task: <input type="text" name="list" value="" size=8><input type="hidden" value="'.$projectKey.'" name="pid"><input type="hidden" value="'.$user_id.'" name="user_id"><input type="hidden" value="3" name="level"><input type="hidden" value="Task" name="list_type"><input type="submit" name="Go" value="Go"></form>';
							$result .= '</td>'; 
							$result .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
						} 
						$task = getAllTask($user_id,$categoryKey,$projectKey);
						if($task) {
							$j=0;
							foreach($task as $taskKey => $taskValue) {								
								$categorycnt[$categoryValue]++;									
								$projectcnt[$projectValue]++;
								$result .= '<td>&nbsp;</td><td>&nbsp;</td><td align=left><b>'.$taskValue.'</b></td>';
								$i=0;
								$totalh[$j] = 0;
								foreach($arr as $key => $value) {
									$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']);
									if(number_format($timetaken[$i],2)=="0.00") {
										$taketime = "&nbsp;";
									} else {
										$taketime = number_format($timetaken[$i],2);
									} // end of if timetaken
									$did1++;
									$did = "div".$did1;
									$result .= "<td align=right><div id='".$did."'>".$taketime."<a href=\"javascript:doAjax('ajax_input.php','GET','user_id=".$user_id."&category=".$categoryKey."&project=".$projectKey."&tasks=".$taskKey."&cdate=".date('Y-m-d',$value[0])."&cday=".date('d',$value[0])."&cmonth=".date('m',$value[0])."&cyear=".date('Y',$value[0])."&timetaken=".number_format($timetaken[$i],2)."&did=".$did."&mydate=".date('Y-m-d',$time)."','','".$did."');\"><img border=0 height=5 width=5 align=right src=images/is.jpg></a></div></td>"; 				
									$totalv[$key] += $timetaken[$i]; 
									$totalh[$j] += $timetaken[$i];
									$i++; 
								} // end of foreach $arr as $key=>$value
								$j++; 
								$result .= '</tr>';
							} // end of foreach task
						} // end of if task
					}
				}
			}
			//$result .= '<tr><td align=left>&nbsp;</td><td align=left>&nbsp;</td><td align=right><font color=red><b>Total:</b></font></td>';
			//foreach($arr as $key => $value) {  
				//$grandtotal += $totalv[$key];
				//$result .= '<td align=right><font color=green><b>'.number_format($totalv[$key],2).'</b></font></td>';
			//}
			//$result .= '<td align=right><font color=blue><b>'.number_format($grandtotal,2).'</font></b></td>';
			//$result .= '</tr>';
			//$result .= '<tr><td colspan=11 align=center>TOTAL HOURS WORKED THIS WEEK: <b>'.number_format($grandtotal,2).'</b></td></tr>';
			$result .= '</table>';
			
		}
			$result .= "</div>";
			$result .= "<div id='tempDiv'></div>";
			return $result;
	}
}

if(!function_exists('getTimeSheetNew2')) { 
	function getTimeSheetNew2($time,$user_id) {		
		static $did1 = 0; 
	
		$colname_rsTimesheet = "-1";
		if (isset($user_id)) {
		  $colname_rsTimesheet = (get_magic_quotes_gpc()) ? $user_id : addslashes($user_id);
		}
		$query_rsTimesheet = sprintf("SELECT * FROM procentris_timesheet WHERE user_id = %s", $colname_rsTimesheet);
		$rsTimesheet = mysql_query($query_rsTimesheet) or die(mysql_error());
		$row_rsTimesheet = mysql_fetch_assoc($rsTimesheet);
		$totalRows_rsTimesheet = mysql_num_rows($rsTimesheet);
		
		$returnArray = getWorkingHours($time,$user_id);
		$today = getdate($time);
		$chkdate = $today['weekday'];
		$chkdate2 = strtolower($chkdate);
		
		$arr = getDays($time);
		$fromdate = $arr['monday']['year']."-".$arr['monday']['mon']."-".$arr['monday']['mday'];
		$todate = $arr['sunday']['year']."-".$arr['sunday']['mon']."-".$arr['sunday']['mday'];
		//$fromdate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		//$todate = $arr[$chkdate2]['year']."-".$arr[$chkdate2]['mon']."-".$arr[$chkdate2]['mday'];
		$category = array();
		$category = getAllCategory($user_id);
		$result = '<div id="divTimesheetNew"><br><br>';
		//$result .= "<a href=\"javascript:doAjax('ajaxAddNew.php','GET','user_id=".$user_id."&mydate=".date('Y-m-d',$time)."','','newCategory');\">ADD NEW TASK</a><br>";
		//$result .= "<div id='newCategory'></div>";
		if($category) {
			$result .= '<br>';
			foreach($category as $categoryKey=>$categoryValue) {
				$categorycnt[$categoryValue]=1;
				if($categorycnt[$categoryValue]==1) { 					
					$result .= "<a href=\"javascript:toggleLayer2('c".$categoryKey."');\"><img border=0 align=left height=11 width=13 src=images/is.jpg title='Show Projects'></a> ";
					$result .= '<font color=blue><b>'.$categoryValue.'</b></font>';
					$did1++;
					$did = "div".$did1;
					$result .= '<form name="'.$did.'" action="" method="post">Add New Project: <input type="text" name="list" value="" size=8><input type="hidden" value="'.$categoryKey.'" name="pid"><input type="hidden" value="'.$user_id.'" name="user_id"><input type="hidden" value="2" name="level"><input type="hidden" value="Project" name="list_type"><input type="hidden" value="c'.$categoryKey.'" name="showCategory"><input type="submit" name="Go" value="Go"></form>';
				} 
				$project = getAllProject($user_id,$categoryKey);				
				if($project) {		
					$result .= '<div id="c'.$categoryKey.'" style="display:none;">';			
					foreach($project as $projectKey=>$projectValue) {
						$projectcnt[$projectValue]=1;
						if($projectcnt[$projectValue]==1) {
							$did1++;
							$did = "div".$did1;
							$result .= '<blockquote>';
							$result .= "<a href=\"javascript:toggleLayer2('p".$projectKey."');\"><img border=0 align=left height=11 width=13 src=images/is.jpg title='Show Tasks'></a> ";
							$result .= '<font color=red><b>'.$projectValue.'</b></font>';
							$did1++;
							$did = "div".$did1;
							$result .= '<form name="'.$did.'" action="" method="post">Add New Task: <input type="text" name="list" value="" size=8><input type="hidden" value="'.$projectKey.'" name="pid"><input type="hidden" value="'.$user_id.'" name="user_id"><input type="hidden" value="3" name="level"><input type="hidden" value="Task" name="list_type"><input type="hidden" value="p'.$projectKey.'" name="showProject"><input type="hidden" value="c'.$categoryKey.'" name="showCategory"><input type="submit" name="Go" value="Go"></form>';
							$result .= '</blockquote>'; 
						} 
						$result .= "<div id='p".$projectKey."' style='display:none;'>";
						// start of code
						$task = getAllTask($user_id,$categoryKey,$projectKey);
						if($task) {
							$j=0;
							$result1 = "";
							foreach($task as $taskKey => $taskValue) {								
								$categorycnt[$categoryValue]++;									
								$projectcnt[$projectValue]++;
								$result1 .= '<td align=left><b>'.$taskValue.'</b></td>';
								$i=0;
								$totalh[$j] = 0;
								foreach($arr as $key => $value) {
									$timetaken[$i] = getTime($user_id,$categoryKey,$projectKey,$taskKey,$arr[$key]['year']."-".$arr[$key]['mon']."-".$arr[$key]['mday']);
									if(number_format($timetaken[$i],2)=="0.00") {
										$taketime = "&nbsp;";
									} else {
										$taketime = number_format($timetaken[$i],2);
									} // end of if timetaken
									$did1++;
									//$cid = "divc".$categoryKey."p".$projectKey."t".$taskKey."a".$did1;
									$did = "div".$did1;
									$result1 .= "<td align=right><div id='".$did."'>".$taketime."<a href=\"javascript:doAjax('ajax_input.php','GET','user_id=".$user_id."&category=".$categoryKey."&project=".$projectKey."&tasks=".$taskKey."&cdate=".date('Y-m-d',$value[0])."&cday=".date('d',$value[0])."&cmonth=".date('m',$value[0])."&cyear=".date('Y',$value[0])."&timetaken=".number_format($timetaken[$i],2)."&did=".$did."&mydate=".date('Y-m-d',$time)."','','".$did."');\"><img border=0 height=5 width=5 align=right src=images/is.jpg></a></div></td>"; 				
									$totalv[$key] += $timetaken[$i]; 
									$totalh[$j] += $timetaken[$i];
									$i++; 
								} // end of foreach $arr as $key=>$value
								$j++; 
								$result1 .= '</tr>';
							} // end of foreach task
							
						// end of code
						$tmp = "";
						$tmp = wireframe($today,$arr,$returnArray,$result1);
						$result .= "<blockquote><blockquote>";
						$result .= $tmp;
						$result .= "</blockquote></blockquote>";
						} // end of if task						
						$result .= "</div>";
					}
					$result .= '</div>';
				}
			}
		}
			$result .= "</div>";
			$result .= "<div id='tempDiv'></div>";
			return $result;
	}
}
?>