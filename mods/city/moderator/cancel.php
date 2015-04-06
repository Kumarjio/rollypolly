<?php
check_login();
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH);
  exit;
}
include(SITEDIR.'/includes/navLeftSideVars.php');
?>
<h1><?php echo $pageTitle; ?></h1>
<p>Apply For City Moderator</p>
<p>Your payment was cancelled. You can apply again <a href="<?php echo $currentURL; ?>/city/moderator/apply">here</a></p>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>