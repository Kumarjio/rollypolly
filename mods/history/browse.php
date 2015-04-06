<?php
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Browse/Search History';
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
$nearby = getNearbyCities($globalCity['nearby']);
$nearbyCities = $nearby[1];
$layoutStructure = 'timeline';
if (empty($_GET['radius'])) {
  $_GET['radius'] = 30;
}
if (empty($_GET['lat']) && empty($_GET['lng'])) {
  $_GET['lat'] = $globalCity['latitude'];
  $_GET['lng'] = $globalCity['longitude'];
}
?>
<?php
$searchTerm = '';
if (!empty($_GET['keyword'])) {
  $searchTerm = "AND (z_history.history_title LIKE '%".$_GET['keyword']."%' OR z_history.history_description LIKE '%".$_GET['keyword']."%')";
}
$currentPage = $_SERVER["PHP_SELF"];

if (!empty($_GET['lat']) && !empty($_GET['lng']) && !empty($_GET['radius'])) {
  $distanceFrom = ", (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * SIN(RADIANS(z_history.history_lat)) + COS(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * COS(RADIANS(z_history.history_lat)) * COS(RADIANS(".GetSQLValueString($_GET['lng'], 'double')." -(z_history.history_lng)))))*60*1.1515,2)) as distance";
  $distanceWhere = " AND (ROUND(
	DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * SIN(RADIANS(z_history.history_lat)) + COS(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * COS(RADIANS(z_history.history_lat)) * COS(RADIANS(".GetSQLValueString($_GET['lng'], 'double')." -(z_history.history_lng)))))*60*1.1515,2)) <= ".GetSQLValueString($_GET['radius'], 'int');

$maxRows_rsViewLoc = 100;
$pageNum_rsViewLoc = 0;
if (isset($_GET['pageNum_rsViewLoc'])) {
  $pageNum_rsViewLoc = $_GET['pageNum_rsViewLoc'];
}
$startRow_rsViewLoc = $pageNum_rsViewLoc * $maxRows_rsViewLoc;

mysql_select_db($database_connMain, $connMain);
$query_rsViewLoc = "SELECT z_history.*, google_auth.email, google_auth.gender, google_auth.name, google_auth.picture, google_auth.link $distanceFrom FROM z_history LEFT JOIN google_auth ON z_history.uid = google_auth.uid WHERE z_history.is_private = 0 AND z_history.history_status = 1 AND z_history.history_approved = 1 $searchTerm $distanceWhere ORDER BY z_history.history_date DESC";
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
} else {

$maxRows_rsView = 100;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT z_history.*, google_auth.email, google_auth.gender, google_auth.name, google_auth.picture, google_auth.link FROM z_history LEFT JOIN google_auth ON z_history.uid = google_auth.uid WHERE z_history.is_private = 0 AND z_history.history_status = 1 AND z_history.history_approved = 1 AND z_history.city_id IN ($nearbyCities) $searchTerm ORDER BY z_history.history_date DESC";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

//getString
$get_rsView = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsView);
//getString Ends


$maxRows_rsViewWorld = 100;
$pageNum_rsViewWorld = 0;
if (isset($_GET['pageNum_rsViewWorld'])) {
  $pageNum_rsViewWorld = $_GET['pageNum_rsViewWorld'];
}
$startRow_rsViewWorld = $pageNum_rsViewWorld * $maxRows_rsViewWorld;

mysql_select_db($database_connMain, $connMain);
$query_rsViewWorld = "SELECT z_history.*, google_auth.email, google_auth.gender, google_auth.name, google_auth.picture, google_auth.link FROM z_history LEFT JOIN google_auth ON z_history.uid = google_auth.uid WHERE z_history.is_private = 0 AND z_history.history_status = 1 AND z_history.history_approved = 1 AND z_history.city_id NOT IN ($nearbyCities) $searchTerm ORDER BY z_history.history_date DESC";
$query_limit_rsViewWorld = sprintf("%s LIMIT %d, %d", $query_rsViewWorld, $startRow_rsViewWorld, $maxRows_rsViewWorld);
$rsViewWorld = mysql_query($query_limit_rsViewWorld, $connMain) or die(mysql_error());
$row_rsViewWorld = mysql_fetch_assoc($rsViewWorld);

if (isset($_GET['totalRows_rsViewWorld'])) {
  $totalRows_rsViewWorld = $_GET['totalRows_rsViewWorld'];
} else {
  $all_rsViewWorld = mysql_query($query_rsViewWorld);
  $totalRows_rsViewWorld = mysql_num_rows($all_rsViewWorld);
}
$totalPages_rsViewWorld = ceil($totalRows_rsViewWorld/$maxRows_rsViewWorld)-1;

//getString
$get_rsViewWorld = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsViewWorld = sprintf("&totalRows_rsView=%d%s", $totalRows_rsViewWorld, $get_rsViewWorld);
//getString Ends

}
?>
<script language="javascript">

