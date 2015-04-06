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
<p>Your payment was successfully received by us. We will get back to you in 24 to 48 hours. You will be notified by email if you received city moderation for this city. You can buy moderation for other cities as well.</p>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>