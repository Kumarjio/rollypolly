<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Edit Residential Place';

$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  header("Location: /");
  exit;
}
$params = array();
$params['where'] = sprintf(' AND residential_id = %s', $modelGeneral->qstr($id));
$details = $modelGeneral->getDetails('z_residential LEFT JOIN google_auth ON z_residential.uid = google_auth.uid', 0, $params);
if (empty($details) || empty($details[0]) || $details[0]['uid'] != $_SESSION['user']['id']) {
  header("Location: ".$currentURL.'/residential/myplace');
  exit;
}
$details = $details[0];
if (empty($_POST)) {
  $_POST = $details;
}
$latitude = $details['residential_lat'];
$longitude = $details['residential_lng'];

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      $data = array();
      $data['residential_id'] = $_GET['id'];
      $data['residential_updated_dt'] = date('Y-m-d H:i:s');
      $data['residential_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['residential_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : $data['address'];
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $where = sprintf('uid = %s AND residential_id=%s', $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_GET['id']));
      $result = $modelGeneral->updateDetails('z_residential', $data, $where);
      $error = 'Your residential place is update successfully.';
      header("Location: ".$currentURL."/residential/edit?id=".$_GET['id']."&msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');

?>
</script>
<h3>Edit Residential Place</h3>
<?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."/residential/edit?id=".$_GET['id']; ?>" onSubmit="return validateFrm();">
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
                
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Update Place" class="inputText">
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>