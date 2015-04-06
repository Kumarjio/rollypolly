<?php
try {
check_login();

include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}
$layoutStructure = 'autoTimeline';
$pageTitle = 'View Products';


$maxRows_rsView = 25;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$kw = '';
if (!empty($_GET['keyword'])) {
  $kw = " AND (b.product_name like '%".$_GET['keyword']."%' OR b.product_description like '%".$_GET['keyword']."%')";
}

$query_rsView = ("SELECT b.*, i.*, b.product_id as id FROM product_base as b LEFT JOIN product_inventory as i ON b.product_id = i.product_id AND i.cty_id = ".$globalCity['id']." WHERE b.product_status = 1 $kw ORDER BY b.product_name ASC");
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = $modelGeneral->fetchAll($query_limit_rsView, array(), 0);

//getting rowCount
$queryTotalRows = "SELECT count(*) as cnt FROM product_base as b WHERE b.product_status = 1 $kw";
if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $rowCountResult = $modelGeneral->fetchRow($queryTotalRows, array(), 0);
  $totalRows_rsView = $rowCountResult['cnt'];
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

//getString
$get_rsViewRaw = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsViewRaw);

//search box
ob_start();
include(SITEDIR.'/mods/auto/productSearchBox.php');
$search_box = ob_get_clean();
//search box ends

?>
<style type="text/css">
#mapCanvas {
  display:none;
}
</style>
<div class="page-header">
  <h1>Products</h1>
  <p><a href="<?php echo $currentURL; ?>/admin/city/store/productBase">Create New Product</a></p>
</div>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>

<div class="row">
  <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
    <ul class="event-list">
      <?php foreach ($rsView as $key => $rowResult) {
      if (!empty($rowResult['product_images'])) {
        $images = json_decode($rowResult['product_images'], 1);
        if (!empty($images)) {
          $image = $images[0];
        }
      }
        ?>
        <li>
            <img src="<?php echo $image; ?>" />
            <div class="info">
              <h2 class="title"><?php echo $rowResult['product_name']; ?></h2>
              <p class="desc" style="font-size:11px">
                <?php echo $rowResult['product_description']; ?>
                <?php if (!empty($rowResult['cost_price'])) { ?>
                  <br>
                  <b>Cost Price: </b>$ <?php echo $cost = $rowResult['cost_price']; ?>
                  ,
                  <b>Discount Percentage: </b><?php echo $rowResult['discount_percentage']; ?>% = $ <?php echo $discount = number_format($rowResult['cost_price'] * ($rowResult['discount_percentage'] / 100), 2); ?>
                  <br>
                  <b>Tax Percentage: </b><?php echo $rowResult['tax_percentage']; ?>% = $ <?php echo $tax = number_format($rowResult['cost_price'] * ($rowResult['tax_percentage'] / 100), 2); ?>
                  ,
                  <b>Commission Percentage: </b><?php echo $rowResult['commission_percentage']; ?>% = $ <?php echo $commission = number_format($rowResult['cost_price'] * ($rowResult['commission_percentage'] / 100), 2); ?>
                  <br>
                  <b>Net Price: </b>$ <?php echo $cost - $discount + $tax + $commission; ?>
                <?php } ?>
              </p>
              <ul>
                  <?php if (is_super_admin()) { ?>
                      <li style="width:50%;"><a href="<?php echo $currentURL; ?>/admin/city/store/productBase?product_id=<?php echo $rowResult['id']; ?>"><span class="fa fa-globe"></span> Edit</a></li>
                  <?php } ?>
                  <li style="width:50%;"><a href="<?php echo $currentURL; ?>/admin/city/store/productBaseInventory?product_id=<?php echo $rowResult['id']; ?>"><span class="fa fa-globe"></span> <?php if (!empty($rowResult['cost_price'])) { ?>Edit<?php } else { ?>Add To<?php } ?> Inventory</a></li>
              </ul>
            </div>
        </li>
      <?php } ?>
      
    </ul>
  </div>
</div>


<p><strong>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?></strong>
</p>
<ul class="pager">
  <?php if ($pageNum_rsView > 0) { // Show if not first page ?>
  <li class="previous"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/admin/city/store/productBaseView', max(0, $pageNum_rsView - 1), $get_rsView); ?>">&larr; Previous</a></li>
  <?php } // Show if not first page ?>
  <?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
  <li class="next"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/admin/city/store/productBaseView', min($totalPages_rsView, $pageNum_rsView + 1), $get_rsView); ?>">Next &rarr;</a></li>
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