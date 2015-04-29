<?php require_once('../../Connections/connGroupjole.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}
include('../config.php');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$colname_rsUser = "-1";
if (isset($_SESSION['MM_UserId'])) {
  $colname_rsUser = $_SESSION['MM_UserId'];
}
mysql_select_db($database_connGroupjole, $connGroupjole);
$query_rsUser = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $connGroupjole) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

//validation

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $_POST['group_id'] = guid();
    $_POST['user_id'] = $_SESSION['MM_UserId'];
    $_POST['group_created_dt'] = date('Y-m-d H:i:s');
    $_POST['topics_added'] = json_encode($_POST['categories']);
    $_POST['payment_status'] = 'Pending';
    $_POST['group_type'] = GROUPFEESTYPE;
    if (GROUPFEESTYPE == 1) {
        $_POST['payment_status'] = 'Completed';
    }
    $_POST['email'] = $row_rsUser ['email'];
    $_POST['name'] = $row_rsUser ['name'];
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO groups (group_id, user_id, group_name, group_headline, group_url, group_description, members_called, group_created_dt, topics_added, location, lat, lng, city, `state`, country, payment_status, group_type) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['group_id'], "text"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['group_name'], "text"),
                       GetSQLValueString($_POST['group_headline'], "text"),
                       GetSQLValueString($_POST['group_url'], "text"),
                       GetSQLValueString($_POST['group_description'], "text"),
                       GetSQLValueString($_POST['members_called'], "text"),
                       GetSQLValueString($_POST['group_created_dt'], "date"),
                       GetSQLValueString($_POST['topics_added'], "text"),
                       GetSQLValueString($_POST['group_location'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lng'], "double"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['payment_status'], "text"),
                       GetSQLValueString($_POST['group_type'], "int"));

  mysql_select_db($database_connGroupjole, $connGroupjole);
  $Result1 = mysql_query($insertSQL, $connGroupjole) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $_POST['member_status'] = 1;
    $_POST['profile_question_answers'] = '';
    $_POST['member'] = 1;
    $_POST['organizer'] = 1;
    $_POST['coorganizer'] = 0;
    $_POST['assistant_organizer'] = 0;
    $_POST['event_organizer'] = 0;
  $insertSQL = sprintf("INSERT INTO group_members (group_id, member_user_id, member_joined_date, member_status, profile_question_answers, member, organizer, coorganizer, assistant_organizer, event_organizer) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['group_id'], "text"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['group_created_dt'], "date"),
                       GetSQLValueString($_POST['member_status'], "int"),
                       GetSQLValueString($_POST['profile_question_answers'], "text"),
                       GetSQLValueString($_POST['member'], "int"),
                       GetSQLValueString($_POST['organizer'], "int"),
                       GetSQLValueString($_POST['coorganizer'], "int"),
                       GetSQLValueString($_POST['assistant_organizer'], "int"),
                       GetSQLValueString($_POST['event_organizer'], "int"));

  mysql_select_db($database_connGroupjole, $connGroupjole);
  $Result1 = mysql_query($insertSQL, $connGroupjole) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  send_email($_POST['email'], 'New Group "'.$_POST['group_name'].'" Created at '.SITENAME, 'group_new.php', $_POST);
  $insertGoTo = "createConfirm.php?group_id=".$_POST['group_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_connGroupjole, $connGroupjole);
$query_rsCategories = "SELECT l.topic_id as ltopic_id, l.topic as ltopic, l.parent_id as lparent_id, l.sorting as lsorting, r.topic_id as rtopic_id, r.topic as rtopic, r.parent_id as rparent_id, r.sorting as rsorting FROM topics as l LEFT JOIN topics as r ON l.parent_id = r.topic_id";
$rsCategories = mysql_query($query_rsCategories, $connGroupjole) or die(mysql_error());
$row_rsCategories = mysql_fetch_assoc($rsCategories);
$totalRows_rsCategories = mysql_num_rows($rsCategories);
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/groupjole_theme2.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Create New Group</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<!-- GOOGLE FONT-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- /GOOGLE FONT-->


<!-- Le styles -->
<!-- Latest compiled and minified CSS BS 3.0. -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/theme2.css" rel="stylesheet">
<link href="../assets/css/site.css" rel="stylesheet">



<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!-- Fav and touch icons -->


<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="//maps.google.com/maps/api/js?sensor=false&libraries=places"></script>

<script src="../assets/js/ang/app.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="../assets/js/bootstrap.js"></script>

<script src="../assets/js/googleMap.js"></script>

<!-- InstanceBeginEditable name="head" -->
<meta charset="UTF-8">
<script language="javascript">
var autocompleteCreateGroup;
$( document ).ready(function() {
    var input = document.getElementById('group_location');
    var options = {
      types: ['geocode']
    };
    
    autocompleteCreateGroup = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocompleteCreateGroup, 'place_changed', function() {
        fillCreateGroup();
      });
});

function fillCreateGroup() {
      var place = autocompleteCreateGroup.getPlace();
      //console.log(place);
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      $('#lat').val(lat);
      $('#lng').val(lng);
      for (key in place.address_components) {
        var loc = place.address_components[key];
        if (loc.types[0] === "locality") {
          $('#city').val(loc.long_name);
        } else if (loc.types[0] === "administrative_area_level_1") {
          $('#state').val(loc.short_name);
        } else if (loc.types[0] === "country") {
          $('#country').val(loc.long_name);
        } else {
          continue;
        }
      }
}

function geolocateCreateGroup() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = new google.maps.LatLng(
              position.coords.latitude, position.coords.longitude);
          autocompleteCreateGroup.setBounds(new google.maps.LatLngBounds(geolocation,
              geolocation));
        });
      }
    }
