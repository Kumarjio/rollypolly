<?php

define('RDIR', dirname(dirname(__FILE__)));
define('SITEDIR', RDIR);
$subTitle = '';
$pageTitle = '';
try {
include_once('../Connections/connAds.php');
$connMainAdodb = $connAdsAdodb;
function pr($d) { echo '<pre>'; print_r($d); echo '</pre>'; }

function isMobile()
{
  //Detect special conditions devices
  $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
  $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
  $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
  $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
  $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
  //do something with this information
  if( $iPod || $iPhone || $iPad || $Android || $webOS){
      //browser reported as a webOS device -- do something here
      return true;
  }
  return false;
}
if (!function_exists('regexp')) {
	function regexp($input, $regexp, $casesensitive=false)
	{
		if ($casesensitive === true) {
			if (preg_match_all("/$regexp/sU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		} else {
			if (preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		}

		return false;
	}
}

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



//my autoloader
function myautoload($class_name) {
    $classPath = RDIR.'/api/help/MkGalaxy/'.implode('/', explode('_', $class_name));
   if (file_exists($classPath.'.class.php')) {
    include_once $classPath . '.class.php';
   }
}
spl_autoload_register('myautoload', true);


//$modelGeneral = new Models_General();

class CMSActivities extends Models_General
{
  public function getRandomKeywords($limit=50)
  {
    
    $query = 'select * from short_keywords WHERE status = 1 ORDER BY RAND() LIMIT '.$limit;
    $result = $this->fetchAll($query, array(), 3600);
    return $result;
  }
  public function getTopKeywords($limit=50)
  {
    
    $query = 'select * from short_keywords WHERE status = 1 ORDER BY views DESC  LIMIT '.$limit;
    $result = $this->fetchAll($query, array(), 3600);
    return $result;
  }
  public function getRecentKeywords($limit=50)
  {
    
    $query = 'select * from short_keywords WHERE status = 1 AND updated_on != \'0000-00-00 00:00:00\' ORDER BY updated_on DESC  LIMIT '.$limit;
    $result = $this->fetchAll($query, array(), 3600);
    return $result;
  }
  public function getKeywordDetails($id)
  {
    $query = 'select * from short_keywords WHERE keyword_id = '.$this->qstr($id);
    $result = $this->fetchRow($query, array(), 3600);
    if (empty($result)) {
      $this->clearCache($query, array());
    }
    return $result;
  }
  public function getKeywordURL($url)
  {
    $query = 'select * from short_keywords WHERE kw_url_lookup = '.$this->qstr($url);
    $result = $this->fetchRow($query, array(), 3600);
    if (empty($result)) {
      $this->clearCache($query, array());
    }
    return $result;
  }
}


$CMSActivities = new CMSActivities();

$id = !empty($_GET['id']) ? $_GET['id'] : '';
$q = !empty($_GET['q']) ? $_GET['q'] : '';
if (!empty($id)) {
  $idDetails = $CMSActivities->getKeywordDetails($id);
} else if (empty($id) && !empty($q)) {
  $idDetails = $CMSActivities->getKeywordURL($q);
}

//ban words
function isBanWord($q) {
  $ban_array = array(
    'sex',
    'fuck',
    'nude',
    'porn',
    'bastard',
    'escort',
    'dildo',
    'ejaculate',
    'fellatio',
    'beastial',
    'bestial',
    'bitch',
    'blowjob',
    'boob',
    'clitoris',
    'cock',
    'cunilingus',
    'cumshot',
    'cunt',
    'cyberfuc',
    'cyberfuck',
    'dick',
    'damn',
    'dog-fucker',
    'fagot'
  );
  $isvalid = true;
  foreach ( $ban_array as $v ) {
    if ( strpos(strtolower($q), $v) !== false ) {
      //$url = 'http://cms.mkgalaxy.com';
      //header("Location: ".$url);
      //exit;
      return true;
    }
  }
  return false;
}
//band words ends
$isBanWord = isBanWord($q);

$s = !empty($_GET['s']) ? trim($_GET['s']) : '';
$sURL = !empty($_GET['s']) ? url_name_v2(trim($_GET['s'])) : '';
if (!empty($s)) {
  $tmpDetails = $CMSActivities->getKeywordURL($sURL);
  if (!empty($tmpDetails)) {
    $id = $tmpDetails['keyword_id'];
    $q = $tmpDetails['kw_url_lookup'];
  } else {
    $d = array();
    $d['keyword'] = $s;
    $d['kw_url_lookup'] = $sURL;
    $d['created_on'] = date('Y-m-d H:i:s');
    $d['ip'] = $_SERVER['REMOTE_ADDR'];
    $d['views'] = 0;
    $d['status'] = 1;
    $d['new'] = 1;
    $q = $sURL;
    $id = $CMSActivities->addDetails('short_keywords', $d);
  }
  $url = 'http://cms-'.$q.'.mkgalaxy.com';
  header("Location: ".$url);
  exit;
}

$query = "update short_keywords set views = views + 1 where keyword_id = ?";
$connMainAdodb->Execute($query, array($id));

$url = 'http://cms-'.$q.'.mkgalaxy.com';

$pageTitle = ucwords($idDetails['keyword']);
$recentKeywords = $CMSActivities->getRecentKeywords();
$randomKeywords = $CMSActivities->getRandomKeywords();
$topKeywords = $CMSActivities->getTopKeywords();

$request_uri = !empty($_GET['request_uri']) ? $_GET['request_uri'] : '/';

$page = substr($request_uri, 1);
if (empty($page) || !file_exists($page.'.php')) {
  $page = 'home';
}

ob_start();
include($page.'.php');
$contentForTemplate = ob_get_clean();
} catch (Exception $e) {
  $contentForTemplate = $e->getMessage;
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<title><?php echo $pageTitle; ?></title>
<link rel="stylesheet" href="http://mkgalaxy.com/cms/base.css?v=1">
<link rel="stylesheet" href="http://mkgalaxy.com/cms/style.css">
<link rel="icon" type="image/x-icon" href="http://mkgalaxy.com/cms/favicon.ico" />
<style type="text/css">
#htitle
{
	font-size: 24px;
	color: rgb(255,255,255);
	text-decoration: none;
	float:left;
}

</style>

</head>

<body class="jquery-ui search search-no-results listing single-author">
<!--jquery home page page-id-5 page-template page-template-page-fullwidth-php page-slug-index single-author singular-->
<header>
	<section id="global-nav">
		<nav>
			<div class="constrain">
				<ul class="projects">
					<!--<li class=""><a href="#" title="">Project A</a></li>
					<li class=""><a href="#" title="">Project B</a></li>
					<li class=""><a href="#" title="">Project C</a></li>-->
				</ul>
				<ul class="links">
					<!--<li><a href="#">Menu1</a></li>
					<li class="dropdown"><a href="#">Menu2</a>
						<ul>
							<li><a href="#">Submenu1</a></li>
							<li><a href="#">Submenu2</a></li>
						</ul>
					</li>-->
				</ul>
			</div>
		</nav>
	</section>
</header>


<div id="container">
	<div id="logo-events" class="constrain clearfix">
		<a href="http://cms.mkgalaxy.com/"><h2 class="" id="htitle">MKGalaxy World</h2></a>

		<aside></aside>
	</div>

	<nav id="main" class="constrain clearfix">
		<div class="menu-top-container">
	<ul id="menu-top" class="menu">
    <li class="menu-item"><a href="<?php echo $url; ?>">Home</a></li>
	</ul>
</div>

		<form method="get" class="searchform" action="">
	<button type="submit" class="icon-search"><span class="visuallyhidden">search</span></button>
	<label>
		<span class="visuallyhidden">Search</span>
		<input type="text" name="s" value="<?php echo $s; ?>"
			placeholder="Search...">
	</label>
</form>
	</nav>

	<div id="content-wrapper" class="clearfix row">

<div class="content-right twelve columns">
	<div id="content">

		<header class="page-header">
			<h1 class="page-title"><?php echo $pageTitle; ?></h1>
			<hr>
		</header>

				<article id="post-0" class="post no-results not-found">
        <?php //include('googleads.php'); ?>
				<header class="entry-header">
					<h1 class="entry-title"><?php echo $subTitle; ?></h1>
				</header>

				<div class="entry-content">
					<?php echo $contentForTemplate; ?>
				</div>
			</article>
			</div>

	<div id="sidebar" class="widget-area" role="complementary">
  
  <?php include('googleads.php'); ?>
  <?php if (!empty($recentKeywords)) { ?>
	<aside class="widget">
		<h3 class="widget-title">Recent Applications</h3>
		<ul>
      <?php foreach ($recentKeywords as $k => $v) { ?>
			<li><a href="http://cms-<?php echo trim($v['kw_url_lookup']); ?>.mkgalaxy.com"><?php echo ucwords($v['keyword']); ?> (<?php echo $v['views']; ?> Views)</a> </li>
      <?php } ?>
		</ul>
	</aside>
    <?php } ?>
  <aside class="widget">
  <?php include('googleads.php'); ?>
  </aside>
  <?php if (!empty($topKeywords)) { ?>
	<aside class="widget">
		<h3 class="widget-title">Top Applications</h3>
		<ul>
      <?php foreach ($topKeywords as $k => $v) { ?>
			<li><a href="http://cms-<?php echo trim($v['kw_url_lookup']); ?>.mkgalaxy.com"><?php echo ucwords($v['keyword']); ?> (<?php echo $v['views']; ?> Views)</a> </li>
      <?php } ?>
		</ul>
	</aside>
    <?php } ?>
  <?php if (!empty($randomKeywords)) { ?>
	<aside class="widget">
		<h3 class="widget-title">Useful Applications</h3>
		<ul>
      <?php foreach ($randomKeywords as $k => $v) { ?>
			<li><a href="http://cms-<?php echo trim($v['kw_url_lookup']); ?>.mkgalaxy.com"><?php echo ucwords($v['keyword']); ?> (<?php echo $v['views']; ?> Views)</a> </li>
      <?php } ?>
		</ul>
	</aside>
    <?php } ?>
</div>
</div>

	</div>
</div>

<footer class="clearfix simple">
	<br /><br /><br /><br /><br /><br /><br /><br />
</footer>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-58206389-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>