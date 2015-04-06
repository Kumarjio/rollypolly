<?php
$pageTitle = 'Add New Job';
check_login();

include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/jobs/categoriesJobs.php');
include(SITEDIR.'/libraries/nonce.php');
$nonce_key = 'job_posting_';

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
    throw new Exception('Please choose atleast one category. ');
  }
  if (!ft_nonce_is_valid( $_POST['_nonce'] , $nonce_key.$_SESSION['_nonce'] , $_SESSION['user']['id'] )) {
    throw new Exception('Invalid Request');
  }
  if (empty($error)) {
    //submit the form
    $url = APIHTTPPATH.'/help/jobs/add';
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
                <li><a href="<?php echo $currentURL; ?>/jobs/view">Jobs</a></li>
                <li class="active">New</li>
              </ul>
            </div>
        </div>
</div>
<form name="form1" id="form1" method="post">
<div class="error"><?php echo $error; ?></div>
<div id="wizard">
    <h1>Location</h1>
    <div>
      <?php 
      include(SITEDIR.'/libraries/addresses/addressGrabberBody.php');
      ?>
      <br />
      <p><b>Show Address To Users: </b><input type="checkbox" name="showAddress" id="showAddress" value="1" checked></p>
    </div>
    <h1>Category</h1>
    <div>
      <label for="categories">Choose Category:</label><br>
      <select name="categories[]" size="10" multiple="MULTIPLE" id="categories" style="width:100%" onChange="calcCharges()">
      <?php foreach ($categoriesJobs as $key => $value) { ?>
      <option value="<?php echo $key; ?>" <?php if (!empty($_POST['categories']) && in_array($key, $_POST['categories'])) echo 'selected'; ?>><?php echo $value; ?></option>
      <?php } ?>
      </select>
      <input type="hidden" name="totalCost" id="totalCost" />
      <div id="displayCost" style="display:none;">
          Your current total: $ <span id="totalCostString"></span> ($75 per category selected)
      </div>
      <br />
      <a href="<?php echo HTTPPATH; ?>/about/jobPostingCharges" target="_blank">Rules for posting</a><br /><br />
      <b>Other Parameters: </b><br />
      <input type="checkbox" name="telecommuting" id="telecommuting" value="1" /> telecommute<br />
      <input type="checkbox" name="part_time" id="part_time" value="1" /> part-time<br />
      <input type="checkbox" name="contract" id="contract" value="1" /> contract<br />
      <input type="checkbox" name="non_profit" id="non_profit" value="1" /> non-profit<br />
      <input type="checkbox" name="internship" id="internship" value="1" /> internship<br />
      <input type="checkbox" name="disabilities" id="disabilities" value="1" /> disabilities
    </div>
    <h1>Details</h1>
    <div>
      <p>
        <label for="compensation"><strong>Compensation:</strong> [please be as detailed as possible]</label>
        <br>
        <input type="text" name="compensation" id="compensation" style="width:100%" value="<?php if (!empty($_POST['compensation']))  echo $_POST['compensation']; ?>" class="required">
      </p>
      <?php 
      include(SITEDIR.'/modules/common/formelements.php');
      ?>
    </div>
</div>
      <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1" />
      <?php ft_nonce_create_form_input( $nonce_key.$_SESSION['_nonce'] , $_SESSION['user']['id'] ); ?>
</form>
<script>
  $("#wizard").steps({
    transitionEffect: "slideLeft",
    /*
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            $("#form1 .body:eq(" + newIndex + ") label.error").remove();
            $("#form1 .body:eq(" + newIndex + ") .error").removeClass("error");
        }
        $("#form1").validate().settings.ignore = ":disabled,:hidden";
        return $("#form1").valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
        {
            $("#form1").steps("next");
        }
        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
        if (currentIndex === 2 && priorIndex === 3)
        {
            $("#form1").steps("previous");
        }
    },

    onFinishing: function (event, currentIndex)
    {
        $("#form1").validate().settings.ignore = ":disabled";
        return $("#form1").valid();
    },
    */
    onFinished: function (event, currentIndex)
    {
        $('#form1').submit();
    }
  });
  //http://www.jquery-steps.com/Examples#advanced-form
</script>
<script language="javascript">
function calcCharges()
{
  var countryID = '<?php echo $globalCity['con_id']; ?>';
  countryID = parseInt(countryID);
  if (countryID != 223) {
    return false;
  }
  var basePrice = 75;
  var field = $('#categories').val();
  var count = field.length;
  var total = basePrice * count;
  $('#totalCost').val(total);
  $('#totalCostString').html(total);
  $('#displayCost').show();
}
</script>