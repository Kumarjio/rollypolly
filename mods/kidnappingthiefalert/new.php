<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Create New Alert';

$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      if (empty($_POST['amber_alert_category'])) {
        throw new Exception('Please choose the category. ');
      }
      if (empty($_POST['amber_alert_title'])) {
        throw new Exception('Please fill the title. ');
      }
      if (empty($_POST['amber_alert_description'])) {
        throw new Exception('Please fill the description. ');
      }
      $data = array();
      $data['uid'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['amber_alert_title'] = !empty($_POST['amber_alert_title']) ? $_POST['amber_alert_title'] : '';
      $data['amber_alert_description'] = !empty($_POST['amber_alert_description']) ? $_POST['amber_alert_description'] : '';
      $data['amber_alert_email'] = !empty($_POST['amber_alert_email']) ? $_POST['amber_alert_email'] : '';
      $data['amber_alert_phone'] = !empty($_POST['amber_alert_phone']) ? $_POST['amber_alert_phone'] : '';
      $data['amber_alert_image'] = !empty($_POST['amber_alert_image']) ? $_POST['amber_alert_image'] : '';
      $data['amber_alert_category'] = !empty($_POST['amber_alert_category']) ? $_POST['amber_alert_category'] : '';
      $data['amber_alert_status'] = 'Pending';
      $data['admin_comments'] = '';
      $data['amber_alert_updated'] = date('Y-m-d H:i:s');
      $data['amber_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['amber_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $result = $modelGeneral->addDetails('z_amber_alert', $data, $_SESSION['user']['id']);
      $error = 'Your post is submitted successfully. The post is currently under review and it will go live in some time.';
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      unset($_POST);
      header("Location: ".$currentURL."/amberalert/new?msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>
<h3>Add New Alert</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."../../amberalert/new"; ?>">
<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLocation"><span class="glyphicon glyphicon-file">
                            </span>Location</a>
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
                            </span>Details</a>
                        </h4>
                    </div>
                    <div id="collapseDetail" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="form-group">
                            <strong>Alert Category:</strong> <br />
                            <select id="amber_alert_category" name="amber_alert_category">
                              <option value="">Choose alert category</option>
                              <option value="Child Kidnapping">Child Kidnapping</option>
                              <option value="Adult Kidnapping">Adult Kidnapping</option>
                              <option value="Animal Kidnapping">Animal (Dogs / Cats / Others) Kidnapping</option>
                              <option value="Item Stolen">Item Stolen</option>
                              <option value="Other">Other</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <strong>Alert Title:</strong> <br />
                            <input type="text" name="amber_alert_title" id="amber_alert_title" class="inputText" value="<?php echo !empty($_POST['amber_alert_title']) ? $_POST['amber_alert_title'] : ''; ?>" placeholder="Enter Alert Title" />
                          </div>
                          <div class="form-group">
                              <strong>Alert Description:</strong> <br />
                              <textarea name="amber_alert_description" rows="5" id="amber_alert_description" class="inputText" placeholder="Enter Alert Description"><?php echo !empty($_POST['amber_alert_description']) ? $_POST['amber_alert_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                              <strong>Alert Image:</strong> <br />
                              <input type="text" name="amber_alert_image" id="amber_alert_image" class="inputText" placeholder="Enter Image URL" value="<?php echo !empty($_POST['amber_alert_image']) ? $_POST['amber_alert_image'] : ''; ?>" />
                          </div>
                          <div class="form-group">
                              <strong>Alert Email:</strong> <br />
                              <input type="text" name="amber_alert_email" id="amber_alert_email" class="inputText" placeholder="Enter Email" value="<?php echo !empty($_POST['amber_alert_email']) ? $_POST['amber_alert_email'] : ''; ?>" />
                          </div>
                          <div class="form-group">
                              <strong>Alert Phone:</strong> <br />
                              <input type="text" name="amber_alert_phone" id="amber_alert_phone" class="inputText" placeholder="Enter Phone" value="<?php echo !empty($_POST['amber_alert_phone']) ? $_POST['amber_alert_phone'] : ''; ?>" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="amber_alert_status" id="amber_alert_status">
  <input type="hidden" name="admin_comments" id="admin_comments">
  <input type="hidden" name="amber_alert_approved" id="amber_alert_approved">
  <input type="hidden" name="amber_alert_updated" id="amber_alert_updated">
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Alert" class="inputText">
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>