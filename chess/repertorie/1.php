<?php
set_time_limit(0);
$layoutStructure = 'simple';
$php_userid = 'gambitkumar';
$php_password = 'password';
$cookie_file_path = COOKIE_FILE_PATH;
$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
$reffer = "http://www.net-chess.com/";
/*
$LOGINURL = "http://net-chess.com/command.cgi";
$POSTFIELDS = 'command=login&p1=gambitkumar&p2=password&';

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$LOGINURL);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_REFERER, $reffer);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
$result = curl_exec ($ch);
curl_close ($ch);*/

$url = 'http://net-chess.com/game/g1105214219';
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
//curl_setopt($ch, CURLOPT_POST, 1); 
//curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_REFERER, $reffer);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
$result = curl_exec ($ch);
curl_close ($ch);

echo ($result);
exit;
?>
<?php

if($_POST['login'])
{
	$php_userid = rawurlencode($_POST['login']);
	$php_password = rawurlencode($_POST['passwd']);
	$cookie_file_path = "/home/phptrack/public_html/curl/cookie.txt"; // Please set your Cookie File path
	//$cookie_file_path = "C:/Inetpub/wwwroot/sept2005/phptrack/curl/forum_help_codes/cookie.txt"; // Please set your Cookie File path
	
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
    $reffer = "http://www.paypal.com/";

	// log out.
	$LOGINURL = "https://www.paypal.com/cgi-bin/webscr?cmd=_logout";	
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$LOGINURL);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    $result = curl_exec ($ch);
    curl_close ($ch);
		
//1. Get first login page to set basic cookies

	$LOGINURL = "https://www.paypal.com/cgi-bin/webscr?cmd=_login-run";	
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$LOGINURL);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    $loginpage_html = curl_exec ($ch);
    curl_close ($ch);		
	
// 2- Post Login Data to Page https://www.paypal.com/cgi-bin/webscr

	$LOGINURL = "https://www.paypal.com/cgi-bin/webscr";
	$POSTFIELDS = 'close_external_flow=false&cmd=_login-submit&login_cmd=&login_params=&login_cancel_cmd=&login_email='.$php_userid.'&login_password='.$php_password.'&submit=Log+In&form_charset=UTF-8';

	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$LOGINURL);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_REFERER, $reffer);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    $result = curl_exec ($ch);
    curl_close ($ch); 
	preg_match_all("/href=\"(.*?)\"\>click/", $result, $arr_welcome_url);	
	$welcomeURL = html_entity_decode($arr_welcome_url[1][0]);	
	
// 3- Post Data to History Page https://history.paypal.com/uk/cgi-bin/webscr

	$LOGINURL = "https://history.paypal.com/uk/cgi-bin/webscr";
	$POSTFIELDS = 'cmd=_history-download-submit&history_cache=&type=custom_date_range&from_b='.$_POST['from_b'].'&from_a='.$_POST['from_a'].'&from_c='.$_POST['from_c'].'&to_b='.$_POST['to_b'].'&to_a='.$_POST['to_a'].'&to_c='.$_POST['to_c'].'&custom_file_type='.$_POST['custom_file_type'].'&latest_completed_file_type=&submit.x=Download+History&form_charset=UTF-8';

	$ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$LOGINURL);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_REFERER, $reffer);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    $result = curl_exec ($ch);
    curl_close ($ch); 
	echo "<textarea rows=30 cols=130>".$result."</textarea>"; 	
} 
else
{
	login_form();
}	

////////////////////////////////////////////////////////////////////////////////////////////////
function login_form()
{

?>
<html>
<head>
<title>paypal History</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


</head>

<body>
Download CSV PayPal History
<form method="post" action="paypal.php" autocomplete=off name="login_form" >
<table id="yreglgtb" summary="form: login information">
					<tbody><tr>
						<th><label for="username">paypal! ID:</label></th>
						<td><input name="login" id="login" value="" size="17" class="yreg_ipt" type="text"></td>
					</tr>
					<tr>
						<th><label for="passwd">Password:</label></th>
						<td><input name="passwd" id="passwd" value="" size="17" class="yreg_ipt" type="password"></td>
					</tr>
				
				</tbody></table>
<table cellpadding="0" cellspacing="0">
<tbody><tr>
<td valign="top"></td>
<td>
<span class="emphasis">Custom Date Range</span><br>Download all payments that started within the date range you specify.</td>
<td>&nbsp;</td>
</tr>
<tr><td colspan="3"><img src="history_form_files/pixel.gif" alt="" border="0" height="20" width="1"></td></tr>
<tr>
<td>&nbsp;</td>
<td colspan="3"><table cellpadding="0" cellspacing="0"><tbody><tr>
<td valign="top">From:</td>
<td>&nbsp;</td>
<td><table>
<tbody><tr>
<td><input maxlength="2" name="from_b" size="2" value="17" type="text"></td>
<td>/</td>
<td><input maxlength="2" name="from_a" size="2" value="12" type="text"></td>
<td>/</td>
<td><input maxlength="4" name="from_c" size="2" value="2005 " type="text"></td>
</tr>
<tr>
<td class="small" colspan="2">Day</td>
<td class="small" colspan="2">Month</td>
<td class="small" colspan="2">Year</td>
</tr>
</tbody></table></td>
<td><img src="history_form_files/pixel.gif" alt="" border="0" height="1" width="15"></td>
<td class="" valign="top">To:</td>
<td>&nbsp;</td>
<td><table>
<tbody><tr>
<td><input maxlength="2" name="to_b" size="2" value="24" type="text"></td>
<td>/</td>
<td><input maxlength="2" name="to_a" size="2" value="12" type="text"></td>
<td>/</td>
<td><input maxlength="4" name="to_c" size="2" value="2005 " type="text"></td>
</tr>
<tr>
<td class="small" colspan="2">Day</td>
<td class="small" colspan="2">Month</td>
<td class="small" colspan="2">Year</td>
</tr>
</tbody></table></td>
</tr></tbody></table></td>
<td>&nbsp;</td>
</tr>
<tr><td colspan="3"><img src="history_form_files/pixel.gif" alt="" border="0" height="10" width="1"></td></tr>
<tr>
<td>&nbsp;</td>
<td nowrap="nowrap">File Types for Download: <br><select name="custom_file_type" ><option value="">-- Select --</option><option value="comma_allactivity" selected>Comma Delimited - All Activity</option><option value="comma_completed">Comma Delimited - Completed Payments</option><option value="comma_balaffecting">Comma Delimited - Balance Affecting Payments</option><option value="tabdelim_allactivity">Tab Delimited - All Activity</option><option value="tabdelim_completed">Tab Delimited - Completed Payments</option><option value="tabdelim_balaffecting">Tab Delimited - Balance Affecting Payments</option><option value="quicken">Quicken (.qif) *</option><option value="quickbooks">Quickbooks (.iif)</option></select>
</td>
<td>&nbsp;</td>
</tr>
</tbody></table>				
<input value="Sign In" type="submit">
</form>

</body>
</html>
<?
}
?>