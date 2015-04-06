<?php
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  header("Location: /");
  exit;
}
$params = array();
$params['where'] = sprintf(' AND residential_id = %s', $modelGeneral->qstr($id));
$details = $modelGeneral->getDetails('z_residential LEFT JOIN google_auth ON z_residential.uid = google_auth.uid', 0, $params);
$details = $details[0];

if ($_SESSION['user']['id'] != $details['uid']) {
  header("Location: ".$_SERVER['HTTP_REFERER']);
  exit;
}

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM z_residential WHERE residential_id=%s AND uid=%s",
                       GetSQLValueString($_GET['id'], "text"),
                       GetSQLValueString($_SESSION['user']['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  header("Location: ".$_SERVER['HTTP_REFERER']);
  exit;
}

  header("Location: ".$_SERVER['HTTP_REFERER']);
  exit;
?>