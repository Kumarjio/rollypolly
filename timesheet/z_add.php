<?php require_once('../Connections/conn.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../users/login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$colname_rsUser = "0";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUser = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsUser = sprintf("SELECT * FROM procentris_users WHERE username = '%s'", $colname_rsUser);
$rsUser = mysql_query($query_rsUser, $conn) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>
<?php
$_SESSION['MM_UserId'] = $row_rsUser['user_id'];
function get_keyword_category($id) {
	$rs = mysql_query("select * from procentris_list where list_id = '".$id."' and deleted = 0") or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());
	$rec = mysql_fetch_array($rs);
	return $rec;
}
function catlink_categories($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."' and user_id = '".$_SESSION['MM_UserId']."' and deleted = 0";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = '<a href="'.$_SERVER['PHP_SELF'].'?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
		array_unshift($q,$category);
		catlink_categories($pid,$q);	
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
function catlink_categories1($cat_id,$q) {
	global $displayLink, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."' and deleted = 0";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = '<a href="'.$_SERVER['PHP_SELF'].'?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
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
	$query = "select * from procentris_list where list_id = '".$cat_id."' and deleted = 0";
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
		$category = '<a href="'.$_SERVER['PHP_SELF'].'?s='.$s.'&list_id='.$cat_id.'&pid='.$cat_id.'">'.$rec['list'].'</a>';
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
function catlink_categories2($cat_id,$q) {
	global $displayLink2, $s;
	$query = "select * from procentris_list where list_id = '".$cat_id."' and user_id = '".$_SESSION['MM_UserId']."' and deleted = 0";
	$rs = mysql_query($query) or die('Error in line '.__LINE__.' of File '.__FILE__.': '.mysql_error());;
	$rec = mysql_fetch_array($rs);
	if(mysql_num_rows($rs)>0) {
		$cat_id = $rec['list_id'];
		$pid = $rec['pid'];
		$category = $rec['list'];
		array_unshift($q,$category);
		catlink_categories2($pid,$q);	
	} else {
		foreach($q as $value) {
			$displayLink2 .= $value.' -> ';
		}
		$displayLink2 = substr($displayLink2,0,-4);
	}
}
function countcat_categories($cat_id) {
	$q = "select count(list_id) as cnt from procentris_list where pid = '".$cat_id."' and user_id = '".$_SESSION['MM_UserId']."' and deleted = 0";
	$r = mysql_query($q) or die("eq");
	$rec = mysql_fetch_array($r);
	return $rec['cnt'];
}
// chilld categories end
if($_POST['list']) {
	if($_POST['list']) {
		$query = "insert into procentris_list(list, pid, level, list_type, user_id) values('".addslashes(stripslashes($_POST['list']))."', '".$_POST['pid']."', '".$_POST['level']."', '".$_POST['list_type']."', '".$_POST['user_id']."')";
		mysql_query($query) or die("could not insert");
		$message = 'New Category Added Successfully';
		$success = 1;
	} else {
		// category name not selected, so give the error.
		$message = 'Please enter Category Name.';
	}
}
$pid = $_REQUEST['pid'];
if(!$pid) $pid = 0;
$list_id = $_REQUEST['list_id'];
$parent = get_keyword_category($pid);
$parent_name = $parent['list'];

$rs = mysql_query("select * from procentris_list where list_id = '".$pid."' and user_id = '".$_SESSION['MM_UserId']."' and deleted = 0") or die("Error in line no ".__LINE__." of File ".__FILE__." with error: ".mysql_error());
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

// get child categories
$query = "select * from procentris_list where pid = '".$pid."' and user_id = '".$_SESSION['MM_UserId']."' and deleted = 0 order by list_id desc";
$rs = mysql_query($query);
while($rec = mysql_fetch_array($rs)) {
	$catxyz[] = '<a href="'.$_SERVER['PHP_SELF'].'?pid='.$rec['list_id'].'&list_id='.$rec['list_id'].'">'.$rec['list'].'</a> ('.countcat_categories($rec['list_id']).') ';
	$child[$rec['list_id']] = $rec['list'];
}
if($catxyz) {
	$child_categories = implode(', ',$catxyz);
}



$page_title = "Category Management: ".$displayLink2;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/procentris.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Add New <?php echo $list_type; ?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function YY_checkform() { //v4.66
//copyright (c)1998,2002 Yaromat.com
  var args = YY_checkform.arguments; var myDot=true; var myV=''; var myErr='';var addErr=false;var myReq;
  for (var i=1; i<args.length;i=i+4){
    if (args[i+1].charAt(0)=='#'){myReq=true; args[i+1]=args[i+1].substring(1);}else{myReq=false}
    var myObj = MM_findObj(args[i].replace(/\[\d+\]/ig,""));
    myV=myObj.value;
    if (myObj.type=='text'||myObj.type=='password'||myObj.type=='hidden'){
      if (myReq&&myObj.value.length==0){addErr=true}
      if ((myV.length>0)&&(args[i+2]==1)){ //fromto
        var myMa=args[i+1].split('_');if(isNaN(myV)||myV<myMa[0]/1||myV > myMa[1]/1){addErr=true}
      } else if ((myV.length>0)&&(args[i+2]==2)){
          var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-z]{2,4}$");if(!rx.test(myV))addErr=true;
      } else if ((myV.length>0)&&(args[i+2]==3)){ // date
        var myMa=args[i+1].split("#"); var myAt=myV.match(myMa[0]);
        if(myAt){
          var myD=(myAt[myMa[1]])?myAt[myMa[1]]:1; var myM=myAt[myMa[2]]-1; var myY=myAt[myMa[3]];
          var myDate=new Date(myY,myM,myD);
          if(myDate.getFullYear()!=myY||myDate.getDate()!=myD||myDate.getMonth()!=myM){addErr=true};
        }else{addErr=true}
      } else if ((myV.length>0)&&(args[i+2]==4)){ // time
        var myMa=args[i+1].split("#"); var myAt=myV.match(myMa[0]);if(!myAt){addErr=true}
      } else if (myV.length>0&&args[i+2]==5){ // check this 2
            var myObj1 = MM_findObj(args[i+1].replace(/\[\d+\]/ig,""));
            if(myObj1.length)myObj1=myObj1[args[i+1].replace(/(.*\[)|(\].*)/ig,"")];
            if(!myObj1.checked){addErr=true}
      } else if (myV.length>0&&args[i+2]==6){ // the same
            var myObj1 = MM_findObj(args[i+1]);
            if(myV!=myObj1.value){addErr=true}
      }
    } else
    if (!myObj.type&&myObj.length>0&&myObj[0].type=='radio'){
          var myTest = args[i].match(/(.*)\[(\d+)\].*/i);
          var myObj1=(myObj.length>1)?myObj[myTest[2]]:myObj;
      if (args[i+2]==1&&myObj1&&myObj1.checked&&MM_findObj(args[i+1]).value.length/1==0){addErr=true}
      if (args[i+2]==2){
        var myDot=false;
        for(var j=0;j<myObj.length;j++){myDot=myDot||myObj[j].checked}
        if(!myDot){myErr+='* ' +args[i+3]+'\n'}
      }
    } else if (myObj.type=='checkbox'){
      if(args[i+2]==1&&myObj.checked==false){addErr=true}
      if(args[i+2]==2&&myObj.checked&&MM_findObj(args[i+1]).value.length/1==0){addErr=true}
    } else if (myObj.type=='select-one'||myObj.type=='select-multiple'){
      if(args[i+2]==1&&myObj.selectedIndex/1==0){addErr=true}
    }else if (myObj.type=='textarea'){
      if(myV.length<args[i+1]){addErr=true}
    }
    if (addErr){myErr+='* '+args[i+3]+'\n'; addErr=false}
  }
  if (myErr!=''){alert('The required information is incomplete or contains errors:\t\t\t\t\t\n\n'+myErr)}
  document.MM_returnValue = (myErr=='');
}
//-->
</script>
<script language="javascript">
window.onload = function() {
	document.form1.list.focus();
}
</script>
<script language="javascript">
function toggleLayer(whichLayer, iState) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = iState? "":"none";
	}
}
function toggleLayer2(whichLayer) {
	if (document.getElementById){
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.all) {
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	} else if (document.layers) {
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		if(style2.display=="") {
			style2.display = "none";
		} else if(style2.display=="none") {
			style2.display = "";
		}
		//style2.display = iState? "":"none";
	}
}
</script>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
body,td,th,select,input,submit,button,div,p {
	font-family: Verdana;
	font-size: 11px;
}
body {
	background-color: #B5D452;
}
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="800" border="2" align="center" cellpadding="1" cellspacing="0" bordercolor="#000000" bgcolor="#FFFFFF" height="500">
  <tr>
    <td valign="top"><table width="100%"  border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td valign="top"><img src="../images/logo_hirez3.gif" width="100" height="100"></td>
        <td valign="top"><h1>Procentris Time Reporting System </h1> </td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><a href="../index.php">Home</a><?php if(!$_SESSION['MM_Username']) { ?> | <a href="../users/register.php">Register</a> | <a href="../users/login.php">Login</a><?php } ?><?php if($_SESSION['MM_Username']) { ?> | <a href="addhome.php">Add Timesheet</a> | <a href="timesheet_new.php">Add Timesheet <span class="style1">(NEW)</span></a> | <a href="timesheet.php">Timesheet</a> | <a href="deleted_task_list.php">Deleted Task List</a> | <a href="user_leavebalance.php">Leave Balance</a> | <a href="../users/edit.php">Edit Details</a> | <a href="../users/logout.php">Logout</a><?php } ?><?php if($_SESSION['MM_UserGroup']=="Admin" || $_SESSION['MM_UserGroup']=="SUPERADMIN") { ?> | <a href="admin.php">Admin</a>          <?php } ?>
          <?php if($_SESSION['MM_Username']) { ?><br>
          You are logged in as: <?php echo $_SESSION['MM_Username']; ?><?php } ?></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><hr></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
