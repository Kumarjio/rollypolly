<?php
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');

$my = false;
if (!empty($_GET['my'])) {
  $my = true;
}

$get_rsView = getString(array('id', 'my'));
if ($my)
  $redirectURL = $currentURL.'/auto/my?clear=1'.$get_rsView;
else
  $redirectURL = $currentURL.'/auto/browse?clear=1'.$get_rsView;

$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  header("Location: ".$redirectURL);
  exit;
}

$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}
$tablename = 'auto_'.$resultModule['module_name'];


$query_rowResult = "SELECT a.*, u.name as fullname, u.* FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid WHERE 1 AND a.id = ?";
$rowResult = $modelGeneral->fetchRow($query_rowResult, array($_GET['id']), 300);

if ($_SESSION['user']['id'] != $rowResult['uid']) {
  header("Location: ".$redirectURL);
  exit;
}

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("UPDATE $tablename SET rc_deleted = 1, rc_deleted_dt = NOW() WHERE id=%s AND uid=%s",
                       GetSQLValueString($_GET['id'], "text"),
                       GetSQLValueString($_SESSION['user']['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  header("Location: ".$redirectURL);
  exit;
}

  header("Location: ".$redirectURL);
  exit;
?>