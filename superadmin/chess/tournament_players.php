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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO chess_swiss_league_players (lid, player_name, player_details) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['lid'], "int"),
                       GetSQLValueString($_POST['player_name'], "text"),
                       GetSQLValueString($_POST['player_details'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['pid'])) && ($_GET['pid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM chess_swiss_league_results WHERE p1=%s",
                       GetSQLValueString($_GET['pid'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['pid'])) && ($_GET['pid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM chess_swiss_league_results WHERE p2=%s",
                       GetSQLValueString($_GET['pid'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['pid'])) && ($_GET['pid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM chess_swiss_league_players WHERE pid=%s",
                       GetSQLValueString($_GET['pid'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

$colname_rsTournament = "-1";
if (isset($_GET['lid'])) {
  $colname_rsTournament = $_GET['lid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsTournament = sprintf("SELECT * FROM chess_swiss_league WHERE lid = %s", GetSQLValueString($colname_rsTournament, "int"));
$rsTournament = mysql_query($query_rsTournament, $connMain) or die(mysql_error());
$row_rsTournament = mysql_fetch_assoc($rsTournament);
$totalRows_rsTournament = mysql_num_rows($rsTournament);

$colname_rsPlayer = "-1";
if (isset($_GET['lid'])) {
  $colname_rsPlayer = $_GET['lid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsPlayer = sprintf("SELECT * FROM chess_swiss_league_players WHERE lid = %s", GetSQLValueString($colname_rsPlayer, "int"));
$rsPlayer = mysql_query($query_rsPlayer, $connMain) or die(mysql_error());
$row_rsPlayer = mysql_fetch_assoc($rsPlayer);
$totalRows_rsPlayer = mysql_num_rows($rsPlayer);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Players</title>
</head>

<body>
<h3>Tournament Manage &quot;<?php echo $row_rsTournament['league_name']; ?>&quot;</h3>
<p><a href="tournament.php">Back</a> | <a href="tournament_players.php?lid=<?php echo $_GET['lid']; ?>">Refresh Current Page</a></p>
<h3>Add Players</h3>
<form method="post" name="form3" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Player Name:</td>
      <td><input type="text" name="player_name" value="" size="32" required></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Player Details:</td>
      <td><textarea name="player_details" cols="50" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="lid" value="<?php echo $_GET['lid']; ?>">
  <input type="hidden" name="MM_insert" value="form3">
</form>
<?php if ($totalRows_rsPlayer > 0) { // Show if recordset not empty ?>
  <h3>List of Players</h3>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>PID</strong></td>
      <td><strong>Player Name</strong></td>
      <td><strong>Delete</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsPlayer['pid']; ?></td>
        <td><?php echo $row_rsPlayer['player_name']; ?></td>
        <td><a href="tournament_players.php?lid=<?php echo $row_rsPlayer['lid']; ?>&pid=<?php echo $row_rsPlayer['pid']; ?>" onClick="var a = confirm('do you really want to delete this player?'); return a;">Delete</a></td>
      </tr>
      <?php } while ($row_rsPlayer = mysql_fetch_assoc($rsPlayer)); ?>
  </table>
  <?php } // Show if recordset not empty ?>

</body>
</html>
<?php
mysql_free_result($rsTournament);

mysql_free_result($rsPlayer);
?>
