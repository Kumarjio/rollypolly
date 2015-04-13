<?php require_once('../Connections/connWork.php'); ?>
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
$query_rsView = "SELECT * FROM donations WHERE is_featured = 1 AND donation_status = 1 AND donation_payment_status = 'Completed' ORDER BY donation_created ASC";
$rsView = mysql_query($query_rsView, $connWork) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);
?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="row">
  <?php do { ?>
  <div class="col-xs-6 col-sm-12 col-md-12 col-lg-12">
      <div class="panel panel-default">
      <img class="img-responsive" style="width: 100%;" src="images/<?php echo $row_rsView['user_id']; ?>/thumbs/<?php echo $row_rsView['donation_image']; ?>" alt="" />
        <div class="panel-body">
        <p class="lead text-danger text-center">
          <a href="detail.php?did=<?php echo $row_rsView['did']; ?>"><?php echo $row_rsView['donation_title']; ?></a>
        </p>
        <p>
          <a class="btn btn-warning btn-md btn-block" href="detail.php?did=<?php echo $row_rsView['did']; ?>">$ <?php echo $row_rsView['donation_needed']; ?></a>
        </p>
      </div>
    </div>
  </div>
  <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</div>
</div>
<?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsView);
?>