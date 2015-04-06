<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{

	private $obj;

	private $layout;

	function Layout($layout="layout_main")
	{
		$this->obj =& get_instance();
		$this->layout = $layout;
	}

	function setLayout($layout)
	{
		$this->layout = $layout;
	}

	function view($view, $data=array(), $return=false)
	{
		$loadedData = array();
		$loadedData['content_for_layout'] = $this->obj->load->view($view, $data, true);
		$loadedData = array_merge($loadedData, $data);

		// loading default
		if (empty($loadedData['title'])) {
			$loadedData['title'] = TITLE;
		}

		if($return)
		{
			$output = $this->obj->load->view($this->layout, $loadedData, true);
			return $output;
		}
		else
		{
			$this->obj->load->view($this->layout, $loadedData, false);
		}
	}
}
?>