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
  $insertSQL = sprintf("INSERT INTO chess_swiss_league_results (lid, round, p1, p2, color) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lid'], "int"),
                       GetSQLValueString($_POST['round'], "int"),
                       GetSQLValueString($_POST['p1'], "int"),
                       GetSQLValueString($_POST['p2'], "int"),
                       GetSQLValueString($_POST['color'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());

  $insertSQL = sprintf("INSERT INTO chess_swiss_league_results (lid, round, p1, p2, color) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lid'], "int"),
                       GetSQLValueString($_POST['round'], "int"),
                       GetSQLValueString($_POST['p2'], "int"),
                       GetSQLValueString($_POST['p1'], "int"),
                       GetSQLValueString(($_POST['color'] == 'W' ? 'B' : 'W'), "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());

  $insertGoTo = "tournament_manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE chess_swiss_league_results SET round=%s, p1=%s, p2=%s, `result`=%s, color=%s WHERE rid=%s",
                       GetSQLValueString($_POST['round'], "int"),
                       GetSQLValueString($_POST['p1'], "int"),
                       GetSQLValueString($_POST['p2'], "int"),
                       GetSQLValueString($_POST['result'], "text"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['rid'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($updateSQL, $connMain) or die(mysql_error());

  $updateGoTo = "tournament_manage.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO chess_swiss_league_players (lid, player_name, player_details) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['lid'], "int"),
                       GetSQLValueString($_POST['player_name'], "text"),
                       GetSQLValueString($_POST['player_details'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
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

$colname_rsPlayers = "-1";
if (isset($_GET['lid'])) {
  $colname_rsPlayers = $_GET['lid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsPlayers = sprintf("SELECT * FROM chess_swiss_league_players WHERE lid = %s", GetSQLValueString($colname_rsPlayers, "int"));
$rsPlayers = mysql_query($query_rsPlayers, $connMain) or die(mysql_error());
$row_rsPlayers = mysql_fetch_assoc($rsPlayers);
$totalRows_rsPlayers = mysql_num_rows($rsPlayers);

$colname_rsResults = "-1";
if (isset($_GET['lid'])) {
  $colname_rsResults = $_GET['lid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsResults = sprintf("SELECT r.*, p1.pid as pid1, p1.player_name as player_name1, p1.player_details as player_details1, p2.pid as pid2, p2.player_name as player_name2, p2.player_details as player_details2 FROM chess_swiss_league_results as r LEFT JOIN chess_swiss_league_players as p1 ON r.p1 = p1.pid LEFT JOIN chess_swiss_league_players as p2 ON r.p2 = p2.pid WHERE r.lid = %s ORDER BY r.round, r.p1, r.p2", GetSQLValueString($colname_rsResults, "int"));
$rsResults = mysql_query($query_rsResults, $connMain) or die(mysql_error());
$row_rsResults = mysql_fetch_assoc($rsResults);
$totalRows_rsResults = mysql_num_rows($rsResults);

$colname_rsEdit = "-1";
if (isset($_GET['edit_id'])) {
  $colname_rsEdit = $_GET['edit_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsEdit = sprintf("SELECT * FROM chess_swiss_league_results WHERE rid = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $connMain) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

$colname_rsRounds = "-1";
if (isset($_GET['lid'])) {
  $colname_rsRounds = $_GET['lid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsRounds = sprintf("SELECT max(round) as rd FROM chess_swiss_league_results WHERE lid = %s", GetSQLValueString($colname_rsRounds, "int"));
$rsRounds = mysql_query($query_rsRounds, $connMain) or die(mysql_error());
$row_rsRounds = mysql_fetch_assoc($rsRounds);
$totalRows_rsRounds = mysql_num_rows($rsRounds);

//current rounds
$rounds = !empty($row_rsRounds['rd']) ? $row_rsRounds['rd'] : 0;


if (!empty($_GET['generate']) && !empty($_GET['round'])) {
  $query = sprintf("select * from chess_swiss_league_results where lid = %s AND round = %s", GetSQLValueString($_GET['lid'], "int"), GetSQLValueString($_GET['round'], "int"));
  $rs = mysql_query($query) or die(mysql_error());
  $rows = mysql_num_rows($rs);
  if ($rows > 0) {
    header("Location: tournament_manage.php?lid=".$_GET['lid']);
    exit;
  }
  $players = array();
  do {
      $players[] = $row_rsPlayers;
  } while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers));
  $rows = mysql_num_rows($rsPlayers);
  if($rows > 0) {
    mysql_data_seek($rsPlayers, 0);
    $row_rsPlayers = mysql_fetch_assoc($rsPlayers);
  }
  $pairings = array();
  if ($_GET['round'] == 1) {
    for ($i = 0; $i < count($players); $i = $i + 2) {
      $k = $i;
      //(lid, round, p1, p2, color)
      if (!isset($players[$k+1]['pid'])) {
        $pairs = array('from' => $players[$k]['pid'], 'to' => -1, 'round' => $_GET['round']);
      } else {
        $pairs = array('from' => $players[$k]['pid'], 'to' => $players[$k+1]['pid'], 'round' => $_GET['round']);
      }
      $pairings[] = $pairs;
      $insertSQL = sprintf("INSERT INTO chess_swiss_league_results (lid, round, p1, p2, color) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_GET['lid'], "int"),
                       GetSQLValueString($_GET['round'], "int"),
                       GetSQLValueString($pairs['from'], "int"),
                       GetSQLValueString($pairs['to'], "int"),
                       GetSQLValueString('W', "text"));

      mysql_select_db($database_connMain, $connMain);
      $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
    
      if ($pairs['to'] != -1) {
          $insertSQL = sprintf("INSERT INTO chess_swiss_league_results (lid, round, p1, p2, color) VALUES (%s, %s, %s, %s, %s)",
                               GetSQLValueString($_GET['lid'], "int"),
                               GetSQLValueString($_GET['round'], "int"),
                               GetSQLValueString($pairs['to'], "int"),
                               GetSQLValueString($pairs['from'], "int"),
                               GetSQLValueString('B', "text"));
        
          mysql_select_db($database_connMain, $connMain);
          $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
      }
    }
    header("Location: tournament_manage.php?lid=".$_GET['lid']);
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Tournament Manage</title>
</head>

<body>
<h3>Tournament Manage &quot;<?php echo $row_rsTournament['league_name']; ?>&quot;</h3>
<p><a href="tournament.php">Back</a>  | <a href="tournament_manage.php?lid=<?php echo $_GET['lid']; ?>">Refresh Current Page</a> | <a href="tournament_manage.php?lid=<?php echo $_GET['lid']; ?>&generate=1&round=<?php echo $rounds + 1; ?>">Generate Round <?php echo $rounds + 1; ?></a></p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <p>
    <label for="round"><strong>Round Number:</strong></label>
    <input type="text" name="round" id="round">
  </p>
  <p>Player 1 Color: 
    <input name="color" type="radio" id="radio" value="W" checked>
    <label for="color">White 
      <input type="radio" name="color" id="radio2" value="B">
    Black</label>
  </p>
  <p>
    <label for="p1">Player 1:</label>
    <select name="p1" id="p1">
      <?php
do {  
?>
      <option value="<?php echo $row_rsPlayers['pid']?>"><?php echo $row_rsPlayers['player_name']?></option>
      <?php
} while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers));
  $rows = mysql_num_rows($rsPlayers);
  if($rows > 0) {
      mysql_data_seek($rsPlayers, 0);
	  $row_rsPlayers = mysql_fetch_assoc($rsPlayers);
  }
?>
    </select>
  </p>
  <p>
    <label for="p2">Player 2:</label>
    <select name="p2" id="p2">
      <?php
do {  
?>
      <option value="<?php echo $row_rsPlayers['pid']?>"><?php echo $row_rsPlayers['player_name']?></option>
      <?php
} while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers));
  $rows = mysql_num_rows($rsPlayers);
  if($rows > 0) {
      mysql_data_seek($rsPlayers, 0);
	  $row_rsPlayers = mysql_fetch_assoc($rsPlayers);
  }
?>
    </select>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit">
    <input name="lid" type="hidden" id="lid" value="<?php echo $_GET['lid']; ?>">
  </p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
  <h3>Edit Results</h3>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table>
      <tr valign="baseline">
        <td nowrap align="right">Round:</td>
        <td><input type="text" name="round" value="<?php echo htmlentities($row_rsEdit['round'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">P1:</td>
        <td><select name="p1">
          <?php
do {  
?>
          <option value="<?php echo $row_rsPlayers['pid']?>"<?php if (!(strcmp($row_rsPlayers['pid'], htmlentities($row_rsEdit['p1'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsPlayers['player_name']?></option>
          <?php
} while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers));
  $rows = mysql_num_rows($rsPlayers);
  if($rows > 0) {
      mysql_data_seek($rsPlayers, 0);
	  $row_rsPlayers = mysql_fetch_assoc($rsPlayers);
  }
?>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">P2:</td>
        <td><select name="p2">
          <?php
do {  
?>
          <option value="<?php echo $row_rsPlayers['pid']?>"<?php if (!(strcmp($row_rsPlayers['pid'], htmlentities($row_rsEdit['p2'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsPlayers['player_name']?></option>
          <?php
} while ($row_rsPlayers = mysql_fetch_assoc($rsPlayers));
  $rows = mysql_num_rows($rsPlayers);
  if($rows > 0) {
      mysql_data_seek($rsPlayers, 0);
	  $row_rsPlayers = mysql_fetch_assoc($rsPlayers);
  }
?>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">Result:</td>
        <td valign="baseline"><table>
          <tr>
            <td><input type="radio" name="result" value="W" <?php if (!(strcmp(htmlentities($row_rsEdit['result'], ENT_COMPAT, 'UTF-8'),"W"))) {echo "checked=\"checked\"";} ?>>
              W</td>
            </tr>
          <tr>
            <td><input type="radio" name="result" value="D" <?php if (!(strcmp(htmlentities($row_rsEdit['result'], ENT_COMPAT, 'UTF-8'),"D"))) {echo "checked=\"checked\"";} ?>>
              D</td>
            </tr>
          <tr>
            <td><input type="radio" name="result" value="L" <?php if (!(strcmp(htmlentities($row_rsEdit['result'], ENT_COMPAT, 'UTF-8'),"L"))) {echo "checked=\"checked\"";} ?>>
              L</td>
            </tr>
        </table></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">Color:</td>
        <td valign="baseline"><table>
          <tr>
            <td><input type="radio" name="color" value="W" <?php if (!(strcmp(htmlentities($row_rsEdit['color'], ENT_COMPAT, 'UTF-8'),"W"))) {echo "checked=\"checked\"";} ?>>
              W</td>
            </tr>
          <tr>
            <td><input type="radio" name="color" value="B" <?php if (!(strcmp(htmlentities($row_rsEdit['color'], ENT_COMPAT, 'UTF-8'),"B"))) {echo "checked=\"checked\"";} ?>>
              B</td>
            </tr>
        </table></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="Update record"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2">
    <input type="hidden" name="rid" value="<?php echo $row_rsEdit['rid']; ?>">
  </form>
  <?php } // Show if recordset not empty ?>
<p>&nbsp;</p>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <td>rid</td>
    <td>lid</td>
    <td>round</td>
    <td>p1</td>
    <td>p2</td>
    <td>result</td>
    <td>color</td>
    <td>Edit</td>
  </tr>
  <?php $results = array(); ?>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsResults['rid']; ?></td>
      <td><?php echo $row_rsResults['lid']; ?></td>
      <td><?php echo $row_rsResults['round']; ?></td>
      <td><?php echo $row_rsResults['p1']; ?></td>
      <td><?php echo $row_rsResults['p2']; ?></td>
      <td><?php echo $row_rsResults['result']; ?></td>
      <td><?php echo $row_rsResults['color']; ?></td>
      <td><a href="tournament_manage.php?lid=<?php echo $row_rsResults['lid']; ?>&edit_id=<?php echo $row_rsResults['rid']; ?>">Edit</a></td>
    </tr>
    <?php
    $results[$row_rsResults['p1']]['rounds'][$row_rsResults['round']] = $row_rsResults;
    $results[$row_rsResults['p1']]['details'] = $row_rsResults;
    ?>
    <?php } while ($row_rsResults = mysql_fetch_assoc($rsResults)); ?>
</table>
<p>
  <?php 
//echo '<pre>';
//print_r($results);
//echo '</pre>';
?>
</p>
<?php
if (!empty($results)) {
  foreach ($results as $k => $v) { ?>
<table border="1" cellspacing="1" cellpadding="5">
  <tr>
    <td colspan="4"><h3>Nimzowistch Chess Club Match Record</h3>
    <p>Phone: 4085052726 Email: manishkk74@gmail.com</p></td>
  </tr>
  <tr>
    <td><strong>Player Number:</strong></td>
    <td><?php echo $k; ?></td>
    <td><strong>Player Name:</strong></td>
    <td><?php echo $v['details']['player_name1']; ?></td>
  </tr>
  <tr>
    <td colspan="4"><table width="100%" border="1" cellpadding="5" cellspacing="1">
      <tr>
        <td valign="top"><strong>Color</strong></td>
        <td valign="top"><strong>Win</strong></td>
        <td valign="top"><strong>Draw</strong></td>
        <td valign="top"><strong>Loss</strong></td>
        <td valign="top"><strong>Opponents Number</strong></td>
        <td valign="top"><strong>Opponents Name</strong></td>
        <td valign="top"><strong>Match Pts</strong></td>
      </tr>
      <?php if (!empty($v['rounds'])) { 
      $total = 0;
      ?>
      <?php foreach ($v['rounds'] as $rounds) {
        $win = ''; $loss = ''; $draw = '';
      if ($rounds['result'] == 'W') { $win = 'X'; $total = $total + 1.0; }
      if ($rounds['result'] == 'D') { $draw = 'X'; $total = $total + 0.5; }
      if ($rounds['result'] == 'L') { $loss = 'X'; $total = $total + 0; }
      
      ?>
      <tr>
        <td valign="top"><?php echo $rounds['color']; ?></td>
        <td valign="top"><?php echo $win; ?></td>
        <td valign="top"><?php echo $draw; ?></td>
        <td valign="top"><?php echo $loss; ?></td>
        <td valign="top"><?php echo $rounds['pid2']; ?></td>
        <td valign="top"><?php echo !empty($rounds['player_name2']) ? $rounds['player_name2'] : 'Bye'; ?></td>
        <td valign="top"><?php echo $total; ?></td>
      </tr>
      <?php }} ?>
    </table></td>
  </tr>
</table>
<p>&nbsp; </p>
<?php
  }
}
?>
</body>
</html>
<?php
mysql_free_result($rsTournament);

mysql_free_result($rsPlayers);

mysql_free_result($rsResults);

mysql_free_result($rsEdit);

mysql_free_result($rsRounds);
?>
