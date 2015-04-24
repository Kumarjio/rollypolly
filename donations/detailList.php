<?php require_once('../Connections/connWork.php'); ?>
<?php session_start(); ?>
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

$colname_DetailRS1 = "-1";
if (isset($_GET['did'])) {
  $colname_DetailRS1 = $_GET['did'];
}
mysql_select_db($database_connWork, $connWork);
$query_DetailRS1 = sprintf("SELECT * FROM donations as d LEFT JOIN donations_calc_received
 as r ON d.did = r.did2 WHERE d.did = %s", GetSQLValueString($colname_DetailRS1, "text"));
$DetailRS1 = mysql_query($query_DetailRS1, $connWork) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
$percentage = $row_DetailRS1['total_amount'] * (100 / $row_DetailRS1['donation_needed']);

?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/Donations_detail1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $row_DetailRS1['donation_title']; ?></title>
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

<?php 
require_once('inc_category.php'); 

?>

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
    
    <?php include('inc_menu.php'); ?>
    
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

    <div class="col-lg-12"><!-- middle col -->
<!-- InstanceBeginEditable name="EditRegion4" -->
<div class="page-header">
<h3><?php echo $row_DetailRS1['donation_title']; ?></h3>
</div>
<!-- InstanceEndEditable -->

<div class="row">
<div class="col-lg-12">
<!-- InstanceBeginEditable name="EditRegion3" -->
<div>
  <img src="images/<?php echo $row_DetailRS1['user_id']; ?>/<?php echo $row_DetailRS1['donation_image']; ?>" class="imgDetailWide" />
</div>
<div>
  <strong><br>
  Description:</strong><br />
  <?php echo $row_DetailRS1['donation_desc']; ?>
</div>
<hr />
<p class="text-danger">
  <strong>Required Donation:</strong> $ <?php echo $row_DetailRS1['donation_needed'];?>
  <?php if ($row_DetailRS1['total_amount'] > 0) { ?>
   <br /><strong>Donation Received:</strong> $ <?php echo $row_DetailRS1['total_amount']; ?>
  <br /><strong>Pending Donation:</strong> $ <?php echo $row_DetailRS1['donation_needed'] - $row_DetailRS1['total_amount']; ?>
  <?php } ?>
</p>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
    <?php echo $percentage; ?>%
  </div>
</div>
<?php if (!isset($_SESSION['MM_UserId']) || (isset($_SESSION['MM_UserId']) && $_SESSION['MM_UserId'] != $row_DetailRS1['user_id'])) { ?>
<h3>Donate Money To This Campaign</h3>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <p>
    
    <strong>Donation Amount: </strong>$
<input name="amount" type="text" required id="amount" placeholder="Enter Donation Amount" value="<?php echo $amount; ?>" size="45">
      <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $row_DetailRS1['donation_paypal_email']; ?>">
  <input type="hidden" name="item_name" id="item_name" value="Donation Funds For [DID:<?php echo $_GET['did']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"donar_user_id":"<?php echo !empty($_SESSION['MM_UserId']) ? $_SESSION['MM_UserId']: ''; ?>", "did":"<?php echo $_GET['did']; ?>"}' >
      <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['did']; ?>" >
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="http://godonateme.com/detailConfirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://godonateme.com/detailCancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://godonateme.com/detailNotifyPaypal.php">
    <!-- Display the payment button. -->
    </p>
    <p>
      <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
      <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" ></p>
</form>
<?php } ?>
<!-- InstanceEndEditable -->
</div>
</div>


</div><!-- middle col -->



</div><!-- / inner .row -->
</div><!-- / container -->
</section>

<section class="custom-footer">
<?php include('inc_footer.php'); ?>
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
<?php
mysql_free_result($DetailRS1);
?>
