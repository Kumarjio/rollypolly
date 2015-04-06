<?php require_once('../Connections/conn.php'); ?>
<?php
if(is_numeric($_GET['timetaken'])) {
	$value = number_format($_GET['timetaken'],2);
	if($value=="0.00") {
		//$error = "You cannot add 0 time.";
	}
	if(preg_match("/(.*)\.([0-9]{0,2})$/",$value,$array)) {
		if($array[2]=="25" || $array[2]=="50" || $array[2]=="75" || $array[2]=="00" || $array[2]=="" || $array[2]=="5" || $array[2]=="0") {			
			//echo $array[0];
			//echo " allowed";
		} else {
			//echo $array[0];
			$error = "Please Correct the entry. Entry should be like ".$array[1].", ".$array[1].".00, ".$array[1].".25, ".$array[1].".50, ".$array[1].".75 and not like ".$array[0]."";
		}	
	} else {
		//echo $_POST['timetaken'];echo " allowed";
		$error = "Cannot match the number. Please Try Again.";
	}
} else {	
	$error = "Time Taken should be numeric.";
}
if($error) {
	echo number_format($_GET['timetaken'],2)."<a href=\"javascript:doAjax('ajax_input.php','GET','user_id=".$_GET['user_id']."&category=".$_GET['category']."&project=".$_GET['project']."&tasks=".$_GET['tasks']."&cdate=".$_GET['cdate']."&cday=".$_GET['cday']."&cmonth=".$_GET['cmonth']."&cyear=".$_GET['cyear']."&timetaken=".number_format($_GET['timetaken'],2)."&did=".$_GET['did']."&mydate=".$_GET['mydate']."','','".$_GET['did']."');\"><img border=0 height=5 width=5 align=right src=images/is.jpg></a>";
	echo "<br>";
	echo $error;
} else {
	$query = "select * from procentris_timesheet where user_id = '".$_GET['user_id']."' and category = '".$_GET['category']."' and project = '".$_GET['project']."' and tasks = '".$_GET['tasks']."' and cdate = '".$_GET['cdate']."' and cmonth = '".$_GET['cmonth']."' and cday = '".$_GET['cday']."' and cyear = '".$_GET['cyear']."'";
	$rs = mysql_query($query) or die('err');
	
	if(mysql_num_rows($rs)==0) {
		// insert
		if($value!="0.00") {
			$query = "insert into procentris_timesheet set user_id = '".$_GET['user_id']."', category = '".$_GET['category']."', project = '".$_GET['project']."', tasks = '".$_GET['tasks']."', cdate = '".$_GET['cdate']."', cmonth = '".$_GET['cmonth']."', cday = '".$_GET['cday']."', cyear = '".$_GET['cyear']."', timetaken = '".$_GET['timetaken']."'";
			$rs1 = mysql_query($query) or die('err');
		}
	} else {
		// update
		$rec = mysql_fetch_array($rs);
		$query = "update procentris_timesheet set category = '".$_GET['category']."', project = '".$_GET['project']."', tasks = '".$_GET['tasks']."', cdate = '".$_GET['cdate']."', cmonth = '".$_GET['cmonth']."', cday = '".$_GET['cday']."', cyear = '".$_GET['cyear']."', timetaken = '".$_GET['timetaken']."' where timesheet_id = '".$rec['timesheet_id']."'";
		$rs1 = mysql_query($query) or die('err');
		if($value=="0.00") {
			$query = "delete from procentris_timesheet where timesheet_id = '".$rec['timesheet_id']."'";
			$rs1 = mysql_query($query) or die('err');
		}
	}
	echo number_format($_GET['timetaken'],2)."<a href=\"javascript:doAjax('ajax_input.php','GET','user_id=".$_GET['user_id']."&category=".$_GET['category']."&project=".$_GET['project']."&tasks=".$_GET['tasks']."&cdate=".$_GET['cdate']."&cday=".$_GET['cday']."&cmonth=".$_GET['cmonth']."&cyear=".$_GET['cyear']."&timetaken=".number_format($_GET['timetaken'],2)."&did=".$_GET['did']."&mydate=".$_GET['mydate']."','','".$_GET['did']."');\"><img border=0 height=5 width=5 align=right src=images/is.jpg></a>";
}
?>