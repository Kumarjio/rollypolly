<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');

if (empty($_POST['category_id'])) $_POST['category_id'] = 1;
if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $data = array();
      $data['module_id'] = MODULE_LAWYER;
      $data['user_id'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';
      $data['details']['description'] = !empty($_POST['description']) ? $_POST['description'] : '';
      $data['details']['image'] = !empty($_SESSION['user']['picture']) ? $_SESSION['user']['picture'] : '';
      $data['details']['charges'] = !empty($_POST['charges']) ? $_POST['charges'] : '';
      if ($data['details']['charges'] < 10) {
        throw new Exception('Minimum charge is $10');
      }
      $data['details']['paypal_email'] = !empty($_POST['paypal_email']) ? $_POST['paypal_email'] : '';
      $data['lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $latitude = $data['lat'];
      $longitude = $data['lng'];
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $data['record_status'] = 1;
      $data['record_updated_date'] = date('Y-m-d H:i:s');
      $data['ifield1'] = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
      $data['ffield1'] = !empty($_POST['charges']) ? $_POST['charges'] : '';
      $data['categories'] = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
      $App_records_add = new App_records_add();
      $result = $App_records_add->execute($data);
      //update settings
      if (!empty($_POST['paypal_email']) && $_POST['paypal_email'] != $_SESSION['settings']['paypal_email_address']) {
          updateSettings($_SESSION['user']['id'], array('paypal_email_address' => $_POST['paypal_email']));
          /*$generalMode = new Models_General();
          $where = sprintf('uid = %s', $generalMode->qstr($_SESSION['user']['id']));
          $generalMode->updateDetails('settings', array('paypal_email_address' => $_POST['paypal_email']), $where);
          $settings = $generalMode->getDetails('settings', 0, $params);
          $_SESSION['settings'] = $settings[0];*/
      }
      $error = $result['confirm']. '. This post is currently under review and it will go live in 24 to 48 hours.';
      unset($_POST);
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}

mysql_select_db($database_connMain, $connMain);
$query_rsCategory = "SELECT * FROM categories WHERE module_id = 2 ORDER BY category ASC";
$rsCategory = mysql_query($query_rsCategory, $connMain) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);

$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>

<h3>Add New Lawyer's Information</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="">
<?php
include(SITEDIR.'/libraries/addresses/addressGrabberBody2.php');
?>

<p><strong>Lawyer's Picture:</strong> <br />
<img src="<?php echo $_SESSION['user']['picture']; ?>" class="imglist" />
</p>
<p><strong>Lawyer's Category:</strong> <br />
  <select name="category_id" id="category_id">
    <option value="">Choose Category</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsCategory['category_id']?>"<?php if (!empty($_POST['category_id'])) { if (!(strcmp($row_rsCategory['category_id'], $_POST['category_id']))) {echo "selected=\"selected\"";} } ?>><?php echo $row_rsCategory['category']?></option>
    <?php
} while ($row_rsCategory = mysql_fetch_assoc($rsCategory));
  $rows = mysql_num_rows($rsCategory);
  if($rows > 0) {
      mysql_data_seek($rsCategory, 0);
	  $row_rsCategory = mysql_fetch_assoc($rsCategory);
  }
?>
  </select>
</p>
<p><strong>Lawyer's Name:</strong> <br />
<input name="title" type="text" required id="title" placeholder="Enter Name" value="<?php echo !empty($_POST['title']) ? $_POST['title'] : ''; ?>" style="width:100%;" />
</p>
<p><strong>Lawyer's Description:</strong> <br />
  <textarea name="description" rows="5" id="description" style="width:100%;" placeholder="Enter Description"><?php echo !empty($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
</p>
<p><strong>Lawyer's Online Consultation Charges:</strong> <br />
<input name="charges" type="text" required id="charges" placeholder="Enter Charge in $" value="<?php echo !empty($_POST['charges']) ? $_POST['charges'] : ''; ?>" style="width:100%;" /><br />
<strong>Note:</strong> This will be 1 month unlimited messages for a particular single case with each client. (20% will be deducted as admin fees)
</p>
<p><strong>Lawyer's Paypal Email:</strong> <br />
<input name="paypal_email" type="email" required id="paypal_email" placeholder="Enter Paypal Email" value="<?php echo !empty($_SESSION['settings']['paypal_email_address']) ? $_SESSION['settings']['paypal_email_address'] : ''; ?>" style="width:100%;" />
</p>
<p><input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Lawyer Information Record">
</p>
</form>
<?php
mysql_free_result($rsCategory);
?>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>