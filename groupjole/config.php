<?php
if (!isset($_SESSION)) {
  session_start();
}


define('SITEDIR', dirname(__FILE__));
define('ROOTDIR', dirname(dirname(__FILE__)));
define('LOGGING', true);

//three types of payment subscription
define('GROUPFEESTYPE', 1); //1 = FREE, 2 = FREE FOR 3 MONTHS, 3 = NOT FREE
define('GROUPFEETRAILAMOUNT', 1.33);//a1
define('GROUPFEETRAILPERIODNUMBER', 3);//p1
define('GROUPFEETRAILPERIODTYPE', 'M');//t1
define('GROUPFEEAMOUNT', 11.24);//a3
define('GROUPFEEPERIODNUMBER', 12);//p3
define('GROUPFEEPERIODTYPE', 'M');//t3

set_include_path(get_include_path(). PATH_SEPARATOR. ROOTDIR.'/libraries/library'. PATH_SEPARATOR. ROOTDIR.'/libraries/pear'. PATH_SEPARATOR. ROOTDIR.'/libraries');

require_once('FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true);
$firephp->setEnabled(true);

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

function log_error($message, $key='')
{
    global $firephp;
    $firephp->error($message, $key);
    $firephp->trace('Trace');
}

function log_log($message, $key='')
{
    if (!LOGGING) return;
    global $firephp;
    $firephp->log($message, $key);
}

function log_info($message, $key='')
{
    if (!LOGGING) return;
  global $firephp;
  $firephp->info($message, $key);
}


function log_warn($message, $key='')
{
    if (!LOGGING) return;
  global $firephp;
  $firephp->warn($message, $key);
}

function pr($d)
{
    echo '<pre>';
    print_r($d);
    echo '</pre>';   
}



define('EMAILTEMPLATEDIR', SITEDIR.'/emails/templates');
log_log(EMAILTEMPLATEDIR, 'EMAILTEMPLATEDIR:');
$dir = dirname($_SERVER['PHP_SELF']);
if ($dir == '/') $dir = '';
log_log($dir, 'Dir:');
$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
log_log($host, 'Host:');
define('SITENAME', ucwords('groupjole.com'));
log_log(SITENAME, 'SITENAME:');
define('ROOTDOMAIN', $host);
log_log(ROOTDOMAIN, 'ROOTDOMAIN:');
define('HTTPPATH', 'http://'.$host.$dir);
log_log(HTTPPATH, 'HTTPPATH:');
define('ROOTHTTPPATH', $dir);
log_log(ROOTHTTPPATH, 'ROOTHTTPPATH:');
define('FROM_EMAIL', 'support@groupjole.com');
log_log(FROM_EMAIL, 'FROM_EMAIL:');


include_once(SITEDIR.'/emails/email.class.php');
?>