<?php require_once(SITEDIR.'/Connections/connMain.php'); ?>
<?php
check_super_admin();
$pageTitle = 'Country';
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

$colname_rsLocation = "-1";
if (isset($_GET['id'])) {
  $colname_rsLocation = $_GET['id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsLocation = sprintf("SELECT * FROM geo_countries WHERE con_id = %s", GetSQLValueString($colname_rsLocation, "int"));
$rsLocation = mysql_query($query_rsLocation, $connMain) or die(mysql_error());
$row_rsLocation = mysql_fetch_assoc($rsLocation);
$totalRows_rsLocation = mysql_num_rows($rsLocation);

$colname_rsBids = "-1";
if (isset($_GET['id'])) {
  $colname_rsBids = $_GET['id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsBids = sprintf("SELECT * FROM location_bids WHERE location_id = %s AND location_type = 'Country' AND period_bids = 'Jan 2015 To Dec 2015' ORDER BY bid_amount DESC", GetSQLValueString($colname_rsBids, "int"));
$rsBids = mysql_query($query_rsBids, $connMain) or die(mysql_error());
$row_rsBids = mysql_fetch_assoc($rsBids);
$totalRows_rsBids = mysql_num_rows($rsBids);
?>
<div style="font-size:11px;">
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td><strong>con_id</strong></td>
    <td><strong>name</strong></td>
    <td><strong>country_owner_id</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsLocation['con_id']; ?></td>
      <td><?php echo $row_rsLocation['name']; ?></td>
      <td><?php echo $row_rsLocation['country_owner_id']; ?></td>
    </tr>
    <?php } while ($row_rsLocation = mysql_fetch_assoc($rsLocation)); ?>
</table>
<h3>Bids
</h3>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td><strong>location_id</strong></td>
    <td><strong>owner_id</strong></td>
    <td><strong>location_type</strong></td>
    <td><strong>bid_amount</strong></td>
    <td><strong>bid_created_dt</strong></td>
    <td><strong>period_bids</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsBids['location_id']; ?></td>
      <td><?php echo $row_rsBids['owner_id']; ?></td>
      <td><?php echo $row_rsBids['location_type']; ?></td>
      <td><?php echo $row_rsBids['bid_amount']; ?></td>
      <td><?php echo $row_rsBids['bid_created_dt']; ?></td>
      <td><?php echo $row_rsBids['period_bids']; ?></td>
    </tr>
    <?php } while ($row_rsBids = mysql_fetch_assoc($rsBids)); ?>
</table>
<p>&nbsp;</p>
</div>
<?php
mysql_free_result($rsLocation);

mysql_free_result($rsBids);
?>
