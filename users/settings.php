<?php
check_login();
$pageTitle = 'Settings';
$generalMode = new Models_General();
$params = array();
$params['where'] = sprintf('AND uid = %s', $generalMode->qstr($_SESSION['user']['id']));
if (isset($_POST['submit'])) {
  $data = $_POST;
  if (!empty($data['dob'])) {
    $data['dob'] = $data['dob'].':00';
  }
  unset($data['submit']);
  updateSettings($_SESSION['user']['id'], $data);
  //$where = sprintf('uid = %s', $generalMode->qstr($_SESSION['user']['id']));
  //$generalMode->updateDetails('settings', $data, $where);
  //$settings = $generalMode->getDetails('settings', 0, $params);
  //$_SESSION['settings'] = $settings[0];
}

$settings = $_SESSION['settings'];
//include(SITEDIR.'/libraries/addresses/nearby.php');
?>

<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/styles/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.datetimepicker.js"></script>
<script type='text/javascript' src='<?php echo HTTPPATH; ?>/scripts/autocomplete/jquery.autocomplete.js'></script>
<link href="<?php echo HTTPPATH; ?>/scripts/autocomplete/jquery.autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
$().ready(function() {
 	$("#birth_city").autocomplete("<?php echo APIDIR; ?>/locations/findcityStr.php", {
		minChars: 3,
    max: 200,
		width: 320,
		selectFirst: false,
		formatItem: function(data, i, total) {
			return data[1];
		},
		formatResult: function(data, value) {
			return data[1];
		}
	});

	$("#birth_city").result(function(event, data, formatted) {
		if (data) {
			$('#birth_city_id').val(formatted);
		}
	});
});
</script>

<link href="<?php echo HTTPPATH; ?>/styles/accordian.css" rel="stylesheet" type="text/css">
<h1><?php echo $pageTitle; ?></h1>
<form method="post" action="" name="form1" id="form1">
<div class="row">
    <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Profile</h3>
                </div>
                <div class="panel-body">
                    <p><b>Name: </b> <?php echo $_SESSION['user']['name']; ?></p>
                    <p><b>Email: </b> <?php echo $_SESSION['user']['email']; ?></p>
                    <p><b>Gender: </b> <?php echo $_SESSION['user']['gender']; ?></p>
                    <p><img src="<?php echo $_SESSION['user']['picture']; ?>" class="imglist" /></p>
                </div>
              </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
    <div id="st-accordion" class="st-accordion">
    <ul>
        <li>
            <a href="#">Contact Information<span class="st-arrow">Open or Close</span></a>
            <div class="st-content">
              <p><label for="paypal_email_address">Paypal Email Address:</label><br />
                <input type="text" name="paypal_email_address" id="paypal_email_address" value="<?php echo !empty($settings['paypal_email_address']) ? $settings['paypal_email_address'] : ''; ?>" style="width:100%"></p>
              <p><label for="phone_number">Phone Number:</label><br />
                <input type="text" name="phone_number" id="phone_number" style="width:100%" value="<?php echo !empty($settings['phone_number']) ? $settings['phone_number'] : ''; ?>"></p>
            </div>
        </li>
        <li>
            <a href="#">Personal Information<span class="st-arrow">Open or Close</span></a>
            <div class="st-content">
              <p><label for="dob">Birth Date:</label><br />
                <input type="text" name="dob" id="dob" value="<?php echo !empty($settings['dob']) ? substr($settings['dob'], 0, -3) : ''; ?>" style="width:100%"></p>
              <p><label for="dob">Birth City:</label><br />
                <input type="text" name="birth_city" id="birth_city" style="width:75%" placeholder="Enter Birth City" value="<?php echo !empty($settings['birth_city']) ? $settings['birth_city'] : ''; ?>" />
<input type="hidden" name="birth_city_id" id="birth_city_id" value="<?php echo !empty($settings['birth_city_id']) ? $settings['birth_city_id'] : ''; ?>" /> </p>
              <div class="form-group">
                  <strong>Share Horo Page:</strong> <br />
                  <input type="checkbox" name="share_horo" id="share_horo" class="form-control" value="<?php echo!empty($settings['share_horo']) ? $settings['share_horo'] : 1; ?>" <?php echo empty($settings['share_horo']) ? '' : 'checked'; ?>/>
              </div>
            </div>
        </li>
    </ul>
    <p><input type="submit" name="submit" id="submit" value="Save Information" style="width:100%" /></p>
      </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo HTTPPATH; ?>/scripts/accordian/jquery.accordion.js"></script>
<script type="text/javascript" src="<?php echo HTTPPATH; ?>/scripts/accordian/jquery.easing.1.3.js"></script>
<script type="text/javascript">
$(function() {
  $('#st-accordion').accordion();
});
</script>
<script language="javascript">
  $('#dob').datetimepicker({
    format:'Y-m-d H:i',
    step:5,
    defaultDate:'<?php echo !empty($settings['dob']) ? substr($settings['dob'], 0, -9) : date('Y-m-d'); ?>',
    defaultSelect: true,
    todayButton: true,
    defaultSelect: false,
    allowBlank: true
});
</script>
</form>