<?php
check_super_admin();
$pageTitle = 'Manage Shouts';
$layoutStructure = 'simple';

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("UPDATE z_shout set shout_deleted = 1 WHERE shout_id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['c'])) && ($_GET['c'] != "") && (isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("UPDATE z_shout set shout_status = %s WHERE shout_id=%s",
                       GetSQLValueString($_GET['c'], "int"),
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

$maxRows_rsView = 10;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "0";
if (isset($_GET['status'])) {
  $colname_rsView = $_GET['status'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT z_shout.*, geo_cities.name as city, geo_states.name as state, geo_countries.name as country FROM z_shout LEFT JOIN geo_cities ON z_shout.city_id = geo_cities.cty_id LEFT JOIN geo_states ON geo_cities.sta_id = geo_states.sta_id LEFT JOIN geo_countries ON geo_states.con_id = geo_countries.con_id WHERE z_shout.shout_status = %s AND z_shout.shout_deleted = 0 ORDER BY z_shout.shout_id DESC", GetSQLValueString($colname_rsView, "int"));
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


include(SITEDIR.'/libraries/addresses/nearby.php');
?>
<h3>Shout Manage For <?php echo ($_GET['status'] == 1) ? 'Active' : 'Pending Approval'; ?> Shouts</h3>
<p><a href="/superadmin/manager/shout?status=0">Pending Approvals</a> | <a href="/superadmin/manager/shout?status=1">Active</a></p>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="top"><strong>Shout ID</strong></td>
      <td valign="top"><strong>Details</strong></td>
      <td valign="top"><strong>City</strong></td>
      <td valign="top"><strong>State</strong></td>
      <td valign="top"><strong>Country</strong></td>
      <td valign="top"><strong>Change Status</strong></td>
      <td valign="top"><strong>Delete</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td valign="top"><?php echo $row_rsView['shout_id']; ?></td>
        <td valign="top"><strong>UID:</strong> <br>
          <?php echo $row_rsView['uid']; ?><br>
          <strong>Shout: <br>
          </strong><?php echo $row_rsView['shout']; ?><br>
          <strong>Status:</strong> <?php echo $row_rsView['shout_status']; ?><br>
        <strong>Date:</strong> <?php echo $row_rsView['shout_dt']; ?></td>
        <td valign="top"><?php echo $row_rsView['city']; ?></td>
        <td valign="top"><?php echo $row_rsView['state']; ?></td>
        <td valign="top"><?php echo $row_rsView['country']; ?></td>
        <td valign="top"><a href="/superadmin/manager/shout?status=<?php echo $colname_rsView; ?>&c=<?php echo ($row_rsView['shout_status'] == 1) ? 0 : 1; ?>&id=<?php echo $row_rsView['shout_id']; ?>">Make <?php echo ($row_rsView['shout_status'] == 1) ? 'Inactive' : 'Active'; ?></a></td>
        <td valign="top"><a href="/superadmin/manager/shout?status=<?php echo $colname_rsView; ?>&delete_id=<?php echo $row_rsView['shout_id']; ?>" onClick="var result = confirm('do you really want to delete this entry? you cannot revert back the change?'); return result;">Delete</a></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?> &nbsp;
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
  <?php } // Show if recordset not empty ?>
</p>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
  <p>No Shout Message.</p>
  <?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsView);
?>
