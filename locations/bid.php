<?php
check_login();
$pageTitle = 'Create New Bid...';
function getCountry()
{
  $url = APIHTTPPATH.'/locations/countries.php';
  $countryList = curlget($url);
  $countryList = json_decode($countryList, 1);
  return $countryList;
}
function getStates($country)
{
  $url = APIHTTPPATH.'/locations/states.php?id='.$country ;
  $stateList = curlget($url);
  $stateList = json_decode($stateList, 1);
  return $stateList;
}
function getCityList($state)
{
  $url = APIHTTPPATH.'/locations/cities.php?id='.$state ;
  $cityList = curlget($url);
  $cityList = json_decode($cityList, 1);
  return $cityList;
}
function mybids($uid)
{
  //api/help/bid/mybids?uid=
  $url = APIHTTPPATH.'/help/bid/mybids?uid='.$uid ;
  $myBids = curlget($url);
  $myBids = json_decode($myBids, 1);
  return $myBids;
}
function cityBids($uid, $city, $bid)
{
  ///api/help/bid/city?uid=&city=&bid=
  $url = APIHTTPPATH.'/help/bid/city?uid='.$uid.'&city='.$city.'&bid='.$bid ;
  $cityBids = curlget($url);
  $cityBids = json_decode($cityBids, 1);
  return $cityBids;
}
function stateBids($uid, $state, $bid)
{
  ///api/help/bid/state?uid=&state=&bid=
  $url = APIHTTPPATH.'/help/bid/state?uid='.$uid.'&state='.$state.'&bid='.$bid ;
  $stateBids = curlget($url);
  $stateBids = json_decode($stateBids, 1);
  return $stateBids;
}
function countryBids($uid, $country, $bid)
{
  ///api/help/bid/state?uid=&state=&bid=
  $url = APIHTTPPATH.'/help/bid/country?uid='.$uid.'&country='.$country.'&bid='.$bid ;
  $countryBids = curlget($url);
  $countryBids = json_decode($countryBids, 1);
  return $countryBids;
}



if (!empty($_GET['country']) && !empty($_GET['state'])) {
  $countryList = getCountry();
  $stateList = getStates($_GET['country']);
  $cityList = getCityList($_GET['state']);
} else if (!empty($_GET['country'])) {
  $countryList = getCountry();
  $stateList = getStates($_GET['country']);
} else {
  $countryList = getCountry();
}

if (!empty($_GET['bidCountryAmount']) && !empty($_GET['country'])) {
  $bid = countryBids($_SESSION['user']['id'], $_GET['country'], $_GET['bidCountryAmount']);
} else if (!empty($_GET['bidStateAmount']) && !empty($_GET['state'])) {
  $bid = stateBids($_SESSION['user']['id'], $_GET['state'], $_GET['bidStateAmount']);
} else if (!empty($_GET['bidCityAmount']) && !empty($_GET['city'])) {
  $bid = cityBids($_SESSION['user']['id'], $_GET['city'], $_GET['bidCityAmount']);
}

