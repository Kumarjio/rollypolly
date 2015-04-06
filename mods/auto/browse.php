<?php
try {
include(SITEDIR.'/mods/auto/browse_base.php');

include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
$pageTitle = 'Search/Browse '.$resultModule['menu_display_name'];

?>

<div class="page-header">
  <h1><?php 
  $myHead = '';
  if (!empty($my)) {
    $myHead = 'My ';
  }
  if (!empty($locationBox)) {
    $myHead .= $globalCity['city'].' ';
  }
  echo $myHead.' "'.(!empty($resultModule['page_title']) ? $resultModule['page_title'] : $resultModule['menu_display_name']).'"'; ?></h1>
  <?php if ($resultModule['new_page'] == 1) { ?><p><a href="<?php echo $currentURL; ?>/auto/new?module_id=<?php echo $colname_rsModule; ?>">Create New</a></p><?php } ?>
</div>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<!--Display List Starts Here -->

<?php
include(SITEDIR.'/mods/auto/display_list_template/'.$resultModule['display_list_template'].'.php');
?>

<p><strong>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?></strong>
</p>
<ul class="pager">
  <?php if ($pageNum_rsView > 0) { // Show if not first page ?>
  <li class="previous"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/auto/browse', max(0, $pageNum_rsView - 1), $get_rsView); ?>">&larr; Previous</a></li>
  <?php } // Show if not first page ?>
  <?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
  <li class="next"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/auto/browse', min($totalPages_rsView, $pageNum_rsView + 1), $get_rsView); ?>">Next &rarr;</a></li>
  <?php } // Show if not last page ?>
</ul>


<!--Display List Ends Here -->
<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<div>No Record Found.</div>
<?php } // Show if recordset empty ?>


<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>