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
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Success</title>
</head>

<body>
<h1>Confirmation</h1>
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

</body>
</html>