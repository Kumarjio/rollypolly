<?php

define('PERIOD_BIDS', 'Jan 2015 To Dec 2015');

if (!function_exists('curlget')) {
	function curlget($url, $post=0, $POSTFIELDS='') {
		$https = 0;
		if (substr($url, 0, 5) === 'https') {
			$https = 1;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);  
		if (!empty($post)) {
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
		curl_setopt($ch, CURLOPT_COOKIEJAR,COOKIE_FILE_PATH);
		if (!empty($https)) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		$result = curl_exec($ch); 
		curl_close($ch);
		return $result;
	}
}


if (!function_exists('pr')) {
function pr($d){
	echo '<pre>';
	print_r($d);
	echo '</pre>';
}
}




function url_name_v2($name='')
{
	if (empty($name)) {
		return $name;
	}

	$patterns = array();
	$patterns[0] = "/\s+/";
	$patterns[1] = '/[^A-Za-z0-9]+/';
	$replacements = array();
	$replacements[0] = "-";
	$replacements[1] = '-';
	ksort($patterns);
	ksort($replacements);
	$output = preg_replace($patterns, $replacements, $name);
	$output = strtolower($output);
	return $output;
}//end list_name_url()


function findCitySearch($searchTerm) {
  $geo = new Models_Geo();
  $cities = $geo->findCityDetails($searchTerm, 1);
  return $cities;
  
}

function nearbyCities($lat, $lon, $radius=100, $order='distance', $limit=30)
{
  $geo = new Models_Geo();
  $nearby = $geo->get_nearby_cities($lat, $lon, $radius, $order, $limit);
  return $nearby;
}


function findCity($city_id)
{
  $geo = new Models_Geo();
  global $modelGeneral;
  $cityDetails = $geo->cityDetail($city_id);
  if (empty($cityDetails)) {
    return false;
  }
  $cityDetails['sql'] = $geo->sql;
  $lat = !empty($cityDetails['latitude']) ? $cityDetails['latitude']: null;
  $lon = !empty($cityDetails['longitude']) ? $cityDetails['longitude']: null;
  $radius = 30;
  $order = 'distance';
  $limit = 30;
  if (empty($lat) || empty($lon)) {
    $cityDetails['nearby'] = array();
  } else {
    $cityDetails['nearby'] = $geo->get_nearby_cities($lat, $lon, $radius, $order, $limit);
  }
  if (empty($cityDetails['extraDetails'])) {
    $etc = fetchCityXtraDetails($lat, $lon);
    if (!empty($etc)) {
      $d = array();
      $d['extraDetails'] = json_encode($etc);
      $d['lat_h'] = $etc['location']['lat_h'];
      $d['lat_m'] = $etc['location']['lat_m'];
      $d['lat_s'] = $etc['location']['lat_s'];
      $d['lon_h'] = $etc['location']['lon_h'];
      $d['lon_m'] = $etc['location']['lon_m'];
      $d['lon_e'] = $etc['location']['lon_e'];
      $d['zone_h'] = $etc['location']['zone_h'];
      $d['zone_m'] = $etc['location']['zone_m'];
      $d['dst'] = $etc['location']['dst'];
      $d['rawOffset'] = $etc['timezone']['rawOffset'];
      $d['dstOffset'] = $etc['timezone']['dstOffset'];
      $cityDetails = array_merge($cityDetails, $d);
      $where = sprintf('cty_id = %s', $modelGeneral->qstr($cityDetails['id']));
      $modelGeneral->updateDetails('geo_cities', $d, $where);
      $modelGeneral->clearCache($cityDetails['sql']);
    }
  } else {
    $etc = json_decode($cityDetails['extraDetails'], 1);
  }
  $cityDetails['etc'] = $etc;
  $cityDetails['url'] = HTTPPATH.'/city-'.url_name_v2($cityDetails['city']).'-'.$cityDetails['id'];
  $cityDetails['pageTitle'] = $cityDetails['city'].', '.$cityDetails['statename'].', '.$cityDetails['countryname'];
  return $cityDetails;
}

  function fetchCityXtraDetails($lat, $lon)
  {
    $etc = array();
    try {
      if (empty($lat) || empty($lon)) {
        throw new Exception('lat, lon empty');
      }
      $etc['timezone'] = getdetailsonlatlon($lat, $lon);
      if (empty($etc['timezone'])) {
        throw new Exception('empty details');
      }
      if ($etc['timezone']['rawOffset'] != $etc['timezone']['dstOffset']) {
        $etc['location']['dst'] = 1;
      } else {
        $etc['location']['dst'] = 0;
      }
      $etc['dd2dms'] = dd2dms($lat, $lon);
      $etc['location']['lat_h'] = $etc['dd2dms'][2];
      $etc['location']['lat_m'] = $etc['dd2dms'][4];
      $etc['location']['lat_s'] = ($etc['dd2dms'][0] == 'S') ? 1 : 0;
      $etc['location']['lon_h'] = $etc['dd2dms'][3];
      $etc['location']['lon_m'] = $etc['dd2dms'][5];
      $etc['location']['lon_e'] = ($etc['dd2dms'][1] == 'E') ? 1 : 0;
      $zones = makeTime(abs($etc['timezone']['rawOffset']));
      $etc['location']['zone_h'] = $zones[0];
      $etc['location']['zone_m'] = $zones[1];
    } catch (Exception $e) {
      $etc = array();
    }
    return $etc;
  }

  function makeTime($num) {
    $returnnum = array();
    if ($num) {
      $returnnum[0] = (int) $num;
      $num -= (int) $num; 
      $num *= 60;
      $returnnum[1] = (int) $num;
      $num -= (int) $num; 
      $num *= 60;
      $returnnum[2] = (int) $num;
    }
  
    return $returnnum;
  }
  
function getdetailsonlatlon($lat, $lon)
{
  $url = "http://wc5.org/api/v1/fetch.php?timezone=1&lat=".$lat."&lng=".$lon;
  $c = @file_get_contents($url);
  $json = json_decode($c, 1);
  return $json;
}
function dd2dms($lat, $lon)
	{
		$returnArr = array();
		if (substr($lat, 0, 1) == '-') {
			$ddLatVal = substr($lat, 1, (strlen($lat) - 1));
			$ddLatType = 'S';
		} else {
			$ddLatVal = $lat;
			$ddLatType = 'N';
		}
		$returnArr[0] = $ddLatType;
		if (substr($lon, 0, 1) == '-') {
			$ddLongVal = substr($lon, 1, (strlen($lon) - 1));
			$ddLonType = 'W';
		} else {
			$ddLongVal = $lon;
			$ddLonType = 'E';
		}
		$returnArr[1] = $ddLonType;
		// degrees = degrees
		$ddLatVals = explode('.', $ddLatVal);
		$dmsLatDeg = $ddLatVals[0];
		$returnArr[2] = $dmsLatDeg;
		
		$ddLongVals = explode('.', $ddLongVal);
		$dmsLongDeg = $ddLongVals[0];
		$returnArr[3] = $dmsLongDeg;
		
		// * 60 = mins
		$ddLatRemainder  = (float) ("0." . $ddLatVals[1]) * 60;
		$dmsLatMinVals   = explode('.', $ddLatRemainder);
		$dmsLatMin = $dmsLatMinVals[0];
		$returnArr[4] = $dmsLatMin;

		$ddLongRemainder  = (float) ("0." . $ddLongVals[1]) * 60;
		$dmsLongMinVals   = explode('.', $ddLongRemainder);
		$dmsLongMin = $dmsLongMinVals[0];
		$returnArr[5] = $dmsLongMin;
		
		// * 60 again = secs
		$ddLatMinRemainder = ("0." . $dmsLatMinVals[1]) * 60;
		$dmsLatSec   = round($ddLatMinRemainder);
		$returnArr[6] = $dmsLatSec;
    if (empty($dmsLongMinVals[1])) $dmsLongMinVals[1] = 0;
		$ddLongMinRemainder = ("0." . $dmsLongMinVals[1]) * 60;
		$dmsLongSec   = round($ddLongMinRemainder);
		$returnArr[7] = $dmsLongSec;
		return $returnArr;
	}

function makecityurl($city_id, $city)
{
  return HTTPPATH.'/city-'.url_name_v2($city).'-'.$city_id;
}

function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    return $miles; 
}

