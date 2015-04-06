<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	public function horo()
	{
		$data['steps'] = $this->load->view('pages/steps', array(), true);
		$this->load->view('kundali/pages', $data);
	}

	public function sn()
	{
		$data['steps'] = $this->load->view('pages/steps', array(), true);
		$this->load->view('sn/pages', $data);
	}
}