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

$colname_rsUser = "-1";
if (isset($_SESSION['MM_UserId'])) {
  $colname_rsUser = $_SESSION['MM_UserId'];
}
mysql_select_db($database_connGroupjole, $connGroupjole);
$query_rsUser = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $connGroupjole) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

$colname_rsGroup = "-1";
if (isset($_GET['group_id'])) {
  $colname_rsGroup = $_GET['group_id'];
}
$coluser_rsGroup = "-1";
if (isset($_SESSION['MM_UserId'])) {
  $coluser_rsGroup = $_SESSION['MM_UserId'];
}
mysql_select_db($database_connGroupjole, $connGroupjole);
$query_rsGroup = sprintf("SELECT * FROM groups WHERE group_id = %s AND user_id = %s", GetSQLValueString($colname_rsGroup, "text"),GetSQLValueString($coluser_rsGroup, "int"));
$rsGroup = mysql_query($query_rsGroup, $connGroupjole) or die(mysql_error());
$row_rsGroup = mysql_fetch_assoc($rsGroup);
$totalRows_rsGroup = mysql_num_rows($rsGroup);
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/groupjole_theme2.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Group Settings</title>
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
                        <h3> Group Settings For Group &quot;<?php echo $row_rsGroup['group_name']; ?>&quot;</h3>
                    <!-- InstanceEndEditable -->
                    </div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div class="row">
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Basics</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          Group Name<br>
                          Group Headline
                          <br>
                          Group Description<br>
                          What are members called?<br>
                      Group Photo<br>
                      Group Youtube Video
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Members</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          Profile Questions<br>
                          Membership to the group
                          <br>
                          New member profile requirements<br>
                          Welcome message to the new members</div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Topics</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          Choose the right topics so the right people can find your group</div>

                  </div>
               </div>
            </div>

        </div>
     </div>
</div>
<br />
<div class="row">
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Content Visibility</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          What non-members can see about your group and event details
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Your venues</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          
                              Edit and manage your venues                                  <br>
                              Share your venues <br>
                              Add a venue
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Member roles</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          Manage roles and customize which members can help run the Meetup group<br>
                              Who can create new pages?
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
</div>
<br />
<div class="row">
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Optional Features</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          What non-members can see about your group and event details
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Your venues</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          
                              Edit and manage your venues                                  <br>
                              Share your venues <br>
                              Add a venue
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
    <div class="col-lg-4">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Member roles</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          Manage roles and customize which members can help run the Meetup group<br>
                              Who can create new pages?
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
</div>
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
mysql_free_result($rsUser);

mysql_free_result($rsGroup);
?>
