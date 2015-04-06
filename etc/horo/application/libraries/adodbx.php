<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class adodbx
{

	public function __construct()
	{
		if ( ! class_exists('ADONewConnection') )
		{
			require_once(APPPATH.'libraries/adodb/adodb.inc.php');
			require_once(APPPATH.'libraries/adodb/adodb-error.inc.php');
		}
	
		$obj =& get_instance();
		$this->_init_adodb_library($obj);
	}

	private function _convert_driver($driver)
	{
		$arr = array('postgre' => 'postgres');
		if (array_key_exists($driver, $arr)) {
			return $arr[$driver];
		}

		return $driver;
	}

	public function _init_adodb_library(&$ci)
	{
		$db_var = false;
		$debug = false;
		$show_errors = true;
		$active_record = false;
		$db = NULL;

		if (!isset($dsn)) {
			// fallback to using the CI database file 
			include(APPPATH.'config/database'.EXT); 
			$group = 'default'; 
			$driver = $this->_convert_driver($db[$group]['dbdriver']);
			$dsn = $driver.'://'.$db[$group]['username'] 
			.':'.$db[$group]['password'].'@'.$db[$group]['hostname']
			.'/'.$db[$group]['database'];
		}
		
		// Show Message Adodb Library PHP
		if ($show_errors) {
			require_once(APPPATH.'libraries/adodb/adodb-errorhandler.inc'.EXT);
		}

		// $ci is by reference, refers back to global instance
		//$ADODB_CACHE_DIR = $path.'/tmp/adodbcache';
		$ci->adodb =& ADONewConnection($dsn);
		////$connAdodb->LogSQL();
		// Use active record adodbx
		$ci->adodb->setFetchMode(ADODB_FETCH_ASSOC);

		if ($db_var) {
			// Also set the normal CI db variable
			$ci->db =& $ci->adodb;
		}

		if ($active_record) {
			require_once(APPPATH.'libraries/adodb/adodb-active-record.inc'.EXT);
			ADOdb_Active_Record::SetDatabaseAdapter($ci->adodbx);
		}

		if ($debug) {
			$ci->adodb->debug = true;
		}
	}

	/*
	* Function Factory for call ADODB Active Record function
	*/
	public function ADOdb_Active_Record_Factory($classname, $tablename = null) {
		// create the class
		eval('class '.$classname.' extends ADOdb_Active_Record{}');

		if ($tablename != null) {
			return new $classname($tablename);
		} else {
			return new $classname;
		}
	}
}
?>