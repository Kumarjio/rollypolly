<?php
checkLogin();
$query2 = "select * from maps_addressbook where uid = ? ORDER by updated_dt DESC";
$params2 = array($_SESSION['user']['id']);
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['delete_id'])) {
    $queryDel = "delete from maps_addressbook where uid = ? AND addr_id = ?";
    $paramsDel = array($_SESSION['user']['id'], $_GET['delete_id']);
    $General->deleteDetails($queryDel, $paramsDel);
    $General->clearCache($query2, $params2);   
}
if (isset($_GET['address']) && isset($_GET['submit'])) {
    //pr($_GET);
    $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$_GET['curlat'].','.$_GET['curlng'].'&destination='.$_GET['lat'].','.$_GET['lng'].'&mode=driving&alternatives=true&key='.DEVELOPERKEY;
    if (!empty($_GET['avoid'])) {
        $str = implode('|', $_GET['avoid']);
        $url .= '&avoid='.$str;    
    }
    
    //maps_addressbook
    $query = "select * from maps_addressbook where address = ? AND uid = ?";
    $params = array($_GET['address'], $_SESSION['user']['id']);
    $returnCheck = $General->fetchRow($query, $params, 0);
    if (empty($returnCheck)) {
        $arr = array();
        $arr['uid'] = $_SESSION['user']['id'];
        $arr['address'] = $_GET['address'];
        $arr['lat'] = $_GET['lat'];
        $arr['lng'] = $_GET['lng'];
        $arr['url'] = $url;
        $arr['updated_dt'] = date('Y-m-d H:i:s');
        $General->addDetails('maps_addressbook', $arr); 
        $General->clearCache($query2, $params2);   
    } else {
        $arr = array();
        $arr['updated_dt'] = date('Y-m-d H:i:s');
        $where = sprintf('addr_id = %s', $General->qstr($returnCheck['addr_id']));
        $General->updateDetails('maps_addressbook', $arr, $where);
        $General->clearCache($query2, $params2);   
    }
    $results = curlget($url);
    $routes = json_decode($results, 1);
}
$addressBook = $General->fetchAll($query2, $params2, 600);
?>
<a href="<?php echo $url; ?>" target="_blank">External Link</a> | <a href="/">Home</a> | <a href="/directionsNoMap">Refresh</a>
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
    return false;
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
    $('#mapContent').show();
    $('#curlathtml').html(latitude);
    $('#curlnghtml').html(longitude);
    $('#speedhtml').html(position.coords.speed);
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
<div id="mapContent" style="display:none;">
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
      <br />
      <div>
        <div id="curlathtml"></div>
        <div id="curlnghtml"></div>
        <div id="speedhtml"></div>
      </div>
</form>
</div>
      <br />
<?php if (!empty($routes['routes'])) {
?>
<table width="100%" border="1" cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Route</strong></td>
        <td><strong>Distance</strong></td>
        <td><strong>Duration</strong></td>
        <td><strong>Address</strong></td>
    </tr>
<?php
    foreach ($routes['routes'] as $key => $route) {
?>

    <tr>
        <td><?php echo $route['summary']; ?></td>
        <td><?php echo $route['legs'][0]['distance']['text']; ?></td>
        <td><?php echo $route['legs'][0]['duration']['text']; ?></td>
        <td><?php echo $route['legs'][0]['start_address']; ?><br>
        <?php echo $route['legs'][0]['end_address']; ?>
        </td>
    </tr>
<?php
    }
?>
</table>

<?php
} else if (isset($routes['routes'])) {
  echo 'no route found';  
  echo '<br>';
  echo $url;
  echo '<br>';
  pr($_GET);
  pr($routes);
}

    //pr($routes);
?>
<?php if (!empty($routes['routes'])) {
    foreach ($routes['routes'] as $key => $route) {
        ?>
<h3><?php echo $route['summary']; ?></h3>
<ol class="bigFont">
        <?php
        foreach ($route['legs'][0]['steps'] as $k => $v) {
        ?>
<li><?php echo $v['html_instructions']; ?> (<?php echo $v['distance']['text']; ?>, <?php echo $v['duration']['text']; ?>)</li>
        <?php    
        }
        ?>
</ol>
        <?php
    }
}
?>
<hr />
<?php if (!empty($addressBook)) { 
?>
<table width="100%" border="1" cellspacing="1" cellpadding="5">
    <tr>
        <td><strong>Address</strong></td>
        <td><strong>Lat</strong></td>
        <td><strong>Lng</strong></td>
        <td><strong>Updated</strong></td>
        <td><strong>Delete</strong></td>
    </tr>
    <?php
foreach ($addressBook as $k => $v) {
?>
    <tr>
        <td><a href="/directionsNoMap?address=<?php echo urlencode($v['address']); ?>&lat=<?php echo $v['lat']; ?>&lng=<?php echo $v['lng']; ?>"><?php echo $v['address']; ?></a></td>
        <td><?php echo $v['lat']; ?></td>
        <td><?php echo $v['lng']; ?></td>
        <td><?php echo $v['updated_dt']; ?></td>
        <td><a href="/directionsNoMap?delete_id=<?php echo $v['addr_id']; ?>&action=delete" onClick="var a = confirm('do you really want to delete this address'); return a;">Delete</a></td>
    </tr>

<?php } ?>

</table>
<?php } ?>
<div id="mapCanvas" style="width:100%; height:100%; min-height:300px; min-width: 300px; display:none;"></div>