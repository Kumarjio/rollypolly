<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_Chain extends MY_Controller
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
		$this->load->driver('cache');
		$this->load->library('email');
		/*
		$this->email->from('mkgxy@mkgalaxy.com', 'From Admin');
		$this->email->to('mkgxy@mkgalaxy.com'); 
		
		$this->email->subject('Email at api horo');
		$this->email->message('get: '.var_export($_GET, 1).', post: '.var_export($_POST, 1));
		$this->email->send();
		
		
		$validurls = array('login', 'register', 'fetchcity');
		if (!in_array($this->router->method, $validurls)) {
			if (!isset($_GET['token'])) {
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
		}*/
	}
	
	
	public function currentUser()
	{
		$data = array();
		try {
			$this->load->model('Api_Model');
			$data = $this->Api_Model->getCurrentUser();
			$this->json_(array('error' => 0, 'error_message' => "", 'data' => $data));
		} catch (Exception $e) {
			$this->json_(array('error' => 1, 'error_message' => $e->getMessage(), 'code' => $e->getCode()));
		}
	}
}