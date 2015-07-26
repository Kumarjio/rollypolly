<?php
session_name('my_session');
session_start();
define('SITEDIR', dirname(__FILE__));
define('ROOTDIR', dirname(dirname(dirname(__FILE__))));
define('SITENAME', 'Religion of Humanity');

$dir = dirname($_SERVER['PHP_SELF']);
if ($dir == '/') $dir = '';
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
define('ROOTDOMAIN', $host);
define('HTTPPATH', 'http://'.$host.$dir);
define('ROOTHTTPPATH', $dir);
define('LOGINURL', '/users/login');
//google

define('CLIENTID', '437724595536-f03d4v33miaklu3bm6c9a9h66bpcjcko.apps.googleusercontent.com');
define('CLIENTSECRET', '7MwIuBxomLeLIJv-Qip7G_us');
define('DEVELOPERKEY', 'AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw');

define('COOKIE_FILE_PATH', ROOTDIR.'/cache');

//include path
set_include_path(get_include_path(). PATH_SEPARATOR. ROOTDIR.'/libraries/library'. PATH_SEPARATOR. ROOTDIR.'/libraries/pear'. PATH_SEPARATOR. ROOTDIR.'/libraries');
//functions

define('EMAILTEMPLATEDIR', SITEDIR.'/emails/templates');


if (!function_exists('curlget')) {
	function curlget($url, $post=0, $POSTFIELDS='') {
		$https = 0;
		if (substr($url, 0, 5) === 'https') {
			$https = 1;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);  
		if (!empty($post)) {
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
		curl_setopt($ch, CURLOPT_COOKIEJAR,COOKIE_FILE_PATH);
		if (!empty($https)) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}

		$result = curl_exec($ch); 
		curl_close($ch);
		return $result;
	}
}


if (!function_exists('pr')) {
function pr($d){
	echo '<pre>';
	print_r($d);
	echo '</pre>';
}
}

if (!function_exists('checkLogin')) {
    function checkLogin()
    {
        if (empty($_SESSION['user'])) {
            $url = '/users/login?redirect_url='.urlencode($_SERVER['REQUEST_URI']);
            header("Location: ".$url);
            exit;   
        }
    }
}


if (!function_exists('guid')) {
function guid()
{
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $guid = substr($charid, 0, 8) . '-' .
            substr($charid, 8, 4) . '-' .
            substr($charid, 12, 4) . '-' .
            substr($charid, 16, 4) . '-' .
            substr($charid, 20, 12);
   return $guid;
}
}

include_once('emails/email.class.php');


include('connGroupjole.php');
$connMainAdodb = $connGroupjoleAdodb;
include('General.class.php');
$General = new General();

//functions end
$pageTitle = 'Religion of Humanity';
$page = 'home';
$p = !empty($_GET['p']) ? $_GET['p'] : 'home';
//$p = str_replace('.mk', '', $p);
if (!empty($p) && file_exists('pages/'.$p.'.php')) {
    $page = $p;
}
$fileToInclude = 'pages/'.$page.'.php';

ob_start();
include($fileToInclude);
$contentForTemplate = ob_get_clean();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle; ?></title>
<base href="<?php echo HTTPPATH; ?>/" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<style type="text/css">
body {
    font-family: Verdana;
    font-size: 11px;    
}

.error {
    font-weight: bold;
    color: #F00;    
}
</style>
</head>

<body>
<p>Heder</p>
<?php echo $contentForTemplate; ?>
<p>Footer</p>
</body>
</html>