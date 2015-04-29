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
include('../config.php');

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
<title>Confirmation Page</title>
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
                        <h3>Group Fee Subscription</h3>
                    <!-- InstanceEndEditable -->
                    </div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($totalRows_rsGroup == 0) { ?>
<p>This group is not created by you or is currently unavailable.</p>
<?php }  else { ?>
<h3><?php echo $row_rsGroup['group_name']; ?></h3>
<?php if ($row_rsGroup['group_type'] == 1) { ?>
<p>You have successfully created a group. <a href="manage.php?group_id=<?php echo $row_rsGroup['group_id']; ?>">Click here</a> to manage it.</p>
<?php } else if ($row_rsGroup['group_type'] == 2) { ?>
<p>You have successfully created a group. Please subscribe to paypal by clicking link given below. It is currently under administrator review process and it will be shortly be alive after payment processing and admin approval. <strong>Payment Terms:</strong> $<?php echo GROUPFEETRAILAMOUNT; ?> USD for the first <?php echo GROUPFEETRAILPERIODNUMBER; ?> months
Then $<?php echo GROUPFEEAMOUNT; ?> USD for each <?php echo GROUPFEEPERIODNUMBER; ?> months.</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="renu09@live.com">
  <input type="hidden" name="item_name" id="item_name" value="Group Fees [<?php echo $row_rsGroup['group_name']; ?> - <?php echo $_GET['group_id']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"user_id":"<?php echo $_SESSION['MM_UserId']; ?>", "group_id":"<?php echo $_GET['group_id']; ?>"}' >
      <input type="hidden" name="currency_code" value="USD" />

  <!-- First two months of subscription are free. -->
  <input type="hidden" name="a1" value="<?php echo GROUPFEETRAILAMOUNT; ?>" />
  <input type="hidden" name="p1" value="<?php echo GROUPFEETRAILPERIODNUMBER; ?>" />
  <input type="hidden" name="t1" value="<?php echo GROUPFEETRAILPERIODTYPE; ?>" />

  <!-- Recurring subscription payments. -->
  <input type="hidden" name="a3" value="<?php echo GROUPFEEAMOUNT; ?>" />
  <input type="hidden" name="p3" value="<?php echo GROUPFEEPERIODNUMBER; ?>" />
  <input type="hidden" name="t3" value="<?php echo GROUPFEEPERIODTYPE; ?>" />
  <input type="hidden" name="src" value="1" />
  <input type="hidden" name="sra" value="1" />
   <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['group_id']; ?>" >
  <input type="hidden" name="return" value="http://groupjole.com/groups/confirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://groupjole.com/groups/cancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://groupjole.com/groups/notifyPaypal.php">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_subscribe_cc_147x47.png"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>

<?php } else if ($row_rsGroup['group_type'] == 3) { ?>
<p>You have successfully created a group. Please subscribe to paypal by clicking link given below. It is currently under administrator review process and it will be shortly be alive after payment processing and admin approval. <strong>Payment Terms:</strong> $<?php echo GROUPFEEAMOUNT; ?> USD for each <?php echo GROUPFEEPERIODNUMBER; ?> months.</p>


<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="renu09@live.com">
  <input type="hidden" name="item_name" id="item_name" value="Group Fees [<?php echo $row_rsGroup['group_name']; ?> - <?php echo $_GET['group_id']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"user_id":"<?php echo $_SESSION['MM_UserId']; ?>", "group_id":"<?php echo $_GET['group_id']; ?>"}' >
      <input type="hidden" name="currency_code" value="USD" />

  <!-- Recurring subscription payments. -->
  <input type="hidden" name="a3" value="<?php echo GROUPFEEAMOUNT; ?>" />
  <input type="hidden" name="p3" value="<?php echo GROUPFEEPERIODNUMBER; ?>" />
  <input type="hidden" name="t3" value="<?php echo GROUPFEEPERIODTYPE; ?>" />
  <input type="hidden" name="src" value="1" />
  <input type="hidden" name="sra" value="1" />
   <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['group_id']; ?>" >
  <input type="hidden" name="return" value="http://groupjole.com/groups/confirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://groupjole.com/groups/cancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://groupjole.com/groups/notifyPaypal.php">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_subscribe_cc_147x47.png"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>

<?php } ?>
<?php } ?>
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
mysql_free_result($rsGroup);
?>
