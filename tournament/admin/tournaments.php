<?php require_once('../../Connections/connChess.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

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
  $insertSQL = sprintf("INSERT INTO tournament (tournament) VALUES (%s)",
                       GetSQLValueString($_POST['tournament'], "text"));

  mysql_select_db($database_connChess, $connChess);
  $Result1 = mysql_query($insertSQL, $connChess) or die(mysql_error());
}

$maxRows_rsTournaments = 10;
$pageNum_rsTournaments = 0;
if (isset($_GET['pageNum_rsTournaments'])) {
  $pageNum_rsTournaments = $_GET['pageNum_rsTournaments'];
}
$startRow_rsTournaments = $pageNum_rsTournaments * $maxRows_rsTournaments;

mysql_select_db($database_connChess, $connChess);
$query_rsTournaments = "SELECT * FROM tournament ORDER BY tournament ASC";
$query_limit_rsTournaments = sprintf("%s LIMIT %d, %d", $query_rsTournaments, $startRow_rsTournaments, $maxRows_rsTournaments);
$rsTournaments = mysql_query($query_limit_rsTournaments, $connChess) or die(mysql_error());
$row_rsTournaments = mysql_fetch_assoc($rsTournaments);

if (isset($_GET['totalRows_rsTournaments'])) {
  $totalRows_rsTournaments = $_GET['totalRows_rsTournaments'];
} else {
  $all_rsTournaments = mysql_query($query_rsTournaments);
  $totalRows_rsTournaments = mysql_num_rows($all_rsTournaments);
}
$totalPages_rsTournaments = ceil($totalRows_rsTournaments/$maxRows_rsTournaments)-1;

$queryString_rsTournaments = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsTournaments") == false && 
        stristr($param, "totalRows_rsTournaments") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsTournaments = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsTournaments = sprintf("&totalRows_rsTournaments=%d%s", $totalRows_rsTournaments, $queryString_rsTournaments);
?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Tournaments</title>
</head>

<body>
<h1>Tournaments </h1>
<p><a href="index.php">Back</a></p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Tournament:</td>
      <td><input type="text" name="tournament" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsTournaments > 0) { // Show if recordset not empty ?>
  <h3>View All Tournaments</h3>
  <p> Records <?php echo ($startRow_rsTournaments + 1) ?> to <?php echo min($startRow_rsTournaments + $maxRows_rsTournaments, $totalRows_rsTournaments) ?> of <?php echo $totalRows_rsTournaments ?>
  <table border="0" width="50%" align="center">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_rsTournaments > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsTournaments=%d%s", $currentPage, 0, $queryString_rsTournaments); ?>">First</a>
      <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_rsTournaments > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsTournaments=%d%s", $currentPage, max(0, $pageNum_rsTournaments - 1), $queryString_rsTournaments); ?>">Previous</a>
      <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsTournaments < $totalPages_rsTournaments) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsTournaments=%d%s", $currentPage, min($totalPages_rsTournaments, $pageNum_rsTournaments + 1), $queryString_rsTournaments); ?>">Next</a>
      <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_rsTournaments < $totalPages_rsTournaments) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsTournaments=%d%s", $currentPage, $totalPages_rsTournaments, $queryString_rsTournaments); ?>">Last</a>
      <?php } // Show if not last page ?>      </td>
    </tr>
      </table>
  </p>
  <table border="1">
    <tr>
      <td>tournament_id</td>
      <td>tournament</td>
      <td>Players</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsTournaments['tournament_id']; ?></td>
        <td><?php echo $row_rsTournaments['tournament']; ?></td>
        <td><a href="players.php?tournament_id=<?php echo $row_rsTournaments['tournament_id']; ?>">Players</a></td>
      </tr>
      <?php } while ($row_rsTournaments = mysql_fetch_assoc($rsTournaments)); ?>
      </table>
  <?php } // Show if recordset not empty ?></body>
</html>
<?php
mysql_free_result($rsTournaments);
?>
