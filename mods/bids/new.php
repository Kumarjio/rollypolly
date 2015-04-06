<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Create New Bid';

$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      $data = array();
      $data['residential_id'] = guid();
      $data['uid'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['residential_created_dt'] = date('Y-m-d H:i:s');
      $data['residential_updated_dt'] = date('Y-m-d H:i:s');
      $data['residential_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['residential_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : $data['address'];
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $result = $modelGeneral->addDetails('z_residential', $data, $_SESSION['user']['id']);
      $error = 'Your residential place is submitted successfully.';
      unset($_POST);
      header("Location: ".$currentURL."/residential/new?msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>
<h3>Add New Residential Place</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."/residential/new"; ?>" onSubmit="return validateFrm();">
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
                            <strong>History Title:</strong> <br />
                            <input type="text" name="history_title" id="history_title" class="inputText" value="<?php echo !empty($_POST['history_title']) ? $_POST['history_title'] : ''; ?>" placeholder="Enter History Title" />
                          </div>
                          <div class="form-group">
                            <strong>History Date:</strong> <br />
                            <input type="text" name="history_date" id="history_date" class="inputText" value="<?php echo !empty($_POST['history_date']) ? $_POST['history_date'] : ''; ?>" placeholder="Enter History Date" />
                          </div>
                          <div class="form-group">
                              <strong>History Description:</strong> <br />
                              <textarea name="history_description" rows="5" id="history_description" class="inputText" placeholder="Enter History Description"><?php echo !empty($_POST['history_description']) ? $_POST['history_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>History Visibility:</strong> <br />
                            <input type="radio" name="is_private" id="is_private_1" value="1" <?php echo !empty($_POST['is_private']) ? 'checked' : ''; ?> /> Private 
                            <input type="radio" name="is_private" id="is_private_2" value="0" <?php echo (empty($_POST['is_private']) || !isset($_POST['is_private'])) ? 'checked' : ''; ?> /> Public
                          </div>
                          <div class="form-group">
                            <strong>History Relation:</strong> <br />
                            <input type="radio" name="related_to_me" id="related_to_me_1" value="0" <?php echo (empty($_POST['related_to_me'])) ? 'checked' : ''; ?> /> History is Not Related to Me 
                            <input type="radio" name="related_to_me" id="related_to_me_2" value="1" <?php echo (!empty($_POST['related_to_me'])  || !isset($_POST['related_to_me'])) ? 'checked' : ''; ?> /> History is Related to Me 
                          </div>
                          <div class="form-group" id="history_imgs">
                              <strong>History Image:</strong> <br />
                              <input type="text" name="history_image[]" class="inputText" placeholder="Enter Image URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="history_img_add" id="history_img_add" onClick="addhistoryimg()" value="Add More Image URL" />
                          </div>
                          <div class="form-group" id="history_vids">
                              <strong>History Video:</strong> <br />
                              <input type="text" name="history_video[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="history_vids_add" id="history_vids_add" onClick="addhistoryvideo()" value="Add More Video URL" />
                          </div>
                          <div class="form-group" id="history_web">
                              <strong>History Video:</strong> <br />
                              <input type="text" name="history_urls[]" class="inputText" placeholder="Enter Web URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="history_web_add" id="history_web_add" onClick="addhistoryurls()" value="Add More Web URL" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Residential Place" class="inputText">
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>