$mybids = mybids($_SESSION['user']['id']);
?>
<p><b>Note: </b> Bid amount are based on monthly basis. i.e. if you won bid for any location then you need to pay that amount for each month till completion of period. After completion of period, you have an option to discontinue or bid more than the highest bid for that location in that period. You can discontinue any time if you like.</p>
<p><b>Note: </b> You cannot change the bid once you submit a bid.</p>
<?php if (!empty($countryList)) { //country display ?>
<!-- body -->
<fieldset><legend>Country</legend>
<form method="get" name="formCountry" id="formCountry" action="<?php echo HTTPPATH; ?>/locations/bid">
<p><select name="country" id="country">
<option value="">Select---</option>
<?php if (!empty($countryList)) {
  foreach ($countryList as $country) {
?>
<option value="<?php echo $country['id']; ?>" <?php if (!empty($_GET['country']) && $_GET['country'] == $country['id']) echo 'selected'; ?>><?php echo $country['country']; ?></option>
<?php
  }
}
?>
</select>
<input type="submit" name="submitCountry" id="submitCountry" value="Show States">
</p>
<p>
<label for="bidAmount">
  Bid Amount ($):</label>
<input name="bidCountryAmount" type="number" id="bidCountryAmount" placeholder="Enter Amount You Wish To Buy" min="10" step="1">
<input type="submit" name="submit_country_bid" id="submit_country_bid" value="Add New Country Bid" onClick="return confirmBid();">
</p>
</form>
</fieldset>
<?php } ?>
<?php if (!empty($countryList) && !empty($stateList)) { //state display ?>
<!-- body -->
<fieldset><legend>State</legend>
<form method="get" name="formState" id="formState" action="<?php echo HTTPPATH; ?>/locations/bid">
<p>
<select name="state" id="state">
<option value="">Select---</option>
<?php if (!empty($stateList)) {
  foreach ($stateList as $state) {
?>
<option value="<?php echo $state['id']; ?>" <?php if (!empty($_GET['state']) && $_GET['state'] == $state['id']) echo 'selected'; ?>><?php echo $state['state']; ?></option>
<?php
  }
}
?>
</select>
<input type="submit" name="submitState" id="submitState" value="Show Cities">
</p>
<p>
<label for="bidAmount">
  Bid Amount ($):</label>
<input name="bidStateAmount" type="number" id="bidStateAmount" placeholder="Enter Amount You Wish To Buy" min="10" step="1">
<input type="submit" name="submit_state_bid" id="submit_state_bid" value="Add New State Bid" onClick="return confirmBid();">
<input name="country" id="statecountry" value="<?php echo $_GET['country']; ?>" type="hidden" />
</p>
</form>
</fieldset>
<?php } ?>
<?php if (!empty($countryList) && !empty($stateList) && !empty($cityList)) { //city display ?>
<!-- body -->
<fieldset><legend>City Details</legend>
<form method="get" name="formCity" id="formCity" action="<?php echo HTTPPATH; ?>/locations/bid">
<p>
<select name="city" id="city">
<option value="">Select---</option>
<?php if (!empty($cityList)) {
  foreach ($cityList as $city) {
?>
<option value="<?php echo $city['id']; ?>" <?php if (!empty($_GET['city']) && $_GET['city'] == $city['id']) echo 'selected'; ?>><?php echo $city['city']; ?></option>
<?php
  }
}
?>
</select>
</p>
<p>
<label for="bidAmount">
  Bid Amount ($):</label>
<input name="bidCityAmount" type="number" id="bidCityAmount" placeholder="Enter Amount You Wish To Buy" min="10" step="1">
<input type="submit" name="submit_city_bid" id="submit_city_bid" value="Add New City Bid" onClick="return confirmBid();">
<input name="country" id="citystatecountry" value="<?php echo $_GET['country']; ?>" type="hidden" />
<input name="state" id="citystate" value="<?php echo $_GET['state']; ?>" type="hidden" />
</p>
</form>
</fieldset>
<?php } ?>
<h3>My Bids</h3>
<p>Here are your bids for period of Jan 2015 To Dec 2015, You will get ownership of particular location if your bid is the highest for particular location.</p>
<?php if ($mybids['success'] == 0) {
  ?>
  <div class="error"><?php echo $mybids['msg']; ?></div>
  <?php
} else {
  foreach ($mybids['data']['result'] as $key => $value) {
    if (empty($value)) continue;
    ?>
<fieldset><legend><?php echo ucwords($key); ?></legend>
  <?php foreach ($value as $k => $details) { ?>
  <div>
    <p><b><?php echo $details['location']['name']; ?><?php if (!empty($details['location']['state'])) echo ', '.$details['location']['state']; ?><?php if (!empty($details['location']['country'])) echo ', '.$details['location']['country']; ?></b>, Bid Amount: $ <?php echo number_format($details['bidDetail']['bid_amount'], 2); ?> /month (<strong>Created Date: </strong><?php echo date('jS M, Y', strtotime($details['bidDetail']['bid_created_dt'])); ?></p>
  </div>
  <?php } ?>
</fieldset>
    <?php
  }
}
//pr($mybids);
?>
<script language="javascript">
function confirmBid()
{
  var y = confirm('Do you really want to submit the bid');
  if (!y) {
    return false;
  }
  return true;
}
</script>