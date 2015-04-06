<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller
{
	protected $location;
	protected $birthdetails;
	protected $mybirthdetails;

	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header('content-type: application/json; charset=utf-8');
		$this->load->database();
		$this->load->library('Kundali');
		$this->load->driver('cache');
		$this->load->library('email');
/*
		$this->email->from('mkgxy@mkgalaxy.com', 'From Admin');
		$this->email->to('mkgxy@mkgalaxy.com'); 
		
		$this->email->subject('Email at api horo');
		$this->email->message('get: '.var_export($_GET, 1).', post: '.var_export($_POST, 1));
		$this->email->send();
*/
		
		$validurls = array('login', 'register', 'fetchcity', 'fetch', 'match', 'matchDateTime', 'currtmplocation', 'fetchcitymore', 'matchDay', 'matchLatLonDays', 'matchMultiLatLonDays');
		if (!in_array($this->router->method, $validurls)) {
			if (empty($_GET['token'])) {
				echo json_encode(array('error' => 1, 'error_message' => 'Missing Token'));
				exit;
			}
			$content = $this->cache->file->get($_GET['token']);
			if (empty($content)) {
				echo json_encode(array('error' => 1, 'error_message' => 'Invalid Token'));
				exit;
			}
			$this->cache->file->save($_GET['token'], $content, 3600 * 24 * 7);
			$data = json_decode($content, 1);
			$this->user_id = $data['result']['user_id'];
		}
	}

  private function dbConnect()
  {
    $hostname_connMain = "localhost";
    $database_connMain = "consultl_mkgxymain";
    $username_connMain = "consultl_user";
    $password_connMain = "passwords123";
    $connMain = mysql_connect($hostname_connMain, $username_connMain, $password_connMain) or trigger_error(mysql_error(),E_USER_ERROR);
    mysql_select_db($database_connMain, $connMain) or die('could not select db');
    $dsn_connMain = 'mysql:dbname='.$database_connMain.';host='.$hostname_connMain;

    //adodb try
    define('BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))).'/mkgxy');
    $site_path = BASE_DIR;//'/home135/sub004/sc29722-KLXJ';
    include($site_path.'/libraries/adodb/adodb.inc.php');
    
    $ADODB_CACHE_DIR = $site_path.'/cache/ADODB_cache';
    $connMainAdodb = ADONewConnection('mysql');
    $connMainAdodb->Connect($hostname_connMain, $username_connMain, $password_connMain, $database_connMain);
    $this->_connMain = $connMainAdodb;
    $this->_connMain->SetFetchMode(ADODB_FETCH_ASSOC);
    $this->_connMain->Execute("SET NAMES utf8");
    if (!empty($_GET['debug']) && $_GET['debug'] == 1) {
      $this->_connMain->debug = true;
    }
  }

    private function qstr($value)
    {
        return $this->_connMain->qstr($value);
    }

    
    public $sql;

    private function addDetails($tableName, $data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute($tableName, $data, 'INSERT');
      $id = $this->_connMain->Insert_ID();
      return $id;
    }

    private function updateDetails($tableName, $data=array(), $where='')
    {
      if (empty($where)) {
          throw new Exception('could not update');
      }
      $updateSQL = $this->_connMain->AutoExecute($tableName, $data, 'UPDATE', $where);
      return true;
    }

  private function getDetails($tableName, $cache=1, $params=array())
  {
    $where = !empty($params['where']) ? $params['where'] : '';
    $group = !empty($params['group']) ? $params['group'] : '';
    $order = !empty($params['order']) ? $params['order'] : '';
    $fields = !empty($params['fields']) ? $params['fields'] : '*';
    $limit = !empty($params['limit']) ? $params['limit'] : '';
    $cacheTime = !empty($params['cacheTime']) ? $params['cacheTime'] : '300';
    $sql = "SELECT $fields FROM $tableName WHERE 1 $where $group $order $limit";
    $this->sql = $sql;
    if ($cache) {
      $result = $this->_connMain->CacheExecute($cacheTime, $sql);
    } else {
      $result = $this->_connMain->Execute($sql);
    }
    $return = array();
    while (!$result->EOF) {
        $return[] = $result->fields;
        $result->MoveNext();
     }
    return $return;
  }

    private function clearCache($sql)
    {
        $this->_connMain->CacheFlush($sql);
        return true;
    }

	//http://horo.mkgalaxy.com/api/login?email=3@mkgalaxy.com&password=1
	public function login()
	{
		try {
			$email = $this->input->get_post('email');
			$password = $this->input->get_post('password');
			$email = trim($email);
			$password = trim($password);
			$this->load->model('Api_Model');
			$data = $this->Api_Model->find_user($email, $password);
			if (!empty($data)) {
				$key = md5($data['user_id']);
				$data['token'] = $key;
				$this->user_id = $data['user_id'];
				ob_start();
				$this->mybirthdetails();
				$data['birthdetails'] = $this->mybirthdetails[0];
				ob_end_clean();
				$result = array('type' => 'login', 'error' => 0, 'error_message' => '', 'result' => $data);
				$res = json_encode($result);
				$this->cache->file->save($key, $res, 3600 * 24 * 7);
			}
		} catch (Exception $e) {
			$result = array('type' => 'login', 'error' => 1, 'error_message' => $e->getMessage(), 'result' => array());
		}
		echo json_encode($result);
	}
	
	
	//http://horo.mkgalaxy.com/api/login?email=3@mkgalaxy.com&password=1
	public function getUser()
	{
		try {
			$user_id = isset($this->user_id) ? $this->user_id : null;
			if (empty($user_id)) {
				$user_id = $this->input->get_post('user_id');
			}
			$this->load->model('Api_Model');
			$data = $this->Api_Model->find_user_by_id($user_id);
			if (!empty($data)) {
				$key = md5($data['user_id']);
				$data['token'] = $key;
				$this->user_id = $data['user_id'];
				ob_start();
				$this->mybirthdetails();
				$data['birthdetails'] = $this->mybirthdetails[0];
				ob_end_clean();
				$result = array('type' => 'finduserbyid', 'error' => 0, 'error_message' => '', 'result' => $data);
				$res = json_encode($result);
				$this->cache->file->save($key, $res, 3600 * 24 * 7);
			}
		} catch (Exception $e) {
			$result = array('type' => 'finduserbyid', 'error' => 1, 'error_message' => $e->getMessage(), 'result' => array());
		}
		echo json_encode($result);
	}
	//http://horo.mkgalaxy.com/api/register?email=3@mkgalaxy.com&password=1&username=man3&gender=male&name=manish
	//http://horo.mkgalaxy.com/api/register?email=222211@mkgalaxy.com&password=1&username=1sss&gender=male&name=manish&geonameId=1275339&q=mumbai&bday=5&bmonth=6&byear=1974&bhour=12&bmin=30
	public function register()
	{
		try {
			$email = $this->input->get_post('email');
			$password = $this->input->get_post('password');
			$username = $this->input->get_post('username');
			$gender = $this->input->get_post('gender');
			$name = $this->input->get_post('name');
			$this->load->model('Api_Model');
			$post['username'] = $username;
			$post['email'] = $email;
			$post['name'] = $name;
			$post['gender'] = $gender;
			$post['password'] = $password;
			$post['marital_status'] = $this->input->get_post('marital_status');
			$post['profession'] = $this->input->get_post('profession');
			$post['looking_for'] = $this->input->get_post('looking_for');
			$post['personalitytype'] = $this->input->get_post('personalitytype');
			$post['education'] = $this->input->get_post('education');
			$post['imageFile'] = $this->input->get_post('imageFile');
			$data = $this->Api_Model->insert_user($post);
			if (empty($data)) {
				throw new Exception('could not proceed, please try again later - 2.');
			}
			$this->user_id = $data['user_id'];
			ob_start();
			$this->locationset();
			$_REQUEST['location_id'] = $_GET['location_id'] = $_POST['location_id'] = $this->location['id'];
			$data['location_id'] = $this->location['id'];
			$_REQUEST['bname'] = $name;
			$this->mybirthdetailspost();
			$this->mybirthdetails();
			$data['birthdetails'] = $this->mybirthdetails[0];
			ob_end_clean();
			$key = md5($data['user_id']);
			$data['token'] = $key;
			$this->email->from('admin@wc5.org', 'Compatibility Match');
			$this->email->to( $email); 
$message = "Dear $name,
You are successfully registered with Compatibility Match App. Email us at admin@wc5.org if you have any questions.

Regards
Administrator";
			$this->email->subject('New User Registration at Compatibility Match App');
			$this->email->message($message);
			$this->email->send();
			$result = array('type' => 'register', 'error' => 0, 'error_message' => '', 'result' => $data);
			$res = json_encode($result);
			$this->cache->file->save($key, $res, 3600 * 24 * 7);
		} catch (Exception $e) {
			$result = array('type' => 'register', 'error' => 1, 'error_message' => $e->getMessage(), 'result' => array());
		}
		echo json_encode($result);
	}
	
	
	//http://horo.mkgalaxy.com/api/update?email=3@mkgalaxy.com&password=1&username=man3&gender=male&name=manish
	public function update()
	{
		try {
			$email = $this->input->get_post('email');
			$gender = $this->input->get_post('gender');
			$name = $this->input->get_post('name');
			$this->load->model('Api_Model');
			$post['email'] = $email;
			$post['name'] = $name;
			$post['gender'] = $gender;
			$post['marital_status'] = $this->input->get_post('marital_status');
			$post['profession'] = $this->input->get_post('profession');
			$post['looking_for'] = $this->input->get_post('looking_for');
			$post['personalitytype'] = $this->input->get_post('personalitytype');
			$post['education'] = $this->input->get_post('education');
			$post['status'] = $this->input->get_post('status');
			$user_id = $this->user_id;
			if (empty($user_id)) {
				throw new Exception('Invalid User Id');
			}
			$data = $this->Api_Model->update_user($user_id, $post);
			if (empty($data)) {
				throw new Exception('could not proceed, please try again later - 2.');
			}
			ob_start();
			$this->mylocation();
			$locs = ob_get_clean();
			$locations = json_decode($locs, true);
			ob_start();
			$this->mybirthdetails();
			$bd = ob_get_clean();
			$birthDetails = json_decode($bd, true);
			//updating location
			if ($this->input->get_post('q')  && $this->input->get_post('geonameId') && !empty($locations['records'][0])) {
				$name = $this->input->get_post('q');
				$geonameId = $this->input->get_post('geonameId');
				$loc_array = $this->location($name, $geonameId);
				$loc_array['user_id'] = $this->user_id;
				$result = $this->Api_Model->update_location($locations['records'][0]['location_id'], $loc_array);
			}
			if (!empty($locations['records'][0]['location_id']) && !empty($birthDetails['data'][0]['bid'])) {
				$this->updateBirthProfile($user_id, $birthDetails['data'][0]['bid'], $locations['records'][0]['location_id']);
			}
			$data = $this->Api_Model->find_user_by_id($user_id);
			if (empty($data)) {
				throw new Exception('Invalid User Data');
			}
			$key = md5($data['user_id']);
			$data['token'] = $key;
			$this->user_id = $data['user_id'];
			ob_start();
			$this->mybirthdetails();
			$data['birthdetails'] = $this->mybirthdetails[0];
			ob_end_clean();
			$result = array('type' => 'update', 'error' => 0, 'error_message' => '', 'result' => $data);
			$res = json_encode($result);
			$this->cache->file->save($key, $res, 3600 * 24 * 7);
		} catch (Exception $e) {
			$result = array('type' => 'update', 'error' => 1, 'error_message' => $e->getMessage(), 'result' => array());
		}
		echo json_encode($result);
	}
	
	//http://horo.mkgalaxy.com/api/fetch?q=san+jose
	public function fetch()
	{
		try {
		if (isset($_REQUEST['q']) && empty($_REQUEST['q'])) {
			throw new Exception('empty search criteria');
		}
		if ($this->input->get('q')) {
			$key = md5('fetchLocs_'.$this->input->get('q'));
			$c = $this->cache->file->get($key);
			if (empty($c)) {
				$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($this->input->get('q'));
				$c = file_get_contents($url);
				if (empty($c)) {
					throw new Exception('Empty content. Please change parameter or try again after few minutes.');
				}
				$content = json_decode($c, true);
				if (!empty($content['geonames'])) {
					foreach ($content['geonames'] as $k => $v) {
						$content['geonames'][$k]['full'] = $v['name'].', '.$v['adminName1'].', '.$v['countryCode'];
					}
				}
				$c = json_encode($content);
				$this->cache->file->save($key, $c, 3600 * 24 * 7 * 50);
			}
			echo $c;
		} else if ($this->input->get('timezone') && $this->input->get('lat') && $this->input->get('lng')) {
			$key = md5('fetch2_'.$this->input->get('lat').'_'.$this->input->get('lng'));
			$c = $this->cache->file->get($key);
			if (empty($c)) {
				$url = "http://wc5.org/api/v1/fetch.php?timezone=1&lat=".$this->input->get('lat')."&lng=".$this->input->get('lng');
				$c = file_get_contents($url);
				$this->cache->file->save($key, $c, 3600 * 24 * 7 * 50);
			}
			echo $c;
		}
		} catch (Exception $e) {
			$result = array('type' => 'error', 'error' => 1, 'msg' => $e->getMessage());
			echo json_encode($result);
		}
	}
	
	private function getdetailsonlatlon($lat, $lon)
	{
		$key = md5('fetch2_'.$lat.'_'.$lon);
		$c = $this->cache->file->get($key);
		if (empty($c)) {
			$url = "http://wc5.org/api/v1/fetch.php?timezone=1&lat=".$lat."&lng=".$lon;
			$c = file_get_contents($url);
			$this->cache->file->save($key, $c, 3600 * 24 * 7 * 50);
		}
		return $c;
	}

	//http://horo.mkgalaxy.com/api/fetchcity?q=san+jose
	public function fetchcity()
	{
		try {
			$key = md5('fetchCity_'.$this->input->get('q'));
			$return = $this->cache->file->get($key);
			if (empty($return)) {
				$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($this->input->get('q'));
				$c = file_get_contents($url);
				$contents = json_decode($c, true);
				$result = array();
				if ($contents['totalResultsCount'] > 0) {
					foreach ($contents['geonames'] as $k => $v) {
						$result[] = array('geonameId' => $v['geonameId'], 'q' => $this->input->get('q'), 'location' => $v['toponymName'].', '.$v['adminName1'].', '.$v['countryCode']);
					}
				}
				$return = json_encode($result);
				$this->cache->file->save($key, $return, 3600 * 24 * 7 * 50);
			}
		} catch (Exception $e) {
			$return = array('type' => 'error', 'error' => 1, 'msg' => $e->getMessage());
		}
			if (isset($_REQUEST['callback'])) {
				$return = $_REQUEST['callback'].'('.$return .')';
			}
			
			echo $return;
	}

	//http://horo.mkgalaxy.com/api/fetchcitymore?q=san+jose
	//http://horo.mkgalaxy.com/api/fetchcitymore?q=san+jose&geonameId=1
	public function fetchcitymore()
	{
		try {
      $geonameId = $this->input->get_post('geonameId');//$more = $this->getdetailsonlatlon();
			$key = md5('fetchCityMore4_'.$this->input->get('q').'_'.$geonameId);
			$return = $this->cache->file->get($key);
			if (empty($return)) {
				$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($this->input->get('q'));
				$c = file_get_contents($url);
				$contents = json_decode($c, true);
				$result = array();
				if ($contents['totalResultsCount'] > 0) {
					foreach ($contents['geonames'] as $k => $v) {
            if (!empty($geonameId) && $geonameId == $v['geonameId']) {
              $result = array();
              $timezone = $this->getdetailsonlatlon($v['lat'], $v['lng']);
              $timezoneContent = json_decode($timezone, 1);
              $result = array('geonameId' => $v['geonameId'], 'q' => $this->input->get('q'), 'location' => $v['toponymName'].', '.$v['adminName1'].', '.$v['countryCode'], 'lat' => $v['lat'], 'lon' => $v['lng'], 'rawOffset' => $timezoneContent['rawOffset'], 'dstOffset' => $timezoneContent['dstOffset'], 'gmtOffset' => $timezoneContent['gmtOffset']);
              break;
            } else {
						  $result[] = array('geonameId' => $v['geonameId'], 'q' => $this->input->get('q'), 'location' => $v['toponymName'].', '.$v['adminName1'].', '.$v['countryCode'], 'lat' => $v['lat'], 'lon' => $v['lng']);
            }
					}
				}
				$return = json_encode($result);
				$this->cache->file->save($key, $return, 3600 * 24 * 7 * 50);
			}
		} catch (Exception $e) {
			$return = array('type' => 'error', 'error' => 1, 'msg' => $e->getMessage());
		}
			if (isset($_REQUEST['callback'])) {
				$return = $_REQUEST['callback'].'('.$return .')';
			}
			
			echo $return;
	}

	//http://horo.mkgalaxy.com/api/match?bq=mumbai&bgid=1275339&gq=ulhasnagar&ggid=1253894&bmonth=6&bday=5&byear=1974&bhour=12&bmin=30&gmonth=4&gday=5&gyear=1977&ghour=8&gmin=30
	public function match()
	{
		$data = array();
		
		try {
			$data['points'] = array();
			//get boy location
			$array = array('q' => 'place', 'gid' => 'Place Id', 'bmonth' => 'Month', 'bday' => 'Day', 'byear' => 'Year', 'bhour' => 'Hour', 'bmin' => 'Minute');
			$data['boy']['q'] = $this->input->get_post('bq');
			$data['boy']['gid'] = $this->input->get_post('bgid');
			$data['boy']['bmonth'] = $this->input->get_post('bmonth');
			$data['boy']['bday'] = $this->input->get_post('bday');
			$data['boy']['byear'] = $this->input->get_post('byear');
			$data['boy']['bhour'] = $this->input->get_post('bhour');
			$data['boy']['bmin'] = $this->input->get_post('bmin');
			foreach ($array as $k => $v) {
				if (!isset($data['boy'][$k])) {
					throw new Exception('Missing Boys '.$v);
				}
			}
			$data['boy']['location'] = $this->location($data['boy']['q'], $data['boy']['gid']);
			$v = $data['boy']['location'];
			$returnArr = $this->kundali->precalculate($data['boy']['bmonth'], $data['boy']['bday'], $data['boy']['byear'], $data['boy']['bhour'], $data['boy']['bmin'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
			$data['boy']['horo'] = $returnArr;
			
			//get boy location
			$data['girl']['q'] = $this->input->get_post('gq');
			$data['girl']['gid'] = $this->input->get_post('ggid');
			$data['girl']['bmonth'] = $this->input->get_post('gmonth');
			$data['girl']['bday'] = $this->input->get_post('gday');
			$data['girl']['byear'] = $this->input->get_post('gyear');
			$data['girl']['bhour'] = $this->input->get_post('ghour');
			$data['girl']['bmin'] = $this->input->get_post('gmin');
			foreach ($array as $k => $v) {
				if (!isset($data['girl'][$k])) {
					throw new Exception('Missing Girls '.$v);
				}
			}
			$data['girl']['location'] = $this->location($data['girl']['q'], $data['girl']['gid']);
			$v = $data['girl']['location'];
			$returnArr = $this->kundali->precalculate($data['girl']['bmonth'], $data['girl']['bday'], $data['girl']['byear'], $data['girl']['bhour'], $data['girl']['bmin'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
			$data['girl']['horo'] = $returnArr;
			$pts = $this->kundali->getpoints($data['boy']['horo'][9], $data['girl']['horo'][9]);
			$data['points']= array('points' => $pts, 'result' => $this->kundali->interpret($pts));
		} catch (Exception $e) {
			$data = array('error' => 1, 'msg' => $e->getMessage());
		}
		/*
		$this->email->from('mkgxy@mkgalaxy.com', 'From Admin');
		$this->email->to('mkgxy@mkgalaxy.com'); 
		$this->email->subject('Email at api horo');
		$this->email->message($_SERVER['REQUEST_URI']."\n".$_SERVER['QUERY_STRING']."\n".'get: '.var_export($_GET, 1)."\n".', post: '.var_export($_POST, 1)."\n".'data: '.var_export($data, 1));
		$this->email->send();
		*/
		$this->json_($data);
	}
	
	

  //http://horo.mkgalaxy.com/api/matchLatLonDays?fromlat=19.23&fromlng=73.15&tolat=19.162744439768783&tolng=72.93824546557619&fromdate=1974-06-05%2012:30:00&todate=2009-06-06%2018:00:00
  public function matchLatLonDays()
  {
    $data = $this->getpts($this->input->get_post('fromlat'), $this->input->get_post('fromlng'), $this->input->get_post('tolat'), $this->input->get_post('tolng'), $this->input->get_post('fromdate'), $this->input->get_post('todate'));
    $this->json_($data);
  }
  //getDetails($tableName, $cache=1, $params=array())
  //$this->dbConnect();

  //http://horo.mkgalaxy.com/api/matchMultiLatLonDays
  public function matchMultiLatLonDays()
  {
    $data = array();
    try {
      $this->dbConnect();
      $fromData = array();
      $toData = array();
      if (!empty($_POST['fromCityID'])) {
        $params = array();
        $params['where'] = ' AND cty_id = '.$this->qstr($_POST['fromCityID']);
        $from = $this->getDetails('geo_cities', 1, $params);
        $fromData = $from[0];
      }
      if (!empty($_POST['toCityID'])) {
        $params = array();
        $params['where'] = ' AND cty_id = '.$this->qstr($_POST['toCityID']);
        $to = $this->getDetails('geo_cities', 1, $params);
        $toData = $to[0];
      }
      if (empty($_POST['params']) || !is_array($_POST['params'])) {
        throw new Exception('empty params');
      }
      foreach ($_POST['params'] as $params) {
        $data[$params['id']] = $this->getpts($params['fromlat'], $params['fromlng'], $params['tolat'], $params['tolng'], $params['fromdate'], $params['todate']);
      }
    } catch (Exception $e) {
      $data = array('error' => 1, 'msg' => $e->getMessage());
    }
    $this->json_($data);
  }
  
  private function getpts($fromlat, $fromlng, $tolat, $tolng, $fromdate, $todate)
  {
    $data = array();
    try {
      $data['from']['lat'] = $fromlat;
      $data['from']['lon'] = $fromlng;
      $data['to']['lat'] = $tolat;
      $data['to']['lon'] = $tolng;
      $data['from']['date'] = $fromdate;
      $data['to']['date'] = $todate;
      if (empty($data['from']['lat']))
        throw new Exception('Missing From Latitude');
      if (empty($data['from']['lon']))
        throw new Exception('Missing From Longitude');
      if (empty($data['to']['lat']))
        throw new Exception('Missing To Latitude');
      if (empty($data['to']['lon']))
        throw new Exception('Missing To Longitude');
      if (empty($data['from']['date']))
        throw new Exception('Missing From Date');
      if (empty($data['to']['date']))
        throw new Exception('Missing To Date');
      $tmp = explode(' ', $data['from']['date']);
      $date = $tmp[0];
      $time = $tmp[1];
      $tmp = explode('-', $date);
      $month = $tmp[1];
      $day = $tmp[2];
      $year = $tmp[0];
      $tmp = explode(':', $time);
      $hour = $tmp[0];
      $minute = $tmp[1];
      $data['from']['day'] = $day;
      $data['from']['month'] = $month;
      $data['from']['year'] = $year;
      $data['from']['hour'] = $hour;
      $data['from']['minute'] = $minute;
      
      $c = $this->getdetailsonlatlon($data['from']['lat'], $data['from']['lon']);
      $data['from']['timezone'] = json_decode($c, true);
      if ($data['from']['timezone']['rawOffset'] != $data['from']['timezone']['dstOffset']) {
        $data['from']['location']['dst'] = 1;
      } else {
        $data['from']['location']['dst'] = 0;
      }
      $data['from']['dd2dms'] = $this->dd2dms($data['from']['lat'], $data['from']['lon']);
      $data['from']['location']['lat_h'] = $data['from']['dd2dms'][2];
      $data['from']['location']['lat_m'] = $data['from']['dd2dms'][4];
      $data['from']['location']['lat_s'] = ($data['from']['dd2dms'][0] == 'S') ? 1 : 0;
      $data['from']['location']['lon_h'] = $data['from']['dd2dms'][3];
      $data['from']['location']['lon_m'] = $data['from']['dd2dms'][5];
      $data['from']['location']['lon_e'] = ($data['from']['dd2dms'][1] == 'E') ? 1 : 0;
      $zones = $this->makeTime(abs($data['from']['timezone']['rawOffset']));
      $data['from']['location']['zone_h'] = $zones[0];
      $data['from']['location']['zone_m'] = $zones[1];
      $v = $data['from']['location'];
      $returnArr = $this->kundali->precalculate($data['from']['month'], $data['from']['day'], $data['from']['year'], $data['from']['hour'], $data['from']['minute'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
      $data['from']['horo'] = $returnArr;
      
      $tmp = explode(' ', $data['to']['date']);
      $date = $tmp[0];
      $time = $tmp[1];
      $tmp = explode('-', $date);
      $month = $tmp[1];
      $day = $tmp[2];
      $year = $tmp[0];
      $tmp = explode(':', $time);
      $hour = $tmp[0];
      $minute = $tmp[1];
      $data['to']['day'] = $day;
      $data['to']['month'] = $month;
      $data['to']['year'] = $year;
      $data['to']['hour'] = $hour;
      $data['to']['minute'] = $minute;
      $c = $this->getdetailsonlatlon($data['to']['lat'], $data['to']['lon']);
      $data['to']['timezone'] = json_decode($c, true);
      if ($data['to']['timezone']['rawOffset'] != $data['to']['timezone']['dstOffset']) {
        $data['to']['location']['dst'] = 1;
      } else {
        $data['to']['location']['dst'] = 0;
      }
      $data['to']['dd2dms'] = $this->dd2dms($data['to']['lat'], $data['to']['lon']);
      $data['to']['location']['lat_h'] = $data['to']['dd2dms'][2];
      $data['to']['location']['lat_m'] = $data['to']['dd2dms'][4];
      $data['to']['location']['lat_s'] = ($data['to']['dd2dms'][0] == 'S') ? 1 : 0;
      $data['to']['location']['lon_h'] = $data['to']['dd2dms'][3];
      $data['to']['location']['lon_m'] = $data['to']['dd2dms'][5];
      $data['to']['location']['lon_e'] = ($data['to']['dd2dms'][1] == 'E') ? 1 : 0;
      $zones = $this->makeTime(abs($data['to']['timezone']['rawOffset']));
      $data['to']['location']['zone_h'] = $zones[0];
      $data['to']['location']['zone_m'] = $zones[1];
      
      
      $v = $data['to']['location'];
      $returnArr = $this->kundali->precalculate($data['to']['month'], $data['to']['day'], $data['to']['year'], $data['to']['hour'], $data['to']['minute'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
      $data['to']['horo'] = $returnArr;
      $pts = $this->kundali->getpoints($data['from']['horo'][9], $data['to']['horo'][9]);
      $data['points'] = array('points' => $pts, 'result' => $this->kundali->interpret($pts));
    } catch (Exception $e) {
      $data = array('error' => 1, 'msg' => $e->getMessage());
    }
    return $data;
  }
  //http://horo.mkgalaxy.com/api/matchDateTime?bq=mumbai&bgid=1275339&gq=ulhasnagar&ggid=1253894&bmonth=6&bday=5&byear=1974&bhour=12&bmin=30&gmonth=4&gday=5&gyear=1977&ghour=8&gmin=30
//&change=1
	public function matchDateTime()
	{
		$data = array();
		
		try {
			$format = $this->input->get_post('format') ? $this->input->get_post('format') : 'json';
      $change = $this->input->get_post('change') ? $this->input->get_post('change') : false;
			$data['points'] = array();
			//get boy location
			$array = array('q' => 'place', 'gid' => 'Place Id', 'bmonth' => 'Month', 'bday' => 'Day', 'byear' => 'Year', 'bhour' => 'Hour', 'bmin' => 'Minute');
			$data['boy']['q'] = $this->input->get_post('bq');
			$data['boy']['gid'] = $this->input->get_post('bgid');
			$data['boy']['bmonth'] = $this->input->get_post('bmonth');
			$data['boy']['bday'] = $this->input->get_post('bday');
			$data['boy']['byear'] = $this->input->get_post('byear');
			$data['boy']['bhour'] = $this->input->get_post('bhour');
			$data['boy']['bmin'] = $this->input->get_post('bmin');
			foreach ($array as $k => $v) {
				if (empty($data['boy'][$k])) {
					throw new Exception('Missing Your '.$v);
				}
			}
			$data['boy']['location'] = $this->location($data['boy']['q'], $data['boy']['gid']);
			$v = $data['boy']['location'];
			$returnArr = $this->kundali->precalculate($data['boy']['bmonth'], $data['boy']['bday'], $data['boy']['byear'], $data['boy']['bhour'], $data['boy']['bmin'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
			$data['boy']['horo'] = $returnArr;
			
			//get boy location
			$data['girl']['q'] = $this->input->get_post('gq');
			$data['girl']['gid'] = $this->input->get_post('ggid');
			$data['girl']['bmonth'] = $this->input->get_post('gmonth');
			$data['girl']['bday'] = $this->input->get_post('gday');
			$data['girl']['byear'] = $this->input->get_post('gyear');
			$data['girl']['bhour'] = $this->input->get_post('ghour');
			$data['girl']['bmin'] = $this->input->get_post('gmin');
			$data['girl']['lat'] = $this->input->get_post('lat');
			$data['girl']['lon'] = $this->input->get_post('lon');
			unset($array['bhour']);
			unset($array['bmin']);
			unset($array['q']);
			unset($array['gid']);
			foreach ($array as $k => $v) {
				if (empty($data['girl'][$k])) {
					throw new Exception('Missing Day\'s '.$v);
				}
			}
			if (!empty($data['girl']['lat']) && !empty($data['girl']['lat'])) {
				$c = $this->getdetailsonlatlon($data['girl']['lat'], $data['girl']['lon']);
				$data['girl']['timezone'] = json_decode($c, true);
				if ($data['girl']['timezone']['rawOffset'] != $data['girl']['timezone']['dstOffset']) {
					$data['girl']['location']['dst'] = 1;
				} else {
					$data['girl']['location']['dst'] = 0;
				}
				$data['girl']['dd2dms'] = $this->dd2dms($data['girl']['lat'], $data['girl']['lon']);
				$data['girl']['location']['lat_h'] = $data['girl']['dd2dms'][2];
				$data['girl']['location']['lat_m'] = $data['girl']['dd2dms'][4];
				$data['girl']['location']['lat_s'] = ($data['girl']['dd2dms'][0] == 'S') ? 1 : 0;
				$data['girl']['location']['lon_h'] = $data['girl']['dd2dms'][3];
				$data['girl']['location']['lon_m'] = $data['girl']['dd2dms'][5];
				$data['girl']['location']['lon_e'] = ($data['girl']['dd2dms'][1] == 'E') ? 1 : 0;
				$zones = $this->makeTime(abs($data['girl']['timezone']['rawOffset']));
				$data['girl']['location']['zone_h'] = $zones[0];
				$data['girl']['location']['zone_m'] = $zones[1];
			} else if (!empty($data['girl']['q']) && !empty($data['girl']['gid'])) {
				$data['girl']['location'] = $this->location($data['girl']['q'], $data['girl']['gid']);
			} else {
				throw new Exception('Choose lat and long or place and place Id');
			}
			
			$v = $data['girl']['location'];
			$date = $data['girl']['byear'].'-'.$data['girl']['bmonth'].'-1';
      $no_of_days = date('t', strtotime($date));
			//$counter = 0;
      $past = null;
			for($counter = 0; $counter < $no_of_days; $counter++) {
				$day = date('d', strtotime("$date +$counter day"));
				$month = date('m', strtotime("$date +$counter day"));
				$year = date('Y', strtotime("$date +$counter day"));
				for ($j = 0; $j < 24; $j = $j + 1) {
					$hour = $j;
					$returnArr = $this->kundali->precalculate($month, $day, $year, $hour, 0, $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
					$data['girl']['horo'] = $returnArr;
					$pts = $this->kundali->getpoints($data['boy']['horo'][9], $data['girl']['horo'][9]);
					$d = $year.'-'.$month.'-'.$day.' '.$hour.':00';
          if ($change) {
              if ($past == $data['girl']['horo'][9]) {
                  continue;
              } else {
                  $past = $data['girl']['horo'][9];
              }
          }
					$data['points'][] = array('points' => $pts, 'result' => $this->kundali->interpret($pts), 
          //'date' => date('j F, Y g a', strtotime($d)), 
          'real_date' => $d, 'number' => $data['girl']['horo'][9], 'naks' => $data['girl']['horo'][7], 'person' => $data['boy']['horo'], 'day' => $data['girl']['horo']);
				}
				//$counter++;
			}
		} catch (Exception $e) {
			$data = array('error' => 1, 'msg' => $e->getMessage());
		}
		if ($format == 'html') {
			header('Content-Type: text/html; charset=utf-8');
			$this->load->view('maps/matchdatetime.php', array('data' => $data));
		} else {
			$this->json_($data);
		}
	}
	
	//http://horo.mkgalaxy.com/api/matchDay?bq=mumbai&bgid=1275339&gq=ulhasnagar&ggid=1253894&bmonth=6&bday=5&byear=1974&bhour=12&bmin=30&gmonth=4&gday=5&gyear=1977&ghour=8&gmin=30
//http://horo.mkgalaxy.com/api/matchDay?bq=mumbai&bgid=1275339&gq=&ggid=&lat=37.2483&lon=-121.856&bmonth=6&bday=5&byear=1974&bhour=12&bmin=30&gmonth=3&gday=1&gyear=2014&ghour=8&gmin=30
	public function matchDay()
	{
		$data = array();
		
		try {
			$format = $this->input->get_post('format') ? $this->input->get_post('format') : 'json';
			$data['points'] = array();
			//get boy location
			$array = array('q' => 'place', 'gid' => 'Place Id', 'bmonth' => 'Month', 'bday' => 'Day', 'byear' => 'Year', 'bhour' => 'Hour', 'bmin' => 'Minute');
			$data['person']['q'] = $this->input->get_post('bq');
			$data['person']['gid'] = $this->input->get_post('bgid');
			$data['person']['bmonth'] = $this->input->get_post('bmonth');
			$data['person']['bday'] = $this->input->get_post('bday');
			$data['person']['byear'] = $this->input->get_post('byear');
			$data['person']['bhour'] = $this->input->get_post('bhour');
			$data['person']['bmin'] = $this->input->get_post('bmin');
			foreach ($array as $k => $v) {
				if (empty($data['person'][$k])) {
					throw new Exception('Missing Person\'s '.$v);
				}
			}
			$data['person']['location'] = $this->location($data['person']['q'], $data['person']['gid']);
			$v = $data['person']['location'];
			$returnArr = $this->kundali->precalculate($data['person']['bmonth'], $data['person']['bday'], $data['person']['byear'], $data['person']['bhour'], $data['person']['bmin'], $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
			$data['person']['horo'] = $returnArr;
			
			//get day location
			$data['day']['q'] = $this->input->get_post('gq');
			$data['day']['gid'] = $this->input->get_post('ggid');
			$data['day']['lat'] = $this->input->get_post('lat');
			$data['day']['lon'] = $this->input->get_post('lon');
			$data['day']['bmonth'] = $this->input->get_post('gmonth');
			$data['day']['bday'] = $this->input->get_post('gday');
			$data['day']['byear'] = $this->input->get_post('gyear');
			$data['day']['bhour'] = $this->input->get_post('ghour');
			$data['day']['bmin'] = $this->input->get_post('gmin');
			$date = $data['day']['byear'].'-'.$data['day']['bmonth'].'-'.$data['day']['bday'];
				unset($array['q']);
				unset($array['gid']);
			foreach ($array as $k => $v) {
				if (empty($data['day'][$k])) {
					throw new Exception('Missing Day\' s '.$v);
				}
			}
			if (!empty($data['day']['lat']) && !empty($data['day']['lat'])) {
				$c = $this->getdetailsonlatlon($data['day']['lat'], $data['day']['lon']);
				$data['day']['timezone'] = json_decode($c, true);
				if ($data['day']['timezone']['rawOffset'] != $data['day']['timezone']['dstOffset']) {
					$data['day']['location']['dst'] = 1;
				} else {
					$data['day']['location']['dst'] = 0;
				}
				$data['day']['dd2dms'] = $this->dd2dms($data['day']['lat'], $data['day']['lon']);
				$data['day']['location']['lat_h'] = $data['day']['dd2dms'][2];
				$data['day']['location']['lat_m'] = $data['day']['dd2dms'][4];
				$data['day']['location']['lat_s'] = ($data['day']['dd2dms'][0] == 'S') ? 1 : 0;
				$data['day']['location']['lon_h'] = $data['day']['dd2dms'][3];
				$data['day']['location']['lon_m'] = $data['day']['dd2dms'][5];
				$data['day']['location']['lon_e'] = ($data['day']['dd2dms'][1] == 'E') ? 1 : 0;
				$zones = $this->makeTime(abs($data['day']['timezone']['rawOffset']));
				$data['day']['location']['zone_h'] = $zones[0];
				$data['day']['location']['zone_m'] = $zones[1];
				//$data['day']['calc'] = $this->kundali->precalculate($data['day']['bmonth'], $data['day']['bday'], $data['day']['byear'], $data['day']['bhour'], $data['day']['bmin'], $data['day']['location']['zone_h'], $data['day']['location']['zone_m'], $data['day']['location']['lon_h'], $data['day']['location']['lon_m'], $data['day']['location']['lat_h'], $data['day']['location']['lat_m'], $data['day']['location']['dst'], $data['day']['location']['lon_e'], $data['day']['location']['lat_s'] );
			} else if (!empty($data['day']['q']) && !empty($data['day']['gid'])) {
				$data['day']['location'] = $this->location($data['day']['q'], $data['day']['gid']);
			} else {
				throw new Exception('Choose lat and long or place and place Id');
			}
			$v = $data['day']['location'];
			$counter = 0;
			while($counter < 5) {
				$day = date('d', strtotime("$date +$counter day"));
				$month = date('m', strtotime("$date +$counter day"));
				$year = date('Y', strtotime("$date +$counter day"));
				for ($j = 0; $j < 24; $j = $j + 1) {
					$hour = $j;
					$returnArr = $this->kundali->precalculate($month, $day, $year, $hour, 0, $v['zone_h'], $v['zone_m'], $v['lon_h'], $v['lon_m'], $v['lat_h'], $v['lat_m'], $v['dst'], $v['lon_e'], $v['lat_s']);
					$pts = $this->kundali->getpoints($data['person']['horo'][9], $returnArr[9]);
					//$data['points']
					$newdate = $year.'-'.$month.'-'.$day.' '.$hour.':00';
					$date2 = strtotime($newdate);
					$newdate = $newdate.' '.date('D', $date2);
					$data['points'][] = array('date' => $newdate, 'points' => $pts, 'naks' => $returnArr[7], 'ref' => $returnArr[9], 'result' => $this->kundali->interpret($pts));
				}
				$counter++;
			}
		} catch (Exception $e) {
			$data = array('error' => 1, 'msg' => $e->getMessage());
		}
		if ($format == 'html') {
			pr($data);
			exit;
		} else {
			$this->json_($data);
		}
	}
	
	private function location($q, $id)
	{
		if ($q == "" || $id == "") {
				throw new Exception('Missing parameter q or geonameId');
			}
			$name = $q;
			$geonameId = $id;
			$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($q);
			$key = md5($name.'-'.$geonameId);
			$content = $this->cache->file->get($key);
			if (!empty($content)) {
				$post = json_decode($content, 1);
				$post['cache'] = 1;
			} else {
				$c = file_get_contents($url);
				$location = json_decode($c, true);
				if ($location['totalResultsCount'] == 0) {
					throw new Exception('No location found.');
				}
				foreach ($location['geonames'] as $k => $v) {
					if ($v['geonameId'] == $id) {
						$found = $v;
					}
				}
				if (empty($found)) {
					throw new exception('no location found');
				}
				$post = $this->getPostData($found['lat'], $found['lng']);
				$post["xtra"] = $found['adminName1'];
				$post["location_name"] = $name;
				$post["geonameId"] = $geonameId;
				$this->cache->file->save($key, json_encode($post), 3600 * 24 * 7 * 50);
				$post['cache'] = 0;
			}
		return $post;
	}

	private function iFetch($lat, $lon)
	{
		$url = "http://wc5.org/api/v1/fetch.php?timezone=1&lat=".$lat."&lng=".$lon;
		$c = file_get_contents($url);
		$return = json_decode($c, true);
		return $return;
	}
	private function nearby($lat, $lon, $radius=30, $limit=30)
	{
		$url = "http://world.mkgalaxy.com/api/nearby.php?lat=".$lat."&lon=".$lon."&order=distance&limit=".$limit."&radius=".$radius;
		$c = file_get_contents($url);
		$return = json_decode($c, true);
		return $return;
	}

	private function round100000($v) {
		return round($v * 100000) / 100000;
	}
	
	private function dd2dms($lat, $lon)
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

	private function makeTime($num) {
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

	//http://horo.mkgalaxy.com/api/locationset?q=santa%20clara&geonameId=5393015
	public function locationset()
	{
		try {
			//{"time":"2012-02-19 00:04","countryName":"India","sunset":"2012-02-18 18:38","rawOffset":5.5,"dstOffset":5.5,"countryCode":"IN","gmtOffset":5.5,"lng":73.15,"sunrise":"2012-02-18 07:04","timezoneId":"Asia/Kolkata","lat":19.2166667}
			if ($this->input->get_post('q') == "" || $this->input->get_post('geonameId') == "") {
				throw new Exception('Missing parameter q or geonameId');
			}
			$name = $this->input->get_post('q');
			$geonameId = $this->input->get_post('geonameId');
			$post = $this->location($name, $geonameId);
			/*
			$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($this->input->get_post('q'));
			$c = file_get_contents($url);
			$location = json_decode($c, true);
			if ($location['totalResultsCount'] == 0) {
				throw new Exception('No location found.');
			}
			foreach ($location['geonames'] as $k => $v) {
				if ($v['geonameId'] == $this->input->get_post('geonameId')) {
					$found = $v;
				}
			}
			$post = $this->getPostData($found['lat'], $found['lng']);
			$post["xtra"] = $found['adminName1'];
			$post["location_name"] = $name;
			$post["geonameId"] = $geonameId;
			*/
			$post['user_id'] = $this->user_id;
			$this->load->model('Api_Model');
			unset($post['cache']);
			$result = $this->Api_Model->set_location($post);
			$this->location = $result;
		} catch (Exception $e) {
			$result = array('success' => 0, 'msg' => $e->getCode());
		}
		echo json_encode($result);
		
			/*
			$extra = $this->iFetch($found['lat'], $found['lng']);
			if (empty($extra)) {
				throw new Exception('Could not found. Please try again later.');
			}
			if ($extra['rawOffset'] == $extra['dstOffset']) {
				$dst = 0;
			} else {
				$dst = 1;
			}
			$returnnum = $this->makeTime(abs($extra['rawOffset']));
			$returnlatlng = $this->dd2dms($found['lat'], $found['lng']);
			$lat_h = $returnlatlng[2];
			$lat_m = $returnlatlng[4];
			$lat_s = ($returnlatlng[0] == 'S') ? 1 : 0;
			$lon_h = $returnlatlng[3];
			$lon_m = $returnlatlng[5];
			$lon_e = ($returnlatlng[1] == 'E') ? 1 : 0;
			$post["location_name"] = $name;
			$post["xtra"] = $found['adminName1'];
			$post["country"] = $name;
			$post["location_lat"] = $found['lat'];
			$post["location_lon"] = $found['lng'];
			$post["dst"] = $dst;
			$post["lat_h"] = $lat_h;
			$post["lat_m"] = $lat_m;
			$post["lat_s"] = $lat_s;
			$post["lon_h"] = $lon_h;
			$post["lon_m"] = $lon_m;
			$post["lon_e"] = $lon_e;
			$post["zone_h"] = $returnnum[0];
			$post["zone_m"] = $returnnum[1];
			*/
	}

	private function getPostData($lat, $lng)
	{
		$extra = $this->iFetch($lat, $lng);
		if (empty($extra)) {
			throw new Exception('Could not found. Please try again later.');
		}
		if ($extra['rawOffset'] == $extra['dstOffset']) {
			$dst = 0;
		} else {
			$dst = 1;
		}
		$returnnum = $this->makeTime(abs($extra['rawOffset']));
		$returnlatlng = $this->dd2dms($lat, $lng);
		$lat_h = $returnlatlng[2];
		$lat_m = $returnlatlng[4];
		$lat_s = ($returnlatlng[0] == 'S') ? 1 : 0;
		$lon_h = $returnlatlng[3];
		$lon_m = $returnlatlng[5];
		$lon_e = ($returnlatlng[1] == 'E') ? 1 : 0;
		$post["location_name"] = $lat.", ".$lng;
		$post["country"] = $extra['countryName'];
		$post["location_lat"] = $lat;
		$post["location_lon"] = $lng;
		$post["dst"] = $dst;
		$post["lat_h"] = $lat_h;
		$post["lat_m"] = $lat_m;
		$post["lat_s"] = $lat_s;
		$post["lon_h"] = $lon_h;
		$post["lon_m"] = $lon_m;
		$post["lon_e"] = $lon_e;
		$post["zone_h"] = $returnnum[0];
		$post["zone_m"] = $returnnum[1];
		return $post;
	}
	//http://horo.mkgalaxy.com/api/mylocation
	public function mylocation()
	{
		try {
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$result = $this->Api_Model->get_location($user_id);
		} catch (Exception $e) {
			$result = array('success' => 0, 'msg' => $e->getCode());
		}
		echo json_encode($result);
	}

	private function updateBirthProfile($user_id, $bid, $location_id)
	{
			//bname=test1&bday=1&bmonth=1&byear=1974&bhour=1&bmin=1&location_id=5&bid=7&user_id=13
			$post = array();
			$post['bname'] = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
			if (empty($post['bname']))
				throw new Exception('Empty Name');
			$post['bday'] = isset($_REQUEST['bday']) ? $_REQUEST['bday'] : null;
			if (empty($post['bday']))
				throw new Exception('Empty Day');
			$post['bmonth'] = isset($_REQUEST['bmonth']) ? $_REQUEST['bmonth'] : null;
			if (empty($post['bmonth']))
				throw new Exception('Empty Month');
			$post['byear'] = isset($_REQUEST['byear']) ? $_REQUEST['byear'] : null;
			if (empty($post['byear']))
				throw new Exception('Empty Year');
			$post['bhour'] = isset($_REQUEST['bhour']) ? $_REQUEST['bhour'] : 0;
			$post['bmin'] = isset($_REQUEST['bmin']) ? $_REQUEST['bmin'] : 0;
			$post['location_id'] = $location_id;
			$this->load->model('Api_Model');
			$post['bid'] = $this->Api_Model->update_birth_profile($user_id, $bid, $post);
			$this->birthdetails = $post;
			return $post;
		
	}

	//http://horo.mkgalaxy.com/api/mybirthdetailspost?bname=xyz&bday=1&bmonth=1&byear=1974&bhour=1&bmin=1&location_id=50
	public function mybirthdetailspost()
	{
		try {
			//bname=test1&bday=1&bmonth=1&byear=1974&bhour=1&bmin=1&location_id=61
			$post = array();
			$post['bname'] = isset($_REQUEST['bname']) ? $_REQUEST['bname'] : null;
			if (empty($post['bname']))
				throw new Exception('Empty Name');
			$post['bday'] = isset($_REQUEST['bday']) ? $_REQUEST['bday'] : null;
			if (empty($post['bday']))
				throw new Exception('Empty Day');
			$post['bmonth'] = isset($_REQUEST['bmonth']) ? $_REQUEST['bmonth'] : null;
			if (empty($post['bmonth']))
				throw new Exception('Empty Month');
			$post['byear'] = isset($_REQUEST['byear']) ? $_REQUEST['byear'] : null;
			if (empty($post['byear']))
				throw new Exception('Empty Year');
			$post['bhour'] = isset($_REQUEST['bhour']) ? $_REQUEST['bhour'] : 0;
			$post['bmin'] = isset($_REQUEST['bmin']) ? $_REQUEST['bmin'] : 0;
			$post['location_id'] = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : null;
			if (empty($post['location_id']))
				throw new Exception('Empty Name');
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$post['bid'] = $this->Api_Model->insert_birth_profile($user_id, $post);
			$this->birthdetails = $post;
			$this->json_(array('error' => 0, 'error_message' => '', 'data' => $post));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	//http://horo.mkgalaxy.com/api/mybirthdetails
	public function mybirthdetails()
	{
		$data = array();
		try {
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$data = $this->Api_Model->get_birth_profile($user_id);
			$this->mybirthdetails = $data;
			$this->json_(array('error' => 0, 'error_message' => "", 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	//http://horo.mkgalaxy.com/api/matchmakingprofilepost?bid=58
	public function matchmakingprofilepost()
	{
		try {
			if (empty($_REQUEST['bid']))
				throw new Exception('Empty Data');
			$data = array();
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$post = $_POST;
			$bd = $this->Api_Model->get_birth_profile_particular($user_id, $_REQUEST['bid']);
			$data['profile'] = $bd;
			$this->load->library('Kundali');
			$profiles = $this->Api_Model->get_birth_profile($user_id);
			if (!empty($profiles)) {
				foreach ($profiles as $k => $v) {
					$date = $v['byear'].'-'.$v['bmonth'].'-'.$v['bday'].' '.$v['bhour'].':'.$v['bmin'];
					$pts = $this->kundali->getpoints($data['profile']['horo'][9], $v['horo'][9]);
					$data['result_points'][] = array('date' => $date, 'points' => $pts, 'naks' => $v['horo'][7], 'ref' => $v['horo'][9], 'result' => $this->kundali->interpret($pts), 'name' => $v['bname']);
				}
			}

			$this->json_(array('error' => 0, 'error_message' => '', 'profile' => $bd, 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	//http://horo.mkgalaxy.com/api/bestmatchespost?bid=58
	public function bestmatchespost()
	{
		try {
			if (empty($_REQUEST['bid']))
				throw new Exception('Empty Data');
			$data = array();
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$post = $_POST;
			$bd = $this->Api_Model->get_birth_profile_particular($user_id, $_REQUEST['bid']);
			$data['profile'] = $bd;
			$this->load->library('Kundali');
			$points = $this->kundali->points();
			$specific_points = $points[$bd['horo'][9]];
			arsort($specific_points);
			$data['records'] = array();
			$counter = 0;
			foreach ($specific_points as $k => $v) {
				$data['records'][$counter]['number'] = $k;
				$data['records'][$counter]['nakshatra'] = $this->kundali->getnaksfromnumber($k);
				$data['records'][$counter]['points'] = $v;
				$counter++;
			}

			$this->json_(array('error' => 0, 'error_message' => '', 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	/*
	 * bid=58&dday=1&dmonth=1&dyear=2014&location_id=61
	 * bid=58&dday=1&dmonth=1&dyear=2014&lat=37.3393857&lon=-121.8949555
	 * http://horo.mkgalaxy.com/api/dailymatchespost?bid=58&dday=1&dmonth=1&dyear=2014&location_id=61
	 * http://horo.mkgalaxy.com/api/dailymatchespost?bid=58&dday=1&dmonth=1&dyear=2014&lat=37.3393857&lon=-121.8949555
	 */
	public function dailymatchespost()
	{
		try {
			date_default_timezone_set('Africa/Casablanca');
			if (empty($_REQUEST['bid']))
				throw new Exception('Empty Data');
			if (empty($_REQUEST['dday']))
				throw new Exception('Empty day');
			if (empty($_REQUEST['dmonth']))
				throw new Exception('Empty month');
			if (empty($_REQUEST['dyear']))
				throw new Exception('Empty year');
			if (empty($_REQUEST['location_id']) && (empty($_REQUEST['lat']) && empty($_REQUEST['lon'])))
				throw new Exception('Empty location');
			$data = array();
			$user_id = $this->user_id;
			$this->load->model('Api_Model');
			$post = array();
			$post['bid'] = isset($_REQUEST['bid']) ? $_REQUEST['bid'] : null;
			if (empty($post['bid']))
				throw new Exception('Empty Bid');
			$post['dday'] = isset($_REQUEST['dday']) ? $_REQUEST['dday'] : null;
			if (empty($post['dday']))
				throw new Exception('Empty Day');
			$post['dmonth'] = isset($_REQUEST['dmonth']) ? $_REQUEST['dmonth'] : null;
			if (empty($post['bid']))
				throw new Exception('Empty Month');
			$post['dyear'] = isset($_REQUEST['dyear']) ? $_REQUEST['dyear'] : null;
			if (empty($post['dyear']))
				throw new Exception('Empty Year');
			$post['location_id'] = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : null;
			$post['lat'] = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : null;
			$post['lon'] = isset($_REQUEST['lon']) ? $_REQUEST['lon'] : null;
			$post['frequency'] = isset($_REQUEST['frequency']) ? $_REQUEST['frequency'] : 1;
			$bd = $this->Api_Model->get_birth_profile_particular($user_id, $post['bid']);
			$data['profile'] = $bd;
			if (!empty($post['location_id'])) {
				$loc = $this->Api_Model->get_location_particular($post['location_id']);
			} else if (!empty($post['lat']) && !empty($post['lon'])) {
				$loc = $this->getPostData($post['lat'], $post['lon']);
			} else {
				throw new Exception('Location Id or lat and lon not found');
			}
			$rad = 30;
			$lim = 30;
			$data['nearby'] = $this->nearby($loc['location_lat'], $loc['location_lon'], $rad, $lim);
			$this->load->library('Kundali');
			$points = $this->kundali->points();
			$from = $post['dday'];
			$post['dmin'] = 0;
			$freq = !empty($post['frequency']) ? (int) $post['frequency'] : 1;
			$html = '';
			$data['post'] = $post;
			$data['location'] = $loc;
			for ($day = $from; $day <= 31; $day++) {
				$check = checkdate((int) $post['dmonth'], (int) $day, $post['dyear']);
				if (empty($check))
					continue;
				$post['dday'] = $day;
				for ($j = 0; $j < 24; $j = $j + $freq) {
					$post['dhour'] = $j;
					$returnArr = $this->kundali->precalculate($post['dmonth'], $post['dday'], $post['dyear'], $post['dhour'], $post['dmin'], $loc['zone_h'], $loc['zone_m'], $loc['lon_h'], $loc['lon_m'], $loc['lat_h'], $loc['lat_m'], $loc['dst'], $loc['lon_e'], $loc['lat_s']);
					$date = $post['dyear'].'-'.$post['dmonth'].'-'.$post['dday'].' '.$post['dhour'].':'.$post['dmin'];
					$date2 = strtotime($date);
					$date = $date.' '.date('D', $date2);
					$pts = $this->kundali->getpoints($data['profile']['horo'][9], $returnArr[9]);
					$data['results'][] = array('date' => $date, 'points' => $pts, 'naks' => $returnArr[7], 'ref' => $returnArr[9], 'result' => $this->kundali->interpret($pts));
				}
			}
 			$this->json_(array('error' => 0, 'error_message' => '', 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	//http://horo.mkgalaxy.com/api/currlocation?latitude=2&longitude=1
	public function currlocation()
	{
		$data = array();
		try {
			$post = array();
			$user_id = $this->user_id;
			$format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'json';
			$post['latitude'] = !empty($_REQUEST['latitude']) ? $_REQUEST['latitude'] : null;
			$post['longitude'] = !empty($_REQUEST['longitude']) ? $_REQUEST['longitude'] : null;
			$post['radius'] = !empty($_REQUEST['radius']) ? $_REQUEST['radius'] : 100;
			$post['limit'] = !empty($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
			$post['gender'] = !empty($_REQUEST['gender']) ? $_REQUEST['gender'] : null;
			$post['fromage'] = isset($_REQUEST['fromage']) ? $_REQUEST['fromage'] : null;
			$post['toage'] = isset($_REQUEST['toage']) ? $_REQUEST['toage'] : null;
			$post['marital_status'] = !empty($_REQUEST['marital_status']) ? $_REQUEST['marital_status'] : null;
			$post['looking_for'] = !empty($_REQUEST['looking_for']) ? $_REQUEST['looking_for'] : null;
			$post['pts'] = isset($_REQUEST['pts']) ? $_REQUEST['pts'] : 0;
			$post['personalitytype'] = !empty($_REQUEST['personalitytype']) ? $_REQUEST['personalitytype'] : null;
			$t = number_format($post['latitude'], 3).'-'.number_format($post['longitude'], 3).'-'.$post['radius'].'-'.$post['limit'].'-'.$post['gender'].'-'.$post['fromage'].'-'.$post['toage'].'-'.$post['marital_status'].'-'.$post['looking_for'].'-'.$post['pts'].'-'.$post['personalitytype'];
			$key = md5($t).time();
			$res = $this->cache->file->get($key);
			$data = json_decode($res, 1);
			$cache = 1;
			if (empty($res)) {
				$this->load->model('Api_Model');
				$cpost = array();
				$cpost['latitude'] = $post['latitude'];
				$cpost['longitude'] = $post['longitude'];
				$data = $this->Api_Model->updateCurrentPosition($user_id, $cpost);
				$nearby = $this->Api_Model->nearbyusers($user_id, $post);
				$data['nearby'] = $nearby;
				$res = json_encode($data);
				$cache = 0;
				//$this->cache->file->save($key, $res, 360);
			}
			$result = array('error' => 0, 'error_message' => "", 'data' => $data, 'cache' => $cache, 't' => $t, 'key' => $key);
		} catch (Exception $e) {
			$result = array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode());
		}
		if ($format == 'html') {
			$newData = array();
			$newData['lat'] = $this->input->get_post('latitude');
			$newData['lon'] = $this->input->get_post('longitude');
			$newData['locations'] = $data['nearby'];
			header('Content-Type: text/html; charset=utf-8');
			$this->load->view('maps/nearby.php', $newData);
		} else {
			$this->json_($result);
		}
	}
	
	
	//http://horo.mkgalaxy.com/api/currtmplocation?latitude=2&longitude=1&phone=14085052726
	public function currtmplocation()
	{
		$data = array();
		try {
			$post = array();
			$phone = !empty($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
			$post['latitude'] = !empty($_REQUEST['latitude']) ? $_REQUEST['latitude'] : null;
			$post['longitude'] = !empty($_REQUEST['longitude']) ? $_REQUEST['longitude'] : null;
				$this->load->model('Api_Model');
				$cpost = array();
				$cpost['latitude'] = $post['latitude'];
				$cpost['longitude'] = $post['longitude'];
				$data = $this->Api_Model->updateTmpCurrentPosition($phone, $cpost);
				$res = json_encode($data);
				$cache = 0;
			$result = array('error' => 0, 'error_message' => "", 'data' => $data);
		} catch (Exception $e) {
			$result = array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode());
		}
		$this->json_($result);
	}
	
	public function nearbyusers()
	{
		$data = array();
		try {
			$post = array();
			$user_id = $this->user_id;
			$post['latitude'] = isset($_REQUEST['latitude']) ? $_REQUEST['latitude'] : null;
			$post['longitude'] = isset($_REQUEST['longitude']) ? $_REQUEST['longitude'] : null;
			$post['radius'] = isset($_REQUEST['radius']) ? $_REQUEST['radius'] : 100;
			$post['limit'] = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 1000;
			$this->load->model('Api_Model');
			$data = $this->Api_Model->nearbyusers($user_id, $post);
			$this->json_(array('error' => 0, 'error_message' => "", 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	public function del_location()
	{
		$user_id = $this->user_id;
		$location_id = $this->input->get('id');
		$this->load->model('Api_Model');
		$ret = $this->Api_Model->delete_location($user_id, $location_id);
		$this->json_($ret);
	}


	public function history()
	{
		$data = array();
		$this->load->model('Api_Model');
		$data['result'] = $this->Api_Model->get_history();
		$this->load->view('kundali/history', $data);
	}
}