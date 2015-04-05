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

$colname_rsView = "0";
if (isset($_GET['parent_id'])) {
  $colname_rsView = $_GET['parent_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsView = sprintf("SELECT * FROM books WHERE parent_id = %s", GetSQLValueString($colname_rsView, "int"));
$rsView = mysql_query($query_rsView, $connChess) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$colname_rsDetail = "-1";
if (isset($_GET['parent_id'])) {
  $colname_rsDetail = $_GET['parent_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsDetail = sprintf("SELECT * FROM books WHERE book_id = %s", GetSQLValueString($colname_rsDetail, "int"));
$rsDetail = mysql_query($query_rsDetail, $connChess) or die(mysql_error());
$row_rsDetail = mysql_fetch_assoc($rsDetail);
$totalRows_rsDetail = mysql_num_rows($rsDetail);

$maxRows_rsProblems = 10;
$pageNum_rsProblems = 0;
if (isset($_GET['pageNum_rsProblems'])) {
  $pageNum_rsProblems = $_GET['pageNum_rsProblems'];
}
$startRow_rsProblems = $pageNum_rsProblems * $maxRows_rsProblems;

$colname_rsProblems = "-1";
if (isset($_GET['parent_id'])) {
  $colname_rsProblems = $_GET['parent_id'];
}
mysql_select_db($database_connChess, $connChess);
$query_rsProblems = sprintf("SELECT * FROM problems WHERE book_id = %s ORDER BY sorting ASC", GetSQLValueString($colname_rsProblems, "int"));
$query_limit_rsProblems = sprintf("%s LIMIT %d, %d", $query_rsProblems, $startRow_rsProblems, $maxRows_rsProblems);
$rsProblems = mysql_query($query_limit_rsProblems, $connChess) or die(mysql_error());
$row_rsProblems = mysql_fetch_assoc($rsProblems);

if (isset($_GET['totalRows_rsProblems'])) {
  $totalRows_rsProblems = $_GET['totalRows_rsProblems'];
} else {
  $all_rsProblems = mysql_query($query_rsProblems);
  $totalRows_rsProblems = mysql_num_rows($all_rsProblems);
}
$totalPages_rsProblems = ceil($totalRows_rsProblems/$maxRows_rsProblems)-1;

$queryString_rsProblems = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsProblems") == false && 
        stristr($param, "totalRows_rsProblems") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsProblems = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsProblems = sprintf("&totalRows_rsProblems=%d%s", $totalRows_rsProblems, $queryString_rsProblems);
?>
<script language="javascript">
function showhidesolution(id)
{
  $('#'+id).toggle( "slow", function() {
    // Animation complete.
  });
}
<?php if (!empty($_GET['pfen'])) { ?>
$( document ).ready(function() {
  $('#mainboard_chessboard_fen').val('<?php echo $_GET['pfen']; ?>');
  $( "#mainboard_chessboard_readfen" ).trigger( "click" );
  //$( "#engine_engine_resume" ).trigger( "click" );
});
<?php }?>
</script>
<h2><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Books</a></h2>
<?php if ($totalRows_rsDetail > 0) { // Show if recordset not empty ?>
  <p><strong>Topic:</strong> <?php echo $row_rsDetail['book_name']; ?></p>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsProblems > 0) { // Show if recordset not empty ?>
  <h3>Positions</h3>
  <p> Records <?php echo ($startRow_rsProblems + 1) ?> to <?php echo min($startRow_rsProblems + $maxRows_rsProblems, $totalRows_rsProblems) ?> of <?php echo $totalRows_rsProblems ?> &nbsp;
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsProblems > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsProblems=%d%s", $currentPage, 0, $queryString_rsProblems); ?>">First</a>
        <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsProblems > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsProblems=%d%s", $currentPage, max(0, $pageNum_rsProblems - 1), $queryString_rsProblems); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsProblems < $totalPages_rsProblems) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsProblems=%d%s", $currentPage, min($totalPages_rsProblems, $pageNum_rsProblems + 1), $queryString_rsProblems); ?>">Next</a>
        <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsProblems < $totalPages_rsProblems) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsProblems=%d%s", $currentPage, $totalPages_rsProblems, $queryString_rsProblems); ?>">Last</a>
        <?php } // Show if not last page ?></td>
    </tr>
  </table>
  </p>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>Position Number</strong></td>
      <td><strong>Fen</strong></td>
      <td><strong>Side To Move</strong></td>
      <td><strong>Comments</strong></td>
      <td><strong>Solution</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsProblems['sorting']; ?></td>
        <td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?parent_id=<?php echo $colname_rsView; ?>&pfen=<?php echo urlencode($row_rsProblems['fen']); ?>"><?php echo $row_rsProblems['fen']; ?></a></td>
        <td><?php echo $row_rsProblems['sidetomove']; ?></td>
        <td><?php echo $row_rsProblems['comments']; ?></td>
        <td><a href="javascript:;" onClick="showhidesolution('soln_<?php echo $row_rsProblems['problem_id']; ?>');">Show</a> <span style="display:none" id="soln_<?php echo $row_rsProblems['problem_id']; ?>"><?php echo $row_rsProblems['solution']; ?></span></td>
      </tr>
      <?php } while ($row_rsProblems = mysql_fetch_assoc($rsProblems)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<p></p>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
      <tr>
          <td><strong>Name</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?parent_id=<?php echo $row_rsView['book_id']; ?>"><?php echo $row_rsView['book_name']; ?></a></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>

<?php
mysql_free_result($rsView);

mysql_free_result($rsDetail);

mysql_free_result($rsProblems);
?>
