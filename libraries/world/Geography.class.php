<?php

//$connMainAdodb->debug = true;
class world_Geography
{
	public function countryList()
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_countries order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['con_id'], 'country' => $recordSet->fields['name']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function stateList($country_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_states WHERE con_id = ".$connMainAdodb->qstr($country_id)." order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'state' => $recordSet->fields['name']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function cityList($state_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select * from geo_cities WHERE sta_id = ".$connMainAdodb->qstr($state_id)." order by name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude']);
			$recordSet->MoveNext();
		}
		return $return;
	}

	public function cityDetail($city_id)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = "select geo_cities.*, geo_states.name as statename, geo_countries.name as countryname from geo_cities left join geo_states on geo_cities.sta_id = geo_states.sta_id  left join geo_countries on geo_cities.con_id = geo_countries.con_id WHERE geo_cities.cty_id = ".$connMainAdodb->qstr($city_id)." order by geo_cities.name, geo_states.name, geo_countries.name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude'], 'statename' => $recordSet->fields['statename'], 'countryname' => $recordSet->fields['countryname']);
			$recordSet->MoveNext();
		}
		return $return;
	}
	

	public function findcity($keyword='')
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
    $city = '%'.$keyword.'%';
		$sql = "select geo_cities.*, geo_states.name as statename, geo_countries.name as countryname from geo_cities left join geo_states on geo_cities.sta_id = geo_states.sta_id  left join geo_countries on geo_cities.con_id = geo_countries.con_id WHERE geo_cities.name like ".$connMainAdodb->qstr($city)." order by geo_cities.name, geo_states.name, geo_countries.name";
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return[] = array('id' => $recordSet->fields['cty_id'], 'sta_id' => $recordSet->fields['sta_id'], 'con_id' => $recordSet->fields['con_id'], 'city' => $recordSet->fields['name'], 'latitude' => $recordSet->fields['latitude'], 'longitude' => $recordSet->fields['longitude'], 'statename' => $recordSet->fields['statename'], 'countryname' => $recordSet->fields['countryname']);
			$recordSet->MoveNext();
		}
		return $return;
	}
	
	public function get_nearby_cities($lat, $lon, $radius=30, $order='distance', $limit=30)
	{
		global $connMainAdodb;
		$connMainAdodb->Execute("SET NAMES utf8");
		$sql = sprintf("select *, (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($lat, 'double').")) * SIN(RADIANS(c.latitude)) + COS(RADIANS(".GetSQLValueString($lat, 'double').")) * COS(RADIANS(c.latitude)) * COS(RADIANS(".GetSQLValueString($lon, 'double')." -(c.longitude)))))*60*1.1515,2)) as distance from geo_cities as c WHERE (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($lat, 'double').")) * SIN(RADIANS(c.latitude)) + COS(RADIANS(".GetSQLValueString($lat, 'double').")) * COS(RADIANS(c.latitude)) * COS(RADIANS(".GetSQLValueString($lon, 'double')." -(c.longitude)))))*60*1.1515,2)) <= ".GetSQLValueString($radius, 'int')." ORDER BY ".$order." LIMIT ".$limit);
		$recordSet = $connMainAdodb->CacheExecute(_FUNC_TIME_DAY, $sql);
		$return = array();
		while (!$recordSet->EOF) {
			$return['city_'.$recordSet->fields['cty_id']] = $recordSet->fields;
      $return['city_'.$recordSet->fields['cty_id']]['url'] = $this->makecityurl($recordSet->fields['cty_id'], $recordSet->fields['name']);
			$recordSet->MoveNext();
		}
		return $return;
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
    
        //$email[] = 'auto@wc5.org';
        //$from = 'From: system<system@'._base_domain.'>';
        //$msg = var_export($arr, 1);
        //foreach ($email as $to) {
          //mail2($to, 'IP Check', $msg, $from);
        //}
        
        return $arr;
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
      
    function makecityurl($city_id, $city)
    {
      return '/city-'.$this->url_name_v2($city).'-'.$city_id;
    }

}

?>