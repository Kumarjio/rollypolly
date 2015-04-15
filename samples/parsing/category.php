<?php
$c = file_get_contents('category.txt');
$t = explode("\n", $c);
foreach ($t as $k => $v) {
  $tmp['"'.($k+1).'"'] = $v;
}
echo json_encode($tmp);
?>