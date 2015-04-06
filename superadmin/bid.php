<?php require_once(SITEDIR.'/Connections/connMain.php'); ?>
<?php
check_super_admin();
$pageTitle = 'Bids';
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsBids = 100;
$pageNum_rsBids = 0;
if (isset($_GET['pageNum_rsBids'])) {
  $pageNum_rsBids = $_GET['pageNum_rsBids'];
}
$startRow_rsBids = $pageNum_rsBids * $maxRows_rsBids;

mysql_select_db($database_connMain, $connMain);
$query_rsBids = "SELECT * FROM location_bids WHERE period_bids = 'Jan 2015 To Dec 2015' ORDER BY location_type, location_id, bid_created_dt ASC";
$query_limit_rsBids = sprintf("%s LIMIT %d, %d", $query_rsBids, $startRow_rsBids, $maxRows_rsBids);
$rsBids = mysql_query($query_limit_rsBids, $connMain) or die(mysql_error());
$row_rsBids = mysql_fetch_assoc($rsBids);

if (isset($_GET['totalRows_rsBids'])) {
  $totalRows_rsBids = $_GET['totalRows_rsBids'];
} else {
  $all_rsBids = mysql_query($query_rsBids);
  $totalRows_rsBids = mysql_num_rows($all_rsBids);
}
$totalPages_rsBids = ceil($totalRows_rsBids/$maxRows_rsBids)-1;

$queryString_rsBids = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsBids") == false && 
        stristr($param, "totalRows_rsBids") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsBids = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsBids = sprintf("&totalRows_rsBids=%d%s", $totalRows_rsBids, $queryString_rsBids);


?>
<div style="font-size:11px;">
<?php if ($totalRows_rsBids > 0) { // Show if recordset not empty ?>
  <p> Records <?php echo ($startRow_rsBids + 1) ?> to <?php echo min($startRow_rsBids + $maxRows_rsBids, $totalRows_rsBids) ?> of <?php echo $totalRows_rsBids ?>, Period: Jan 2015 To Dec 2015
  </p>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsBids > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsBids=%d%s", $currentPage, 0, $queryString_rsBids); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsBids > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsBids=%d%s", $currentPage, max(0, $pageNum_rsBids - 1), $queryString_rsBids); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsBids < $totalPages_rsBids) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsBids=%d%s", $currentPage, min($totalPages_rsBids, $pageNum_rsBids + 1), $queryString_rsBids); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsBids < $totalPages_rsBids) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsBids=%d%s", $currentPage, $totalPages_rsBids, $queryString_rsBids); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
  <table border="1" cellpadding="5" cellspacing="1" width="100%">
    <tr>
      <td><strong>location_id</strong></td>
      <td><strong>owner_id</strong></td>
      <td><strong>location_type</strong></td>
      <td><strong>bid_amount</strong></td>
      <td><strong>bid_created_dt</strong></td>
      </tr>
    <?php do { 
      if ($row_rsBids['location_type'] == 'Country') {
        $url = '/superadmin/locations/country?id='.$row_rsBids['location_id'];
      } else if ($row_rsBids['location_type'] == 'State') {
        $url = '/superadmin/locations/state?id='.$row_rsBids['location_id'];
      } else if ($row_rsBids['location_type'] == 'City') {
        $url = '/superadmin/locations/city?id='.$row_rsBids['location_id'];
      }
    ?>
      <tr>
        <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $row_rsBids['location_id']; ?></a></td>
        <td><?php echo $row_rsBids['owner_id']; ?></td>
        <td><?php echo $row_rsBids['location_type']; ?></td>
        <td><?php echo $row_rsBids['bid_amount']; ?></td>
        <td><?php echo $row_rsBids['bid_created_dt']; ?></td>
        </tr>
      <?php } while ($row_rsBids = mysql_fetch_assoc($rsBids)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsBids == 0) { // Show if recordset empty ?>
  <p>No Bid Found.</p>
  <?php } // Show if recordset empty ?>
</div>
<?php
mysql_free_result($rsBids);
?>
