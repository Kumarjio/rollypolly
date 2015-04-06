<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sn extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->_login();
	}

	public function index()
	{
		echo 'hi';
	}

}