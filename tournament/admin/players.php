<?php require_once('../../Connections/connChess.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	if (isset($_POST['player_id'])) {
		foreach ($_POST['player_id'] as $k => $v) {
		  echo $insertSQL = sprintf("INSERT INTO tournament_player (tournament_id, player_id) VALUES (%s, %s)",
							   GetSQLValueString($_POST['tournament_id'], "int"),
							   GetSQLValueString($v, "int"));
		
		  mysql_select_db($database_connChess, $connChess);
		  $Result1 = @mysql_query($insertSQL, $connChess);
		  }
  	}
}

if ((isset($_GET['tournament_id'])) && ($_GET['tournament_id'] != "") && (isset($_GET['player_id'])) && ($_GET['player_id'] != "") && isset($_GET['delete'])) {
  $deleteSQL = sprintf("DELETE FROM tournament_player WHERE tournament_id=%s and player_id=%s",
                       GetSQLValueString($_GET['tournament_id'], "int"),
                       GetSQLValueString($_GET['player_id'], "int"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($deleteSQL, $connChess) or die(mysql_error());
}

$colname_rsPlayers = "-1";
if (isset($_GET['tournament_id'])) {
  $colname_rsPlayers = (get_magic_quotes_gpc()) ? $_GET['tournament_id'] : addslashes($_GET['tournament_id']);
}
mysql_select_db($database_connChess, $connChess);
$query_rsPlayers = sprintf("SELECT * FROM tournament_player as t LEFT JOIN players as p ON t.player_id = p.player_id WHERE t.tournament_id = %s", $colname_rsPlayers);
$rsPlayers = mysql_query($query_rsPlayers, $connChess) or die(mysql_error());
$row_rsPlayers = mysql_fetch_assoc($rsPlayers);
$totalRows_rsPlayers = mysql_num_rows($rsPlayers);

mysql_select_db($database_connChess, $connChess);
$query_rsP = "SELECT * FROM players ORDER BY player_id ASC";
$rsP = mysql_query($query_rsP, $connChess) or die(mysql_error());
$row_rsP = mysql_fetch_assoc($rsP);
$totalRows_rsP = mysql_num_rows($rsP);
?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Pairings</title>
</head>

<body>
<h1>Players</h1>
<p><a href="index.php">Back</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>Player_id:</td>
          <td valign="top"><select name="player_id[]" size="20" multiple>
            <?php
do {  
?>
            <option value="<?php echo $row_rsP['player_id']?>"><?php echo $row_rsP['player_id']?>. <?php echo $row_rsP['player_name']?></option>
            <?php
} while ($row_rsP = mysql_fetch_assoc($rsP));
  $rows = mysql_num_rows($rsP);
  if($rows > 0) {
      mysql_data_seek($rsP, 0);
	  $row_rsP = mysql_fetch_assoc($rsP);
  }
?>
                                </select>
          </td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>&nbsp;</td>
          <td valign="top"><input type="submit" value="Insert record"></td>
        </tr>
      </table>
      <input type="hidden" name="tournament_id" value="<?php echo $_GET['tournament_id']; ?>">
      <input type="hidden" name="MM_insert" value="form1">
</form>
    <?php if ($totalRows_rsPlayers > 0) { // Show if recordset not empty ?>
      <h3>View Players  </h3>
    <table border="1">
      <tr>
        <td><strong>tournament_id</strong></td>
        <td><strong>player_id</strong></td>
        <td><strong>player_id</strong></td>
        <td><strong>player_name</strong></td>
        <td><strong>player_email</strong></td>
        <td><strong>player_details</strong></td>
        <td>delete</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rsPlayers['tournament_id']; ?></td>
          <td><?php echo $row_rsPlayers['player_id']; ?></td>
          <td><?php echo $row_rsPlayers['player_id']; ?></td>
          <td><?php echo $row_rsPlayers['player_name']; ?></td>
          <td><?php echo $row_rsPlayers['player_email']; ?></td>
          <td><?php echo $row_rsPlayers['player_details']; ?></td>
          <td><a href="players.php?delete=1&tournament_id=<?php echo $row_rsPlayers['tournament_id']; ?>&player_id=<?php echo $row_rsPlayers['player_id']; ?>">delete</a></td>
        </tr>
        <?php } while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers)); ?>
          </table>
      <?php } // Show if recordset not empty ?></body>
</html>
<?php
mysql_free_result($rsPlayers);

mysql_free_result($rsP);
?>