//autocomplete

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};
function init() {
      // Create the autocomplete object, restricting the search
      // to geographical location types.
      autocomplete = new google.maps.places.Autocomplete(
          /** @type {HTMLInputElement} */(document.getElementById('address')),
          { types: ['geocode'] });
      // When the user selects an address from the dropdown,
      // populate the address fields in the form.
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        fillInAddress();
      });
    }

function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace();
      lat = place.geometry.location.lat();
      lng = place.geometry.location.lng();
      $('#lat').val(lat);
      $('#lng').val(lng);
    }

function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = new google.maps.LatLng(
              position.coords.latitude, position.coords.longitude);
          autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
              geolocation));
        });
      }
    }
    
$( document ).ready(function() {
    init();
});
</script>
<div class="page-header">
  <h1>Search <small>History</small></h1>
  <form name="frmSearch" id="frmSearch" action="" method="get">
    <label for="keyword">Keyword:</label>
    <input type="text" name="keyword" id="keyword" value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" style="width:90%"><br /><br />
    <label for="address">Near:</label>
    <input id="address" name="address" placeholder="Enter your address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>"
                                       onFocus="geolocate()" type="text" style="width:70%">
    <label for="radius">Radius (mi):</label>
    <input type="text" name="radius" id="radius" value="<?php echo isset($_GET['radius']) ? $_GET['radius'] : '30'; ?>"  style="width:10%" /><br /><br />
    <input type="submit" name="submit" id="submit" value="Submit" style="width: 100%">
    <input type="hidden" name="lat" id="lat" value="<?php echo !empty($_GET['lat']) ? $_GET['lat'] : ''; ?>" />
    <input type="hidden" name="lng" id="lng" value="<?php echo !empty($_GET['lng']) ? $_GET['lng'] : ''; ?>" />
  </form>
</div>
<?php
if (!empty($_GET['lat']) && !empty($_GET['lng']) && !empty($_GET['radius'])) {
?>
<div class="page-header">
  <h1>History For <small><?php echo (!empty($_GET['address'])) ? $_GET['address'] : $globalCity['city']; ?></small> And Nearby <small><?php echo $_GET['radius']; ?> mi</small></h1>
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
<div>No History Data Found.</div>
<?php } // Show if recordset empty ?>


<?php } else { ?>
<div class="page-header">
  <h1>History For City <small><?php echo $globalCity['city']; ?> And Nearby</small></h1>
</div>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  
  <div class="row">
    <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
      <ul class="event-list">
    <?php do {
          $rowResult = $row_rsView;
          include('inc_content.php');
          } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
          </ul>
          </div>
          
  </div>
  <p>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
  </p>
  <ul class="pager">
    <?php if ($pageNum_rsView > 0) { // Show if not first page ?>
    <li class="previous"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/history/browse', max(0, $pageNum_rsView - 1), $get_rsView); ?>">&larr; Previous</a></li>
    <?php } // Show if not first page ?>
    <?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
    <li class="next"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/history/browse', min($totalPages_rsView, $pageNum_rsView + 1), $get_rsView); ?>">Next &rarr;</a></li>
    <?php } // Show if not last page ?>
  </ul>
  
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<div>No History Data Found.</div>
<?php } // Show if recordset empty ?>

<div class="page-header">
  <h1>History For <small>All Other Places</small></h1>
</div>
<?php if ($totalRows_rsViewWorld > 0) { // Show if recordset not empty ?>
  <div class="row">
    <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
      <ul class="event-list">
    <?php do {
          $rowResult = $row_rsViewWorld;
          include('inc_content.php');
    } while ($row_rsViewWorld = mysql_fetch_assoc($rsViewWorld)); ?>
    </ul>
    </div>
  </div>
  <p>Records <?php echo ($startRow_rsViewWorld + 1) ?> to <?php echo min($startRow_rsViewWorld + $maxRows_rsViewWorld, $totalRows_rsViewWorld) ?> of <?php echo $totalRows_rsViewWorld ?>
  </p>
  <ul class="pager">
    <?php if ($pageNum_rsViewWorld > 0) { // Show if not first page ?>
    <li class="previous"><a href="<?php printf("%s?pageNum_rsViewWorld=%d%s", $currentURL.'/history/browse', max(0, $pageNum_rsViewWorld - 1), $get_rsViewWorld); ?>">&larr; Previous</a></li>
    <?php } // Show if not first page ?>
    <?php if ($pageNum_rsViewWorld < $totalPages_rsViewWorld) { // Show if not last page ?>
    <li class="next"><a href="<?php printf("%s?pageNum_rsViewWorld=%d%s", $currentURL.'/history/browse', min($totalPages_rsViewWorld, $pageNum_rsViewWorld + 1), $get_rsViewWorld); ?>">Next &rarr;</a></li>
    <?php } // Show if not last page ?>
  </ul>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsViewWorld == 0) { // Show if recordset empty ?>
<div>No History Data Found.</div>
<?php } // Show if recordset empty ?>
<?php } ?>
<?php
//mysql_free_result($rsViewLoc);

//mysql_free_result($rsView);

//mysql_free_result($rsViewWorld);
?>