</script>
<!-- InstanceEndEditable -->
</head>
<body data-ng-app="GroupJole">
<div class="wrap">
	<section>
		<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">GroupJole.Com</a>
				</div>
				<?php include('../includes/topMenu.php'); ?>
			</div>
		</nav>
	</section>
	<section class="top-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<h4>
						(Create Your Groups & Events)
					</h4>
				</div>
				<div class="col-lg-8">
					<form class="navbar-form ng-pristine ng-valid pull-right" role="search" action="../index.php" method="get">
						<div class="form-group">
							<input type="text" name="keyword" id="keyword" class="form-control widthAuto" placeholder="Enter Keyword ...." value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" />
							<input type="text" name="addressID" id="addressID" class="form-control widthAuto addressBox" placeholder="Enter City Name ...."  onFocus="geolocate()" value="<?php echo !empty($_GET['addressID']) ? $_GET['addressID'] : ''; ?>" /><input type="hidden" name="s_lat" id="s_lat" value="<?php echo !empty($_GET['s_lat']) ? $_GET['s_lat'] : ''; ?>" /><input type="hidden" name="s_lng" id="s_lng" value="<?php echo !empty($_GET['s_lng']) ? $_GET['s_lng'] : ''; ?>" />
						</div> <button type="submit" class="btn btn-default">Search</button>
					</form>
				</div>
			</div>
       
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
			    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
					<?php include('../includes/offers.php'); ?>
					<?php include('../includes/more.php'); ?>
				</div>
                
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-9 hidden-xs">
                    <div class="page-header">
                    <!-- InstanceBeginEditable name="EditRegionSubHead" -->
                        <h3>Create New Group</h3>
                    <!-- InstanceEndEditable -->
                    </div>
<!-- InstanceBeginEditable name="EditRegion3" -->

<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" data-ng-controller="createCtrl">

<div class="row">
    <div class="col-lg-12">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">About this Meetup Group</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          <strong>Group Name (*):</strong> <br />
                          <input type="text" name="group_name" id="group_name" size="100" required class="inputText" data-ng-model="group_name" />
                       </div>
                      <div class="form-group">
                          <strong>Group URL (*):</strong> <br />
                          http://groupjole.com/group/{{group_name | spaceless}}
                          <input type="text" name="group_url" id="group_url" size="255" data-ng-model="group_url" value="{{group_name | spaceless}}" />
                       </div>
                      <div class="form-group">
                          <strong>Group Headline:</strong> <br />
                          <input type="text" name="group_headline" id="group_headline" class="inputText" />
                       </div>
                      <div class="form-group">
                          <strong>Group Description (*):</strong> <br />
                          What is this Meetup's purpose? Who should join? Why? <br />
                          <textarea name="group_description" class="inputText" rows="5" required></textarea>
                       </div>
                      <div class="form-group">
                          <strong>What members are called (*):</strong> <br />
                          This phrase is used in emails and throughout this group's pages. For example, an email might say "37 Members have RSVPed for tomorrow's Meetup." <br />
                          <input type="text" name="members_called" id="members_called" required placeholder="Members" value="Members" class="inputText" />
                       </div>
                  </div>
               </div>
            </div>
            
            
            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Location</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          <strong>Group Location (*):</strong> <br />
                          <input type="text"  onFocus="geolocateCreateGroup()" name="group_location" id="group_location" size="100" required class="inputText addressBox" /><input type="hidden" name="city" id="city" value="<?php echo (!empty($_GET['city'])) ? $_GET['city'] : ''; ?>" /><input type="hidden" name="state" id="state" value="<?php echo (!empty($_GET['state'])) ? $_GET['state'] : ''; ?>" /><input type="hidden" name="country" id="country" value="<?php echo (!empty($_GET['country'])) ? $_GET['country'] : ''; ?>" /><input type="hidden" name="lat" id="lat" value="<?php echo (!empty($_GET['lat'])) ? $_GET['lat'] : ''; ?>" /><input type="hidden" name="lng" id="lng" value="<?php echo (!empty($_GET['lng'])) ? $_GET['lng'] : ''; ?>" />
                       </div>
                  </div>
               </div>
            </div>
            
            
            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Group Category</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                              <select name="categories[]" size="10" required multiple class="inputText" id="categories">
                                  <?php
do {  
?>
                                  <?php if ($row_rsCategories['lparent_id'] != 0) {?>
                                  <option value="<?php echo $row_rsCategories['ltopic_id']?>"><?php echo $row_rsCategories['rtopic'].' => '.$row_rsCategories['ltopic']; ?></option>
                                  <?php } ?>
                                  <?php
} while ($row_rsCategories = mysql_fetch_assoc($rsCategories));
  $rows = mysql_num_rows($rsCategories);
  if($rows > 0) {
      mysql_data_seek($rsCategories, 0);
	  $row_rsCategories = mysql_fetch_assoc($rsCategories);
  }
?>
                              </select>
                      </div>
                      
                  </div>
               </div>
            </div>

        </div>
     </div>
</div>
<p>
<input type="submit" name="submit" id="submit" value="Create New Group" class="inputText">
</p>
<input type="hidden" name="group_id" value="">
<input type="hidden" name="user_id" value="">
<input type="hidden" name="group_created_dt" value="">
<input type="hidden" name="topics_added" value="">
<input type="hidden" name="MM_insert" value="form1">
</form>
<!-- InstanceEndEditable -->
		        </div>
				
			</div>
			<hr>
		</div>
	</section>
	<section class="custom-footer">
		<div class="container">
			<div class="row">
				
				<?php include('../includes/footerLinks.php'); ?>
				<?php include('../includes/bottomAddress.php'); ?>
			</div>
		</div>
	</section>
</div>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsCategories);

mysql_free_result($rsUser);
?>
