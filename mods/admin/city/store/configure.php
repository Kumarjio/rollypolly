<?php
try {
check_login();

include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}
$pageTitle = $globalCity['name'].' Store Configuration';

$params = array();
$params['where'] = sprintf(' AND store_city_id = %s', $modelGeneral->qstr($globalCity['id']));
$details = $modelGeneral->getDetails('my_store', 0, $params);
$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];
if (empty($details) || empty($details[0])) {
  $d = array();
  $d['store_city_id'] = $globalCity['id'];
  $d['store_created_dt'] = date('Y-m-d H:i:s');
  $d['store_updated_dt'] = date('Y-m-d H:i:s');
  $d['store_status'] = 0;
  $modelGeneral->addDetails('my_store', $d);
  $details = $modelGeneral->getDetails('my_store', 0, $params);
}
$details = $details[0];
if (empty($_POST)) {
  if (!empty($details['delivery_options'])) {
    $details['delivery_options'] = json_decode($details['delivery_options'], 1);
  }
  $_POST = $details;
  $_POST['address'] = !empty($_POST['owner_address']) ? $_POST['owner_address'] : '';
  $_POST['address2'] = !empty($_POST['owner_address2']) ? $_POST['owner_address2'] : '';
}
if (!empty($_POST['owner_lat']) && !empty($_POST['owner_lng'])) {
  $latitude = $_POST['owner_lat'];
  $longitude = $_POST['owner_lng'];
}

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}


if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];

      $data = array();
      $data['owner_name'] = !empty($_POST['owner_name']) ? $_POST['owner_name'] : '';
      $data['store_status'] = !empty($_POST['store_status']) ? $_POST['store_status'] : 0;
      $data['owner_email_address'] = !empty($_POST['owner_email_address']) ? $_POST['owner_email_address'] : '';
      $data['owner_phone'] = !empty($_POST['owner_phone']) ? $_POST['owner_phone'] : '';
      $data['delivery_charge'] = !empty($_POST['delivery_charge']) ? $_POST['delivery_charge'] : '';
      $data['delivery_options'] = !empty($_POST['delivery_options']) ? json_encode($_POST['delivery_options']) : '';
      $data['delivery_details'] = !empty($_POST['delivery_details']) ? $_POST['delivery_details'] : '';
      $data['store_updated_dt'] = date('Y-m-d H:i:s');
      $data['owner_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['owner_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['owner_address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['owner_address2'] = !empty($_POST['address2']) ? $_POST['address2'] : $data['owner_address'];
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';

      $where = sprintf('store_city_id = %s', $modelGeneral->qstr($globalCity['id']));
      $result = $modelGeneral->updateDetails('my_store', $data, $where);
      $error = 'Your Store Details updated successfully.';
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}


include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');
?>
<div class="row">
  <div class="col-lg-12">
    <h3><?php echo $pageTitle; ?></h3>
  </div>
</div>
<form name="form1" id="form1" method="post" action="">
<?php if (!empty($error)) { echo '<div class="row"><div class="col-md-12"><div class="error">'.$error.'</div></div></div>'; } ?>
<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLocation"><span class="glyphicon glyphicon-file">
                            </span>Store Location</a>
                        </h4>
                    </div>
                    <div id="collapseLocation" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php
                                include(SITEDIR.'/libraries/addresses/addressGrabberBody2.php');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                            </span>Store Details</a>
                        </h4>
                    </div>
                    <div id="collapseDetail" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="form-group">
                            <strong>Owner Name:</strong> <br />
                            <input name="owner_name" type="text" class="inputText" id="owner_name" placeholder="Enter Owner Name" value="<?php echo !empty($_POST['owner_name']) ? $_POST['owner_name'] : ''; ?>" maxlength="200" required />
                          </div>
                          <div class="form-group">
                            <strong>Owner Paypal Email:</strong> <br />
                            <input type="text" name="owner_email_address" id="owner_email_address" class="inputText" value="<?php echo !empty($_POST['owner_email_address']) ? $_POST['owner_email_address'] : ''; ?>" placeholder="Owner Paypal Email Address" />
                          </div>
                          <div class="form-group">
                            <strong>Owner Phone:</strong> <br />
                            <input type="text" name="owner_phone" id="owner_phone" class="inputText" value="<?php echo !empty($_POST['owner_phone']) ? $_POST['owner_phone'] : ''; ?>" placeholder="Owner Phone" />
                          </div>
                          <div class="form-group">
                            <strong>Fixed Delivery Charges:</strong> <br />
                            <input type="text" name="delivery_charge" id="delivery_charge" class="inputText" value="<?php echo isset($_POST['delivery_charge']) ? $_POST['delivery_charge'] : ''; ?>" placeholder="Delivery Charge" /><br />
                            <b>Note: </b> If delivery charge of any product is not mentioned then this charge will be applied to all the products.
                          </div>
                          <div class="form-group">
                            <strong>Delivery Payment Options:</strong> <br />
                            <input type="checkbox" name="delivery_options[]" id="delivery_options_1" value="COD" <?php echo (isset($_POST['delivery_options']) && in_array('COD', $_POST['delivery_options'])) ? 'checked' : ''; ?> /> Cash On Delivery<br />
                            <input type="checkbox" name="delivery_options[]" id="delivery_options_2" value="Paypal" <?php echo (isset($_POST['delivery_options']) && in_array('Paypal', $_POST['delivery_options'])) ? 'checked' : ''; ?> /> Paypal<br />
                            <input type="checkbox" name="delivery_options[]" id="delivery_options_3" value="Cheque" <?php echo (isset($_POST['delivery_options']) && in_array('Cheque', $_POST['delivery_options'])) ? 'checked' : ''; ?> /> Cheque<br />
                          </div>
                          <div class="form-group">
                            <strong>Delivery Details (e.g. expected delivery time):</strong> <br />
                            <textarea name="delivery_details" id="delivery_details" class="inputText"><?php echo !empty($_POST['delivery_details']) ? $_POST['delivery_details'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>Store Visibility:</strong> <br />
                            <input type="radio" name="store_status" id="store_status_1" value="1" <?php echo (!empty($_POST['store_status'])) ? 'checked' : ''; ?> /> Enabled 
                            <input type="radio" name="store_status" id="store_status_2" value="0" <?php echo (empty($_POST['store_status']) || !isset($_POST['store_status'])) ? 'checked' : ''; ?> /> Disabled
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Update Configuration" class="inputText">
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>