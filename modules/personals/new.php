<?php
check_login();

include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/personals/categoriesPersonals.php');
include(SITEDIR.'/libraries/nonce.php');
$pageTitle = 'Add New Post for '.$pageTitle;
$nonce_key = 'personals_posting_';
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
    throw new Exception('Invalid Request');
  }
  if (empty($error)) {
    //submit the form
    $url = APIHTTPPATH.'/help/personals/add';
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
                <li><a href="<?php echo $currentURL; ?>/personals/browse">Personals</a></li>
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
      <p><label for="categories">Choose Category:</label><br>
      <select name="categories" id="categories" style="width:100%">
      <?php foreach ($categoriesPersonals as $key => $value) { ?>
      <option value="<?php echo $key; ?>" <?php if (!empty($_POST['categories']) && $key == $_POST['categories']) echo 'selected'; ?>><?php echo $value; ?></option>
      <?php } ?>
      </select></p>
      <p><label for="age"><strong>Age:</strong></label>
      <input name="age" type="number" id="age" max="100" min="18" step="1"></p>
      <p>
        <label for="body"><strong>Body:</strong></label>
        <select name="other_details[body]" id="body">
        <option value="0" selected="selected">-</option>
        <option value="1">athletic</option>
        <option value="2">average</option>
        <option value="3">big</option>
        <option value="4">curvy</option>
        <option value="5">fit</option>
        <option value="6">heavy</option>
        <option value="7">hwp</option>
        <option value="8">skinny</option>
        <option value="9">thin</option>
        </select>
      </p>
      <p>
        <label for="height"><strong>Height:</strong></label>
        <select name="other_details[height]" id="height">
        <option value="0" selected="selected">-</option>
        <option value="1">  6'8" or &gt;  (203cm)</option>
        <option value="2">  6'7"  (200cm)</option>
        <option value="3">  6'6"  (198cm)</option>
        <option value="4">  6'5"  (195cm)</option>
        <option value="5">  6'4"  (193cm)</option>
        <option value="6">  6'3"  (190cm)</option>
        <option value="7">  6'2"  (187cm)</option>
        <option value="8">  6'1"  (185cm)</option>
        <option value="9">  6'0"  (182cm)</option>
        <option value="10">  5'11" (180cm)</option>
        <option value="11">  5'10" (177cm)</option>
        <option value="12">  5'9"  (175cm)</option>
        <option value="13">  5'8"  (172cm)</option>
        <option value="14">  5'7"  (170cm)</option>
        <option value="15">  5'6"  (167cm)</option>
        <option value="26">  5'5"  (165cm)</option>
        <option value="27">  5'4"  (162cm)</option>
        <option value="28">  5'3"  (160cm)</option>
        <option value="29">  5'2"  (157cm)</option>
        <option value="30">  5'1"  (154cm)</option>
        <option value="31">  5'0"  (152cm)</option>
        <option value="32">  4'11" (149cm)</option>
        <option value="33">  4'10" (147cm)</option>
        <option value="34">  4'9"  (144cm)</option>
        <option value="35">  4'8" or &lt;  (142cm)</option>
        </select>
      </p>
      <p>
        <label for="marital_status"><strong>Marital Status:</strong></label>
        <select name="other_details[marital_status]" id="marital_status">
        <option value="0" selected="selected">-</option>
        <option value="1">widowed</option>
        <option value="2">divorced</option>
        <option value="3">married</option>
        <option value="4">separated</option>
        <option value="5">partnered</option>
        <option value="6">single</option>
        </select>
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