<?php
/*
URLS
city data
http://mkgalaxy.com/horo/api?action=findcitybyid&city_id=100002

nearby cities
http://mkgalaxy.com/horo/api?action=nearby&lat=37.3393857&lng=-121.8949555

cityMatch using lat and lng
http://mkgalaxy.com/horo/api?action=cityMatch&lat=37.3393857&lng=-121.8949555

find city
http://mkgalaxy.com/horo/api?action=findCity&q=san+jose

match
http://mkgalaxy.com/horo/api?action=match&from[dob]=1974-06-05+12:30&from[zone_h]=5&from[zone_m]=30&from[lon_h]=72&from[lon_m]=49&from[lat_h]=18&from[lat_m]=57&from[dst]=0&from[lon_e]=1&from[lat_s]=0&to[dob]=2016-01-08+17:30&to[zone_h]=8&to[zone_m]=0&to[lon_h]=121&to[lon_m]=13&to[lat_h]=37&to[lat_m]=48&to[dst]=1&to[lon_e]=0&to[lat_s]=0

*/
$hideLayout = true;
$return = array();
$return['success'] = 1;
try {

  
  $action = isset($_GET['action']) ? $_GET['action'] : null;
  if (empty($action)) {
    throw new Exception('missing action');
  }

  switch ($action) {
    case 'findcitybyid':
      if (empty($_GET['city_id'])) {
        throw new Exception('city_id is missing'); 
      }
      $return['data'] = findCity($_GET['city_id']);
      break;
    case 'nearby':
      if (empty($_GET['lat'])) {
        throw new Exception('lat is missing'); 
      }
      if (empty($_GET['lng'])) {
        throw new Exception('lng is missing'); 
      }
      $return['data'] = nearbyCities($_GET['lat'], $_GET['lng']);
      break;
    case 'cityMatch':
      if (empty($_GET['lat'])) {
        throw new Exception('lat is missing'); 
      }
      if (empty($_GET['lng'])) {
        throw new Exception('lng is missing'); 
      }
      $data = array_values(nearbyCities($_GET['lat'], $_GET['lng'], $radius=100, $order='distance', $limit=1));
      if (empty($data[0])) {
        throw new Exception('no city found');
      }
      $return['data'] = $return['data'] = findCity($data[0]['cty_id']);
      break;
    case 'findCity':
      if (empty($_GET['q'])) {
        throw new Exception('search Term q is missing'); 
      }
      $return['data'] = findCitySearch($_GET['q']);
      break;
    case 'match':
      if (empty($_GET['from'])) {
        throw new Exception('from is missing'); 
      }
      if (empty($_GET['to'])) {
        throw new Exception('to is missing'); 
      }
      $return['data'] = match($_GET['from'], $_GET['to']);
      break;
    
  }
  
} catch (Exception $e) {
  $return['success'] = 0;
  $return['error'] = 1;
  $return['errorMessage'] = $e->getMessage();
}
$return['get'] = $_GET;
$return['actions'] = array('findcitybyid' => array('city_id'), 'nearby' => array('lat', 'lng'), 'cityMatch' => array('lat', 'lng'), 'match' => array('from', 'to'), 'findCity' => array('q'));
echo json_encode($return);