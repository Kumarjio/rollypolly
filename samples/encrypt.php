<form action="<?php echo $SERVER['PHP_SELF']; ?>"
method="post">
This tool will encrypt your PHP script using Base 64 encoding.<br />
Copy and paste your script in the text area below (It is suggested to start with "&lt;&#63;php" and end your script with "?>", to avoid any encoding errors)<br />
<p><textarea name="forencrypt" rows="30" cols="30"></textarea></p>
<br />
<input type="submit" name="submit" value="Encrypt this PHP script now">
</form>
<a href="/encrypt-php.php">Click here to reset or clear this form</a>
<br />
This tool is developed by: <a href="http://www.php-developer.org/">PHP Developer. org</a>
<br />
</td>
<td>
<font color="blue">
<?php
//License: Free to use and provided the link to http://www.php-developer.org SHOULD not be removed.Thanks.
//Author:codex-m

//check if the form has been submitted
if (isset($_POST['forencrypt'])){
//form has been submitted
$phpcode=$_POST['forencrypt'];
$phpcode=stripslashes($phpcode);
//remove space
$phpcode=trim($phpcode);
//count string
$count=strlen($phpcode);
$last=$count-4;
$rest = substr($phpcode, 2, $last);
$display= substr($rest, 4);
$string= $display;
$compressed = gzdeflate($string, 9);
$encode = base64_encode($compressed);
echo "&lt;"."&#63;"."php";
echo '<br />';
echo 'eval(gzinflate(base64_decode('.'&#39;'.$encode.'&#39;'.')));';
echo '<br />';
echo '?'.'>';
}
?>