<?php
check_login();
$pageTitle = 'Forex Exper Advisor Software';
include(SITEDIR.'/libraries/addresses/nearby.php');
?>
<h1><?php echo $pageTitle; ?></h1>
<script language="javascript">
function validateForm()
{
  if (!$('#item_number') || $('#item_number') == "") {
    alert('enter forex metatrader account number');
    return false;
  }
  if (!$('#agree').attr('checked')) {
    alert('please agree to terms and conditions');
    return false;
  }
  $('#item_name').val($('#item_name').val() + " for A/c No. " + $('#item_number').val());
  var custom = $('#customTxt').html() + "|a:" + $('#item_number').val();
  $('#custom').val(custom);
  return true;
}
</script>
<form action="<?php echo PAYPAL_URL; ?>" method="post" onSubmit="return validateForm();">
    
<p><b>Name:</b> <?php echo $_SESSION['user']['name']; ?></p>
<p><b>Email:</b> <?php echo $_SESSION['user']['email']; ?></p>
<p><b>Gender:</b> <?php echo $_SESSION['user']['gender']; ?></p>
<p><img src="<?php echo $_SESSION['user']['picture']; ?>" class="imglist" /></p>
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="<?php echo BUSINESS_PAYPAL_EMAIL; ?>">
  <input type="hidden" name="item_name" id="item_name" value="Forex Software Subscription">
    <p><strong>Forex Metatrader 4 Account Number: </strong>
      <input type="number" size="30" name="item_number" id="item_number" value="" required placeholder="Enter Account Number"></p>
    <input type="hidden" name="a3" value="<?php echo FOREX_RATE; ?>">
    <input type="hidden" name="p3" value="<?php echo FOREX_SUBSCRIPTION_DURATION; ?>">
    <input type="hidden" name="t3" value="<?php echo FOREX_SUBSCRIPTION_UNITS_OF_DURATION; ?>">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="srt" value="0">
    <input type="hidden" name="no_note" value="1">
    <div id="customTxt" style="display:none;">t:<?php echo PAYMENT_TYPE_FOREX; ?>|u:<?php echo $_SESSION['user']['id']; ?></div>
    <input type="hidden" name="custom" id="custom" value="t:<?php echo PAYMENT_TYPE_FOREX; ?>|u:<?php echo $_SESSION['user']['id']; ?>">
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="<?php echo HTTPPATH; ?>/forex/success">
  <input type="hidden" name="cancel_return" value="<?php echo HTTPPATH; ?>/forex/cancel">
  <input type="hidden" name="notify_url" value="<?php echo HTTPPATH; ?>/paypal/notify">
<p><input type="checkbox" name="agree" id="agree"> I agree to terms and conditions</p>
<p><b>Note: </b> You need to pay $ <?php echo FOREX_RATE; ?> every week. Trading is risky business. You can earn a lot or you may loose your money. So you must be prepared for both the cases. Minimum weekly winning percentage is set to 5% and Max loss per week is set to 10%.</p>
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