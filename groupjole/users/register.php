<?php require_once('../../Connections/connGroupjole.php'); ?>
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
include ('../config.php');

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="registerError.php";
  $loginUsername = $_POST['username'];
  $LoginRS__query = sprintf("SELECT username FROM users WHERE username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_connGroupjole, $connGroupjole);
  $LoginRS=mysql_query($LoginRS__query, $connGroupjole) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO users (username, email, password, name, paypal_email, gender, age) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['paypal_email'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['age'], "int"));

  mysql_select_db($database_connGroupjole, $connGroupjole);
  $Result1 = mysql_query($insertSQL, $connGroupjole) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  send_email($_POST['email'], 'New User Registration at '.SITENAME, 'register.php', $_POST);
  $insertGoTo = "registerConfirm.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/groupjole_theme2.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Register New User</title>
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
                        <h3> Register</h3>
                    <!-- InstanceEndEditable -->
                    </div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<div class="row">
    <div class="col-lg-12">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">Register New User</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <div class="form-group">
                          <strong>Username</strong> <br />
                          <input type="text" name="username" id="username" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Password</strong> <br />
                          <input type="password" name="password" id="password" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Confirm Password</strong> <br />
                          <input type="password" name="confirmPassword" id="confirmPassword" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Email</strong> <br />
                          <input type="email" name="email" id="email" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Name</strong> <br />
                          <input type="text" name="name" id="name" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Paypal Email</strong> <br />
                          <input type="text" name="paypal_email" id="paypal_email" required class="inputText" />
                       </div>
                       
                      <div class="form-group">
                          <strong>Gender</strong> <br />
                          <input name="gender" type="radio" id="gender_0" value="Male" checked>
                          <label for="gender_0">Male</label> 
                          <input type="radio" name="gender" value="Female" id="gender_1">
                          <label for="gender_1">Female</label>
                       </div>
                       
                      <div class="form-group">
                          <strong>Age</strong> <br />
                          <input type="number" name="age" id="age" min="18" max="200" class="inputText" />
                       </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
</div>
    <input type="submit" name="submit" id="submit" value="Register as a new user" class="inputText">
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