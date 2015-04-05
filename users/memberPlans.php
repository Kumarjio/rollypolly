<?php
check_login();
$pageTitle = 'Membership Plans';
include(SITEDIR.'/libraries/addresses/nearby.php');
?>

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="nav">Membership Plans</h1>
            </div>
          </div>
        </div>
<div class="row">
   <div class="col-md-6">
  <div class="panel panel-info">
     <div class="panel-heading"><h3 class="text-center">PRO PLAN</h3></div>
         <div class="panel-body text-center">
			<p class="lead" style="font-size:40px"><strong>$<?php echo MEMBERSHIP_RATE; ?> / year</strong></p>
		</div>
                       <ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited View Access</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Share contact details</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Send Unlimited Messages</li>
						</ul>
    <div class="panel-footer">

<form action="<?php echo PAYPAL_URL; ?>" method="post" onSubmit="return validateForm();">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="business" value="<?php echo BUSINESS_PAYPAL_EMAIL; ?>">
  <input type="hidden" name="item_name" id="item_name" value="Membership Subscription">
      <input type="hidden" name="item_number" id="item_number" value="<?php echo MEMBERSHIP_ITEM_NUMBER; ?>"></p>
    <input type="hidden" name="a3" value="<?php echo MEMBERSHIP_RATE; ?>">
    <input type="hidden" name="p3" value="<?php echo MEMBERSHIP_SUBSCRIPTION_DURATION; ?>">
    <input type="hidden" name="t3" value="<?php echo MEMBERSHIP_SUBSCRIPTION_UNITS_OF_DURATION; ?>">
    <input type="hidden" name="src" value="1">
    <input type="hidden" name="srt" value="0">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="custom" id="custom" value="t:<?php echo PAYMENT_MEMBERSHIP; ?>|u:<?php echo $_SESSION['user']['id']; ?>">
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="<?php echo HTTPPATH; ?>/users/success">
  <input type="hidden" name="cancel_return" value="<?php echo HTTPPATH; ?>/users/cancel">
  <input type="hidden" name="notify_url" value="<?php echo HTTPPATH; ?>/paypal/notify">
    <!-- Display the payment button. -->
    <input type="submit" name="submit" border="0" class="btn btn-lg btn-block btn-danger" value="SUBSCRIBE NOW!">
</form>
	</div>
 </div>

  </div>
   <div class="col-md-6">
  <div class="panel panel-success">
    <div class="panel-heading"><h3 class="text-center">FREE PLAN</h3></div>
         <div class="panel-body text-center">
			<p class="lead" style="font-size:40px"><strong>$0 / month</strong></p>
		</div>
                       <ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited View Access</li>
						</ul>
    <div class="panel-footer">
	</div>
 </div>

  </div>
  </div>