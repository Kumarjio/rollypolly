<?php
check_login();

include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/housing/categoriesHousing.php');
include(SITEDIR.'/libraries/nonce.php');
$pageTitle = 'Add New Post for '.$pageTitle;
$nonce_key = 'housing_posting_';
//post

$error = '';
if (!empty($_POST['MM_Insert']) && !empty($_SESSION['_nonce'])) {
  try {
    $latitude = $_POST['lat'];
    $longitude = $_POST['lng'];
  if (empty($_POST['title'])) {
    throw new Exception('Please fill the title. ');
  }
  if (empty($_POST['description'])) {
    throw new Exception('Please fill the description. ');
  }
  if (empty($_POST['categories'])) {
    throw new Exception('Please choose category. ');
  }
  if (!ft_nonce_is_valid( $_POST['_nonce'] , $nonce_key.$_SESSION['_nonce'] , $_SESSION['user']['id'] )) {
    throw new Exception('Invalid Request, Please try again');
  }
  if (empty($error)) {
    //submit the form
    $url = APIHTTPPATH.'/help/housing/add';
    $params = $_POST;
    $params['uid'] = $_SESSION['user']['id'];
    $params['city_id'] = $city_id;
    $POSTFIELDS = http_build_query($params);
    $return = curlget($url, 1, $POSTFIELDS);
    $data = json_decode($return, 1);
    if ($data['success'] == 0) {
      $error = $data['msg'];
    } else {
      unset($_POST);
      $error = $data['data']['confirm'];
      unset($_SESSION['_nonce']);
    }
  }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

if (empty($_SESSION['_nonce'])) {
  $str = md5(time().'-'.$_SESSION['user']['id']);
  $_SESSION['_nonce'] = substr($str, -12, 10);
}
//post ends
include(SITEDIR.'/libraries/addresses/addressGrabberHead.php');
?>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.steps/jquery.steps.min.js"></script>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css"/>
<link href="<?php echo HTTPPATH; ?>/styles/wizardMod.css" rel="stylesheet"/>
          
<div class="row">
          <div class="col-lg-12">
            <div class="bs-component">
              <ul class="breadcrumb">
                <li><a href="<?php echo $currentURL; ?>">Home</a></li>
                <li><a href="<?php echo $currentURL; ?>/housing/browse">Housing</a></li>
                <li class="active">New</li>
              </ul>
            </div>
        </div>
</div>
<form name="form1" id="form1" method="post">
<div class="error"><?php echo $error; ?></div>
<div id="wizard">
    <?php include(SITEDIR.'/modules/common/location.php'); ?>
    <h1>Category</h1>
    <div>
      <p><label for="categories"><strong>Choose Category:</strong></label>
        <br>
      <select name="categories" id="categories" style="width:100%">
      <?php foreach ($categoriesHousing as $key => $value) { ?>
      <option value="<?php echo $key; ?>" <?php if (!empty($_POST['categories']) && $key == $_POST['categories']) echo 'selected'; ?>><?php echo $value; ?></option>
      <?php } ?>
      </select></p>
      <p>
        <label for="sqft"><strong>SqFt:</strong></label>
        <input type="text" name="sqft" id="sqft" size="10">
        <label for="rent"><strong>Rent ($):</strong></label>
        <input type="text" name="rent" id="rent" size="10">
        <label for="avail_on"><strong>
        Available On:</strong></label>
        <input type="text" name="avail_on" id="avail_on" size="10">
      </p>
      <p>
        <label for="br"><strong>Bedrooms:</strong></label>
        <select name="br" id="br">
        </select>
        <label for="ba"><strong>Bathrooms:</strong></label>
        <select name="ba" id="ba">
        </select>
        <label for="housing_type"><strong>Housing Type:</strong></label>
        <select name="housing_type" id="housing_type">
        </select>
      </p>
      <p>
        <label for="laundry"><strong>Laundry:</strong></label>
        <select name="laundry" id="laundry">
        </select>
        <label for="parking"><strong>Parking:</strong></label>
        <select name="parking" id="parking">
        </select>
      </p>
      <p>
        <input name="wheelchair_accessible" type="checkbox" id="wheelchair_accessible" value="1">
        <label for="wheelchair_accessible"><strong>WheelChair Accessible 
          </strong></label>
          <input name="no_smoking" type="checkbox" id="no_smoking" value="1">
          <label for="no_smoking"><strong>No Smoking</strong></label>
          <br>
          <input name="furnished" type="checkbox" id="furnished" value="1">
          <label for="furnished"><strong>Furnished</strong></label>
          <input name="cats" type="checkbox" id="cats" value="1">
          <label for="cats"><strong>Cats</strong></label>
          <input name="dogs" type="checkbox" id="dogs" value="1">
          <label for="dogs"><strong>Dogs</strong></label>
      </p>
      <p>&nbsp;</p>
    </div>
    <h1>Details</h1>
    <div>
      <?php 
      include(SITEDIR.'/modules/common/formelements.php');
      ?>
    </div>
    <?php include(SITEDIR.'/modules/common/images.php'); ?>
</div>
      <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1" />
      <?php ft_nonce_create_form_input( $nonce_key.$_SESSION['_nonce'] , $_SESSION['user']['id'] ); ?>
</form>
<?php include(SITEDIR.'/modules/common/formsubmission.php'); ?>