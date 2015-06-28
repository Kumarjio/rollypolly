<?php require_once('../../../Connections/connP2.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

require_once('../config.php');
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$target_dir = SUBIMAGEDIR.$id.'/';

$colname_rsDetail = "-1";
if (isset($_GET['detail_id'])) {
  $colname_rsDetail = (get_magic_quotes_gpc()) ? $_GET['detail_id'] : addslashes($_GET['detail_id']);
}
mysql_select_db($database_connP2, $connP2);
$query_rsDetail = sprintf("SELECT * FROM image_details WHERE detail_id = %s", $colname_rsDetail);
$rsDetail = mysql_query($query_rsDetail, $connP2) or die(mysql_error());
$row_rsDetail = mysql_fetch_assoc($rsDetail);
$totalRows_rsDetail = mysql_num_rows($rsDetail);

$colname_rsID = "-1";
if (isset($_GET['id'])) {
  $colname_rsID = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_connP2, $connP2);
$query_rsID = sprintf("SELECT * FROM main_image WHERE id = %s", $colname_rsID);
$rsID = mysql_query($query_rsID, $connP2) or die(mysql_error());
$row_rsID = mysql_fetch_assoc($rsID);
$totalRows_rsID = mysql_num_rows($rsID);

$maxRows_rsView = 25;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "-1";
if (isset($_GET['detail_id'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_GET['detail_id'] : addslashes($_GET['detail_id']);
}
mysql_select_db($database_connP2, $connP2);
$query_rsView = sprintf("SELECT * FROM tracking WHERE image_detail_id = %s ORDER BY clicked_time DESC", $colname_rsView);
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connP2) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$colname_rsCount = "-1";
if (isset($_GET['detail_id'])) {
  $colname_rsCount = (get_magic_quotes_gpc()) ? $_GET['detail_id'] : addslashes($_GET['detail_id']);
}
mysql_select_db($database_connP2, $connP2);
$query_rsCount = sprintf("SELECT count(*) as cnt FROM tracking WHERE image_detail_id = %s", $colname_rsCount);
$rsCount = mysql_query($query_rsCount, $connP2) or die(mysql_error());
$row_rsCount = mysql_fetch_assoc($rsCount);
$totalRows_rsCount = mysql_num_rows($rsCount);

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

if (!empty($row_rsID['resizeImg'])){
    $imageDir = IMAGEDIRNEW;
} else {
    $imageDir = IMAGEDIR;
}
?>
<!doctype html>
<html>
<head>
    <title>Tracking</title>

    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<style type="text/css">
body {
 font-family:Verdana;
 font-size: 11px; 
}

.imglist {
  max-height: 70px;
}
</style>
</head>

<body>
<h1>Tracking</h1>
<p><a href="index.php">Back to Home Page</a> | <a href="main.php">Back To Main</a> | <a href="area.php?id=<?php echo $_GET['id']; ?>">Back To Area Selection</a> | <a href="areaSmooth.php?id=<?php echo $_GET['id']; ?>">Back To Smooth Area Selection</a></p>
<table border="1" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><strong>Main Image:<br>
    </strong></td>
    <td valign="top"><img src="<?php echo $imageDir.$row_rsID['fileName']; ?>" class="imglist" /></td>
    <td valign="top"><strong>Sub Image</strong> </td>
    <td valign="top"><?php if (!empty($row_rsDetail['imageFile'])) { ?><img src="<?php echo $target_dir.$row_rsDetail['imageFile']; ?>" class="imglist" /><?php } ?></td>
  </tr>
  <tr>
    <td valign="top"><strong>For Coordinates:</strong></td>
    <td valign="top"><?php echo $row_rsDetail['coordinates']; ?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><strong>Total Clicks: </strong></td>
    <td valign="top"><?php echo $row_rsCount['cnt']; ?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</table>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
  <table border="0" width="50%" align="center">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
          <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
          <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
          <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
          <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  </p>
  <table border="1">
    <tr>
      <td><strong>Tracking ID </strong></td>
      <td><strong>Clicked Time </strong></td>
      <td><strong>IP</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsView['tracking_id']; ?></td>
        <td><?php echo $row_rsView['clicked_time']; ?></td>
        <td><a href="http://www.iplocation.net/?query=<?php echo $row_rsView['ip']; ?>" target="_blank"><?php echo $row_rsView['ip']; ?></a></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
  <p>No Tracking Found. </p>
  <?php } // Show if recordset empty ?><p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsDetail);

mysql_free_result($rsID);

mysql_free_result($rsView);

mysql_free_result($rsCount);
?>
