<?php
checkLogin();
if (isset($_GET['address'])) {
    pr($_GET);
    $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$_GET['curlat'].','.$_GET['curlng'].'&destination='.$_GET['lat'].','.$_GET['lng'].'&mode=driving&alternatives=true&key='.DEVELOPERKEY;
    if (!empty($_GET['avoid'])) {
        $str = implode('|', $_GET['avoid']);
        $url .= '&avoid='.$str;    
    }
    echo $url;  
}
?>

<script language="javascript">

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
        /*str = e.currentTarget.className;
        n = str.indexOf("formMEnter");
        if (n === -1) {
            e.preventDefault();
            return false;
        }*/
        return true;
    }
});
var geocoder2;
var map2;
var marker2;
function showaddress(lat, lng)
{
  filllatlng(lat,lng)
  geocoder2 = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
    geocoder2.geocode({'latLng': latlng}, function(results, status) {
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
        $('#sAddr').html(results[0].formatted_address);
      } else {
        //alert("Geocoder failed due to: " + status);
      }
    });
}
function clearAddr()
{
    $('#address').val('');
}
function codeAddress() {
  var address = document.getElementById('address').value;
  if (!address) {
    return false;
  }
  deleteMarkers();
  geocoder2.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      /*map2.setCenter(results[0].geometry.location);
      marker2 = new google.maps.Marker({
          map: map2,
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
  marker2.setMap(map);
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  marker2 = null;
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
  map2 = new google.maps.Map(document.getElementById('mapCanvas'),
                                mapOptions);
  marker2 = new google.maps.Marker({
      position: myLatlng,
      map: map2,
	  draggable:true,
  });
  
  google.maps.event.addListener(marker2, 'dragend', function() {
    // 3 seconds after the center of the map has changed, pan back to the
    // marker.
	  
    showaddress(marker2.getPosition().lat(), marker2.getPosition().lng());
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
  
  var latitude = '37.8047';
  var longitude = '-121.22';
  displayLocation(latitude, longitude);
}

function updateLocation(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    $('#curlat').val(latitude);
    $('#curlng').val(longitude);
}


function initialize(la, ln) {
    if (la && ln) {
        displayLocation(la, ln);
    } else if ( navigator.geolocation ) {
      navigator.geolocation.getCurrentPosition( displaySelfLocation, handleError );
      
    } else {
      var latitude = '37.8047';
      var longitude = '-121.22';
      displayLocation(latitude, longitude);
    }
    if ( navigator.geolocation ) {
        var positionTimer = navigator.geolocation.watchPosition(updateLocation);
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
      showmap(lat,lng);
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
//google.maps.event.addDomListener(window, 'load', initialize);

$( document ).ready(function() {
    initialize('<?php echo (!empty($_GET['lat'])) ? $_GET['lat'] : ''; ?>', '<?php echo (!empty($_GET['lng'])) ? $_GET['lng'] : ''; ?>');
    init();
});
</script>

<form name="form1" id="form1" method="get" action="">
      <input type="hidden" name="lat" id="lat" value="<?php echo (!empty($_GET['lat'])) ? $_GET['lat'] : ''; ?>">
      <input type="hidden" name="lng" id="lng" value="<?php echo (!empty($_GET['lng'])) ? $_GET['lng'] : ''; ?>">
      <input type="hidden" name="curlat" id="curlat" value="" />
      <input type="hidden" name="curlng" id="curlng" value="" />
      <input id="address" name="address" placeholder="Enter your address"
                                   onFocus="geolocate()" type="text" style="width:100%; height:30px;" value="<?php echo (!empty($_GET['address'])) ? $_GET['address'] : ''; ?>" class="addressBox"></input>
                                   <br />
                                   <input type="checkbox" name="avoid[]" id="avoid1" value="highways" <?php echo (!empty($_GET['avoid']) && in_array('highways', $_GET['avoid'])) ? 'checked' : ''; ?>> Avoid Highways
                                   <input type="checkbox" name="avoid[]" id="avoid2" value="tolls" <?php echo (!empty($_GET['avoid']) && in_array('tolls', $_GET['avoid'])) ? 'checked' : ''; ?>> Avoid Tolls
                                   <input type="checkbox" name="avoid[]" id="avoid3" value="ferries" <?php echo (!empty($_GET['avoid']) && in_array('ferries', $_GET['avoid'])) ? 'checked' : ''; ?>> Avoid Ferries
                                   <br />
      <input type="button" value="Find Address" onclick="codeAddress()">
      <input type="button" value="Clear Address" onclick="clearAddr()">
      <input type="submit" value="Directions" name="submit" />
      <br />
      <div id="sAddr"></div>
</form>
      <br />
    <div id="mapCanvas" style="width:100%; height:100%; min-height:300px; min-width: 300px;"></div>