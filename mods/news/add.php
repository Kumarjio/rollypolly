<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Create New News';

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
      if (empty($_POST['item_title'])) {
        throw new Exception('Please fill the title. ');
      }
      if (empty($_POST['item_description'])) {
        throw new Exception('Please fill the description. ');
      }
      $data = array();
      $data['uid'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['item_title'] = !empty($_POST['item_title']) ? $_POST['item_title'] : '';
      $data['item_description'] = !empty($_POST['item_description']) ? $_POST['item_description'] : '';
      $data['item_date'] = !empty($_POST['item_date']) ? $_POST['item_date'].':00' : '';
      $data['item_approved'] = 0;
      $data['item_updated_dt'] = date('Y-m-d H:i:s');
      $data['item_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['item_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $_POST['item_image'] = !empty($_POST['item_image']) ? array_filter($_POST['item_image']) : array();
      $_POST['item_video'] = !empty($_POST['item_video']) ? array_filter($_POST['item_video']) : array();
      $data['item_image'] = json_encode($_POST['item_image']);
      $data['item_video'] = json_encode($_POST['item_video']);
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $result = $modelGeneral->addDetails('z_news', $data, $_SESSION['user']['id']);
      $error = 'Your news is submitted successfully. The post is currently under review and it will go live in some time.';
      unset($_POST);
      header("Location: ".$currentURL."/news/add?msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>
<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/styles/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.datetimepicker.js"></script>
<script language="javascript">
  function validateFrm()
  {
    if (!$('#item_title').val()) {
      return false;
    }
    return true;
  }
</script>
<h3>Add New News</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."/news/add"; ?>" onSubmit="return validateFrm();">
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
                            <strong>New Title:</strong> <br />
                            <input type="text" name="item_title" id="item_title" class="inputText" value="<?php echo !empty($_POST['item_title']) ? $_POST['item_title'] : ''; ?>" placeholder="Enter News Title" />
                          </div>
                          <div class="form-group">
                            <strong>News Date:</strong> <br />
                            <input type="text" name="item_date" id="item_date" class="inputText" value="<?php echo !empty($_POST['item_date']) ? $_POST['item_date'] : ''; ?>" placeholder="Enter News Date" />
                          </div>
                          <div class="form-group">
                              <strong>News Description:</strong> <br />
                              <textarea name="item_description" rows="5" id="item_description" class="inputText" placeholder="Enter News Description"><?php echo !empty($_POST['item_description']) ? $_POST['item_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group" id="item_imgs">
                              <strong>News Image:</strong> <br />
                              <input type="text" name="item_image[]" class="inputText" placeholder="Enter Image URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="item_img_add" id="item_img_add" onClick="addnewsimg()" value="Add More Image URL" />
                          </div>
                          <div class="form-group" id="item_vids">
                              <strong>News Video Links:</strong> <br />
                              <input type="text" name="item_video[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="item_vids_add" id="item_vids_add" onClick="addnewsvideo()" value="Add More Video URL" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New News" class="inputText">
</p>
</form>
<div id="tmpImgs" style="display:none;"><input type="text" name="item_image[]" class="inputText" placeholder="Enter Image URL" value="" /></div>
<div id="tmpVideos" style="display:none;"><input type="text" name="item_video[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" /></div>

<script language="javascript">
  function addnewsimg()
  {
    $('#item_imgs').append($('#tmpImgs').html());
  }
  function addnewsvideo()
  {
    $('#item_vids').append($('#tmpVideos').html());
  }
  $('#item_date').datetimepicker({
    format:'Y-m-d H:i',
});
</script>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>