<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{


	public function index()
	{
		//$this->layout->view('welcome_message');
	}

	public function logout($url='')
	{
		if (!empty($_COOKIE['user_id'])) {
			setcookie('name', '', -300, '/');
			setcookie('user_id', '', -300, '/');
			setcookie('email', '', -300, '/');
			setcookie('gender', '', -300, '/');
			setcookie('dob', '', -300, '/');
			setcookie('reference', '', -300, '/');
			setcookie('pic', '', -300, '/');
			setcookie('MM_UserGroup', '', -300, '/');
			setcookie('username', '', -300, '/');
			setcookie('MM_Username', '', -300, '/');
			setcookie('access_token', '', -300, '/');
		}

		redirect($this->config->item('base_url').$url);
	}

	public function login()
	{
		$email = $this->input->get_post('email');
		$password = $this->input->get_post('password');
		$url = 'http://wc5.org/api/v1/index.php?type=user&action=login&email='.$email.'&password='.$password;
		$result = curlget($url);
		$result = json_decode($result, 1);
		if ($result['error'] == 0 && !empty($result['result'])) {
			$result['url'] = $this->session->userdata('redirect_url');
			$this->session->unset_userdata('redirect_url');
			$time = 0;
			foreach ($result['result'] as $k => $v) {
				if (is_numeric($k))
					continue;
				setcookie($k, $v, $time, '/');
			}
		}

		$this->json_($result);
	}

	public function register()
	{
		$email = $this->input->get_post('email');
		$password = $this->input->get_post('password');
		$username = $this->input->get_post('username');
		$gender = $this->input->get_post('gender');
		$name = $this->input->get_post('name');
		$email = urlencode($email);
		$password = urlencode($password);
		$username = urlencode($username);
		$gender = urlencode($gender);
		$name = urlencode($name);
		$url = 'http://wc5.org/api/v1/index.php?type=user&action=register&username='.$username.'&name='.$name.'&email='.$email.'&password='.$password.'&gender='.$gender;
		$result = curlget($url);
		$result = json_decode($result, 1);
		if ($result['error'] == 0 && !empty($result['result'])) {
			$result['url'] = $this->session->userdata('redirect_url');
			$this->session->unset_userdata('redirect_url');
			$time = 0;
			foreach ($result['result'] as $k => $v) {
				if (is_numeric($k))
					continue;
				setcookie($k, $v, $time, '/');
			}
		}

		$this->json_($result);
	}

	public function forgot()
	{
		$email = $this->input->get_post('email');
		$url = 'http://wc5.org/api/v1/index.php?type=user&action=forgot&email='.$email;
		$result = curlget($url);
		$result = json_decode($result, 1);
		$this->json_($result);
	}
}