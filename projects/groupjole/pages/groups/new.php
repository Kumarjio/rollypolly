<?php
checkLogin();
$return = array();
if (!empty($_POST)) {
    try {
        $return = $_POST;
        $arr = array();
        $arr['group_id'] = $_POST['group_id'];
        $arr['user_id'] = $_SESSION['user']['user_id'];
        $arr['lat'] = $_POST['lat'];
        $arr['lng'] = $_POST['lng'];
        $arr['city'] = $_POST['city'];
        $arr['state'] = $_POST['state'];
        $arr['country'] = $_POST['country'];
        $arr['group_name'] = $_POST['group_name'];
        $arr['location'] = $_POST['location'];
        $arr['group_description'] = $_POST['group_description'];
        $arr['admin_approved'] = 0;
        $arr['visibility'] = 1;
        $arr['group_created_dt'] = date('Y-m-d H:i:s');
        $arr['group_status'] = 1;
        $General->addDetails('groups', $arr);
        
        header("Location: step2?id=".$arr['group_id']);
        exit;
    } catch (Exception $e) {
        echo $e->getMessage();    
    }
}
?>
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocompleteNewGroup;

function initializeNewGroup() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocompleteNewGroup = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('location')),
      { types: ['(cities)'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocompleteNewGroup, 'place_changed', function() {
    fillInAddressNewGroup();
  });
}

// [START region_fillform]
function fillInAddressNewGroup() {
  // Get the place details from the autocomplete object.
  var place = autocompleteNewGroup.getPlace();
  //console.log(place);
  $('#lat').val(place.geometry.location.lat());
  $('#lng').val(place.geometry.location.lng());
  for (key in place.address_components) {
    var loc = place.address_components[key];
    if (loc.types[0] === "locality") {
      $('#city').val(loc.long_name);
    } else if (loc.types[0] === "administrative_area_level_1") {
      $('#state').val(loc.short_name);
    } else if (loc.types[0] === "country") {
      $('#country').val(loc.long_name);
    } else {
      continue;
    }
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocateNewGroup() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocompleteNewGroup.setBounds(circle.getBounds());
    });
  }
}
// [END region_geolocation]


$( document ).ready(function() {
    initializeNewGroup();
});
    </script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php echo TITLENAME; ?> Group :: Create New</h1>
    </div>
    <div class="col-lg-12">
        <form role="form" method="post" name="form1">
            <div class="form-group">
                <label><?php echo TITLENAME; ?> Location</label>
                <input class="form-control addressBox" type="text" name="location" id="location" placeholder="Enter <?php echo TITLENAME; ?> Location" value="<?php echo !empty($return['location']) ? $return['location'] : ''; ?>" required>
                <input type="hidden" value="<?php echo !empty($return['lat']) ? $return['lat'] : ''; ?>" name="lat" id="lat" />
                <input type="hidden" value="<?php echo !empty($return['lng']) ? $return['lng'] : ''; ?>" name="lng" id="lng" />
                <input type="hidden" value="<?php echo !empty($return['city']) ? $return['city'] : ''; ?>" name="city" id="city" />
                <input type="hidden" value="<?php echo !empty($return['state']) ? $return['state'] : ''; ?>" name="state" id="state" />
                <input type="hidden" value="<?php echo !empty($return['country']) ? $return['country'] : '' ?>" name="country" id="country" />
            </div>
            <div class="form-group">
                <label><?php echo TITLENAME; ?> Name</label>
                <input class="form-control" type="text" name="group_name" id="group_name" placeholder="Enter <?php echo TITLENAME; ?> Name" value="<?php echo !empty($return['group_name']) ? $return['group_name'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Who should join, and why?</label>
                <textarea class="form-control" name="group_description" id="group_description" placeholder="Write a brief description to let members know what to expect." rows="5" required><?php echo !empty($return['group_description']) ? $return['group_description'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <p>
                    <label>Terms & Conditions</label>
                    <br>
                    <input type="checkbox" name="terms" id="terms" value="1" <?php echo !empty($return['terms']) ? 'checked' : ''; ?> required> 
                    <strong>What it means to be a Meetup</strong></p>
                <ul>
                    <li> Real, in-person conversations                        </li>
                    <li>Open and honest intentions </li>
                    <li>Always safe and respectful </li>
                    <li>Put your members first </li>
                </ul>
                <p>We review all Meetups based on our <a href="<?php echo HTTPPATH; ?>/terms" target="_blank">Community Guidelines</a>. </p>
            </div>
            
            <input type="hidden" value="<?php echo !empty($return['group_id']) ? $return['group_id'] : guid(); ?>" name="group_id" id="group_id" />
            <button type="submit" class="btn btn-default">Submit Button</button>
            <button type="reset" class="btn btn-default">Reset Button</button>
        </form>
    </div>
</div>