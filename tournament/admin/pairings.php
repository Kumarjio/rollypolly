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

$colname_rsTournamentPlayers = "1";
if (isset($_GET['tournament_id'])) {
  $colname_rsTournamentPlayers = $_GET['tournament_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsTournamentPlayers = sprintf("SELECT * FROM tournament_player as t INNER JOIN players as p on t.player_id = p.player_id WHERE t.tournament_id = %s", GetSQLValueString($colname_rsTournamentPlayers, "int"));
$rsTournamentPlayers = mysql_query($query_rsTournamentPlayers, $connChess) or die(mysql_error());
$row_rsTournamentPlayers = mysql_fetch_assoc($rsTournamentPlayers);
$totalRows_rsTournamentPlayers = mysql_num_rows($rsTournamentPlayers);

function pr($d)
{
 
 echo '<pre>';
 print_r($d);
 echo '</pre>';      
}
if (!empty($_POST['player_id']) && !empty($_POST['round'])) {
   foreach ($_POST['player_id'] as $k => $v) {
        if (empty($_POST['player_id'][$k]) || empty($_POST['player_id2'][$k])) {
            continue;   
        }
        echo $query = sprintf("insert into tournament_pairings set tournament_id = %s, round = %s, player_id = %s, player_id2 = %s, white = %s, black = %s", 
            GetSQLValueString($colname_rsTournamentPlayers, "int"), 
            GetSQLValueString($_POST['round'] , "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int")
            );
         mysql_query($query) or die(mysql_error());
         echo '<br>';
        echo $query = sprintf("insert into tournament_pairings set tournament_id = %s, round = %s, player_id2 = %s, player_id = %s, white = %s, black = %s", 
            GetSQLValueString($colname_rsTournamentPlayers, "int"), 
            GetSQLValueString($_POST['round'] , "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int")
            );
         mysql_query($query) or die(mysql_error());
   }
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Pairings</title>
</head>

<body>
<h1>Pairings For Tournament </h1>
<form id="form1" name="form1" method="post">
    <p>
        <label for="round">Round:</label>
        <input type="number" name="round" id="round">
    </p>
    <p>
        <input type="submit" name="submit" id="submit" value="Generate Pairings">
    </p>
    <ol>
    <?php if ($totalRows_rsTournamentPlayers > 0) { 
    for ($i = 0; $i < ceil($totalRows_rsTournamentPlayers/2); $i++) {
    ?>
    <li>
        <label for="player_id[]">Player White:</label>
        <input type="number" name="player_id[]" id="player_id[]">
        <label for="player_id2[]">Player Black:</label>
        <input type="number" name="player_id2[]" id="player_id2[]">
    </li>
    <?php
    }
    } ?>
    </ol>
    <p>
        <input type="submit" name="submit" id="submit" value="Create Pairings">
    </p>
</form>
<?php 
 ?>
</body>
</html>
<?php
mysql_free_result($rsTournamentPlayers);
?>
