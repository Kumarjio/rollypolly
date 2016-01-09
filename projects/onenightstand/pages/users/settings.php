<?php 
checkLogin();
if (!empty($_POST)) {
    include_once(SITEDIR.'/Kundali.class.php');
    $arr = array();
    $arr['user_id'] = $_SESSION['user']['user_id'];
    $arr['nickname'] = $_POST['nickname'];
    $arr['bmonth'] = $_POST['bmonth'];
    $arr['bday'] = $_POST['bday'];
    $arr['byear'] = $_POST['byear'];
    $arr['btime'] = $_POST['btime'];
    $arr['bplace'] = $_POST['bplace'];
    $arr['blatitude'] = $_POST['blatitude'];
    $arr['blongitude'] = $_POST['blongitude'];
    if (!empty($arr['blatitude']) && !empty($arr['blongitude'])) {
        $arr['placeLocation'] = json_encode(getXtraDetails($arr['blatitude'], $arr['blongitude']));
    }
    $query = "select * from dcomerce_settings WHERE user_id = ?";
    $return = $General->fetchRow($query, array($_SESSION['user']['user_id']), 0);
    $General->clearCache($General->sql, array($_SESSION['user']['user_id']));
    if (empty($return)) {
        $userId = $General->addDetails('dcomerce_settings', $arr);
    } else {
        $where = sprintf("user_id = %s", $General->qstr($_SESSION['user']['user_id']));
        $General->updateDetails('dcomerce_settings', $arr, $where);
    }
}
$query = "select * from dcomerce_settings WHERE user_id = ?";
$return = $General->fetchRow($query, array($_SESSION['user']['user_id']), 1500);
?>
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;

function initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('bplace')),
      { types: ['(cities)'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();
  $('#blatitude').val(place.geometry.location.lat());
  $('#blongitude').val(place.geometry.location.lng());
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
// [END region_geolocation]


$( document ).ready(function() {
    initialize();
});
    </script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Settings</h1>
    </div>
    <div class="col-lg-12">
    <form role="form" method="post" name="form1">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static"><?php echo $_SESSION['user']['name']; ?></p>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <p class="form-control-static"><?php echo $_SESSION['user']['gender']; ?></p>
        </div>
        <div class="form-group">
            <label>Nick Name</label>
            <input class="form-control" type="text" name="nickname" id="nickname" placeholder="Enter Nick Name" value="<?php echo $return['nickname']; ?>">
        </div>
        <div class="form-group">
            <label>Birth Month</label>
            <input class="form-control" min="1" max="12" type="number" name="bmonth" id="bmonth" placeholder="Enter Birth Month" value="<?php echo $return['bmonth']; ?>">
        </div>
        <div class="form-group">
            <label>Birth Day</label>
            <input class="form-control" min="1" max="31" type="number" name="bday" id="bday" placeholder="Enter Birth Day" value="<?php echo $return['bday']; ?>">
        </div>
        <div class="form-group">
            <label>Birth Year</label>
            <input class="form-control" min="1900" type="number" name="byear" id="byear" placeholder="Enter Birth Year" value="<?php echo $return['byear']; ?>">
        </div>
        <div class="form-group">
            <label>Birth Time</label>
            <input class="form-control" type="text" name="btime" id="btime" placeholder="Enter Birth Time eg. 15:30 (hrs:mins)" value="<?php echo $return['btime']; ?>">
        </div>
        <div class="form-group">
            <label>Birth Place</label>
            <input class="form-control addressBox" type="text" name="bplace" id="bplace" placeholder="Enter Birth Place" value="<?php echo $return['bplace']; ?>">
            <input type="hidden" value="<?php echo $return['blatitude']; ?>" name="blatitude" id="blatitude" />
            <input type="hidden" value="<?php echo $return['blongitude']; ?>" name="blongitude" id="blongitude" />
        </div>
        <button type="submit" class="btn btn-default">Submit Button</button>
        <button type="reset" class="btn btn-default">Reset Button</button>
    </form>
<br />
<br />
<br />
<br />
<br />
<br />
    </div>
</div>