<?php require_once('../Connections/conn.php'); ?>
<?php
session_start();
?>
<?php
$colname_rsDelete = "0";
if (isset($_GET['del_id'])) {
  $colname_rsDelete = (get_magic_quotes_gpc()) ? $_GET['del_id'] : addslashes($_GET['del_id']);
}
$coluser_rsDelete = "0";
if (isset($_SESSION['MM_Username'])) {
  $coluser_rsDelete = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conn, $conn);
$query_rsDelete = sprintf("SELECT * FROM procentris_list, procentris_users WHERE procentris_list.list_id = %s AND procentris_list.user_id = procentris_users.user_id AND procentris_users.username = '%s'", $colname_rsDelete,$coluser_rsDelete);
$rsDelete = mysql_query($query_rsDelete, $conn) or die(mysql_error());
$row_rsDelete = mysql_fetch_assoc($rsDelete);
$totalRows_rsDelete = mysql_num_rows($rsDelete);
?>
<?php if ($totalRows_rsDelete > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php
$query = "update procentris_list set deleted = 0 where list_id = '".$row_rsDelete['list_id']."'";
mysql_query($query);
?>
<?php } while ($row_rsDelete = mysql_fetch_assoc($rsDelete)); ?>
<?php } // Show if recordset not empty ?>
<?php
$deleteGoTo = "deleted_task_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
  exit;
?>
<?php
mysql_free_result($rsDelete);
?>
