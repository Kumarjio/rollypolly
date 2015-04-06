<?php require_once('../../Connections/connMain.php'); ?>
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

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("UPDATE help_messages SET message_approved = 1 WHERE message_id=%s",
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("UPDATE help_messages SET deleted = 1 WHERE message_id=%s",
                       GetSQLValueString($_GET['delete_id'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

$maxRows_rsChat = 100;
$pageNum_rsChat = 0;
if (isset($_GET['pageNum_rsChat'])) {
  $pageNum_rsChat = $_GET['pageNum_rsChat'];
}
$startRow_rsChat = $pageNum_rsChat * $maxRows_rsChat;

mysql_select_db($database_connMain, $connMain);
$query_rsChat = "SELECT * FROM help_messages WHERE message_approved = 0 and deleted = 0 ORDER BY message_id ASC";
$query_limit_rsChat = sprintf("%s LIMIT %d, %d", $query_rsChat, $startRow_rsChat, $maxRows_rsChat);
$rsChat = mysql_query($query_limit_rsChat, $connMain) or die(mysql_error());
$row_rsChat = mysql_fetch_assoc($rsChat);

if (isset($_GET['totalRows_rsChat'])) {
  $totalRows_rsChat = $_GET['totalRows_rsChat'];
} else {
  $all_rsChat = mysql_query($query_rsChat);
  $totalRows_rsChat = mysql_num_rows($all_rsChat);
}
$totalPages_rsChat = ceil($totalRows_rsChat/$maxRows_rsChat)-1;

$queryString_rsChat = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsChat") == false && 
        stristr($param, "totalRows_rsChat") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsChat = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsChat = sprintf("&totalRows_rsChat=%d%s", $totalRows_rsChat, $queryString_rsChat);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Chat Approval</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Chat Approval</h1>
<p><a href="index.php">Back</a></p>
<?php if ($totalRows_rsChat > 0) { // Show if recordset not empty ?>
  <h3>Pending Messages</h3>
  <p> Records <?php echo ($startRow_rsChat + 1) ?> to <?php echo min($startRow_rsChat + $maxRows_rsChat, $totalRows_rsChat) ?> of <?php echo $totalRows_rsChat ?> &nbsp;
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsChat > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsChat=%d%s", $currentPage, 0, $queryString_rsChat); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsChat > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsChat=%d%s", $currentPage, max(0, $pageNum_rsChat - 1), $queryString_rsChat); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsChat < $totalPages_rsChat) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsChat=%d%s", $currentPage, min($totalPages_rsChat, $pageNum_rsChat + 1), $queryString_rsChat); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsChat < $totalPages_rsChat) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsChat=%d%s", $currentPage, $totalPages_rsChat, $queryString_rsChat); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
  </p>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>action</td>
      <td><strong>message_id</strong></td>
      <td><strong>uid</strong></td>
      <td><strong>to_uid</strong></td>
      <td><strong>subject</strong></td>
      <td><strong>message</strong></td>
      <td><strong>message_read</strong></td>
      <td><strong>message_date</strong></td>
      <td><strong>read_date</strong></td>
      <td><strong>status</strong></td>
      <td><strong>message_approved</strong></td>
      <td><strong>module_id</strong></td>
      <td><strong>message_from_delete</strong></td>
      <td><strong>message_to_delete</strong></td>
      <td><strong>message_from_delete_dt</strong></td>
      <td><strong>message_to_delete_dt</strong></td>
      <td><strong>deleted</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="chat.php?id=<?php echo $row_rsChat['message_id']; ?>" onClick="var a = confirm('do you really want to approve this?'); return a;">approve</a> | <a href="chat.php?delete_id=<?php echo $row_rsChat['message_id']; ?>" onClick="var a = confirm('do you really want to delete this?'); return a;">delete</a> | <a href="chat.php?edit_id=<?php echo $row_rsChat['message_id']; ?>">edit</a></td>
        <td><?php echo $row_rsChat['message_id']; ?></td>
        <td><?php echo $row_rsChat['uid']; ?></td>
        <td><?php echo $row_rsChat['to_uid']; ?></td>
        <td><?php echo $row_rsChat['subject']; ?></td>
        <td><?php echo $row_rsChat['message']; ?></td>
        <td><?php echo $row_rsChat['message_read']; ?></td>
        <td><?php echo $row_rsChat['message_date']; ?></td>
        <td><?php echo $row_rsChat['read_date']; ?></td>
        <td><?php echo $row_rsChat['status']; ?></td>
        <td><?php echo $row_rsChat['message_approved']; ?></td>
        <td><?php echo $row_rsChat['module_id']; ?></td>
        <td><?php echo $row_rsChat['message_from_delete']; ?></td>
        <td><?php echo $row_rsChat['message_to_delete']; ?></td>
        <td><?php echo $row_rsChat['message_from_delete_dt']; ?></td>
        <td><?php echo $row_rsChat['message_to_delete_dt']; ?></td>
        <td><?php echo $row_rsChat['deleted']; ?></td>
      </tr>
      <?php } while ($row_rsChat = mysql_fetch_assoc($rsChat)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsChat == 0) { // Show if recordset empty ?>
  <p>No Record Found.</p>
  <?php } // Show if recordset empty ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsChat);
?>
