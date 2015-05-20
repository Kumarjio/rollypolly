<?php
if (empty($_SESSION['user'])) {
	header("Location: /users/login");
	exit;
}
?>

<h1>Real Money Making</h1>
<form id="form1" name="form1" method="get" action="/step2" data-ng-controller="step1Ctrl">
  <fieldset><legend>Step 1 Amount Overview</legend>
<a name="step1"></a>
  <p><strong>How Much Money You Need: </strong><br />
    <input name="amount" type="text" id="amount" size="50" data-ng-model="amount" data-ng-init="amount=0" />
  </p>
  <p><strong>Amount to Deposit with any forex broker near your area:</strong><br /> 
    <input name="deposit" type="text" id="deposit" size="50" value="{{amount * 10 * 2}}" readonly="readonly" />
  </p>
  <p><strong>Admin Charges:</strong><br />
    <input name="adminFees" type="text" id="adminFees" size="50" value="{{amount * (10 / 100)}}" readonly="readonly" />
</p>
  <p><strong>Paypal Fees:</strong><br />
    <input name="paypalFees" type="text" id="paypalFees" value="{{(amount * (10 / 100)) * 3.5 / 100}}" readonly="readonly" />
</p>
  <p><strong>Net Fees:</strong><br />
    <input name="netFees" type="text" id="netFees" value="{{(amount * (10 / 100)) + ((amount * (10 / 100)) * 3.5 / 100)}}" readonly="readonly" />
</p>
  <p>
    <input name="agree" type="checkbox" id="agree" value="1" required />
  I agree to terms and conditions </p>
  <p><strong>Time to get the above money is:</strong> 1 day to 3 months depending on market conditions
  </p>
  
<p>
  <label>
    <input type="submit" name="Submit" value="Proceed To Step 2" />
  </label>
  <input name="MM_Step" type="hidden" id="MM_Step" value="1" />
</p>
  </fieldset>
</form>