function iptocity($ip)
{
    define('IPSNIFF_URL', 'http://api.ipinfodb.com/v3/ip-city/?');
    define('IPSNIFF_KEY', '0b3b2f8bd9f606ba8032ef0b9fbe054041788fb0f9d7c21214cd050a9b561845');

    $url = IPSNIFF_URL.'key='.IPSNIFF_KEY.'&ip='.$ip;
    $string = curlget($url);
    //$string = 'OK;;63.150.3.118;US;UNITED STATES;CALIFORNIA;SAN JOSE;95101;37.3169;-121.874;-08:00';
    $tmp = explode(';', $string);
    $arr['status'] = !empty($tmp[0]) ? trim($tmp[0]) : '';
    $arr['text1'] = !empty($tmp[1]) ? trim($tmp[1]) : '';
    $arr['ip'] = !empty($tmp[2]) ? trim($tmp[2]) : '';
    $arr['countrycode'] = !empty($tmp[3]) ? trim($tmp[3]) : '';
    $arr['country'] = !empty($tmp[4]) ? trim($tmp[4]) : '';
    $arr['state'] = !empty($tmp[5]) ? trim($tmp[5]) : '';
    $arr['city'] = !empty($tmp[6]) ? trim($tmp[6]) : '';
    $arr['zip'] = !empty($tmp[7]) ? trim($tmp[7]) : '';
    $arr['lat'] = !empty($tmp[8]) ? trim($tmp[8]) : '';
    $arr['lon'] = !empty($tmp[9]) ? trim($tmp[9]) : '';
    $arr['timezone'] = !empty($tmp[10]) ? trim($tmp[10]) : '';
    $arr['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    $arr['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $arr['referrer'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    return $arr;
}

function check_city_owner($city_id)
{
  $check = is_city_owner($_SESSION['user']['id'], $city_id);
  if (!$check) {
    header("Location: ".HTTPPATH);
    exit;
  }
  return true;
}

function check_state_owner($sta_id)
{
  $check = is_state_owner($_SESSION['user']['id'], $sta_id);
  if (!$check) {
    header("Location: ".HTTPPATH);
    exit;
  }
  return true;
}

function check_country_owner($con_id)
{
  $check = is_country_owner($_SESSION['user']['id'], $con_id);
  if (!$check) {
    header("Location: ".HTTPPATH);
    exit;
  }
  return true;
}

function check_login()
{
  if (empty($_SESSION['user'])) {
    $referralUrl = $_SERVER['REQUEST_URI'];
    $_SESSION['redirectUrl'] = $referralUrl;
    header("Location: ".HTTPPATH.'/users/login');
    exit;
  }
}


function getNearbyCities($nearby=array())
{
  if (empty($nearby)) {
    return false;
  }
  $return = array();
  foreach ($nearby as $value) {
    $return[] = $value['cty_id'];
  }
  $string = implode(',', $return);
  return array($return, $string);
}

function ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] 'ago' ";
}


