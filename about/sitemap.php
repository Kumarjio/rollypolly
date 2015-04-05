<?php
try {
$pageTitle = 'Sitemap';
$layoutStructure = 'mainBrowse';

$maxRows_rsView = 25;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;
$t = (3600*24);
$query_rsView = "(SELECT ci.*, ci.name as city, s.name as state, co.name as country FROM geo_city_owners as o LEFT JOIN geo_cities as ci ON o.cty_id = ci.cty_id LEFT JOIN geo_states as s ON ci.sta_id = s.sta_id LEFT JOIN geo_countries as co ON ci.con_id = co.con_id WHERE ci.con_id = 223)";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$result1 = $modelGeneral->fetchAll($query_limit_rsView, array(), $t);

$queryTotalRows = "(SELECT count(ci.cty_id) as cnt FROM geo_city_owners as o LEFT JOIN geo_cities as ci ON o.cty_id = ci.cty_id LEFT JOIN geo_states as s ON ci.sta_id = s.sta_id LEFT JOIN geo_countries as co ON ci.con_id = co.con_id WHERE ci.con_id = 223)";
if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $rowCountResult = $modelGeneral->fetchRow($queryTotalRows, array(), $t);
  $totalRows_rsView = $rowCountResult['cnt'];
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

//getString
$get_rsViewRaw = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsViewRaw);

//include(SITEDIR.'/libraries/addresses/nearby.php');

?>
<h2><?php echo $pageTitle; ?></h2>
<div class="container-fluid" id="main">
  <div class="row">
    <div class="col-xs-12" id="leftsidebar">
    
      <div class="panel panel-primary">
        <div class="panel-heading">List of Cities</div>
        <div class="panel-body">

<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>

<p><strong>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?></strong>
</p>
<ul class="pager">
  <?php if ($pageNum_rsView > 0) { // Show if not first page ?>
  <li class="previous"><a href="<?php printf("%s?pageNum_rsView=%d%s", HTTPPATH.'/about/sitemap', max(0, $pageNum_rsView - 1), $get_rsView); ?>">&larr; Previous</a></li>
  <?php } // Show if not first page ?>
  <?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
  <li class="next"><a href="<?php printf("%s?pageNum_rsView=%d%s", HTTPPATH.'/about/sitemap', min($totalPages_rsView, $pageNum_rsView + 1), $get_rsView); ?>">Next &rarr;</a></li>
  <?php } // Show if not last page ?>
</ul>

<!--Display List Starts Here -->

<div class="row">
  <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
    <ul class="event-list">

<?php
  foreach ($result1 as $key => $rowResult) {
    $image = 'https://maps.googleapis.com/maps/api/staticmap?center='.$rowResult['latitude'].','.$rowResult['longitude'].'&maptype=roadmap&markers=color:blue%7Clabel:S%7C'.$rowResult['latitude'].','.$rowResult['longitude'].'&zoom=13&size=200x200&key='.DEVELOPERKEY; 
        ?>
<li>
<img src="<?php echo $image; ?>" />
<div class="info">
    <?php $detailURL = makecityurl($rowResult['cty_id'], $rowResult['city']);
    $title = $rowResult['city'].', '.$rowResult['state'].', '.$rowResult['country']; ?>
    <h2 class="title"><a href="<?php echo $detailURL; ?>"><?php echo $title; ?></a></h2>
    <p class="desc" style="font-size:11px">
    </p>
        <script language="javascript">
            var latlng = new google.maps.LatLng(<?php echo $rowResult['latitude']; ?>, <?php echo $rowResult['longitude']; ?>);
            console.log(latlng);
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: '<?php echo addslashes($title); ?>'
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<h3><a href="<?php echo $detailURL; ?>"><?php echo addslashes($title); ?></a></h3>');
                infowindow.open(map, this);
            });
        </script>
</div>
<div class="social">
  <ul>
    <li class="facebook" style="width:33%;"><a href="javascript:;" onClick="fb('<?php echo $detailURL; ?>');"><span class="fa fa-facebook"></span></a></li>
    <li class="twitter" style="width:34%;"><a href="javascript:;" onClick="MM_openBrWindow('https://twitter.com/home?status=<?php echo urlencode($title.' at '.$detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-twitter"></span></a></li>
    <li class="google-plus" style="width:33%;"><a href="javascript:;" onClick="MM_openBrWindow('https://plus.google.com/share?url=<?php echo urlencode($title); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-google-plus"></span></a></li>
  </ul>
</div>
</li>
        <?php
  }
?>
    </ul>
  </div>
</div>



<!--Display List Ends Here -->
<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<div>No Record Found.</div>
<?php } // Show if recordset empty ?>

        </div>
      </div>
      
    </div>
  </div>
</div>


<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>