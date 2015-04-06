<?php
function pr($d) { echo '<pre>'; print_r($d); echo '</pre>'; }

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

if (!empty($_POST)) {
  //pr($_POST);
  echo $input = $_POST['list'];
  $regexp = '<p align="left">.*<p>(.*)<\/p>(.*)<\/p>';
  $matches = regexp($input, $regexp);
  pr($matches);
  $regexp = '<p>(.*)<\/font>(.*)';
  $matches = regexp($matches[2], $regexp);
  pr($matches);
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<h1>Clarke Prescriber</h1>
<form id="form1" name="form1" method="post">
  <p>
    <label for="list">List:<br>
    </label>
    <textarea name="list" cols="70" rows="10" id="list"><?php echo !empty($_POST['list']) ? $_POST['list'] : ''; ?></textarea>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit">
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>