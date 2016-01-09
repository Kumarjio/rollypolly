<?php
checkLogin();
$return = array();
$userId = !empty($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : '';
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$query = "select * from groups WHERE user_id = ? AND group_id = ?";
$returnSettings = $General->fetchRow($query, array($userId, $id), CACHETIME);
if (empty($returnSettings)) {
    header("Location: new");
    exit;    
}
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Payment Details
        </h1>
    </div>
    <div class="col-lg-12">
        
        <p>You have successfully created a group "<?php echo $returnSettings['group_name']; ?>". </p>
        <p>Please subscribe to paypal by clicking link given below. It is currently under administrator review process and it will be shortly be alive after payment processing and admin approval. </p>
        <p><strong>Payment Terms:</strong> $1.00 USD for the first 3 months
            Then $12.00 USD for each 12 months.</p>
        
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick-subscriptions">
            <input type="hidden" name="business" value="renu09@live.com">
          <input type="hidden" name="item_name" id="item_name" value="<?php echo TITLENAME; ?> Fees [<?php echo $returnSettings['group_name']; ?> - <?php echo $returnSettings['group_id']; ?>]">
              <input type="hidden" name="custom" id="custom" value='{"user_id":"<?php echo $_SESSION['user']['user_id']; ?>", "group_id":"<?php echo $returnSettings['group_id']; ?>"}' >
              <input type="hidden" name="currency_code" value="USD" />
        
          <!-- First two months of subscription are free. -->
          <input type="hidden" name="a1" value="1.00" />
          <input type="hidden" name="p1" value="3" />
          <input type="hidden" name="t1" value="M" />
        
          <!-- Recurring subscription payments. -->
          <input type="hidden" name="a3" value="12" />
          <input type="hidden" name="p3" value="12" />
          <input type="hidden" name="t3" value="M" />
          <input type="hidden" name="src" value="1" />
          <input type="hidden" name="sra" value="1" />
           <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['id']; ?>" >
          <input type="hidden" name="return" value="<?php echo HTTPPATH; ?>/groups/confirmPaypal">
          <input type="hidden" name="cancel_return" value="<?php echo HTTPPATH; ?>/groups/cancelPaypal">
          <input type="hidden" name="notify_url" value="<?php echo HTTPPATH; ?>/groups/notifyPaypal">
            <!-- Display the payment button. -->
            <input type="image" name="submit" border="0"
            src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_subscribe_cc_147x47.png"
            alt="PayPal - The safer, easier way to pay online">
            <img alt="" border="0" width="1" height="1"
            src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
        </form>
    </div>
</div>