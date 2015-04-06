<?php
$pageTitle = '';
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
include(SITEDIR.'/modules/navLeftSideVars.php');
$pageTitle .= ' Businesses';
$city_id = $_GET['city_id'];
?>
<div style="width:100%">
  <div style="float:left; text-align:center">
    <a href="<?php echo $currentURL; ?>/businesses/add"><img src="<?php echo HTTPPATH; ?>/images/plus.png" /> <br />
  Add New Business</a> </div>
  <div style="float:left; text-align:center">
    <a href="<?php echo $currentURL; ?>/businesses/search"><img src="<?php echo HTTPPATH; ?>/images/search.png" /> <br />
  Search Businesses</a> </div>
  <br style="clear:both;" />
  </div>
</div>