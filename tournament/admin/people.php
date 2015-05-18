<?php require_once('../../Connections/connChess.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO players (player_name, player_email, player_details) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['player_name'], "text"),
                       GetSQLValueString($_POST['player_email'], "text"),
                       GetSQLValueString($_POST['player_details'], "text"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($insertSQL, $connChess) or die(mysql_error());

  $insertGoTo = "people.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE players SET player_name=%s, player_email=%s, player_details=%s WHERE player_id=%s",
                       GetSQLValueString($_POST['player_name'], "text"),
                       GetSQLValueString($_POST['player_email'], "text"),
                       GetSQLValueString($_POST['player_details'], "text"),
                       GetSQLValueString($_POST['player_id'], "int"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($updateSQL, $connChess) or die(mysql_error());
}

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM players WHERE player_id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($deleteSQL, $connChess) or die(mysql_error());
}

$maxRows_rsView = 200;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connChess, $connChess);
$query_rsView = "SELECT * FROM players";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connChess) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$colname_rsEdit = "-1";
if (isset($_GET['edit_id'])) {
  $colname_rsEdit = $_GET['edit_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsEdit = sprintf("SELECT * FROM players WHERE player_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $connChess) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

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
<title>Manage People</title>
</head>

<body>
<h1>Manage People</h1>
<p><a href="index.php">Back</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table>
        <tr valign="baseline">
            <td nowrap align="right"><strong>Player_name:</strong></td>
            <td><input type="text" name="player_name" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
            <td nowrap align="right"><strong>Player_email:</strong></td>
            <td><input type="text" name="player_email" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
            <td nowrap align="right"><strong>Player_details:</strong></td>
            <td><input type="text" name="player_details" value="" size="32"></td>
        </tr>
        <tr valign="baseline">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" value="Insert record"></td>
        </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
    <h3>Edit People</h3>
    <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
        <table>
            <tr valign="baseline">
                <td nowrap align="right"><strong>Player_name:</strong></td>
                <td><input type="text" name="player_name" value="<?php echo htmlentities($row_rsEdit['player_name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
                <td nowrap align="right"><strong>Player_email:</strong></td>
                <td><input type="text" name="player_email" value="<?php echo htmlentities($row_rsEdit['player_email'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
                <td nowrap align="right"><strong>Player_details:</strong></td>
                <td><input type="text" name="player_details" value="<?php echo htmlentities($row_rsEdit['player_details'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
            </tr>
            <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td><input type="submit" value="Update record"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form2">
        <input type="hidden" name="player_id" value="<?php echo $row_rsEdit['player_id']; ?>">
    </form>
    <?php } // Show if recordset not empty ?>
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
            <td><strong>player_id</strong></td>
            <td><strong>player_name</strong></td>
            <td><strong>player_email</strong></td>
            <td><strong>player_details</strong></td>
            <td><strong>Edit</strong></td>
            <td><strong>Delete</strong></td>
        </tr>
        <?php do { ?>
            <tr>
                <td><?php echo $row_rsView['player_id']; ?></td>
                <td><?php echo $row_rsView['player_name']; ?></td>
                <td><?php echo $row_rsView['player_email']; ?></td>
                <td><?php echo $row_rsView['player_details']; ?></td>
                <td><a href="people.php?edit_id=<?php echo $row_rsView['player_id']; ?>">Edit</a></td>
                <td><a href="people.php?delete_id=<?php echo $row_rsView['player_id']; ?>" onClick="var a = confirm('do you really want to delete this person?'); return a;">Delete</a></td>
            </tr>
            <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
    </table>
    <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
    <p>No People Found.</p>
    <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsEdit);
?>
