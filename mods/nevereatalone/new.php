<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Create New NeverEatAlone Meeting';

$restaurantType = array(1 => 'Indian Restaurant', 'Chinese Restaurant', 'American Restaurant', 'Thai Restaurant');

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
      if (empty($_POST['history_title'])) {
        throw new Exception('Please fill the title. ');
      }
      if (empty($_POST['history_description'])) {
        throw new Exception('Please fill the description. ');
      }
      $data = array();
      $data['history_id'] = guid();
      $data['uid'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['history_title'] = !empty($_POST['history_title']) ? $_POST['history_title'] : '';
      $data['history_description'] = !empty($_POST['history_description']) ? $_POST['history_description'] : '';
      $data['is_private'] = !empty($_POST['is_private']) ? $_POST['is_private'] : 0;
      $data['history_date'] = !empty($_POST['history_date']) ? $_POST['history_date'].':00' : '';
      $data['history_approved'] = 0;
      if (!empty($data['is_private'])) {
        $data['history_approved'] = 1;
      }
      $data['history_updated_dt'] = date('Y-m-d H:i:s');
      $data['history_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['history_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['related_to_me'] = !empty($_POST['related_to_me']) ? $_POST['related_to_me'] : 0;
      $_POST['history_image'] = !empty($_POST['history_image']) ? array_filter($_POST['history_image']) : array();
      $_POST['history_video'] = !empty($_POST['history_video']) ? array_filter($_POST['history_video']) : array();
      $_POST['history_urls'] = !empty($_POST['history_urls']) ? array_filter($_POST['history_urls']) : array();
      $data['history_image'] = json_encode($_POST['history_image']);
      $data['history_video'] = json_encode($_POST['history_video']);
      $data['history_urls'] = json_encode($_POST['history_urls']);
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $result = $modelGeneral->addDetails('z_history', $data, $_SESSION['user']['id']);
      if (!empty($data['is_private']))
        $error = 'Your history is submitted successfully.';
      else
        $error = 'Your history is submitted successfully. The post is currently under review and it will go live in some time.';
      unset($_POST);
      header("Location: ".$currentURL."/history/new?msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>
<!-- Date time picker -->
<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/styles/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.datetimepicker.js"></script>
<!-- Date time picker -->
<script language="javascript">
  function validateFrm()
  {
    if (!$('#history_title').val()) {
      return false;
    }
    return true;
  }
</script>
<h3>Add New NeverEatAlone Meeting</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."/nevereatalone/new"; ?>" onSubmit="return validateFrm();">
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
                            <strong>Restaurant Name:</strong> <br />
                            <input type="text" name="title" id="title" class="inputText" value="<?php echo !empty($_POST['title']) ? $_POST['title'] : ''; ?>" placeholder="Enter Title" />
                          </div>
                          <div class="form-group">
                            <strong>Meeting Start Date:</strong> <br />
                            <input type="text" name="start_date" id="start_date" class="inputText" value="<?php echo !empty($_POST['start_date']) ? $_POST['start_date'] : ''; ?>" placeholder="Enter Start Date" />
                          </div>
                          <div class="form-group">
                            <strong>Meeting End Date:</strong> <br />
                            <input type="text" name="end_date" id="end_date" class="inputText" value="<?php echo !empty($_POST['end_date']) ? $_POST['end_date'] : ''; ?>" placeholder="Enter End Date" />
                          </div>
                          <div class="form-group">
                              <strong>Meeting Description:</strong> <br />
                              <textarea name="description" rows="5" id="description" class="inputText" placeholder="Enter History Description"><?php echo !empty($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>Restaurant Category:</strong> <br />
                            <select name="category" id="category">
                            <option value="">Select Restaurant Category</option>
                            <?php foreach ($restaurantType as $key => $type) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
                            <?php } ?>
                            </select>
                            <input type="text" name="category" id="category" class="inputText" value="<?php echo !empty($_POST['title']) ? $_POST['title'] : ''; ?>" placeholder="Enter Title" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Meeting" class="inputText">
</p>
</form>
<script language="javascript">
  //date time picker
  $('#start_date').datetimepicker({
    format:'Y-m-d H:i',
  });
  $('#end_date').datetimepicker({
    format:'Y-m-d H:i',
  });
  //date time picker
</script>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>