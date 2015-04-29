<?php
// *** Logout the current user.
$logoutGoTo = "login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
$_SESSION['MM_UserId'] = NULL;
$_SESSION['MM_Name'] = NULL;      
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);	      
unset($_SESSION['MM_UserId']);
unset($_SESSION['MM_Name']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>