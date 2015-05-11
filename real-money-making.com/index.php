<?php
session_name('my_session');
session_start();
define('SITEDIR', dirname(__FILE__));
define('ROOTDIR', dirname(dirname(__FILE__)));

$dir = dirname($_SERVER['PHP_SELF']);
if ($dir == '/') $dir = '';
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
define('ROOTDOMAIN', $host);
define('HTTPPATH', 'http://'.$host.$dir);
define('ROOTHTTPPATH', $dir);
define('LOGINURL', '/users/login');
//google


define('CLIENTID', '437724595536-9lpldtcmu2i1meoqoaid2b4anvoiei4s.apps.googleusercontent.com');
define('CLIENTSECRET', 'vXT--wPGsLgzRSH2aDrQh8OU');
define('DEVELOPERKEY', 'AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw');

define('COOKIE_FILE_PATH', SITEDIR.'/cache');

//include path
set_include_path(get_include_path(). PATH_SEPARATOR. SITEDIR.'/libraries/library'. PATH_SEPARATOR. SITEDIR.'/libraries/pear'. PATH_SEPARATOR. SITEDIR.'/libraries');
//functions

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



//functions end
$pageTitle = 'Welcome to Real Money Making';
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
<!DOCTYPE html>
<html lang="en" data-ng-app="GroupJole">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title><?php echo $pageTitle; ?></title>
<base href="<?php echo HTTPPATH; ?>/" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<!-- GOOGLE FONT-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- /GOOGLE FONT-->


<!-- Le styles -->
<!-- Latest compiled and minified CSS BS 3.0. -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/theme3.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!-- Fav and touch icons -->


<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/ico/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="wrap">
	<section>
		<nav id="topnav" class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">Real-Money-Making.Com</a>
				</div>
				
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="/">Home</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
					</ul>
					<div class="nav navbar-nav navbar-right">
                        <?php if (empty($_SESSION['user']['id'])) { ?>
						 <a class="btn btn-danger navbar-btn" href="/users/login">Login</a> <?php } else { ?> <a class="btn btn-success navbar-btn" href="/users/login?logout=1">Logout</a><?php } ?>
					</div>
				</div>
				
			</div>
		</nav>
	</section>
    <?php echo $contentForTemplate; ?>
    <section class="custom-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-7">
                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
                            <div>
                                <ul class="list-unstyled">
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4  col-xs-6">
                            <div>
                                <ul class="list-unstyled">
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
                            <div>
                                <ul class="list-unstyled">
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                    <li>
                                         <a>Link anchor</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-5">
                     <span class="text-right"> <address> <strong>Twitter, Inc.</strong><br /> 795 Folsom Ave, Suite 600<br /> San Francisco, CA 94107<br /> <abbr title="Phone">P:</abbr> (123) 456-7890</address> <address> <strong>Full Name</strong><br /> <a href="mailto:#">first.last@example.com</a></address></span>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>

<script src="assets/js/ang/app.js"></script>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.js"></script>


</body>
</html>