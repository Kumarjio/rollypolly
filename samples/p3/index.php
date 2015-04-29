<?php require_once('../../Connections/connWork.php'); ?>
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

$maxRows_rsView = 10;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connWork, $connWork);
$query_rsView = "SELECT * FROM donations ORDER BY donation_title ASC";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connWork) or die(mysql_error());
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
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<p>View Groups</p>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
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
</p>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <td>did</td>
        <td>user_id</td>
        <td>donation_title</td>
        <td>donation_desc</td>
        <td>donation_needed</td>
        <td>donation_created</td>
        <td>donation_status</td>
        <td>donation_category_id</td>
        <td>donation_payment_details</td>
        <td>donation_payment_status</td>
        <td>donation_payment_date</td>
        <td>donation_image</td>
        <td>donation_paypal_email</td>
        <td>is_featured</td>
        <td>city</td>
        <td>state</td>
        <td>country</td>
        <td>lat</td>
        <td>lng</td>
    </tr>
    <?php do { ?>
    <tr>
        <td><?php echo $row_rsView['did']; ?></td>
        <td><?php echo $row_rsView['user_id']; ?></td>
        <td><?php echo $row_rsView['donation_title']; ?></td>
        <td><?php echo $row_rsView['donation_desc']; ?></td>
        <td><?php echo $row_rsView['donation_needed']; ?></td>
        <td><?php echo $row_rsView['donation_created']; ?></td>
        <td><?php echo $row_rsView['donation_status']; ?></td>
        <td><?php echo $row_rsView['donation_category_id']; ?></td>
        <td><?php echo $row_rsView['donation_payment_details']; ?></td>
        <td><?php echo $row_rsView['donation_payment_status']; ?></td>
        <td><?php echo $row_rsView['donation_payment_date']; ?></td>
        <td><?php echo $row_rsView['donation_image']; ?></td>
        <td><?php echo $row_rsView['donation_paypal_email']; ?></td>
        <td><?php echo $row_rsView['is_featured']; ?></td>
        <td><?php echo $row_rsView['city']; ?></td>
        <td><?php echo $row_rsView['state']; ?></td>
        <td><?php echo $row_rsView['country']; ?></td>
        <td><?php echo $row_rsView['lat']; ?></td>
        <td><?php echo $row_rsView['lng']; ?></td>
    </tr>
    <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</table>
<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsView);
?>
