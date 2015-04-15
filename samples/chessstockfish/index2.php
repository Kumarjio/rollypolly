<?php require_once('Connections/conn.php'); ?>
<?php

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.realpath('PEAR'));
require_once 'Games/Chess/Standard.php';
include_once('functions.php');
include_once('Chess.class.php');

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

$maxRows_rsView = 50;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "%";
if (isset($_GET['fen'])) {
  $colname_rsView = $_GET['fen'];
}
mysql_select_db($database_conn, $conn);
$query_rsView = sprintf("SELECT * FROM finalgames WHERE fen LIKE %s ORDER BY id ASC", GetSQLValueString("%" . $colname_rsView . "%", "text"));
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $conn) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$colname_rsSelf = "-1";
if (isset($_GET['fen'])) {
  $colname_rsSelf = $_GET['fen'];
}
mysql_select_db($database_conn, $conn);
$query_rsSelf = sprintf("SELECT * FROM finalgames WHERE fen = %s", GetSQLValueString($colname_rsSelf, "text"));
$rsSelf = mysql_query($query_rsSelf, $conn) or die(mysql_error());
$row_rsSelf = mysql_fetch_assoc($rsSelf);
$totalRows_rsSelf = mysql_num_rows($rsSelf);

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Chess</title>
<link rel="stylesheet" type="text/css" media="screen" href="css/chess.css"></link>
	<script type="text/javascript" src="js/ChessFen.js"></script>
	<style type="text/css" media="screen">
	body{
		font-family:"Trebuchet MS";
		font-size:0.9em;
		margin:0px;
	}

	#boardContainer{
		width:760px;

		padding:5px;
	}
	pre{
		font-family:Courier New, Courier New, Courier, monospace;
		color: #000;	
		font-size:0.75em;
		border:1px solid #317082;
		border-left:5px solid #317082;		
		padding:2px;
	}
	img{
		border:0px;
	}
	input{
		font-size:11px;
	}

	</style>
<script type="text/javascript">
	
	function displayFen(fenString)
	{
		try{
			chessObj.loadFen(fenString,'boardDiv');
			document.getElementById('fenInput').value = fenString;
			document.getElementById('fenString').innerHTML = fenString;
		}catch(e){
			alert('Invalid FEN');	
		}
	}
	
	
	</script>
</head>

<body>
<?php if ($totalRows_rsSelf > 0) { ?>
<div id="mainContainer">
	<div id="boardContainer">
		<p><b>My own FEN </b> ( <span id="fenString" style="font-size:0.9em;font-style:italic"></span> )</p>
		<div><table><tr><td>Type FEN in the field below:</td></tr>
		<tr><td><input type="text" id="fenInput" style="width:360px" onchange="displayFen(this.value)"></td></tr></table></div>
		<div id="boardDiv"></div>
	</div>
</div>

<script type="text/javascript">
var chessObj = new DHTMLGoodies.ChessFen( { pieceType:'alpha',squareSize:60 });
displayFen('<?php echo $row_rsSelf['fen']; ?>');




</script>
<?php } ?>
<h1>View Game</h1>
<?php if ($totalRows_rsView == 0) { 
echo 'no position found.';
}?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
	<table border="1" cellspacing="0" cellpadding="5">
		<?php do { ?>
			<tr>
				<td align="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row_rsView['id']; ?>&fen=<?php echo $row_rsView['fenpost']; ?>"><?php echo $row_rsView['move']; ?></a>&nbsp;</td>
				<td><?php echo $row_rsView['fen']; ?>&nbsp;</td>
				<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row_rsView['id']; ?>&fen=<?php echo $row_rsView['fen']; ?>">Previous</a>&nbsp;</td>
				<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row_rsView['id']; ?>&fen=<?php echo $row_rsView['fenpost']; ?>">Next</a>&nbsp;</td>
			</tr>
			<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
	</table>
	<p>&nbsp;
		
		Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
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
	<?php } // Show if recordset not empty ?>
</p>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsSelf);
?>
