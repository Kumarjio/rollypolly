<?php
session_name('my_session');
//ini_set('session.cookie_domain', '.mkgalaxy.com'); 
//ini_set('session.cookie_domain', '.'.$_SERVER['HTTP_HOST']);
session_start();
define('ROOTDIR', dirname(__FILE__));
define('SITEDIR', dirname(__FILE__));
$dir = dirname($_SERVER['PHP_SELF']);
if ($dir == '/') $dir = '';
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
define('SITENAME', ucwords('MKGalaxy.com'));
//define('HTTPPATH', 'http://mkgalaxy.com'.$dir);
define('ROOTDOMAIN', $host);
define('HTTPPATH', 'http://'.$host.$dir);
define('ROOTHTTPPATH', $dir);
define('APIDIR', $dir.'/api');
//define('APIHTTPPATH', 'http://mkgalaxy.com'.APIDIR);
define('APIHTTPPATH', 'http://'.$host.APIDIR);
define('ADMIN_EMAIL', 'mkgxy@mkgalaxy.com');
//define('HOROPOINTURL', 'http://horo.mkgalaxy.com/api/matchMultiLatLonDays');
define('LOGINURL', '/users/login');
define('PLACESAPIKEY', 'AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw');
define('DEFAULT_LATITUDE', 37.3867);
define('DEFAULT_LONGITUDE', -121.897);
define('ENCRYPTKEY', 'JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN');


define('IMAGESHACK_KEY', '146HIJUV6647a9b0459fac0d39cd709c328dace3');
define('IMAGESHACK_USERNAME', 'websmc');
define('IMAGESHACK_PASSWORD', 'myflash74');
define('IMAGESHACK_APIURL_IMAGES', 'http://www.imageshack.us/upload_api.php');
define('IMAGESHACK_APIURL_VIDEOS', 'http://render.imageshack.us/upload_api.php');

//for home api - zillow
define('ZWSID', 'X1-ZWz1e2ncdidv63_7vqv1');

//more to call class
include_once(SITEDIR.'/Connections/connMain.php');
$filename = str_replace(':', '_', $host);

if (!file_exists(SITEDIR.'/configs/'.$filename.'.php')) {
  header("Location: ".HTTPPATH);
  exit;
}
include_once(SITEDIR.'/configs/'.$filename.'.php');
set_include_path(get_include_path(). PATH_SEPARATOR. SITEDIR.'/libraries/library'. PATH_SEPARATOR. SITEDIR.'/libraries/pear'. PATH_SEPARATOR. SITEDIR.'/libraries');
require_once('FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true);
$firephp->setEnabled(true);
//my autoloader
function myautoload($class_name) {
    $classPath = SITEDIR.'/api/help/MkGalaxy/'.implode('/', explode('_', $class_name));
   if (file_exists($classPath.'.class.php')) {
    include_once $classPath . '.class.php';
   }
}
spl_autoload_register('myautoload', true);
//zend autoloadar
require_once('Zend/Loader/Autoloader.php');
if (class_exists('Zend_Loader_Autoloader', false))
{
  Zend_Loader_Autoloader::getInstance();
}
//end

function log_error($message, $key='')
{
  global $firephp;
  $firephp->error($message, $key);
  $firephp->trace('Trace');
}

function log_log($message, $key='')
{
  global $firephp;
  $firephp->log($message, $key);
}

function log_info($message, $key='')
{
  global $firephp;
  $firephp->info($message, $key);
}


function log_warn($message, $key='')
{
  global $firephp;
  $firephp->warn($message, $key);
}

$modelGeo = new Models_Geo();
$modelGeneral = new Models_General();

include_once('constants.php');
include_once('functions.php');

//$ip = $_SERVER['REMOTE_ADDR'];
//if (!($ip === '67.164.21.22')) {
  //echo 'access not allowed';
  //exit;
//}
