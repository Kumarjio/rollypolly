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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO chess_swiss_league (league_name, league_date) VALUES (%s, %s)",
                       GetSQLValueString($_POST['league_name'], "text"),
                       GetSQLValueString($_POST['league_date'], "date"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

mysql_select_db($database_connMain, $connMain);
$query_rsTournament = "SELECT * FROM chess_swiss_league";
$rsTournament = mysql_query($query_rsTournament, $connMain) or die(mysql_error());
$row_rsTournament = mysql_fetch_assoc($rsTournament);
$totalRows_rsTournament = mysql_num_rows($rsTournament);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Tournaments</title>
</head>

<body>
<h3>Add New Tournament</h3>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">League_name:</td>
      <td><input type="text" name="league_name" value="" size="32" required></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">League_date:</td>
      <td><input type="text" name="league_date" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsTournament > 0) { // Show if recordset not empty ?>
  <h3>List All Tournaments</h3>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>ID</strong></td>
      <td><strong>Tournament Name</strong></td>
      <td><strong>Tournament Date</strong></td>
      <td><strong>Players</strong></td>
      <td><strong>Manage</strong></td>
      <td><strong>Edit</strong></td>
      <td><strong>Delete</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsTournament['lid']; ?></td>
        <td><?php echo $row_rsTournament['league_name']; ?></td>
        <td><?php echo $row_rsTournament['league_date']; ?></td>
        <td><a href="tournament_players.php?lid=<?php echo $row_rsTournament['lid']; ?>">Players</a></td>
        <td><a href="tournament_manage.php?lid=<?php echo $row_rsTournament['lid']; ?>">Manage</a></td>
        <td>Edit</td>
        <td>Delete</td>
      </tr>
      <?php } while ($row_rsTournament = mysql_fetch_assoc($rsTournament)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsTournament);
?>
