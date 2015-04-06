<?php
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
if (!empty($_REQUEST['cty'])) {
  $status = 0;
  $insertSQL = sprintf("INSERT INTO geo_city_owners (cty_id, owner_id, expiry_date, subs_expiry_date, status) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_REQUEST['cty'], "int"),
                       GetSQLValueString($_SESSION['user']['id'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s', strtotime("+1 month")), "date"),
                       GetSQLValueString(date('Y-m-d H:i:s', strtotime("+1 year")), "date"),
                       GetSQLValueString($status, "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());

  $insertGoTo = $currentURL;
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}
//getting owern information
$Models_Geo = new Models_Geo();
$ownerDetails = $Models_Geo->getOwnerDetails($city_id, 0);
if (!empty($ownerDetails)) {
  header("Location: ".$currentURL);
  exit;
}
?>
<h1><?php echo $pageTitle; ?></h1>
<p>Apply For City Moderator</p>
<form action="<?php echo PAYPAL_URL; ?>" method="post">
    
<p><b>Name:</b> <?php echo $_SESSION['user']['name']; ?></p>
<p><b>Email:</b> <?php echo $_SESSION['user']['email']; ?></p>
<p><b>Gender:</b> <?php echo $_SESSION['user']['gender']; ?></p>
<p><img src="<?php echo $_SESSION['user']['picture']; ?>" class="imglist" /></p>
<p><input type="checkbox" name="agree" id="agree"> I agree to terms and conditions</p>
<p><b>Note: </b> You need to pay $ <?php echo CITY_RATE; ?> every month.</p>
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="<?php echo BUSINESS_PAYPAL_EMAIL; ?>">
    <input type="hidden" name="item_name" value="City Moderator for city <?php echo $globalCity['pageTitle'];?>">
    <input type="hidden" name="item_number" value="1211">
    <input type="hidden" name="a3" value="<?php echo CITY_RATE; ?>">
    <input type="hidden" name="p3" value="<?php echo SUBSCRIPTION_DURATION; ?>">
    <input type="hidden" name="t3" value="<?php echo SUBSCRIPTION_UNITS_OF_DURATION; ?>">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="srt" value="0">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="custom" value="t:<?php echo PAYMENT_TYPE_CITY; ?>|u:<?php echo $_SESSION['user']['id']; ?>|c:<?php echo $globalCity['id']; ?>">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="return" value="<?php echo $currentURL; ?>/city/moderator/success">
    <input type="hidden" name="cancel_return" value="<?php echo $currentURL; ?>/city/moderator/cancel">
    <input type="hidden" name="notify_url" value="<?php echo HTTPPATH; ?>/paypal/notify">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
    <!--<a href="<?php echo $currentURL; ?>/city/moderator/apply?cty=<?php echo $globalCity['id']; ?>">Subscribe</a>-->
</form>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>