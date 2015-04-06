<?php

include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/jobs/categoriesJobs.php');
if (empty($_GET['job_id'])) {
  header("Location: ".$currentURL.'/jobs');
  exit;
}
$pageTitle = 'View Jobs';
try {
    $error = '';
    $result = array();
    if (empty($_GET['job_id'])) {
      throw new Exception('job not found');
    }
    $url = APIHTTPPATH.'/help/jobs/detail?job_id='.$_GET['job_id'];
    $return = curlget($url);
    $data = json_decode($return, 1);
    if ($data['success'] == 0) {
      $error = $data['msg'];
    } else {
      $result = $data['data'];
      $pageTitle = $result['title'];
      $to_uid = $result['uid'];
    }
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>
<div class="row">
        <div class="col-lg-12">
            <div class="bs-component">
              <ul class="breadcrumb">
                <li><a href="<?php echo $currentURL; ?>">Home</a></li>
                <li><a href="<?php echo $currentURL; ?>/jobs/new">Post</a></li>
                <li><a href="<?php echo $currentURL; ?>/jobs/view?category=&city=">View</a></li>
                <li class="active">Detail</li>
              </ul>
            </div>
        </div>
</div>
<script type="text/javascript" src="<?php echo HTTPPATH.'/scripts/map.js'; ?>"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAALUsWUxJrv3zXUNCu0Kas1RQFv3AXA4OcITNh-zHKPaxsGpzj0xQrVCwfLY_kBbxK-4-gSU4j3c7huQ"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<div style="padding:5px;"><a href="<?php echo $currentURL; ?>/jobs/detail?job_id=<?php echo $_GET['job_id']; ?>&view=road">Road Map</a> | <a href="<?php echo $currentURL; ?>/jobs/detail?job_id=<?php echo $_GET['job_id']; ?>&view=street">Street View</a></div>
		<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
		<div id="mapCanvascitydetailstreetview" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } else { ?>
		<div id="mapCanvas" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } ?>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $result['job_lat']; ?>';
var longitude = '<?php echo $result['job_lng']; ?>';
<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
initializeGoogleStreetMap('mapCanvascitydetailstreetview', latitude, longitude);
<?php } else { ?>
initializeGoogleMap('mapCanvas');
<?php } ?>
</script>
<br />
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
      <div class="alert alert-dismissable alert-warning">
        <h4>Compensation</h4>
        <p><?php echo $result['compensation']; ?></p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
        <h4>Description</h4>
        <p><?php echo $result['description']; ?></p>
    </div>
  </div>
</div>
<br><br>
<?php 
include(SITEDIR.'/libraries/messages/new.php');
?>
<?php
//pr($result);
?>