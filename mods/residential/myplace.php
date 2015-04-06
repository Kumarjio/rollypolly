<?php
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Browse/Search Residential';
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
$layoutStructure = 'listingMain';
?>
<?php
$searchTerm = '';
if (!empty($_GET['keyword'])) {
  $searchTerm = "AND (google_auth.name LIKE '%".$_GET['keyword']."%' OR z_residential.address LIKE '%".$_GET['keyword']."%' OR z_residential.address2 LIKE '%".$_GET['keyword']."%')";
}
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsViewLoc = 100;
$pageNum_rsViewLoc = 0;
if (isset($_GET['pageNum_rsViewLoc'])) {
  $pageNum_rsViewLoc = $_GET['pageNum_rsViewLoc'];
}
$startRow_rsViewLoc = $pageNum_rsViewLoc * $maxRows_rsViewLoc;

mysql_select_db($database_connMain, $connMain);
$query_rsViewLoc = "SELECT z_residential.*, google_auth.email, google_auth.gender, google_auth.name, google_auth.picture, google_auth.link $distanceFrom FROM z_residential LEFT JOIN google_auth ON z_residential.uid = google_auth.uid WHERE z_residential.uid = ".$modelGeneral->qstr($_SESSION['user']['id'])." $searchTerm ORDER BY residential_created_dt DESC";
$query_limit_rsViewLoc = sprintf("%s LIMIT %d, %d", $query_rsViewLoc, $startRow_rsViewLoc, $maxRows_rsViewLoc);
$rsViewLoc = mysql_query($query_limit_rsViewLoc, $connMain) or die(mysql_error());
$row_rsViewLoc = mysql_fetch_assoc($rsViewLoc);

if (isset($_GET['totalRows_rsViewLoc'])) {
  $totalRows_rsViewLoc = $_GET['totalRows_rsViewLoc'];
} else {
  $all_rsViewLoc = mysql_query($query_rsViewLoc);
  $totalRows_rsViewLoc = mysql_num_rows($all_rsViewLoc);
}
$totalPages_rsViewLoc = ceil($totalRows_rsViewLoc/$maxRows_rsViewLoc)-1;

//getString
$get_rsViewLoc = getString(array('pageNum_rsViewLoc', 'totalRows_rsViewLoc'));
$get_rsViewLoc = sprintf("&totalRows_rsViewLoc=%d%s", $totalRows_rsViewLoc, $get_rsViewLoc);
//getString Ends

?>
<div class="page-header">
  <h1>Search <small>Place</small></h1>
  <form name="frmSearch" id="frmSearch" action="" method="get">
    <label for="keyword">Keyword:</label>
    <input type="text" name="keyword" id="keyword" value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" style="width:90%"><br /><br />
    <input type="submit" name="submit" id="submit" value="Submit" style="width: 100%">
  </form>
</div>

<div class="page-header">
  <h1>My Residential Places</h1>
</div>
<?php if ($totalRows_rsViewLoc > 0) { // Show if recordset not empty ?>
  
  <div class="row">
    <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
      <ul class="event-list">
    <?php do {
          $rowResult = $row_rsViewLoc;
          include('inc_content.php');
          } while ($row_rsViewLoc = mysql_fetch_assoc($rsViewLoc)); ?>
          </ul>
          </div>
          
  </div>
  <p>Records <?php echo ($startRow_rsViewLoc + 1) ?> to <?php echo min($startRow_rsViewLoc + $maxRows_rsViewLoc, $totalRows_rsViewLoc) ?> of <?php echo $totalRows_rsViewLoc ?>
  </p>
  <ul class="pager">
    <?php if ($pageNum_rsViewLoc > 0) { // Show if not first page ?>
    <li class="previous"><a href="<?php printf("%s?pageNum_rsViewLoc=%d%s", $currentURL.'/history/browse', max(0, $pageNum_rsViewLoc - 1), $get_rsViewLoc); ?>">&larr; Previous</a></li>
    <?php } // Show if not first page ?>
    <?php if ($pageNum_rsViewLoc < $totalPages_rsViewLoc) { // Show if not last page ?>
    <li class="next"><a href="<?php printf("%s?pageNum_rsViewLoc=%d%s", $currentURL.'/history/browse', min($totalPages_rsViewLoc, $pageNum_rsViewLoc + 1), $get_rsViewLoc); ?>">Next &rarr;</a></li>
    <?php } // Show if not last page ?>
  </ul>
  
  <?php } // Show if recordset not empty ?>
  
<?php if ($totalRows_rsViewLoc == 0) { // Show if recordset empty ?>
<div>No Residential Data Found.</div>
<?php } // Show if recordset empty ?>

<?php
mysql_free_result($rsViewLoc);
?>
