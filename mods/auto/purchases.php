<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');

$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];

$uid = !empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';

$t = (60);

$maxRows_rsView = 25;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;
$query_rsView = "SELECT * FROM auto_pre_transactions WHERE user_id = ?";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$resultTransaction = $modelGeneral->fetchAll($query_limit_rsView, array($uid), $t);

//getting rowCount
$queryTotalRows = "SELECT count(*) as cnt FROM auto_pre_transactions WHERE user_id = ?";
if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $rowCountResult = $modelGeneral->fetchRow($queryTotalRows, array($uid), $t);
  $totalRows_rsView = $rowCountResult['cnt'];
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

//getString
$get_rsViewRaw = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsViewRaw);
log_log($get_rsView, 'get_rsView');
//getString Ends

include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
$pageTitle = 'Purchases';
pr($resultTransaction);
?>
<div class="page-header">
  <h1><?php echo $pageTitle; ?></h1>
</div>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<table width="100%" border="1" cellpadding="5" cellspacing="5">
  <tr>
    <td valign="top"><strong>Transaction Date</strong></td>
    <td valign="top"><strong>Amount</strong></td>
    <td valign="top"><strong>Coupon Code</strong></td>
    <td valign="top"><strong>Status</strong></td>
    <td valign="top"><strong>Redeemed</strong></td>
    <td valign="top"><strong>Expiry Date</strong></td>
    <td valign="top"><strong>Order ID</strong></td>
  </tr>
<?php foreach ($resultTransaction as $k => $result) { ?>
  <tr>
    <td valign="top"><?php echo $result['transaction_date']; ?></td>
    <td valign="top"><?php echo $result['amount']; ?></td>
    <td valign="top"><?php echo $result['coupon_code']; ?></td>
    <td valign="top"><?php echo $result['status']; ?></td>
    <td valign="top"><?php echo $result['redeemed']; ?></td>
    <td valign="top"><?php echo !empty($result['expiry_date_time']) ? $result['expiry_date_time'] : 'Never Expires'; ?></td>
    <td valign="top"><?php echo $result['orderId']; ?></td>
  </tr>
<?php } ?>
</table>

<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<div>No Record Found.</div>
<?php } // Show if recordset empty ?>

<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>