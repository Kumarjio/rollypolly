<?php
function convert($hexString)
{
    $hexLenght = strlen($hexString);
    // only hex numbers is allowed
    if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;
    unset($binString);
    for ($x = 1; $x <= $hexLenght/2; $x++) {
        $binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
    }
    
    return $binString;
} 

if($_POST) {
//print_r($_POST);
require_once 'Crypt/Blowfish.php';  // Just including the class

//echo "<hr>";
//$bf = new Crypt_Blowfish('JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN');
//$encrypted = $bf->encrypt('prbc_id=107682291&billeo_id=505&history_flag=HP');
//echo "encrypted: ".bin2hex($encrypted);
//echo "<br>";
//$plaintext = $bf->decrypt(convert('33cc757285de361a1c726a6c465fa02077482c1844311bd154c398e9da14d109c9b83af639b16e635b1caf1d00bf64b8'));
//echo ", plain text: ".trim($plaintext);
//echo "<hr>";

//$bf = new Crypt_Blowfish('JKjVXtFdY3NNT6Fp6U9uM3m5eeWbtqXWrR5qwWpyM9b8SFSdWVK2vruN');
if($_POST['type']=="encrypt") {
  echo "encrypted: ".encryptText($_POST['desc']);
	//$encrypted = $bf->encrypt($_POST['desc']);	
	//echo "encrypted: ".bin2hex($encrypted);
} else if($_POST['type']=="decrypt") {
	echo 'decrypted: '.decryptText($_POST['desc']);
  //$plaintext = $bf->decrypt(convert(trim($_POST['desc'])));
	//echo trim($plaintext);
}

}
?>
<form id="form1" name="form1" method="post" action="">
  <p>Key: 
    <input name="key" type="text" id="key" size="100" value="<?php echo $_POST['key']; ?>" />
</p>
  <p>Text:</p>
  <p>
    <textarea name="desc" cols="50" rows="10" id="desc"></textarea>
  </p>
  <p>
    <label>
    <input name="type" type="radio" value="encrypt" />
    Encrypt </label>
    <label>
    <input name="type" type="radio" value="decrypt" /> 
    Decrypt
</label>
  </p>
  <p>
    <input type="submit" name="Submit" value="Submit" />
  </p>
</form>