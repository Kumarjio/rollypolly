<?php
if (empty($_GET['q'])) {
  header("Location: ".HTTPPATH."/locations/country");
  exit;
}
$q = $_GET['q'];
$pageTitle = 'Search Results';
$url = APIHTTPPATH.'/locations/findcity.php?q='.urlencode($q);
$cityList = curlget($url);
$cityList = json_decode($cityList, 1);
include(SITEDIR.'/libraries/addresses/nearby.php');
?>
<h3>Choose City</h3>
<!-- body -->
<?php if (empty($cityList) || (!empty($cityList) && isset($cityList['success'])  && $cityList['success'] == 0)) { ?>
<div>No Cities Found.</div>
<?php } else { ?>
  <ul>
  <?php foreach ($cityList as $cities) { ?>
  <li><a href="<?php echo makecityurl($cities['id'], $cities['city']); ?>"><?php echo $cities['city']; ?>, <?php echo $cities['statename']; ?>, <?php echo $cities['countryname']; ?></a></li>
  <?php } ?>
  </ul>
<?php } ?>