<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Horo extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->_login();
		$this->load->library('Kundali');
	}

	public function index()
	{
		echo 'coming soon';
	}

	public function matchmaking()
	{
		echo 'coming soon';
	}

	public function matchmakingprofile()
	{
		$data = array();
		try {
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$data['profiles'] = $this->Horo_Model->get_birth_profile($user_id);
		} catch (Exception $e) {
			
		}
		$this->load->view('kundali/matchmakingprofile', $data);
	}

	
	public function matchmakingprofilepost()
	{
		try {
			if (empty($_POST['bid']))
				throw new Exception('Empty Data');
			$data = array();
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$post = $_POST;
			$bd = $this->Horo_Model->get_birth_profile_particular($user_id, $post['bid']);
			$data['profile'] = $bd;
			$this->load->library('Kundali');
			$profiles = $this->Horo_Model->get_birth_profile($user_id);
			if (!empty($profiles)) {
				foreach ($profiles as $k => $v) {
					$date = $v['byear'].'-'.$v['bmonth'].'-'.$v['bday'].' '.$v['bhour'].':'.$v['bmin'];
					$pts = $this->kundali->getpoints($data['profile']['horo'][9], $v['horo'][9]);
					$data['result_points'][] = array('date' => $date, 'points' => $pts, 'naks' => $v['horo'][7], 'ref' => $v['horo'][9], 'result' => $this->kundali->interpret($pts), 'name' => $v['bname']);
				}
			}

			$html = $this->load->view('kundali/matchmakingprofile_list', $data, true);
			$this->json_(array('error' => 0, 'error_message' => '', 'html' => $html));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	public function fetch()
	{
		header('content-type: application/json; charset=utf-8');
		if ($this->input->get('q')) {
			$url = "http://wc5.org/api/v1/fetch.php?q=".urlencode($this->input->get('q'));
			$c = file_get_contents($url);
			echo $c;
		} else if ($this->input->get('timezone') && $this->input->get('lat') && $this->input->get('lng')) {
			$url = "http://wc5.org/api/v1/fetch.php?timezone=1&lat=".$this->input->get('lat')."&lng=".$this->input->get('lng');
			$c = file_get_contents($url);
			echo $c;
		}
	}

	public function location()
	{
		$this->load->view('kundali/location');
	}

	public function locationset()
	{
		$post = $_POST;
		$post['user_id'] = $_COOKIE['user_id'];
		$this->load->model('Horo_Model');
		$ret = $this->Horo_Model->set_location($post);
		$this->json_($ret);
		//header('content-type: application/json; charset=utf-8');
		//echo json_encode($ret);
	}

	
	public function mylocation()
	{
		$user_id = $_COOKIE['user_id'];
		$this->load->model('Horo_Model');
		$ret = $this->Horo_Model->get_location($user_id);
		$this->load->view('kundali/mylocation', $ret);
	}

	public function del_location()
	{
		$user_id = $_COOKIE['user_id'];
		$location_id = $this->input->get('id');
		$this->load->model('Horo_Model');
		$ret = $this->Horo_Model->delete_location($user_id, $location_id);
		$this->json_($ret);
	}

	public function mybirthdetails()
	{
		$data = array();
		try {
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$ret = $this->Horo_Model->get_location($user_id);
			$data['location'] = !empty($ret['records']) ? $ret['records'] : array();
			$data['records'] = $this->Horo_Model->get_birth_profile($user_id);
			$data['birthprofileview'] = $this->load->view('kundali/birthdetails_list', $data, true);
		} catch (Exception $e) {
			
		}
		$this->load->view('kundali/mybirthdetails', $data);
	}

	public function mybirthdetailspost()
	{
		try {
			if (empty($_POST))
				throw new Exception('Empty Data');
			$data = array();
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$post = $_POST;
			$this->Horo_Model->insert_birth_profile($user_id, $post);
			$data['records'] = $this->Horo_Model->get_birth_profile($user_id);
			$html = $this->load->view('kundali/birthdetails_list', $data, true);
			$this->json_(array('error' => 0, 'error_message' => '', 'html' => $html));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	
	public function bestmatches()
	{
		$data = array();
		try {
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$data['profiles'] = $this->Horo_Model->get_birth_profile($user_id);
		} catch (Exception $e) {
			
		}
		$this->load->view('kundali/bestmatches', $data);
	}

	public function bestmatchespost()
	{
		try {
			if (empty($_POST['bid']))
				throw new Exception('Empty Data');
			$data = array();
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$post = $_POST;
			$bd = $this->Horo_Model->get_birth_profile_particular($user_id, $_POST['bid']);
			$data['profile'] = $bd;
			$this->load->library('Kundali');
			$points = $this->kundali->points();
			$specific_points = $points[$bd['horo'][9]];
			arsort($specific_points);
			$data['records'] = array();
			foreach ($specific_points as $k => $v) {
				$data['records'][$k]['number'] = $k;
				$data['records'][$k]['nakshatra'] = $this->kundali->getnaksfromnumber($k);
				$data['records'][$k]['points'] = $v;
			}

			$html = $this->load->view('kundali/bestmatches_list', $data, true);
			$this->json_(array('error' => 0, 'error_message' => '', 'html' => $html));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	
	public function dailymatches()
	{
		$data = array();
		try {
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$data['profiles'] = $this->Horo_Model->get_birth_profile($user_id);
			$ret = $this->Horo_Model->get_location($user_id);
			$data['location'] = !empty($ret['records']) ? $ret['records'] : array();
		} catch (Exception $e) {
			
		}
		$this->load->view('kundali/dailymatches', $data);
	}



	public function dailymatchespost()
	{
		try {
			date_default_timezone_set('Africa/Casablanca');
			if (empty($_POST['bid']))
				throw new Exception('Empty Data');
			if (empty($_POST['dday']))
				throw new Exception('Empty day');
			if (empty($_POST['dmonth']))
				throw new Exception('Empty month');
			if (empty($_POST['dyear']))
				throw new Exception('Empty year');
			if (empty($_POST['location_id']))
				throw new Exception('Empty location');
			$data = array();
			$user_id = $_COOKIE['user_id'];
			$this->load->model('Horo_Model');
			$post = $_POST;
			$bd = $this->Horo_Model->get_birth_profile_particular($user_id, $post['bid']);
			$data['profile'] = $bd;
			$loc = $this->Horo_Model->get_location_particular($post['location_id']);
			$this->load->library('Kundali');
			$points = $this->kundali->points();
			$from = $post['dday'];
			$post['dmin'] = 0;
			$freq = !empty($post['frequency']) ? (int) $post['frequency'] : 1;
			$html = '';
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
					$data['result_points'][] = array('date' => $date, 'points' => $pts, 'naks' => $returnArr[7], 'ref' => $returnArr[9], 'result' => $this->kundali->interpret($pts));
				}
			}

			$html .= $this->load->view('kundali/dailymatches_list', $data, true);
			$this->json_(array('error' => 0, 'error_message' => '', 'html' => $html));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}

	public function history()
	{
		$data = array();
		$this->load->model('Horo_Model');
		$data['result'] = $this->Horo_Model->get_history();
		$this->load->view('kundali/history', $data);
	}
}