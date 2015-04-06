<?php
function delTree($dir) { 
 $files = array_diff(scandir($dir), array('.','..')); 
  foreach ($files as $file) { 
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
  } 
  if (!preg_match('/ADODB_cache$/', $dir, $matches)) {
    echo 'Removing dir: '.$dir;
    echo '<br>';
    rmdir($dir);
  }
} 
if (!empty($_GET['cacheclear'])) {
  delTree(realpath('../../cache/ADODB_cache'));
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Administrator Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Administrator Page</h1>
<p><a href="index.php?cacheclear=1">Clear Cache</a></p>
<p><a href="modules.php">Manage Modules</a></p>
<p><a href="chat.php">Manage Chat</a></p>
</body>
</html>