<?php require_once('../Connections/connWork.php'); ?>
<?php 
if (empty($hide_inc_featured)) {
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

mysql_select_db($database_connWork, $connWork);
$query_rsViewFeatured = "SELECT * FROM donations as d LEFT JOIN donations_calc_received
 as r ON d.did = r.did2 WHERE d.is_featured = 1 AND d.donation_status = 1 AND d.donation_payment_status = 'Completed' ORDER BY d.donation_created ASC";
$rsViewFeatured = mysql_query($query_rsViewFeatured, $connWork) or die(mysql_error());
$row_rsViewFeatured = mysql_fetch_assoc($rsViewFeatured);
$totalRows_rsViewFeatured = mysql_num_rows($rsViewFeatured);
?>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="row">
<?php if ($totalRows_rsViewFeatured > 0) { // Show if recordset not empty ?>
<h3 align="center">Featured Listings</h3>
  <?php do { ?>
  <?php $percentage = $row_rsViewFeatured['total_amount'] * (100 / $row_rsViewFeatured['donation_needed']); ?>
  <div class="col-xs-6 col-sm-12 col-md-12 col-lg-12">
      <div class="panel panel-default">
      <img class="img-responsive" style="width: 100%;" src="images/<?php echo $row_rsViewFeatured['user_id']; ?>/thumbs/<?php echo $row_rsViewFeatured['donation_image']; ?>" alt="" />
        <div class="panel-body">
        <p class="lead text-danger text-center">
          <a href="detailList.php?did=<?php echo $row_rsViewFeatured['did']; ?>"><?php echo $row_rsViewFeatured['donation_title']; ?></a>
        </p>
        <p>
          <a class="btn btn-warning btn-md btn-block" href="detail.php?did=<?php echo $row_rsViewFeatured['did']; ?>"> $ <?php echo $row_rsViewFeatured['donation_needed'];?>
                      <?php if ($row_rsViewFeatured['total_amount'] > 0) { ?>
                       / $ <?php echo $row_rsViewFeatured['total_amount']; ?>
                      / $ <?php echo $row_rsViewFeatured['donation_needed'] - $row_rsViewFeatured['total_amount']; ?>
                      <?php } ?></a>
        </p>
        <div class="progress">
          <div class="bar" title="<?php echo $percentage; ?>" style="width: <?php echo $percentage; ?>%;"></div>
        </div>
      </div>
    </div>
  </div>
  <?php } while ($row_rsViewFeatured = mysql_fetch_assoc($rsViewFeatured)); ?>
<?php } // Show if recordset not empty ?>

<?php include('../donations/inc_googleadsense.php'); ?>
</div>
</div>
<?php
mysql_free_result($rsViewFeatured);
?>
<?php } ?>