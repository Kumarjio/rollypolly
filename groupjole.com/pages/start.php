<?php
if (empty($_SESSION['user'])) {
    $url = $_SERVER['REQUEST_URI'];
    header("Location: /users/login?redirect_url=".urlencode($url));
    exit;   
}
?>
<script type="text/javascript">
// JavaScript Document

var geocoder;
var map;
var marker;

function showaddress(lat, lng)
{
  filllatlng(lat,lng)
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        //console.log(results);
        /*var len = (results[0].address_components).length;
        var zip =  results[0].address_components[(len-1)].short_name;
        var country =  results[0].address_components[(len-2)].short_name;
        var state =  results[0].address_components[(len-3)].short_name;
        var county =  results[0].address_components[(len-4)].short_name;
        var city =  results[0].address_components[(len-5)].short_name;
        $('#curCity').val(city);
        var addr = "lat: "+lat + "|lng:" + lng + "|addr:" + results[0].formatted_address + "|zip:" + zip + "|city:" + city + "|state:" + state + "|country:" + country + "|county:" + county;
        console.log(addr);*/
        $('#address').val(results[0].formatted_address);
      } else {
        //alert("Geocoder failed due to: " + status);
      }
    });
}
function codeAddress() {
  var address = document.getElementById('address').value;
  if (!address) {
    return false;
  }
  $('#address2').val(address);
  deleteMarkers();
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      /*map.setCenter(results[0].geometry.location);
      marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });*/
      showmap(results[0].geometry.location.lat(),results[0].geometry.location.lng());
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  marker.setMap(map);
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  marker = null;
}
function filllatlng(lat,lng)
{
  
  $('#latlng').html(lat + "," + lng);
  $('#lat').val(lat);
  $('#lng').val(lng);
}
function showmap(lat,lng)
{
  filllatlng(lat,lng);
  var myLatlng = new google.maps.LatLng(lat,lng);
  var mapOptions = {
    zoom: 17,
    center: myLatlng,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  map = new google.maps.Map(document.getElementById('mapCanvas'),
                                mapOptions);
  marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
	  draggable:true,
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    // 3 seconds after the center of the map has changed, pan back to the
    // marker.
	  
    showaddress(marker.getPosition().lat(), marker.getPosition().lng());
  });
}
function displayLocation( lat, lng ) {
  showaddress(lat, lng);
  showmap(lat,lng);
}
function displaySelfLocation( position ) {
  lat = position.coords.latitude;
  lng = position.coords.longitude;
  showaddress(lat, lng);
  showmap(lat,lng);
}
function handleError( error ) {
	var errorMessage = [ 
		'We are not quite sure what happened.',
		'Sorry. Permission to find your location has been denied.',
		'Sorry. Your position could not be determined.',
		'Sorry. Timed out.'
	];

	//console.log( errorMessage[ error.code ] );
  
  var latitude = '<?php echo !empty($latitude) ? $latitude : $globalCity['latitude']; ?>';
  var longitude = '<?php echo !empty($longitude) ? $longitude : $globalCity['longitude']; ?>';
  displayLocation(latitude, longitude);
}
function initialize() {
    var newlat = parseFloat('<?php echo !empty($latitude) ? $latitude : ''; ?>');
    var newlon = parseFloat('<?php echo !empty($longitude) ? $longitude : ''; ?>');
    if (newlat && newlon) {
      displayLocation(newlat, newlon);
    } else if ( navigator.geolocation ) {
      navigator.geolocation.getCurrentPosition( displaySelfLocation, handleError );
    } else {
      var latitude = '<?php echo !empty($latitude) ? $latitude : $globalCity['latitude']; ?>';
      var longitude = '<?php echo !empty($longitude) ? $longitude : $globalCity['longitude']; ?>';
      displayLocation(latitude, longitude);
    }
}
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
          /** @type {HTMLInputElement} */(document.getElementById('addressID')),
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
      console.log(place);
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      $('#s_lat').val(lat);
      $('#s_lng').val(lng);
      for (key in place.address_components) {
        var loc = place.address_components[key];
        if (loc.types[0] === "locality") {
          //$('#s_city').val(loc.long_name);
        } else if (loc.types[0] === "administrative_area_level_1") {
          //$('#s_state').val(loc.long_name);
          //$('#s_state_short').val(loc.short_name);
        } else if (loc.types[0] === "country") {
          //$('#s_country').val(loc.long_name);
          //$('#s_country_short').val(loc.short_name);
        } else {
          continue;
        }
      }
      return;
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
google.maps.event.addDomListener(window, 'load', init);

$(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        var str = e.target.className;
        var n = str.indexOf("addressBox");
        if (n === -1) {
          return true;
        } else {
          return false;
        }
        return true;
    }
});
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Start A New Group
        </h1>
    </div>
    <div class="col-lg-12">
        <form id="form1" name="form1" method="post">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLocation"><span class="glyphicon glyphicon-file">
                            </span>Location</a>
                        </h4>
                    </div>
                    <div id="collapseLocation" class="panel-collapse collapse in">
                        <div class="panel-body">
                            
                            <script language="javascript">
                            $( document ).ready(function() {
                                init();
                            });
                            </script>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="latlng"></div>
                                    </div><!--end form-group-->
                                    <div class="form-group">
                                      <div id="mapCanvas"></div>
                                      <input type="hidden" name="lat" id="lat" value="">
                                      <input type="hidden" name="lng" id="lng" value="">
                                      <input id="address" name="address" placeholder="Enter your address"
                                                                   onFocus="geolocate()" type="text" style="width:70%" value="<?php echo (!empty($_POST['address'])) ? $_POST['address'] : ''; ?>" class="addressBox"></input>
                                      <input type="button" value="Find Address" onclick="codeAddress()">
                                      <br /><br />
                                    </div><!--end form-group-->
                                    <div class="form-group">
                                      <input name="showAddress" type="checkbox" id="showAddress" value="1" <?php if ((isset($_POST['showAddress']) && $_POST['showAddress'] == 1) || !isset($_POST['showAddress'])) { ?>checked="checked"<?php } ?>>
                                      <label for="showAddress">Show on map </label>
                                    </div><!--end form-group-->
                                </div><!--end col-md-12-->
                            </div><!--end row-->
                        </div>
                    </div>
                </div>
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                          </span>Details</a>
                      </h4>
                  </div>
                  <div id="collapseDetail" class="panel-collapse collapse in">
                      <div class="panel-body">
                          <div class="form-group">
                            <strong>Tags:</strong> (Comma separated words like tag1, tag2, tag3)<br />
                            <input name="tags" type="text" class="inputText" id="tags" value="<?php echo !empty($_POST['tags']) ? $_POST['tags'] : ''; ?>" maxlength="40" />
                          </div>
                       </div>
                   </div>
              </div>
          </div>
        </form>
    </div>
</div>