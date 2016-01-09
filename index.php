<?php
include_once('init.php');

$defaultPage = 'home';
$page = $defaultPage;
if (!empty($_GET['p'])) {
  $page = $_GET['p'];
}
$page .= '.php';
$pageTitle = 'World Cities';



ob_start();
if ($host !== 'mkgalaxy.com' && empty($_SESSION['sub_city_location'])) {
  $params['where'] = sprintf(" AND d.domain = %s AND d.status = 1", $modelGeneral->qstr($_SERVER['HTTP_HOST']));
  $params['fields'] = ' d.*, c.*, s.name as state, ci.name as country';
  $params['cacheTime'] = 60*60*24;
  $tableName = 'z_domains as d LEFT JOIN geo_cities as c ON d.city_id = c.cty_id LEFT JOIN geo_states as s ON c.sta_id = s.sta_id LEFT JOIN geo_countries as ci ON c.con_id = ci.con_id';
  $cityData = $modelGeneral->getDetails($tableName, 1, $params);
  if (!empty($cityData[0])) {
    $cityDataMore = findCity($cityData[0]['city_id']);
    $_SESSION['sub_city_location']['url'] = $cityDataMore['url'];
    $_SESSION['sub_city_location']['title'] = $cityDataMore['city'];
    $_SESSION['sub_city_location']['id'] = $cityData[0]['city_id'];
    header("Location: ".$_SESSION['sub_city_location']['url']);
    exit;
  }
}


if (!empty($_GET['locationFind']) && isset($_GET['q'])) {
  $tmp = explode('/', $_GET['q']);
  $tmp = array_filter($tmp);

  if (empty($tmp)) {
    include('mods/home.php');
  } else {
    $path = implode('/', $tmp);
    if (file_exists('mods/'.$path.'.php')) {
      include('mods/'.$path.'.php');
    } else {
      include('mods/home.php');
    }
  }
} else {
  if (!empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/') {
    if (!empty($_COOKIE['redirect_url']) && empty($_GET['main'])) {
      header("Location: ".$_COOKIE['redirect_url']);
      exit;
    }
  }
  
  if ($page == 'home.php' && empty($_GET['main'])) {
    //header("Location: http://mkgalaxy.com/city-san-jose-144008");
    //exit;
    $iptoCity = new Models_Iptocity();
    $ip = !empty($_GET['remote_addr']) ? $_GET['remote_addr'] : $_SERVER['REMOTE_ADDR'];
    if ($ip === '::1') {
      $ip = '67.164.21.22';
    }
    $rec = $iptoCity->getCity($ip);//206.208.102.250//173.248.170.131//67.164.21.22
    /*$message = "IP: ".$ip;
    $message .= var_export($rec, 1);
    mail("malejole@gmail.com", "track", $message, "From:<mkgxy@mkgalaxy.com>");
    */
    if (!empty($rec['url'])) {
      header("Location: ".HTTPPATH.$rec['url']);
      exit;
    } else {
      header("Location: http://mkgalaxy.com/city-san-jose-144008");
      exit;
    }
  }
  if (file_exists($page)) {
    include($page);
  } else {
    include($defaultPage.'.php');
  }
}

$contentForTemplate = ob_get_clean();
if (empty($layoutFile)) {
  //$layoutFile = 'template';
  $layoutFile = 'layouts/themeBase/index';
}
$layoutFile = $layoutFile.'.php';

if (empty($hideLayout)) {
  include($layoutFile);
} else {
  echo $contentForTemplate;
}

?>