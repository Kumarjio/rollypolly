<?php
if (empty($_GET['city_id'])) {
  header("Location: ".HTTPPATH);
  exit;
}
$city_id = $_GET['city_id'];
$globalCity = findCity($city_id);
if (empty($globalCity)) {
  header("Location: ".HTTPPATH);
  exit;
}
$pageTitle = $globalCity['pageTitle'];
$currentURL = $globalCity['url'];

//setting cookie
setcookie('redirect_url', $currentURL, (time() + (60*60*24*365)), '/');
$_COOKIE['redirect_url'] = $currentURL;
//setting cookie

$_SESSION['last_location']['url'] = $currentURL;
$_SESSION['last_location']['title'] = $globalCity['city'];
$_SESSION['last_location']['id'] = $city_id;

$cityOwner = false;
$stateOwner = false;
$countryOwner = false;

    //getting city information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['fields'] = '*, a.name as fullname, c.name as name';
    $params['where'] = 'AND o.cty_id = '.$modelGeneral->qstr($globalCity['id']);
    //$params['where'] .= 'AND a.uid = '.$modelGeneral->qstr($_SESSION['user']['id']);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $cityOwnerDetails = $modelGeneral->getDetails('geo_city_owners as o LEFT JOIN geo_cities as c ON o.cty_id = c.cty_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($cityOwnerDetails[0])) {
      $cityOwnerDetails = $cityOwnerDetails[0];
      if (isset($_SESSION['user']['id']) && ($cityOwnerDetails['owner_id'] == $_SESSION['user']['id'] || is_super_admin())) {
        $cityOwner = true;
      }
    }
/*
    //getting state information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['fields'] = '*, a.name as fullname, s.name as name';
    $params['where'] = 'AND s.sta_id = '.$modelGeneral->qstr($globalCity['sta_id']);
    //$params['where'] .= 'AND a.uid = '.$modelGeneral->qstr($_SESSION['user']['id']);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $stateOwnerDetails = $modelGeneral->getDetails('geo_state_owners as o LEFT JOIN geo_states as s ON o.sta_id = s.sta_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($stateOwnerDetails[0])) {
      $stateOwnerDetails = $stateOwnerDetails[0];
      if (isset($_SESSION['user']['id']) && ($stateOwnerDetails['owner_id'] == $_SESSION['user']['id'] || is_super_admin())) {
        $stateOwner = true;
      }
    }

    //getting country information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['fields'] = '*, a.name as fullname, c.name as name';
    $params['where'] = 'AND o.con_id = '.$modelGeneral->qstr($globalCity['con_id']);
    //$params['where'] .= 'AND a.uid = '.$modelGeneral->qstr($_SESSION['user']['id']);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $countryOwnerDetails = $modelGeneral->getDetails('geo_country_owners as o LEFT JOIN geo_countries as c ON o.con_id = c.con_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($countryOwnerDetails[0])) {
      $countryOwnerDetails = $countryOwnerDetails[0];
      if (isset($_SESSION['user']['id']) && ($countryOwnerDetails['owner_id'] == $_SESSION['user']['id'] || is_super_admin())) {
        $countryOwner = true;
      }
    }
*/
//enable following when everything is done
if (empty($cityOwnerDetails)) {
  //header("Location: ".HTTPPATH."/about/citynotfound?id=".$city_id);
  //exit;
}


//building navigation item
$str = '';
$str .= '<div style="font-size:11px">';
$str .= '<b>City: </b>'.$globalCity['city'].'<br />';
$str .= '<b>State: </b>'.$globalCity['statename'].'<br />';
$str .= '<b>Country: </b>'.$globalCity['countryname'].'<br />';
$str .= '<b>Latitude: </b>'.$globalCity['latitude'].'<br />';
$str .= '<b>Longitude: </b>'.$globalCity['longitude'].'<br />';
if (!empty($globalCity['etc']['timezone']['timezoneId'])) {
$str .= '<b>TimeZone: </b>'.$globalCity['etc']['timezone']['timezoneId'].'<br />';
}
if (isset($globalCity['etc']['location']['dst'])) {
$str .= '<b>Daylight Saving: </b>'.(($globalCity['etc']['location']['dst'] == 1) ? 'Yes' : 'No').'<br />';
}
if (!empty($cityOwnerDetails)) {
  $str .= '<b>City Moderator: </b><br />'.$cityOwnerDetails['fullname'].'<br />';
}
if (!empty($stateOwnerDetails)) {
  $str .= '<b>State Moderator: </b><br />'.$stateOwnerDetails['fullname'].'<br />';
}
if (!empty($countryOwnerDetails)) {
  $str .= '<b>Country Moderator: </b><br />'.$countryOwnerDetails['fullname'].'<br />';
}
$str .= '<br />';
if (empty($ownerDetails)) {
  //$str .= '<a href="'.$currentURL.'/city/moderator/apply">Become City Moderator</a>';
}
$str .= '</div>';
$pageDynamicNavigationItem = $str;

//nearby
if (!empty($globalCity['nearby'])) {
$str = '';
foreach ($globalCity['nearby'] as $nearby) {
    $url = HTTPPATH.'/city-'.url_name_v2($nearby['name']).'-'.$nearby['cty_id'];
    $str .= '<a href="'.$url.'">'.$nearby['name'].'</a> ('.$nearby['distance'].' mi)<br />';
}
$pageDynamicNearby = $str;
}
?>