<?php
function calculateTime($var) {
	$check = array("C.L","Comp Off","OFF","S.L","P.L");
	$var = trim($var);
	if($var && !in_array($var,$check)) {
		$var1 = explode("-",$var);
		$one = substr($var1[0],0,2);
		$two = substr($var1[0],2,2);
		$three = substr($var1[1],0,2);
		$four = substr($var1[1],2,2);
		$str1 = strtotime($one.":".$two);
		$str2 = strtotime($three.":".$four);
		$str = $str2-$str1;		
		$hour = date("H",$str);
		$minute = date("i",$str);
		settype($minute, "integer"); 
		if($minute >=0 && $minute <=7) {
			//echo "$minute is between 0 and 7";
			$newminute = 0;
		}
		if($minute >=8 && $minute <=22) {
			//echo "$minute is between 8 and 22";
			$newminute = 25;
		}
		if($minute >=23 && $minute <=37) {
			//echo "$minute is between 23 and 37";
			$newminute = 50;
		}
		if($minute >=38 && $minute <=52) {
			//echo "$minute is between 38 and 52";
			$newminute = 75;
		}
		if($minute >=53 && $minute <=59) {
			//echo "$minute is between 53 and 59";
			$newminute = 0;
			$hour = $hour+1;
		}
		$arr['hour'] = $hour;
		$arr['minute'] = $newminute;
		$arr['time'] = $hour.".".$newminute;
	} else {
		$arr['time'] = $var;
	}
	return $arr;
}
if($_FILES['userfile']['name']) {
	$ext = strrchr($_FILES['userfile']['name'],".");
	if($ext==".csv") {
		move_uploaded_file($_FILES['userfile']['tmp_name'],"files/".$_FILES['userfile']['name']);
		$line = file("files/".$_FILES['userfile']['name']);
		foreach($line as $key => $value) {
			$lines[$key] = $value;
			$var = explode(",",$value);
			foreach($var as $key1 => $value1) {
				$vars[$key][$key1] = $value1;
			}
		}
		foreach($vars[2] as $key=>$value) {
			$monthyear = $vars[2][1];
			if($key>=2) {
				if(trim($vars[2][$key])!="" && is_numeric(trim($vars[2][$key]))) {
					$item[$value] = $key;
				}
			}
		}
		for($i=4;$i<count($vars);$i++) {
			if(trim($vars[$i][1])!="") {
				$name[$i] = $vars[$i][1];
				foreach($item as $key=>$value) {
					//echo $vars[3][$value]." = ".$vars[$i][$value]." (".$key.")";
					$names[$name[$i]][$key]['oldtime'] = $vars[$i][$value]; 
					$arr = calculateTime($vars[$i][$value]);
					$names[$name[$i]][$key]['newtime'] = number_format($arr['time'],2); 
				}
				//echo "<hr>";
			}
		}
		$excel = "";
		foreach($names as $key => $value) {
			$excel .= "Name\t";
			foreach($value as $key1 => $value1) {
				$excel .= $key1."\t\t";
			}
			$excel .= "\r\n";
			break;
		}
		foreach($names as $key => $value) {
			$excel .= $key."\t";
			foreach($value as $key1 => $value1) {
				foreach($value1 as $key2 => $value2) {
					$excel .= $value2."\t";
				}
			}
			$excel .= "\r\n";
		}
		
		$filename = $monthyear.".xls";
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename");
		header("Pragma: no-cache");
		header("Expires: 0");
		print "$excel"; 
	} else {
		$error = 'File Should be in csv format. You have upload in '.$ext.' format.';
	}
}
?>