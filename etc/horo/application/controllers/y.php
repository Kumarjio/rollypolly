<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Y extends MY_Controller
{

	protected $cache;

	protected $cache2;

	protected $limit = 50;

	public function __construct()
	{
		parent::__construct();
		$time = (60*60*24*7);
		$time2 = (60*60*24*30);
		$frontendOptions = array(
			'lifetime' => $time, // cache lifetime of 2 hours
			'automatic_serialization' => true
		);
		$frontendOptions2 = array(
			'lifetime' => $time2, // cache lifetime of 2 hours
			'automatic_serialization' => true
		);
 
		$backendOptions = array(
			'cache_dir' => COOKIE_FILE_PATH.'/zendcache/' // Directory where to put the cache files
		);
 
		// getting a Zend_Cache_Core object
		$this->cache = Zend_Cache::factory('Core',
			 'File',
			 $frontendOptions,
			 $backendOptions);
		$this->cache2 = Zend_Cache::factory('Core',
			 'File',
			 $frontendOptions2,
			 $backendOptions);
	}

	public function index()
	{
		//$this->layout->view('welcome_message');
	}

	public function questions()
	{
		$data = array();
		if( ($rs = $this->cache->load('answers')) !== false ) {
			$data['tags'] = $rs;
		}
		$this->load->view('y/questions', $data);
	}
	public function answers()
	{
		$data = array('error' => 1, 'url' => '');
		if ($this->input->get('kw')) {
			$kw = $this->input->get('kw');
			if( ($rs = $this->cache->load('answers')) === false ) {
				$rs[$kw] = $kw;
				$this->cache->save($rs, 'answers');
			} else {
				$rs1[$kw] = $kw;
				$rs = array_merge($rs1, $rs);
				$rs = array_slice($rs, 0, $this->limit);
				$this->cache->save($rs, 'answers');
			}
			$string = md5($kw);
			$kw = urlencode($kw);
			$url = 'http://answers.yahooapis.com/AnswersService/V1/questionSearch?appid='.YAHOOAPPLICATIONID.'&query='.$kw.'&results=50&output=json';
			if( ($rs2 = $this->cache2->load($string)) === false ) {
				$result = curlget($url);
				$result = json_decode($result, true);
				if (!empty($result['all']['questions'])) {
					$data = array();
					$data['records'] = $result['all']['questions'];
					$this->cache2->save($data, $string);
				} else {
					$url = 'http://answers.yahooapis.com/AnswersService/V1/questionSearch?appid='.YAHOOAPPLICATIONID.'&query='.$kw.'&results=50&output=json';
					if( ($rs2 = $this->cache2->load($string)) === false ) {
						$result = curlget($url);
						$result = json_decode($result, true);
						if (!empty($result['all']['questions'])) {
							$data = array();
							$data['records'] = $result['all']['questions'];
							$this->cache2->save($data, $string);
						}
					}
				}
			} else {
				$data = $rs2;
			}
			$data['url'] = $url;
		}

		//$this->load->view('y/answers', $data);
		$data['html'] = $this->load->view('y/answers', $data, true);
		$this->_custom_output('', array('html' => $data['html'], 'url' => $data['url']), true, 'json');
	}
}