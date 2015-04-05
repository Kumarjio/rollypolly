<?php
if (empty($_GET['id'])) {
  header("Location: ".HTTPPATH);
  exit;
}
$city_id = $_GET['id'];
$globalCity = findCity($city_id);
$pageTitle = 'City "'.$globalCity['city'].'" is not enabled';

$query = 'select c.*, s.name as state, co.name as country from geo_city_owners as o LEFT JOIN geo_cities as c ON o.cty_id = c.cty_id LEFT JOIN geo_states as s ON s.sta_id = c.sta_id LEFT JOIN geo_countries as co ON co.con_id = c.con_id ORDER BY country, state, c.name';
$results = $modelGeneral->fetchAll($query, array(), (60*60));
//include(SITEDIR.'/libraries/addresses/nearby.php');
?>
<h2><?php echo $pageTitle; ?></h2>
<p>Currently there is no manager for "<?php echo $globalCity['city']; ?>" city.</p>
<p>If you like to manage this city then email us at <a href="mailto:bids@mkgalaxy.com">bids@mkgalaxy.com</a> with following details:</p>
<ul>
  <li>City name: <?php echo $globalCity['city']; ?></li>
  <li>City ID: <?php echo $globalCity['id']; ?></li>
  <li>Your Name,</li>
  <li>Your Google Email</li>
  <li>Your address (send us the proof of address). You can only manage a city if you live in that city.</li>
</ul>
<p>Following are the responsibilities of manager:</p>
<ul>
  <li>You will be managing all the activities in that city.</li>
  <li>You will be responsible for providing all the products and services in that city.</li>
  <li>You will be earning in different ways (that will be emailed to you)</li>
</ul>
<p><?php //You need to pay $100 as yearly fee to manage the city and you will get opportunity to earn some percentage of profit from that city (this will be discussed through email). ?>
</p>
<h3>Currently Managed Cities</h3>
<ul>
  <?php foreach ($results as $details) { ?>
  <li><a href="<?php echo makecityurl($details['cty_id'], $details['name']); ?>"><?php echo $details['country']; ?>, <?php echo $details['state']; ?>, <?php echo $details['name']; ?></a></li>
  <?php } ?>
</ul>