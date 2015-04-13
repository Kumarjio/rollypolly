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

$colname_DetailRS1 = "-1";
if (isset($_GET['did'])) {
  $colname_DetailRS1 = $_GET['did'];
}
mysql_select_db($database_connWork, $connWork);
$query_DetailRS1 = sprintf("SELECT * FROM donations WHERE did = %s", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $connWork) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Details</title>
</head>

<body>
		
<table border="1">
  
  <tr>
    <td>donation_title</td>
    <td><?php echo $row_DetailRS1['donation_title']; ?> </td>
  </tr>
  <tr>
    <td>donation_desc</td>
    <td><?php echo $row_DetailRS1['donation_desc']; ?> </td>
  </tr>
  <tr>
    <td>donation_needed</td>
    <td><?php echo $row_DetailRS1['donation_needed']; ?> </td>
  </tr>
  <tr>
    <td>donation_created</td>
    <td><?php echo $row_DetailRS1['donation_created']; ?> </td>
  </tr>
  <tr>
    <td>donation_category_id</td>
    <td><?php echo $row_DetailRS1['donation_category_id']; ?> </td>
  </tr>
  <tr>
    <td>donation_image</td>
    <td><?php echo $row_DetailRS1['donation_image']; ?> </td>
  </tr>
  
  
</table>
<?php if (!isset($_SESSION['MM_UserId']) || (isset($_SESSION['MM_UserId']) && $_SESSION['MM_UserId'] != $row_DetailRS1['user_id'])) { ?>
<h3>Donate Money To This Campaign</h3>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <p>
    
    <strong>Donation Amount: </strong>$
<input name="amount" type="text" required id="amount" placeholder="Enter Donation Amount" value="<?php echo $amount; ?>" size="45">
      <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $row_DetailRS1['donation_paypal_email']; ?>">
  <input type="hidden" name="item_name" id="item_name" value="Donation Funds For [DID:<?php echo $_GET['did']; ?>]">
      <input type="hidden" name="custom" id="custom" value='{"donar_user_id":"<?php echo !empty($_SESSION['MM_UserId']) ? $_SESSION['MM_UserId']: ''; ?>", "did":"<?php echo $_GET['did']; ?>"}' >
      <input type="hidden" name="item_number" id="item_number" value="<?php echo $_GET['did']; ?>" >
    <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="return" value="http://godonateme.com/detailConfirmPaypal.php">
  <input type="hidden" name="cancel_return" value="http://godonateme.com/detailCancelPaypal.php">
  <input type="hidden" name="notify_url" value="http://godonateme.com/detailNotifyPaypal.php">
    <!-- Display the payment button. -->
    </p>
    <p>
      <input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
      <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" ></p>
</form>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($DetailRS1);
?>
