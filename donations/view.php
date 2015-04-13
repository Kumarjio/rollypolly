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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsView = 25;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$gcid = array();
$cid = "";
if (!empty($_GET['cid'])) {
  $_GET['cid'] = array_filter($_GET['cid']);
  if (!empty($_GET['cid'])) {
    $gcid = $_GET['cid'];
    $tmp = implode(',', $_GET['cid']);
    $cid = ' AND donations.donation_category_id IN ('.$tmp.')';
  }
}


mysql_select_db($database_connWork, $connWork);
$query_rsView = "SELECT * FROM donations WHERE donation_status = 1 AND donation_payment_status = 'Completed' $cid ORDER BY donation_created ASC";
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

mysql_select_db($database_connWork, $connWork);
$query_rsCategory = "SELECT * FROM donations_category ORDER BY category ASC";
$rsCategory = mysql_query($query_rsCategory, $connWork) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);

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
<title>View Donations</title>
</head>

<body>
<h1>Donations Requested</h1>
<form id="form1" name="form1" method="get">
  <label for="cid"><strong>Category:</strong></label>
  <br>
  <select name="cid[]" size="5" multiple id="cid">
    <option value="">Select</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rsCategory['category_id']?>"<?php if ((in_array($row_rsCategory['category_id'], $gcid))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCategory['category']?></option>
    <?php
} while ($row_rsCategory = mysql_fetch_assoc($rsCategory));
  $rows = mysql_num_rows($rsCategory);
  if($rows > 0) {
      mysql_data_seek($rsCategory, 0);
	  $row_rsCategory = mysql_fetch_assoc($rsCategory);
  }
?>
  </select>
  <br>
  <input type="submit" name="submit" id="submit" value="Browse">
</form>
<br />
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
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
      <td>donation_image</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsView['did']; ?></td>
        <td><?php echo $row_rsView['user_id']; ?></td>
        <td><a href="detail.php?did=<?php echo $row_rsView['did']; ?>"><?php echo $row_rsView['donation_title']; ?></a></td>
        <td><?php echo $row_rsView['donation_desc']; ?></td>
        <td><?php echo $row_rsView['donation_needed']; ?></td>
        <td><?php echo $row_rsView['donation_created']; ?></td>
        <td><?php echo $row_rsView['donation_status']; ?></td>
        <td><?php echo $row_rsView['donation_category_id']; ?></td>
        <td><?php echo $row_rsView['donation_image']; ?></td>
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
  <p>No Record Found.</p>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsCategory);
?>
