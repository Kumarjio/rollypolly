<?php
require(APPPATH.'libraries/REST_Controller.php');
class Example_api extends REST_Controller
{
	function user_get($id)  
	{
		// respond with information about a user
		$data = array('returned get: '. $id);
		$this->response($data);
	}

	function user_put()  
	{
		// create a new user and respond with a status/errors  
		$data = array('returned put: '. $this->input->put('id'));
		$this->response($data);
	}
	
	function user_post()  
	{
		// update an existing user and respond with a status/errors  
		$data = array('returned post: '. $this->input->post('id'));
		$this->response($data);
	}
	
	function user_delete()  
	{
		// delete a user and respond with a status/errors  
		$data = array('returned delete: '. $this->input->delete('id'));
		$this->response($data);
	}
}