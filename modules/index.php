<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/businesses/categories.php');
include(SITEDIR.'/modules/buysell/categories.php');
include(SITEDIR.'/modules/housing/categoriesHousing.php');
include(SITEDIR.'/modules/services/categoriesServices.php');
include(SITEDIR.'/modules/personals/categoriesPersonals.php');
include(SITEDIR.'/modules/jobs/categoriesJobs.php');
include(SITEDIR.'/modules/community/categoriesCommunity.php');
include(SITEDIR.'/libraries/rss.php');
?>
<script type="text/javascript" src="<?php echo HTTPPATH.'/scripts/map.js'; ?>"></script>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAALUsWUxJrv3zXUNCu0Kas1RQFv3AXA4OcITNh-zHKPaxsGpzj0xQrVCwfLY_kBbxK-4-gSU4j3c7huQ"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<div style="padding:5px;"><a href="<?php echo $currentURL; ?>?view=road">Road Map</a> | <a href="<?php echo $currentURL; ?>?view=street">Street View</a></div>
		<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
		<div id="mapCanvascitydetailstreetview" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } else { ?>
		<div id="mapCanvas" style="width:100%; height:100%; min-width:300px; min-height:300px"></div>
		<?php } ?>
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
<?php if (!empty($_GET['view']) && $_GET['view'] === 'street') { ?>
initializeGoogleStreetMap('mapCanvascitydetailstreetview', latitude, longitude);
<?php } else { ?>
initializeGoogleMap('mapCanvas');
<?php } ?>
</script>
        <div class="row">
          <div class="col-lg-12">
            <div class="bs-component">
                
              <?php
                    $nm = $globalCity['city'].', '.$globalCity['statename'].', '.$globalCity['countryname'];
                    $myRss = new RSSParser("http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=".urlencode($nm)."&cf=all&output=rss"); 
                    $itemNum=0;
                    $myRss_RSSmax=0;
                    if($myRss_RSSmax==0 || $myRss_RSSmax>count($myRss->titles))$myRss_RSSmax=count($myRss->titles);
                    if ($myRss_RSSmax > 0) {
                    ?>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">News <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/news/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                
                    <ul>
                    <?php
                    for($itemNum=0;$itemNum<$myRss_RSSmax;$itemNum++){
                        ?>
                        <li><a href="<?php echo $myRss->links[$itemNum]; ?>" target="_blank"><?php echo $myRss->titles[$itemNum]; ?></a></li>
                 <?php } ?>
                    </ul>
                </div>
              </div>
                    <?php } ?>
                
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><a href="<?php echo $currentURL; ?>/businesses/browse">Businesses</a> <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/businesses/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categories as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/businesses/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Sell <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/sell/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesBuySell as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/sell/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Buy <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/buy/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesBuySell as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/buy/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>

              

              
            </div>
          </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Coupons <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/coupons/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Community <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/community/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesCommunity as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/community/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
              
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Residential <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/personals/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Housing <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/housing/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesHousing as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/housing/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
                
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Personals <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/personals/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesPersonals as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/personals/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>

              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Jobs <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/jobs/new" title="Add New Job Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesJobs as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/jobs/view?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
              
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Help <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/help/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
                
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Services <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/services/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                  <?php foreach ($categoriesServices as $key => $value) {
                    ?>
                    <li><a href="<?php echo $currentURL; ?>/services/browse?category=<?php echo $key; ?>"><?php echo $value; ?></a></li>
                    <?php
                  }
                  ?>
                  </ul>
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Matrimony <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/matrimony/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Meetups <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/meetups/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Barter <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/barter/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Free Stuff <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/freestuff/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">History <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/history/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Complaint Box <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/complaintbox/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Suggestions <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/suggestions/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Weather</h3>
                </div>
                <div class="panel-body">
                  
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Places To Travel <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/placestotravel/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Photos <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/photos/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Videos <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/videos/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Likes/Dislikes <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/likes_dislikes/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Emergency Contacts <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/emergency/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  Panel content
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Movies <span class="pull-right white_a"><a href="<?php echo $currentURL; ?>/movies/new" title="Add New Posting">(+)</a></span></h3>
                </div>
                <div class="panel-body">
                  <ul>
                    <li>Current Movies</li>
                    <li>New Release Movies</li>
                  </ul>
                </div>
              </div>

            </div>
          </div>
        </div>