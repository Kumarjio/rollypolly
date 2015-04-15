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

$MM_restrictGoTo = "login.php";
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

$amount = 0.01;
$amountFeatured = 0.02;
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/Donations_theme1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Success</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/theme1.css" rel="stylesheet">
<link href="assets/css/site.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="shortcut icon" href="assets/ico/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/ico/favicon.ico" type="image/x-icon">

<!-- InstanceBeginEditable name="head" -->
<meta charset="UTF-8">

<!-- InstanceEndEditable -->
</head>
<body>
<div class="wrap">
<section>
<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">godonateme.com</a>
    </div>
    
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav ">
          <?php if (!empty($_SESSION['MM_UserId'])) { ?>
        <li class="active">
          <a href="logout.php">Logout</a>
        </li>
          <?php } ?>
          <?php if (empty($_SESSION['MM_UserId'])) { ?>
        <li class="active">
          <a href="login.php">Login</a>
        </li>
          <?php } ?>
          <?php if (empty($_SESSION['MM_UserId'])) { ?>
        <li>
          <a href="register.php">Register</a>
        </li>
          <?php } ?>
        <li>
          <a href="new.php">Create</a>
        </li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <?php if (!empty($_SESSION['MM_UserId'])) { ?>
        <li>
          <a href="javascript:;">Welcome, <?php echo $_SESSION['MM_Name']; ?></a>
        </li>
          <?php } ?>
        <li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>
          <ul class="dropdown-menu">
            <li>
              <a href="#">Contact Us</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    
  </div>
</nav>
</section>
<section class="top-section">
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php include('inc_googleadsense.php'); ?>
    </div>
  </div>
</div>
</section>

<section>
<div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
        <?php include('inc_category.php'); ?>
      </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<!-- InstanceBeginEditable name="EditRegion5" -->

<!-- InstanceEndEditable -->


<!-- InstanceBeginEditable name="EditRegion4" -->
<div class="page-header">
<h3>Confirmation</h3>
</div>
<!-- InstanceEndEditable -->

<div class="row">
<div class="col-lg-12">
<!-- InstanceBeginEditable name="EditRegion3" -->
<p>You have successfully created a new donation fund request. Click below paypal link to pay a admin fees of $ <?php echo $amount; ?>/-</p>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="renu09@live.com">
  <input type="hidden" name="item_name" id="item_name" value="Fund Creation Fees [DID:<?php echo $_GET['did']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"user_id":"<?php echo $_SESSION['MM_UserId']; ?>", "did":"<?php echo $_GET['did']; ?>", "is_featured":"0"}' >
      <input type="hidden" name="amount" value="<?php echo $amount; ?>">
      <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['did']; ?>" >
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="http://godonateme.com/newConfirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://godonateme.com/newCancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://godonateme.com/newNotifyPaypal.php">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>
<h3>Make this as Featured Listing Item. Featured Item will appear on front page on rotation basis. Click below paypal link to pay a featured admin fees of $ <?php echo $amountFeatured; ?>/-</h3>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="renu09@live.com">
  <input type="hidden" name="item_name" id="item_name" value="Fund Creation Featured Fees [DID:<?php echo $_GET['did']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"user_id":"<?php echo $_SESSION['MM_UserId']; ?>", "did":"<?php echo $_GET['did']; ?>", "is_featured":"1"}' >
      <input type="hidden" name="amount" value="<?php echo $amountFeatured; ?>">
      <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['did']; ?>" >
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="http://godonateme.com/newConfirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://godonateme.com/newCancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://godonateme.com/newNotifyPaypal.php">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>

<!-- InstanceEndEditable -->
</div>
</div>


</div><!-- middle col -->

<?php include('inc_featured.php'); ?>


</div><!-- / inner .row -->
</div>
</section>

<section class="custom-footer">
<div class="container">
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-7">
      <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4  col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-5">
       <span class="text-right"><?php include('inc_siteaddr.php'); ?></span>
    </div>
  </div>
</div>
</section>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.js"></script>

</body>
<!-- InstanceEnd --></html>