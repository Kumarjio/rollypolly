<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
$city_id = $_GET['city_id'];
$globalCity = findCity($city_id);
$pageTitle = $globalCity['pageTitle'];
$currentURL = $globalCity['url'];
//building navigation item
$str = '';
$str .= '<div>';
$str .= '<b>City: </b>'.$globalCity['city'].'<br />';
$str .= '<b>State: </b>'.$globalCity['statename'].'<br />';
$str .= '<b>Country: </b>'.$globalCity['countryname'].'<br />';
$str .= '<b>Latitude: </b>'.$globalCity['latitude'].'<br />';
$str .= '<b>Longitude: </b>'.$globalCity['longitude'].'<br />';
$str .= '<br /></div>';
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