<!-- InstanceBeginEditable name="EditRegion3" -->
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td valign="top"><h3>Add New Project and Task</h3>
<p>  <?php echo $displayLink; ?></p>
<?php if($level_child<4 && $level_child>1) { ?>
<form action="" method="post" name="form1" onSubmit="YY_checkform('form1','list','#q','0','Please Fill Particular Field');return document.MM_returnValue">
  <table cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2"><strong>Add New <?php echo $list_type; ?></strong></td>
    </tr>
    <tr>
      <td><strong>Particulars:</strong></td>
      <td><input name="list" type="text" id="list" size="30" maxlength="100"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="SubmitNewCategory" value="Add New <?php echo $list_type; ?>">
        <input name="pid" type="hidden" id="pid" value="<?php echo $pid; ?>">
        <input name="level" type="hidden" id="level" value="<?php echo $level_child; ?>">
        <input name="user_id" type="hidden" id="user_id" value="<?php echo $row_rsUser['user_id']; ?>">
      <input name="list_type" type="hidden" id="list_type" value="<?php echo $list_type; ?>"></td>
    </tr>
  </table>
</form>
<?php } else { ?>
<!-- <p>You cannot add particulars under task.</p>
 --><?php } ?>
<?php if($child) { ?>
<p><strong>List of Categories Under <?php if($parent_name) echo $parent_name; else echo 'Root'; ?> </strong></p>

	<ol>
	<?php 
	foreach($child as $key => $value) {
		if($level_child==3) {
			?>
		  <li><a href="edit.php?edit_id=<?php echo $key; ?>&list_id=<?php echo $_GET['list_id']; ?>&pid=<?php echo $_GET['pid']; ?>" title="Edit Task"><?php echo $value; ?></a> (<!-- <a href="edit.php?edit_id=<?php echo $key; ?>&list_id=<?php echo $_GET['list_id']; ?>&pid=<?php echo $_GET['pid']; ?>">Edit</a>/  --><a href="add_time.php?list_id=<?php echo $key; ?>&pid=<?php echo $pid; ?>">Add Work Time</a> / <a href="delete_task.php?del_id=<?php echo $key; ?>&list_id=<?php echo $_GET['list_id']; ?>&pid=<?php echo $_GET['pid']; ?>">Delete</a>)</li>
			<?php
		} else {
	?>
	  <li><?php echo '<a href="'.$_SERVER['PHP_SELF'].'?pid='.$key.'&list_id='.$key.'" title="Create A New Category Under '.$value.'">'.$value.'</a> ('.countcat_categories($key).')'; ?> (<a href="edit.php?edit_id=<?php echo $key; ?>&list_id=<?php echo $_GET['list_id']; ?>&pid=<?php echo $_GET['pid']; ?>">Edit</a> / <a href="delete_task.php?del_id=<?php echo $key; ?>&list_id=<?php echo $_GET['list_id']; ?>&pid=<?php echo $_GET['pid']; ?>">Delete</a>)</li>
	  	<?php } ?>
	<?php } ?>
	</ol>
<?php } else { ?>
<p>No Subcategory Found Under <?php if($parent_name) echo $parent_name; else echo 'Root'; ?></p>
<?php } ?>
</td>
    <td valign="top" align="right">
<a href="javascript:;" onClick="javascript:toggleLayer2('todaysmenu');">View Today's Timesheet</a><br><br>
<div id="todaysmenu" style="display:none; ">
	<?php
// get array
include('f_workinghours.php');
if($_GET['mydate']) {
	$time = strtotime($_GET['mydate']);
} else {
	$time = time();
}
$returnArray = getTodaysTime($time,$row_rsUser['user_id']);
echo $returnArray;
?>
</div>
	&nbsp;</td>
  </tr>
</table>
<!-- InstanceEndEditable -->
</td>
      </tr>
    </table></td>
  </tr>
</table>
<?php include('../end.php'); ?>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsUser);
?>
