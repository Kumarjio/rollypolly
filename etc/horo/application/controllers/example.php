<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->library('twilio');

		$from = '+14085052726';
		$to = '+14085052751';
		$message = 'This is a test...';

		$response = $this->twilio->call($from, $to, $message);


		if($response->IsError)
			echo 'Error: ' . $response->ErrorMessage;
		else
			echo 'Sent message to ' . $to;
	}

}