function guid()
{
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $guid = substr($charid, 0, 8) . '-' .
            substr($charid, 8, 4) . '-' .
            substr($charid, 12, 4) . '-' .
            substr($charid, 16, 4) . '-' .
            substr($charid, 20, 12);
   return $guid;
}


function tstobts($ts)
{
    return $ts * 1000;
}

function btstots($bts)
{
    return $bts / 1000;
}

function viewDisplay($link, $title, $more, $updated, $params=array())
{
  $class = '';
  if (!empty($params['class'])) {
    $class = $params['class'];
  }
  $return = '<a href="'.$link.'" class="list-group-item '.$class.'">
                  <h4 class="list-group-item-heading">'.$title.'</h4>
                  <p class="list-group-item-text">'.$more.' (<strong>Updated:</strong> '.ago(strtotime($updated)).')</p>
                </a>';
   return $return;
}


function userDetails($uid, $cache=1)
{
  //api/help/bid/mybids?uid=
  $url = APIHTTPPATH.'/help/users/detail?uid='.$uid.'&cache='.$cache ;
  $userDetails = curlget($url);
  $userDetails = json_decode($userDetails, 1);
  return $userDetails;
}

function redirect()
{
  header("Location: ".HTTPPATH.'/users/login');
  exit;
}

function check_super_admin()
{
  if (empty($_SESSION['user']['access_level'])) {
    redirect();
  }
  if ($_SESSION['user']['access_level'] !== 'admin') {
    redirect();
  }
  return true;
}


function is_super_admin()
{
  if (empty($_SESSION['user']['access_level'])) {
    return false;
  }
  if ($_SESSION['user']['access_level'] === 'admin') {
    return true;
  }
  return false;
}

function is_city_owner($uid, $city_id)
{
    global $modelGeneral;
    //getting city information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['where'] = 'AND o.cty_id = '.$modelGeneral->qstr($city_id);
    $params['where'] .= 'AND a.uid = '.$modelGeneral->qstr($uid);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $cityOwnerDetails = $modelGeneral->getDetails('geo_city_owners as o LEFT JOIN geo_cities as c ON o.cty_id = c.cty_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($cityOwnerDetails[0])) {
      return true;
    }
    return false;
}

