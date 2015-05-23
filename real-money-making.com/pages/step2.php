<?php
if (empty($_SESSION['user'])) {
	header("Location: /users/login");
	exit;
}
if (!isset($_GET['MM_Step'])) {
	header("Location: /step1");
	exit;
}
if (empty($_GET['netFees'])) {
	header("Location: /step1");
	exit;
}
$_GET['netFees'] = number_format($_GET['netFees'], 2);
$amount = $_GET['netFees'];
$arr = array();
$arr = $_GET;
//unset($arr['Submit']);
unset($arr['MM_Step']);
unset($arr['p']);
//unset($arr['agree']);
$arr['user_id'] = $_SESSION['user']['id'];
$customStr = json_encode($arr);
pr($arr);
?>

<h1>Real Money Making</h1>

  <fieldset><legend>Step 2 Payment</legend>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="renu09@live.com">
  <input type="hidden" name="item_name" id="item_name" value="Admin Fees">
      <input type="hidden" name="custom" id="custom" value='<?php echo $customStr; ?>' >
      <input type="hidden" name="amount" value="<?php echo $amount; ?>">
      <input type="hidden" name="item_number" id="item_number" value="1011" >
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="http://real-money-making.com/confirmpaypal">
  <input type="hidden" name="cancel_return" value="http://real-money-making.com/cancelpaypal">
  <input type="hidden" name="notify_url" value="http://real-money-making.com/notifypaypal">
    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
    <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
</form>
  </fieldset>