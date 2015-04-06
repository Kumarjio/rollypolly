<?php
$pageTitle = 'Add New Business';
check_login();
?>
<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
include(SITEDIR.'/modules/navLeftSideVars.php');
$city_id = $_GET['city_id'];
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<style>
    #map-canvas {
        height: 300px;
        width: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
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
        var len = (results[0].address_components).length;
        var zip =  results[0].address_components[(len-1)].short_name;
        var country =  results[0].address_components[(len-2)].short_name;
        var state =  results[0].address_components[(len-3)].short_name;
        var county =  results[0].address_components[(len-4)].short_name;
        var city =  results[0].address_components[(len-5)].short_name;
        //document.title = lat + "|" + lng + "|" + results[0].formatted_address + "|" + zip + "|" + city + "|" + state + "|" + country + "|" + county;
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
function addBusiness()
{
  alert($('#form1').serialize());
  console.log($('#form1').serialize());
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
  map = new google.maps.Map(document.getElementById('map-canvas'),
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
function displayLocation( position ) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
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

	alert( errorMessage[ error.code ] );
  window.location.href = '/';
  return false;
}
function initialize() {
    useragent = navigator.userAgent;
    navigator.geolocation.getCurrentPosition( displayLocation, handleError );
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>
<form action="" id="form1" name="form1" method="POST">

    <div id="latlng"></div>
    <input type="hidden" name="lat" id="lat" value="">
    <input type="hidden" name="lng" id="lng" value="">
    <input type="text" name="address" id="address" style="width:70%">
     <input type="button" value="Find Address" onclick="codeAddress()">
    <div id="map-canvas"></div>
    
    </div>
     <input type="button" value="Add New Business" style="" onclick="addBusiness()">
</form>