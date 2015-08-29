<?php

define('SITEDIR', dirname(__FILE__));
$dir = dirname($_SERVER['PHP_SELF']);
$dir = str_replace('/admin', '', $dir);
if ($dir == '/') $dir = '';
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
define('HTTPHOST', 'http://'.$host.$dir);
define('HTTPPATH', 'http://'.$host.$dir);
define('IMAGEUPLOADDIR', SITEDIR.'/images/userImages/');
define('IMAGEDIR', HTTPHOST.'/images/userImages/');
define('IMAGEUPLOADDIRNEW', SITEDIR.'/images/userImages/resizeImages/');
define('IMAGEDIRNEW', HTTPHOST.'/images/userImages/resizeImages/');

define('SUBIMAGEUPLOADDIR', SITEDIR.'/images/subImages/');
define('SUBIMAGEDIR', HTTPHOST.'/images/subImages/');
?>