function is_state_owner($uid, $sta_id)
{
    global $modelGeneral;
    //getting city information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['where'] = 'AND o.sta_id = '.$modelGeneral->qstr($sta_id);
    $params['where'] .= 'AND a.uid = '.$modelGeneral->qstr($uid);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $OwnerDetails = $modelGeneral->getDetails('geo_state_owners as o LEFT JOIN geo_states as c ON o.sta_id = c.sta_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($OwnerDetails[0])) {
      return true;
    }
    return false;
}
function is_country_owner($uid, $con_id)
{
    global $modelGeneral;
    //getting city information
    $params = array();
    $params['cacheTime'] = 3600 * 24;
    $params['where'] = ' AND o.con_id = '.$modelGeneral->qstr($con_id);
    $params['where'] .= ' AND a.uid = '.$modelGeneral->qstr($uid);
    $params['where'] .= ' AND o.expiry_date > NOW() AND o.subs_expiry_date > NOW() AND o.status = 1';
    $OwnerDetails = $modelGeneral->getDetails('geo_country_owners as o LEFT JOIN geo_countries as c ON o.con_id = c.con_id LEFT JOIN google_auth as a ON a.uid = o.owner_id', 1, $params);
    if (!empty($OwnerDetails[0])) {
      return true;
    }
    return false;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function check_city_owner_bk($city_id, $user_id)
{
  $Models_Geo = new Models_Geo();
  $ownerDetails = $Models_Geo->getOwnerDetails($city_id, 0);
  if ($ownerDetails['owner_id'] != $user_id) {
    throw new Exception('Invalid Owner');
  }
  if ($ownerDetails['status'] != 1) {
    throw new Exception('Ownership not active');
  }
  if (strtotime($ownerDetails['expiry_date']) < time()) {
    throw new Exception('Ownership expired');
  }
  if (strtotime($ownerDetails['subs_expiry_date']) < time()) {
    throw new Exception('Subscription expired');
  }
}

function updateSettings($uid, $paramsData=array())
{
  if (empty($paramsData)) return false;
  $generalMode = new Models_General();
  $where = sprintf('uid = %s', $generalMode->qstr($uid));
  $generalMode->updateDetails('settings', $paramsData, $where);
  $params = array();
  $params['where'] = 'AND '.$where;
  $settings = $generalMode->getDetails('settings', 0, $params);
  $_SESSION['settings'] = $settings[0];
  return true;
}
function displayText($params=array())
{
  $text = '<div class="col-sm-6 col-md-4">
        <div class="thumbnail"> <img src="http://i2.cdn.turner.com/cnn/dam/assets/140313171146-mean-girls-movie-still-story-top.jpg" alt="...">
          <div class="caption">
            <h3>'.(!empty($params['title']) ? $params['title'] : '').'</h3>
            <p>'.(!empty($params['description']) ? substr($params['description'], 0, 150).'...' : '').'</p>
            <p style="font-size:11px">'.(!empty($params['more1']) ? $params['more1'] : '').'</p>
            <p style="font-size:11px">'.(!empty($params['more2']) ? $params['more2'] : '').'</p>
            <p><a href="'.(!empty($params['link']) ? $params['link'] : 'javascript:;').'" class="btn btn-primary" role="button">View Details</a></p>
          </div>
        </div>
      </div>';
    return $text;
}

function monthString($mon)
{
  $mon = (int) $mon;
  $months = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
  return $months[$mon];
}


function getString($array) {
  $get = $_GET;
  if (isset($get['locationFind'])) unset($get['locationFind']);
  if (isset($get['city_id'])) unset($get['city_id']);
  if (isset($get['q'])) unset($get['q']);
  if (isset($get['p'])) unset($get['p']);
  if (!empty($array)) {
    foreach ($array as $ele) {
      if (isset($get[$ele])) unset($get[$ele]);
    }
  }
  $newparam = array();
  if (!empty($get)) {
    foreach ($get as $k => $v) {
      if (is_array($v)) {
          foreach ($v as $k1 => $v1) {
            $newparam[] = $k.'['.$k1.']='.urlencode($v1);
          }
      } else {
        $newparam[] = $k.'='.urlencode($v);
      }
    }
  }
  $query = '';
  if (count($newparam) != 0) {
    $query = "&" . htmlentities(implode("&", $newparam));
  }
  return $query;
}

function mailtoadmin($subject, $message)
{
  mail('manishkk74@gmail.com', $subject, $message, 'From:<mk@mkgalaxy.com');
}

function is_paid($details)
{
  if ($details['member_type'] == 'paid'
  // && $details['member_expires'] > time()
  ) {
    return true;
  }
  
  return false;
}


function isPaidAnyOneByModuleId($moduleID, $id1, $id2)
{
  global $modelGeneral;
  $query = "SELECT * FROM auto_pre_transactions WHERE module_id = ? AND internal_status = 1 AND (user_id = ? OR user_id = ?)";
  $resultTransaction = $modelGeneral->fetchRow($query, array($moduleID, $id1, $id2), 300);
  return $resultTransaction;
}

function postMessage($approved=0)
{
    if (!empty($_SESSION['user']['id']) && !empty($_POST['MM_Insert']) && $_POST['MM_Insert'] === 'formMessage') {
      if (empty($_POST['to_uid'])) return false;
      if (empty($_POST['id'])) return false;
      if (empty($_POST['message'])) return false;
      if (empty($_POST['MM_Insert'])) return false;
      if (empty($_SESSION['user']['id'])) return false;
      $data = array();
      $data['message_id'] = guid();
      $data['uid'] = $_SESSION['user']['id'];
      $data['to_uid'] = $_POST['to_uid'];
      $data['id'] = $_POST['id'];
      $data['message'] = $_POST['message'];
      $data['message_approved'] = $approved;
      $data['message_date'] = date('Y-m-d H:i:s');
      $data['module_id'] = !empty($_POST['module_id']) ? $_POST['module_id'] : '';
      $modelGeneral = new Models_General();
      $modelGeneral->addDetails('help_messages', $data, $_SESSION['user']['id']);
      mailtoadmin('new message posted', $_POST['message']);
    } 
}


function findHoroInfo($data)
{
  if (empty($data['dob'])) {
    return false;
  }
  $Kundali = new Library_Kundali();
  $fromDate = getDateTime($data['dob']);
  $returnArrFrom = $Kundali->precalculate($fromDate['month'], $fromDate['day'], $fromDate['year'], $fromDate['hour'], $fromDate['minute'], $data['zone_h'], $data['zone_m'], $data['lon_h'], $data['lon_m'], $data['lat_h'], $data['lat_m'], $data['dst'], $data['lon_e'], $data['lat_s']);
  return $returnArrFrom;
}

function match($from, $to)
{
  $d = array();
  $d['from'] = findHoroInfo($from);
  $d['to'] = findHoroInfo($to);
  $Kundali = new Library_Kundali();
  $d['points'] = $Kundali->getpoints($d['from'][9], $d['to'][9]);
  $d['results'] = $Kundali->interpret($d['points']);
  return $d;
}

function matchUserDate($uid, $lat, $lng, $custom_date)
{
  if (empty($uid)) {
    return false;
  }
  if (empty($lat)) {
    return false;
  }
  if (empty($lng)) {
    return false;
  }
  if (empty($custom_date)) {
    return false;
  }
  global $modelGeneral;
  $query = "SELECT * FROM settings LEFT JOIN geo_cities ON settings.birth_city_id = geo_cities.cty_id WHERE 1 AND settings.uid = ?";
  $settingsDetail = $modelGeneral->fetchRow($query, array($uid), 3600);
  if (empty($settingsDetail['dob'])) {
    return false;
  }
  if (empty($settingsDetail['birth_city_id'])) {
    return false;
  }
  $geo = new Models_Geo();
  $nearby = $geo->get_nearby_cities($lat, $lng);
  if (empty($nearby)) {
    continue;
  }
  
  $cityDetails = array();
  foreach ($nearby as $v) {
    $cityDetails = $v;
    break;
  }
  if (empty($cityDetails)) {
    return false;
  }
  $to = $cityDetails;
  if(empty($to['extraDetails'])) {
    $tmp = findCity($to['cty_id']);
    unset($tmp['nearby']);
    unset($tmp['etc']);
    $to = array_merge($to, $tmp);
  }
  if(!empty($settingsDetail['extraDetails']) && !empty($settingsDetail['birth_city_id'])) {
    $tmp = findCity($settingsDetail['birth_city_id']);
    unset($tmp['nearby']);
    unset($tmp['etc']);
    $settingsDetail = array_merge($settingsDetail, $tmp);
  }
  $to['dob'] = $custom_date;
  $matchResult = match($settingsDetail, $to);
  return $matchResult;
}//end matchUserDate

function updatePoints($id, $id2)
{
    if (empty($id) || empty($id2)) {
      return false;
    }
    if ($id == $id2) {
      //return false;
    }

    //$modelGeneral = new Models_General();
    global $modelGeneral;
    $return = array();
    $params = array();
    $t = 60 * 60 * 24 * 365 * 5;
    $params['where'] = sprintf(" AND ((user1 = %s AND user2 = %s) OR (user1 = %s AND user2 = %s))", $modelGeneral->qstr($id), $modelGeneral->qstr($id2), $modelGeneral->qstr($id2), $modelGeneral->qstr($id), $t);
    $check = $modelGeneral->getDetails('user_points', 1, $params);
    if (empty($check)) {
      $modelGeneral->clearCache($modelGeneral->sql);
    }
    if (!empty($check)) {
      foreach ($check as $k => $v) {
        $return[$v['user1']][$v['user2']]['points'] = $v['points'];
        $return[$v['user1']][$v['user2']]['results'] = $v['results'];
      }
      return $return;
    }
    //getting points from api
    $params = array();
    $params['where'] = sprintf(" AND (settings.uid = %s OR settings.uid = %s)", $modelGeneral->qstr($id), $modelGeneral->qstr($id2));
    $settingsDetail = $modelGeneral->getDetails('settings LEFT JOIN geo_cities ON settings.birth_city_id = geo_cities.cty_id', 1, $params, $t);
    if (count($settingsDetail) == 1) {
      $settingsDetail[1] = $settingsDetail[0];
    }
    if (count($settingsDetail) == 2) {
        $sql = $modelGeneral->sql;
        if (empty($settingsDetail[0]['extraDetails'])) {
          $tmp = findCity($settingsDetail[0]['cty_id']);
          $settingsDetail[0] = array_merge($settingsDetail[0], $tmp);
        }
        if (empty($settingsDetail[1]['extraDetails'])) {
          $tmp = findCity($settingsDetail[1]['cty_id']);
          $settingsDetail[1] = array_merge($settingsDetail[1], $tmp);
        }
        if (!empty($settingsDetail[0]['dob']) && !empty($settingsDetail[1]['dob']) && !empty($settingsDetail[0]['birth_city_id'])  && !empty($settingsDetail[1]['birth_city_id'])) {
          $Kundali = new Library_Kundali();
          //fetching for first user
          $fromDate = getDateTime($settingsDetail[0]['dob']);
          $toDate = getDateTime($settingsDetail[1]['dob']);
          $returnArrFrom = $Kundali->precalculate($fromDate['month'], $fromDate['day'], $fromDate['year'], $fromDate['hour'], $fromDate['minute'], $settingsDetail[0]['zone_h'], $settingsDetail[0]['zone_m'], $settingsDetail[0]['lon_h'], $settingsDetail[0]['lon_m'], $settingsDetail[0]['lat_h'], $settingsDetail[0]['lat_m'], $settingsDetail[0]['dst'], $settingsDetail[0]['lon_e'], $settingsDetail[0]['lat_s']);
          $returnArrTo = $Kundali->precalculate($toDate['month'], $toDate['day'], $toDate['year'], $toDate['hour'], $toDate['minute'], $settingsDetail[0]['zone_h'], $settingsDetail[1]['zone_m'], $settingsDetail[1]['lon_h'], $settingsDetail[1]['lon_m'], $settingsDetail[1]['lat_h'], $settingsDetail[1]['lat_m'], $settingsDetail[1]['dst'], $settingsDetail[1]['lon_e'], $settingsDetail[1]['lat_s']);
          $pts = $Kundali->getpoints($returnArrFrom[9], $returnArrTo[9]);
          $finalPoints = array('points' => $pts, 'result' => $Kundali->interpret($pts));
          $d = array();
          $d['user1'] = $settingsDetail[0]['uid'];
          $d['user2'] = $settingsDetail[1]['uid'];
          $d['points'] = $finalPoints['points'];
          $d['results'] = $finalPoints['result'];
          $v = array('from' => $returnArrFrom, 'to' => $returnArrTo, 'pts' => $pts, 'result' => $finalPoints);
          $d['details'] = json_encode($v);
          $modelGeneral->addDetails('user_points', $d);
          $return[$d['user1']][$d['user2']]['points'] = $d['points'];
          $return[$d['user1']][$d['user2']]['results'] = $d['results'];

          //fetching for second user
          $fromDate = getDateTime($settingsDetail[1]['dob']);
          $toDate = getDateTime($settingsDetail[0]['dob']);
          $returnArrFrom = $Kundali->precalculate($fromDate['month'], $fromDate['day'], $fromDate['year'], $fromDate['hour'], $fromDate['minute'], $settingsDetail[1]['zone_h'], $settingsDetail[1]['zone_m'], $settingsDetail[1]['lon_h'], $settingsDetail[1]['lon_m'], $settingsDetail[1]['lat_h'], $settingsDetail[1]['lat_m'], $settingsDetail[1]['dst'], $settingsDetail[1]['lon_e'], $settingsDetail[1]['lat_s']);
          $returnArrTo = $Kundali->precalculate($toDate['month'], $toDate['day'], $toDate['year'], $toDate['hour'], $toDate['minute'], $settingsDetail[0]['zone_h'], $settingsDetail[0]['zone_m'], $settingsDetail[0]['lon_h'], $settingsDetail[0]['lon_m'], $settingsDetail[0]['lat_h'], $settingsDetail[0]['lat_m'], $settingsDetail[0]['dst'], $settingsDetail[0]['lon_e'], $settingsDetail[0]['lat_s']);
          $pts = $Kundali->getpoints($returnArrFrom[9], $returnArrTo[9]);
          $finalPoints = array('points' => $pts, 'result' => $Kundali->interpret($pts));
          $d = array();
          $d['user1'] = $settingsDetail[1]['uid'];
          $d['user2'] = $settingsDetail[0]['uid'];
          $d['points'] = $finalPoints['points'];
          $d['results'] = $finalPoints['result'];
          $v = array('from' => $returnArrFrom, 'to' => $returnArrTo, 'pts' => $pts, 'result' => $finalPoints);
          $d['details'] = json_encode($v);
          $modelGeneral->addDetails('user_points', $d);
          $return[$d['user1']][$d['user2']]['points'] = $d['points'];
          $return[$d['user1']][$d['user2']]['results'] = $d['results'];
          $modelGeneral->clearCache($sql);
        }
    }

    return $return;
}

function getDateTime($date)
{
  $return = array();
  $tmp = explode(' ', $date);
  $date = $tmp[0];
  $time = $tmp[1];
  $tmp = explode('-', $date);
  $month = $tmp[1];
  $day = $tmp[2];
  $year = $tmp[0];
  $tmp = explode(':', $time);
  $hour = $tmp[0];
  $minute = $tmp[1];
  $return['day'] = $day;
  $return['month'] = $month;
  $return['year'] = $year;
  $return['hour'] = $hour;
  $return['minute'] = $minute;
  return $return;
}


function convertString($hexString)
{
    $hexLenght = strlen($hexString);
    // only hex numbers is allowed
    if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;
    unset($binString);
    for ($x = 1; $x <= $hexLenght/2; $x++) {
        $binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
    }
    
    return $binString;
} 

function encryptText($text)
{
  require_once 'Crypt/Blowfish.php';
  $bf = new Crypt_Blowfish(ENCRYPTKEY);
  $encrypted = $bf->encrypt($text);
  return bin2hex($encrypted);
}

function decryptText($text)
{
  require_once 'Crypt/Blowfish.php';
  $bf = new Crypt_Blowfish(ENCRYPTKEY);
  $plaintext = $bf->decrypt(convertString(trim($text)));
  return trim($plaintext);
}


if (!function_exists('regexp')) {
	function regexp($input, $regexp, $casesensitive=false)
	{
		if ($casesensitive === true) {
			if (preg_match_all("/$regexp/sU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		} else {
			if (preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		}

		return false;
	}
}
if (!function_exists('urltoword')) {
	function urltoword($url)
	{
		$url = str_replace('-', ' ', $url);
		$url = ucwords(strtolower($url));
		$url = trim($url);
		return $url;
	}
}

function verifyGatewaySignature($proposedSignature, $checkoutId, $amount) {
  return true;
  echo "sig: $proposedSignature, $checkoutId, $amount   {$checkoutId}&{$amount}<br>";
    $amount = number_format($amount, 2);
    echo $signature = hash_hmac("sha1", "{$checkoutId}&{$amount}", $apiSecret);

    return $signature == $proposedSignature;
}

function createNewPost($post, $cURL, $globalCity, $colname_rsModule, $resultModule, $resultModuleFields, $modelGeneral, $tablename, $uid, $approved=0)
{
    $return = array();
    try {
      if (empty($uid)) {
        throw new Exception('user not logged in');
      }
      $latitude = $post['lat'];
      $longitude = $post['lng'];
      $data = $post;
      if (isset($data['MM_Insert'])) unset($data['MM_Insert']);
      if (isset($data['submit'])) unset($data['submit']);
      $data['id'] = guid();
      $data['uid'] = $uid;

      $data['city_id'] = $globalCity['id'];
      $data['module_id'] = $colname_rsModule;
      $data['rc_created_dt'] = date('Y-m-d H:i:s');
      $data['rc_updated_dt'] = date('Y-m-d H:i:s');
      if (!empty($post['lat'])) {
          $data['clatitude'] = $latitude;
          unset($data['lat']);
      }
      if (!empty($post['lng'])) {
          $data['clongitude'] = $longitude;
          unset($data['lng']);
      }
      //encryption
      foreach ($resultModuleFields as $k => $v) {

        //if field is not show then calculate on bases of default value
        if ($v['field_type'] == 'noshow') {
          if (empty($data[$v['field_name']])) {
            if ($v['field_default_value'] === 'current_date_time') {
              $data[$v['field_name']] = date('Y-m-d H:i:s');
            }
          }
        }

        if (isset($data[$v['field_name']]) && $v['encrypted'] == 1) {
          $data[$v['field_name']] = encryptText($data[$v['field_name']]);
        }
      }
      //encryption
      foreach ($post as $k => $v) {
        if (is_array($v)) {
          $post[$k] = !empty($post[$k]) ? array_filter($post[$k]) : array();
          $data[$k] = json_encode($post[$k]);
        }
      }
      if ($approved == 1) {
        $data['rc_approved'] = 1;
      } else {
        $data['rc_approved'] = 0;
        if ($resultModule['paid_module'] == 1 && $resultModule['paid_posting'] == 1) {
            $data['rc_approved'] = 0;
        } else {
          if ($resultModule['approval_needed'] == 0) {
              $data['rc_approved'] = 1;
          }
        }
      }
      $result = $modelGeneral->addDetails($tablename, $data);
      //tag start
      if (!empty($post['title'])) {
        $tmp1 = !empty($post['tags']) ? explode(',', $post['tags']) : array();
        $tmp2 = explode(' ', $post['title']);
        $tmp = array_merge($tmp1, $tmp2);
        $tmp = array_unique($tmp);
        foreach ($tmp as $v) {
          $v = trim($v);
          $d = array();
          $d['id'] = $data['id'];
          $d['tag'] = $v;
          $d['module_id'] = $colname_rsModule;
          $modelGeneral->addDetails('auto_pre_tags', $d);
        }
      }
      //tag ends
      //multiselect
      foreach ($resultModuleFields as $k => $v) {
        if ($v['field_type'] === 'multipleselectbox') {
          //adding category
          if (!empty($post[$v['field_name']])) {
            foreach ($post[$v['field_name']] as $v1) {
              $v1 = trim($v1);
              $d = array();
              $d['id'] = $data['id'];
              $d['category_id'] = $v1;
              $d['col_name'] = $v['field_name'];
              $d['module_id'] = $colname_rsModule;
              $modelGeneral->addDetails('auto_pre_multiselectcats', $d);
            }
          }
        }
      }
      //multiselect
      $return['error'] = 0;
      $return['msg'] = '';
      $return['url'] = $cURL."/auto/confirm?id=".$data['id']."&module_id=".$colname_rsModule."&submit=1&new=1";
  } catch (Exception $e) {
      $error = $e->getMessage();
      $return['error'] = 1;
      $return['msg'] = $error;
  }
  return $return;
}

function add_activity($uid, $type, $url, $cty_id=null, $description=null)
{
  global $modelGeneral;
  $d = array();
  $d['uid'] = $uid;
  $d['activity_date'] = date('Y-m-d H:i:s');
  $d['activity_type'] = $type;
  $d['activity_description'] = $description;
  $d['ip'] = $_SERVER['REMOTE_ADDR'];
  $d['url'] = $url;
  $d['cty_id'] = $cty_id;
  $id = $modelGeneral->addDetails('activities', $d);
  return $id;
}

function currentActivity($type, $url, $cty_id, $description=null)
{
  $uid = '';
  if (!empty($_SESSION['user']['id'])) {
    $uid = $_SESSION['user']['id'];
  } else {
    $uid = null;
  }
  add_activity($uid, $type, $url, $cty_id, $description);
}

function getActivity()
{
  global $modelGeneral;
  $query_rsView = "SELECT a.*, u.name as fullname, u.picture, c.name as city FROM activities as a LEFT JOIN google_auth as u ON a.uid = u.uid LEFT JOIN geo_cities as c ON c.cty_id = a.cty_id ORDER BY a.activity_date DESC";
  $query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, 0, 25);
  $rsView = $modelGeneral->fetchAll($query_limit_rsView, array(), 300);
  return $rsView;
}