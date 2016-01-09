<?php
define('RDIR', dirname(dirname(__FILE__)));
define('SITEDIR', RDIR);
define('SITE_DIR', SITEDIR);
set_include_path(get_include_path(). PATH_SEPARATOR. SITEDIR.'/libraries/library'. PATH_SEPARATOR. SITEDIR.'/libraries/pear');
$arr = explode('.', $_SERVER['HTTP_HOST']);
$baseDomain = $arr[(count($arr) - 2)].'.'.$arr[(count($arr) - 1)];
define('BASEDOMAIN', $baseDomain);

$subTitle = '';
$pageTitle = '';
try {
include_once('../Connections/connMain.php');

//zend autoloadar
require_once('Zend/Loader/Autoloader.php');
if (class_exists('Zend_Loader_Autoloader', false))
{
  Zend_Loader_Autoloader::getInstance();
}

function pr($d) { echo '<pre>'; print_r($d); echo '</pre>'; }

//my autoloader
function myautoload($class_name) {
    $classPath = RDIR.'/api/help/MkGalaxy/'.implode('/', explode('_', $class_name));
   if (file_exists($classPath.'.class.php')) {
    include_once $classPath . '.class.php';
   }
}
spl_autoload_register('myautoload', true);

$params = array();
$params['lifetime'] = (60 * 60 * 24 * 5);
$Library_cache = new Library_cache($params);


function url_name_v2($name='')
{
  if (empty($name)) {
    return $name;
  }
  
  $patterns = array();
  $patterns[0] = "/\s+/";
  $patterns[1] = '/[^A-Za-z0-9]+/';
  $replacements = array();
  $replacements[0] = "-";
  $replacements[1] = '-';
  ksort($patterns);
  ksort($replacements);
  $output = preg_replace($patterns, $replacements, $name);
  $output = strtolower($output);
  return $output;
}//end list_name_url()

//$modelGeneral = new Models_General();

class Cities extends Models_General
{
  public function __construct() {
    parent::__construct();
    $this->_connMain->Execute("SET NAMES utf8");
  }
 
  public function getList()
  {
    
    $query = 'SELECT * 
FROM  `geo_cities` 
WHERE  `extraDetails` IS NULL
ORDER BY RAND()
LIMIT 0 , 30';
    $result = $this->fetchAll($query, array(), 0);
    return $result;
  }
}

$cities = new Cities();
$list = $cities->getList();


} catch (Exception $e) {
  
}
?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>

<h1>Cities</h1>
<?php foreach ($list as $city) { ?>
<div><a href="<?php echo 'http://mkgalaxy.com/city-'.url_name_v2($city['name']).'-'.$city['cty_id']; ?>" target="_blank"><?php echo $city['name']; ?></a></div>
<?php } ?>
<p>&nbsp;</p>
</body>
</html>