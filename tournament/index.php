<?php require_once('../Connections/connChess.php'); ?>
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
$query_rsView = sprintf("SELECT a.board as board1, b.board as board2, a.id AS id1, a.tournament_id AS tid1, a.player_id AS p1, a.player_id2 AS p2, a.round AS r1, a.score AS s1, b.id AS id2, b.tournament_id AS tid2, b.player_id AS p3, b.player_id2 AS p4, b.round AS r2, b.score AS s2, pl1.player_name AS p2Name, pl2.player_name AS p1Name, a.white as white, a.black as black FROM `tournament_pairings` AS b INNER JOIN players AS pl1 ON b.player_id = pl1.player_id INNER JOIN  `tournament_pairings` AS a ON a.player_id2 = b.player_id AND a.player_id = b.player_id2 AND a.round = b.round INNER JOIN players AS pl2 ON a.player_id = pl2.player_id WHERE b.`tournament_id` = %s AND a.`tournament_id` = %s ORDER BY r1 DESC , board1 ASC, p1Name ASC, p2Name ASC", GetSQLValueString($coltournamentid_rsView, "int"),GetSQLValueString($coltournamentid_rsView, "int"));
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

$colid_rsLeaderBoard = "1";
if (isset($_GET['tournament_id'])) {
  $colid_rsLeaderBoard = $_GET['tournament_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsLeaderBoard = sprintf("SELECT *  FROM  pointCalculation as pc LEFT JOIN players as p ON pc.player_id = p.player_id WHERE pc.tournament_id = %s ORDER BY pc.totalpoints DESC, pc.player_id ASC ", GetSQLValueString($colid_rsLeaderBoard, "int"));
$rsLeaderBoard = mysql_query($query_rsLeaderBoard, $connChess) or die(mysql_error());
$row_rsLeaderBoard = mysql_fetch_assoc($rsLeaderBoard);
$totalRows_rsLeaderBoard = mysql_num_rows($rsLeaderBoard);

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
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Tournament Pairings &amp; Results</title>
<style type="text/css">
<!--
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
-->
</style>
</head>

<body>
<div style="width:1000px;">
<h1>San Jose Open Chess Tournament (17th May, 2015)</h1>
<iframe height="480" width="360" frameborder="0" src="http://www.magisto.com/embed/LwESJEQURi80WkRgCzE?l=vem&o=w&c=b"></iframe>
<?php if ($totalRows_rsLeaderBoard > 0) { // Show if recordset not empty ?>
<?php $num = 0; ?>
<div style="float:right">
    <h3>Leader Board</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
          <td><strong>Number</strong></td>
            <td><strong>Player Name</strong></td>
            <td><strong>Points</strong></td>
        </tr>
        <?php do { ?>
		<?php $num++; ?>
            <tr>
              <td><?php echo $num; ?></td>
                <td><?php echo $row_rsLeaderBoard['player_id'].'. '.$row_rsLeaderBoard['player_name']; ?></td>
                <td><?php echo $row_rsLeaderBoard['totalpoints']; ?></td>
            </tr>
            <?php } while ($row_rsLeaderBoard = mysql_fetch_assoc($rsLeaderBoard)); ?>
    </table>
</div>
    <?php } // Show if recordset not empty ?>
    
    
<div style="float:left">
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
    <p>No Record Found</p>
    <?php } // Show if recordset empty ?>
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
          <td valign="top"><strong>Board</strong></td>
            <td valign="top"><strong>Player</strong></td>
            <td valign="top"><strong>Against</strong></td>
            <td valign="top"><strong>Result Player</strong></td>
            <td valign="top"><strong>Result Against Player</strong></td>
        </tr>
        <?php do { ?>
        <?php
        if ($row_rsView['r1'] != $tmpR1) {
               $tmpR1 = $row_rsView['r1'];
               ?>
               
            <tr>
              <td valign="top">&nbsp;</td>
                <td valign="top"><strong>ROUND <?php echo $row_rsView['r1']; ?></strong></td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <?php
        }
        ?>
            <tr>
              <td valign="top"><?php echo $row_rsView['board1']; ?></td>
                <td valign="top"><?php echo $row_rsView['p1']; ?>. <?php echo $row_rsView['p1Name']; ?> (<?php if ($row_rsView['p1'] == $row_rsView['white']) echo 'white'; else if ($row_rsView['p1'] == $row_rsView['black']) echo 'black'; ?>)</td>
                <td valign="top"><?php echo $row_rsView['p2']; ?>. <?php echo $row_rsView['p2Name']; ?> (<?php if ($row_rsView['p2'] == $row_rsView['white']) echo 'white'; else if ($row_rsView['p2'] == $row_rsView['black']) echo 'black'; ?>)</td>
                <td valign="top"><strong><?php echo $row_rsView['s1']; ?></strong> (Player: <?php echo $row_rsView['p1']; ?>)</td>
                <td valign="top"><strong><?php echo $row_rsView['s2']; ?></strong> (Player: <?php echo $row_rsView['p2']; ?>)</td>
            </tr>
            <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
    </table>
<?php } // Show if recordset not empty ?>
</div>


</div>
<!--SELECT a.id as id1, a.tournament_id as tid1, a.player_id as p1, a.player_id2 as p2, a.round as r1, a.score as s1, b.id as id2, b.tournament_id as tid2, b.player_id as p3, b.player_id2 as p4, b.round as r2, b.score as s2 FROM `tournament_pairings` as b INNER JOIN `tournament_pairings` as a ON a.player_id2 = b.player_id and a.player_id = b.player_id2 AND a.round = b.round WHERE b.`tournament_id` = 1 AND a.`tournament_id` = 1 ORDER BY r1 DESC, p1 ASC



SELECT a.id AS id1, a.tournament_id AS tid1, a.player_id AS p1, a.player_id2 AS p2, a.round AS r1, a.score AS s1, b.id AS id2, b.tournament_id AS tid2, b.player_id AS p3, b.player_id2 AS p4, b.round AS r2, b.score AS s2, pl1.player_name AS p2Name, pl2.player_name AS p1Name
FROM  `tournament_pairings` AS b
INNER JOIN players AS pl1 ON b.player_id = pl1.player_id
INNER JOIN  `tournament_pairings` AS a ON a.player_id2 = b.player_id
AND a.player_id = b.player_id2
AND a.round = b.round
INNER JOIN players AS pl2 ON a.player_id = pl2.player_id
WHERE b.`tournament_id` =1
AND a.`tournament_id` =1
ORDER BY r1 DESC , p1 ASC 
LIMIT 0 , 30-->

</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsLeaderBoard);
?>
