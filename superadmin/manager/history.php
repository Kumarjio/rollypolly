<?php 
check_super_admin();
$pageTitle = 'Manage History';
$layoutStructure = 'simple';

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_GET['id'])) && ($_GET['id'] != "") && isset($_GET['action']) && $_GET['action'] == 'delete') {
  $deleteSQL = sprintf("DELETE FROM z_history WHERE history_id=%s",
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  $deleteGoTo = HTTPPATH.'/superadmin/manager/history?approved='.(isset($_GET['approved']) ? $_GET['approved'] : 0).'&pageNum_rsView='.(isset($_GET['pageNum_rsView']) ? $_GET['pageNum_rsView'] : 0);
  header(sprintf("Location: %s", $deleteGoTo));
}
if ((isset($_GET['id'])) && ($_GET['id'] != "") && isset($_GET['action']) && $_GET['action'] == 'approve') {
  $deleteSQL = sprintf("UPDATE z_history set history_approved = 1 WHERE history_id=%s",
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  $deleteGoTo = HTTPPATH.'/superadmin/manager/history?approved='.(isset($_GET['approved']) ? $_GET['approved'] : 0).'&pageNum_rsView='.(isset($_GET['pageNum_rsView']) ? $_GET['pageNum_rsView'] : 0);
  header(sprintf("Location: %s", $deleteGoTo));
}
if ((isset($_GET['id'])) && ($_GET['id'] != "") && isset($_GET['action']) && $_GET['action'] == 'disapprove') {
  $deleteSQL = sprintf("UPDATE z_history set history_approved = 0 WHERE history_id=%s",
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());

  $deleteGoTo = HTTPPATH.'/superadmin/manager/history?approved='.(isset($_GET['approved']) ? $_GET['approved'] : 0).'&pageNum_rsView='.(isset($_GET['pageNum_rsView']) ? $_GET['pageNum_rsView'] : 0);
  header(sprintf("Location: %s", $deleteGoTo));
}


$maxRows_rsView = 10;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "0";
if (isset($_GET['approved'])) {
  $colname_rsView = $_GET['approved'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT * FROM z_history WHERE history_approved = %s ORDER BY history_created_dt ASC", GetSQLValueString($colname_rsView, "int"));
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
?>

<h2>Manage History</h2>
<p><a href="<?php echo HTTPPATH; ?>/superadmin/manager/history?approved=0">Unapproved</a> | <a href="<?php echo HTTPPATH; ?>/superadmin/manager/history?approved=1">Approved</a></p>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
  <p>No Record Found</p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of 
    <?php echo $totalRows_rsView ?>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
  </p>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>Action</td>
      <td>Edit</td>
      <td>Delete</td>
      <td>history_id</td>
      <td>uid</td>
      <td>history_title</td>
      <td>history_description</td>
      <td>city_id</td>
      <td>address</td>
      <td>showAddress</td>
      <td>history_lat</td>
      <td>history_lng</td>
      <td>history_date</td>
      <td>history_created_dt</td>
      <td>history_status</td>
      <td>history_approved</td>
      <td>related_to_me</td>
      <td>history_image</td>
      <td>history_video</td>
      <td>history_urls</td>
      <td>history_points</td>
      <td>history_points_results</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="<?php echo HTTPPATH; ?>/superadmin/manager/history?approved=<?php echo $colname_rsView; ?>&id=<?php echo $row_rsView['history_id']; ?>&action=approve">Approve</a> | <a href="<?php echo HTTPPATH; ?>/superadmin/manager/history?approved=<?php echo $colname_rsView; ?>&id=<?php echo $row_rsView['history_id']; ?>&action=disapprove">Disapprove</a></td>
        <td>Edit</td>
        <td><a href="<?php echo HTTPPATH; ?>/superadmin/manager/history?approved=<?php echo $colname_rsView; ?>&id=<?php echo $row_rsView['history_id']; ?>&action=delete" onClick="var x = confirm('do you want to delete this record?'); return x;">Delete</a></td>
        <td><?php echo $row_rsView['history_id']; ?></td>
        <td><?php echo $row_rsView['uid']; ?></td>
        <td><?php echo $row_rsView['history_title']; ?></td>
        <td><?php echo $row_rsView['history_description']; ?></td>
        <td><?php echo $row_rsView['city_id']; ?></td>
        <td><?php echo $row_rsView['address']; ?></td>
        <td><?php echo $row_rsView['showAddress']; ?></td>
        <td><?php echo $row_rsView['history_lat']; ?></td>
        <td><?php echo $row_rsView['history_lng']; ?></td>
        <td><?php echo $row_rsView['history_date']; ?></td>
        <td><?php echo $row_rsView['history_created_dt']; ?></td>
        <td><?php echo $row_rsView['history_status']; ?></td>
        <td><?php echo $row_rsView['history_approved']; ?></td>
        <td><?php echo $row_rsView['related_to_me']; ?></td>
        <td><?php echo $row_rsView['history_image']; ?></td>
        <td><?php echo $row_rsView['history_video']; ?></td>
        <td><?php echo $row_rsView['history_urls']; ?></td>
        <td><?php echo $row_rsView['history_points']; ?></td>
        <td><?php echo $row_rsView['history_points_results']; ?></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsView);
?>
