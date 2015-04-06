<?php
$pageTitle = 'Find Businesses';
include(SITEDIR.'/modules/businesses/categories.php');
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
var markerX;
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
//https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670522,151.1957362&radius=500&types=food&keyword=Cruises&key=AIzaSyBvXqWIcqyTVRgjXsVjDbdORcNaXHVjtOw
function findBusiness()
{
  deleteMarkers();
  showmap($('#lat').val(),$('#lng').val());
  var frm = $('#form1').serialize();
  var loc = new google.maps.LatLng($('#lat').val(),$('#lng').val());
  var request = {
    location: loc,
    radius: 1000,
    rankby: 'distance'
  };
  if ($('#category').val()) {
    request.types = $('#category').val();
  }
  if ($('#keyword').val()) {
    request.keyword = $('#keyword').val();
  }
  //console.log(request);
  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    console.log(results);
    var str = '<h2>Search Results</h2>';
    for (var i = 0; i < results.length; i++) {
      var lat1 = results[i].geometry.location.lat();
      var lon1 = results[i].geometry.location.lng();
      var lat2 = parseFloat($('#lat').val());
      var lon2 = parseFloat($('#lng').val());
      var d = distance(lat1, lon1, lat2, lon2, 'M');
      d = $.number(d, 2);
      //console.log(lat1 + ", " + lon1 + ", " + lat2 + ", " + lon2 + ", " + d);
      createMarker(results[i], d);
      str = str + '<b>' + results[i].name + '</b> (' + d + ' mi)<br>';
      str = str + 'Location: ' + results[i].vicinity + '<br>';
      str = str + '<a href="<?php echo $currentURL; ?>/businesses/details?place_id='+results[i].place_id+'" target="_blank">View Details</a><br>';//encodeURIComponent
    }
    $('#placeResults').html(str);
  } else {
    alert(status);
  }
}

function createMarker(place, distance) {
  var placeLoc = place.geometry.location;
  var pinIcon = new google.maps.MarkerImage(
      place.icon,
      null, /* size is determined at runtime */
      null, /* origin is 0,0 */
      null, /* anchor is bottom center of the scaled image */
      new google.maps.Size(32, 32)
  );
  markerX = new google.maps.Marker({
    map: map,
    position: place.geometry.location
  });
  markerX.setIcon(pinIcon);
  var content = '<b>'+place.name+'</b><br>';
  content = content + 'Location: '+place.vicinity+'<br>';
  content = content + 'Distance: '+distance+' mi<br>';
  content = content + '<a href="<?php echo $currentURL; ?>/businesses/details?place_id='+place.place_id+'" target="_blank">View Details</a><br>';
  google.maps.event.addListener(markerX, 'click', function() {
    infowindow.setContent(content);
    infowindow.open(map, this);
  });
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  marker.setMap(map);
  if (markerX) {
    markerX.setMap(map);
  }
}
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  marker = null;
  if (markerX) {
    markerX = null;
  }
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
function displayLocation( lat, lng ) {
  //var lat = position.coords.latitude;
  //var lng = position.coords.longitude;
  lat = parseFloat(lat);
  lng = parseFloat(lng);
  showaddress(lat, lng);
  showmap(lat,lng);
}
function initialize() {
    displayLocation('<?php echo $globalCity['latitude']; ?>', '<?php echo $globalCity['longitude']; ?>');
}
google.maps.event.addDomListener(window, 'load', initialize);

function distance(lat1, lon1, lat2, lon2, unit) {
  var radlat1 = Math.PI * lat1/180
  var radlat2 = Math.PI * lat2/180
  var radlon1 = Math.PI * lon1/180
  var radlon2 = Math.PI * lon2/180
  var theta = lon1-lon2
  var radtheta = Math.PI * theta/180
  var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
  dist = Math.acos(dist)
  dist = dist * 180/Math.PI
  dist = dist * 60 * 1.1515
  if (unit=="K") { dist = dist * 1.609344 }
  if (unit=="N") { dist = dist * 0.8684 }
  return dist
}
</script>
<form action="" id="form1" name="form1" method="POST">

    <div id="latlng"></div>
    <input type="hidden" name="lat" id="lat" value="">
    <input type="hidden" name="lng" id="lng" value="">
    <input type="text" name="address" id="address" style="width:70%">
     <input type="button" value="Find Address" onclick="codeAddress()">
    <div id="map-canvas"></div>
    
    </div>
    <div style="margin-bottom:10px;">
    <b>Category: <br>
    </b>
    <select name="category[]" size="5" multiple="MULTIPLE" id="category">
      <?php foreach ($categories as $category => $categoryName) { ?>
      <option value="<?php echo $category; ?>"><?php echo $categoryName; ?></option>
      <?php } ?>
    </select>
    </div>
  <div style="margin-bottom:10px;">
    <b>Keyword: </b><input type="text" name="keyword" id="keyword" />
    </div>
  <input type="button" value="Find Businesses" style="width:100%" onclick="findBusiness()">
</form>
<div id="placeResults">

</div>
<br><br><br>
<div style="text-align:right;">
<b>Could not find a business: <a href="#">Add it here</a>!!</b>
</div>
