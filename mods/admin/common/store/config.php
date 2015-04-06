<?php
$pageTitle = $globalCity['name'].' Store Configuration';


$params = array();
$params['where'] = sprintf(' AND cty_id = %s', $modelGeneral->qstr($globalCity['id']));
$details = $modelGeneral->getDetails('z_store_config', 0, $params);
if (empty($details) || empty($details[0])) {
  $d = array();
  $d['cty_id'] = $globalCity['id'];
  $d['store_created_date'] = date('Y-m-d H:i:s');
  $modelGeneral->addDetails('z_store_config', $d);
  $details = $modelGeneral->getDetails('z_store_config', 0, $params);
}
$details = $details[0];
if (empty($_POST)) {
  $_POST = $details;
}

$latitude = $_POST['store_lat'];
$longitude = $_POST['store_lng'];

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}


if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      $data = array();
      $data['store_name'] = !empty($_POST['store_name']) ? $_POST['store_name'] : '';
      $data['store_description'] = !empty($_POST['store_description']) ? $_POST['store_description'] : '';
      $data['store_status'] = !empty($_POST['store_status']) ? $_POST['store_status'] : 0;
      $data['store_currency'] = isset($_POST['store_currency']) ? $_POST['store_currency'] : 'USD';
      $data['store_currency_display'] = isset($_POST['store_currency_display']) ? $_POST['store_currency_display'] : 'USD';
      $data['owner_paypal_email'] = !empty($_POST['owner_paypal_email']) ? $_POST['owner_paypal_email'] : '';
      $data['store_email'] = !empty($_POST['store_email']) ? $_POST['store_email'] : '';
      $data['store_phone'] = !empty($_POST['store_phone']) ? $_POST['store_phone'] : '';
      $data['delivery_charge'] = !empty($_POST['delivery_charge']) ? $_POST['delivery_charge'] : '';
      $data['store_description'] = !empty($_POST['store_description']) ? $_POST['store_description'] : '';
      
      $data['store_updated_date'] = date('Y-m-d H:i:s');
      
      $data['store_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['store_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';

      $where = sprintf('cty_id = %s', $modelGeneral->qstr($globalCity['id']));
      $result = $modelGeneral->updateDetails('z_store_config', $data, $where);
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
                            <strong>Store Name:</strong> <br />
                            <input name="store_name" type="text" class="inputText" id="store_name" placeholder="Enter Store Name" value="<?php echo !empty($_POST['store_name']) ? $_POST['store_name'] : $globalCity['name'].' Online Home Delivery Store'; ?>" maxlength="200" />
                          </div>
                          <div class="form-group">
                              <strong>Store Description:</strong> <br />
                              <textarea name="store_description" rows="5" id="store_description" class="inputText" placeholder="Enter Store Description"><?php echo !empty($_POST['store_description']) ? $_POST['store_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>Store Currency (e.g. USD or INR):</strong> <br />
                            <input type="text" name="store_currency" id="store_currency" class="inputText" value="<?php echo !empty($_POST['store_currency']) ? $_POST['store_currency'] : ''; ?>" placeholder="Enter Store Currency" />
                          </div>
                          <div class="form-group">
                            <strong>Store Currency Display(e.g. USD or Rs):</strong> <br />
                            <input type="text" name="store_currency_display" id="store_currency_display" class="inputText" value="<?php echo !empty($_POST['store_currency_display']) ? $_POST['store_currency_display'] : ''; ?>" placeholder="Enter Store Currency Display" />
                          </div>
                          <div class="form-group">
                            <strong>Owner Paypal Email:</strong> <br />
                            <input type="text" name="owner_paypal_email" id="owner_paypal_email" class="inputText" value="<?php echo !empty($_POST['owner_paypal_email']) ? $_POST['owner_paypal_email'] : ''; ?>" placeholder="Owner Paypal Email" />
                          </div>
                          <div class="form-group">
                            <strong>Store Email:</strong> <br />
                            <input type="text" name="store_email" id="store_email" class="inputText" value="<?php echo !empty($_POST['store_email']) ? $_POST['store_email'] : ''; ?>" placeholder="Store Email" />
                          </div>
                          <div class="form-group">
                            <strong>Store Phone:</strong> <br />
                            <input type="text" name="store_phone" id="store_phone" class="inputText" value="<?php echo !empty($_POST['store_phone']) ? $_POST['store_phone'] : ''; ?>" placeholder="Store Phone" />
                          </div>
                          <div class="form-group">
                            <strong>Fixed Delivery Charges:</strong> <br />
                            <input type="text" name="delivery_charge" id="delivery_charge" class="inputText" value="<?php echo !empty($_POST['delivery_charge']) ? $_POST['delivery_charge'] : ''; ?>" placeholder="Delivery Charge" /><br />
                            <b>Note: </b> If delivery charge of any product is not mentioned then this charge will be applied to all the products.
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