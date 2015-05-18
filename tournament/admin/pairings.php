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

if ((isset($_GET['delete'])) && (isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tournament_pairings WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($deleteSQL, $connChess) or die(mysql_error());

  $deleteGoTo = "pairings.php";
  header(sprintf("Location: %s", $deleteGoTo));
  exit;
}

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


$maxRows_rsView = 10000;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$coltournamentid_rsView = "1";
if (isset($_GET['tournament_id'])) {
  $coltournamentid_rsView = $_GET['tournament_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsView = sprintf("SELECT a.id AS id1, a.tournament_id AS tid1, a.player_id AS p1, a.player_id2 AS p2, a.round AS r1, a.score AS s1, b.id AS id2, b.tournament_id AS tid2, b.player_id AS p3, b.player_id2 AS p4, b.round AS r2, b.score AS s2, pl1.player_name AS p2Name, pl2.player_name AS p1Name, a.white as white, a.black as black FROM `tournament_pairings` AS b INNER JOIN players AS pl1 ON b.player_id = pl1.player_id INNER JOIN  `tournament_pairings` AS a ON a.player_id2 = b.player_id AND a.player_id = b.player_id2 AND a.round = b.round INNER JOIN players AS pl2 ON a.player_id = pl2.player_id WHERE b.`tournament_id` = %s AND a.`tournament_id` = %s ORDER BY r1 DESC , id1 ASC", GetSQLValueString($coltournamentid_rsView, "int"),GetSQLValueString($coltournamentid_rsView, "int"));
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

$tmpR1 = 0;

function pr($d)
{
 
 echo '<pre>';
 print_r($d);
 echo '</pre>';      
}
if (isset($_POST['MM_Edit']) && $_POST['MM_Edit'] == 'form2') {
	pr($_POST);
	if (isset($_POST['score'])) {
		foreach ($_POST['score'] as $k => $v) {
			$query = "update tournament_pairings set `score` = ".GetSQLValueString($v, "double")." WHERE id = ".GetSQLValueString($k, "int");
			echo $query;
			echo '<br>';
			mysql_query($query) or die(mysql_error());
		}
	}
}

if (!empty($_POST['player_id']) && !empty($_POST['round'])) {
$board = 0;
   foreach ($_POST['player_id'] as $k => $v) {
        if (empty($_POST['player_id'][$k]) || empty($_POST['player_id2'][$k])) {
            continue;   
        }
		$score = null;
		 if ($_POST['player_id'][$k] == $_POST['player_id2'][$k]) {
		 	$score = 1;
		 }
		 $board++;
        echo $query = sprintf("insert into tournament_pairings set tournament_id = %s, round = %s, player_id = %s, player_id2 = %s, white = %s, black = %s, score=%s, board=%s", 
            GetSQLValueString($colname_rsTournamentPlayers, "int"), 
            GetSQLValueString($_POST['round'] , "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($score, "int"), 
            GetSQLValueString($board, "int")
            );
         mysql_query($query) or die(mysql_error());
         echo '<br>';
		 if ($_POST['player_id'][$k] == $_POST['player_id2'][$k]) {
		 	continue;
		 }
        echo $query = sprintf("insert into tournament_pairings set tournament_id = %s, round = %s, player_id2 = %s, player_id = %s, white = %s, black = %s, board = %s", 
            GetSQLValueString($colname_rsTournamentPlayers, "int"), 
            GetSQLValueString($_POST['round'] , "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($_POST['player_id'][$k], "int"), 
            GetSQLValueString($_POST['player_id2'][$k], "int"), 
            GetSQLValueString($board, "int")
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
<h1>Pairings For Tournament</h1>
<p><a href="index.php">Back</a></p>
<form id="form1" name="form1" method="post">
    <p>
        <label for="round">Round:</label>
        <input type="number" name="round" id="round">
    </p>
    <p>
        <input type="submit" name="submit" id="submit" value="Create Pairings">
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




<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
    <p>No Record Found</p>
    <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
    <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
    </p>
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
	<form name="form2" method="post" action="">
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td valign="top"><strong>Player</strong></td>
            <td valign="top"><strong>Against</strong></td>
            <td valign="top"><strong>Result Player</strong></td>
            <td valign="top"><strong>Result Against Player</strong></td>
            <td valign="top"><strong>Edit</strong></td>
            <td valign="top"><strong>Delete</strong></td>
        </tr>
        <?php do { ?>
        <?php
        if ($row_rsView['r1'] != $tmpR1) {
               $tmpR1 = $row_rsView['r1'];
               ?>
               
            <tr>
                <td valign="top"><strong>ROUND <?php echo $row_rsView['r1']; ?></strong></td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <?php
        }
        ?>
            <tr>
                <td valign="top"><?php //pr($row_rsView); ?><?php echo $row_rsView['p1']; ?>. <?php echo $row_rsView['p1Name']; ?> (<?php if ($row_rsView['p1'] == $row_rsView['white']) echo 'white'; else if ($row_rsView['p1'] == $row_rsView['black']) echo 'black'; ?>)</td>
                <td valign="top"><?php echo $row_rsView['p2']; ?>. <?php echo $row_rsView['p2Name']; ?> (<?php if ($row_rsView['p2'] == $row_rsView['white']) echo 'white'; else if ($row_rsView['p2'] == $row_rsView['black']) echo 'black'; ?>)</td>
                <td valign="top"><?php echo $row_rsView['s1']; ?></td>
                <td valign="top"><?php echo $row_rsView['s2']; ?></td>
                <td valign="top">
                  <label><strong>Score
                  </strong></label>
                  <input type="text" name="score[<?php echo $row_rsView['id1']; ?>]" value="<?php echo $row_rsView['s1']; ?>">
                </td>
                <td valign="top"><a href="pairings.php?delete=1&id=<?php echo $row_rsView['id1']; ?>" onClick="var a = confirm('do you really want to delete this record?'); return a;">Delete</a></td>
            </tr>
            <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
    </table>
<input name="MM_Edit" type="text" value="form2">
<label>
<input type="submit" name="Submit" value="Update">
</label>
  </form>
<?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsTournamentPlayers);

mysql_free_result($rsView);
?>
