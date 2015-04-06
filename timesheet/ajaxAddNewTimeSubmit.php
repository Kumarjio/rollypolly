<?php require_once('../Connections/conn.php'); ?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$arr = explode("-",$_POST['cdate']);
	$_POST['cday'] = $arr[2];
	$_POST['cmonth'] = $arr[1];
	$_POST['cyear'] = $arr[0];
	if(is_numeric($_POST['timetaken'])) {
		$value = number_format($_POST['timetaken'],2);
		if($value=="0.00") {
			$_POST["MM_insert"] = "";
			$error = "<p class=error>You cannot add 0 time.</p>";
		}
		if(preg_match("/(.*)\.([0-9]{0,2})$/",$value,$array)) {
			if($array[2]=="25" || $array[2]=="50" || $array[2]=="75" || $array[2]=="00" || $array[2]=="" || $array[2]=="5" || $array[2]=="0") {			
				//echo $array[0];
				//echo " allowed";
				$error = 'Time Added Successfully.';
			} else {
				//echo $array[0];
				$_POST["MM_insert"] = "";
				$error = "<p class=error>Please Correct the entry. Entry should be like ".$array[1].", ".$array[1].".00, ".$array[1].".25, ".$array[1].".50, ".$array[1].".75 and not like ".$array[0]."</p>";
			}	
		} else {
			//echo $_POST['timetaken'];echo " allowed";
		}
	} else {
		$_POST["MM_insert"] = "";
		$error = "<p class=error>Time Taken should be numeric.</p>";
	}
}
?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO procentris_timesheet (user_id, category, project, tasks, timetaken, cdate, cday, cmonth, cyear) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['category'], "int"),
                       GetSQLValueString($_POST['project'], "int"),
                       GetSQLValueString($_POST['tasks'], "int"),
                       GetSQLValueString($_POST['timetaken'], "double"),
                       GetSQLValueString($_POST['cdate'], "date"),
                       GetSQLValueString($_POST['cday'], "int"),
                       GetSQLValueString($_POST['cmonth'], "int"),
                       GetSQLValueString($_POST['cyear'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}
echo $error;
?>
