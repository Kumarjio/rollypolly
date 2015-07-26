<?php
checkLogin();
?>
<div>
    <div id="error"></div>
    Latitude: <span id="lat"></span><br>
    Longitude: <span id="long"></span><br>
    Speed: <span id="speed"></span><br>
    Address: <span id="addressLoc"></span><br>
    Counter: <span id="counter"></span><br>
    Date/Time: <span id="datetime"></span><br><br>
    <div id="mapcontainer" style="width:100%; height:100%; min-height:300px; min-width: 300px;">
    
    </div>
</div>
<script language="javascript">
var marker;
var map;
var counter = 0;
function findaddr(lat, lng, position)
{
    console.log(position.coords);
    var d = new Date();
    var n = d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    var cur = d.getTime();
    $('#counter').html(cur + " (" + (cur - counter) + ") " + counter);
    $('#datetime').html(n);
    var latlng = new google.maps.LatLng(lat, lng);
    
    if (counter != 0 && (cur - counter) < 300000) {
        return false;    
    }
    counter = cur;
    //return false;
    
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'location': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
          console.log(results);
        $('#addressLoc').html(results[0].formatted_address);
        /*map.setZoom(11);
        marker = new google.maps.Marker({
          position: latlng,
          map: map
        });
        infowindow.setContent(results[1].formatted_address);
        infowindow.open(map, marker);*/
      } else {
        $('#error').html('No results found');
      }
    } else {
        $('#error').html('Geocoder failed due to: ' + status);
    }
  });   
}
function success(position) {
    var lat = position.coords.latitude;
    var long = position.coords.longitude;
    var speed = position.coords.speed;
    $('#lat').html(lat);
    $('#long').html(long);
    $('#speed').html(speed);
    findaddr(lat, long, position);
    var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    
    var options = {
        zoom: 16,
        center: coords,
        mapTypeControl: true,
        navigationControlOptions: {
            style: google.maps.NavigationControlStyle.SMALL
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("mapcontainer"), options);
    
    marker = new google.maps.Marker({
      position: coords,
      map: map,
      title:"You are here!"
    });
}

function updatepos(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    var speed = position.coords.speed;
    $('#lat').html(latitude);
    $('#long').html(longitude);
    $('#speed').html(speed);
    findaddr(latitude, longitude, position);
    marker.setPosition(
                new google.maps.LatLng(
                    latitude,
                    longitude
                )
            );
}

function errorHandler(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.");
      break;
    case error.POSITION_UNAVAILABLE:
      alert("Location information is unavailable.");
      break;
    case error.TIMEOUT:
      alert("The request to get user location timed out.");
      break;
    case error.UNKNOWN_ERROR:
      alert("An unknown error occurred.");
      break;
    }
  }
  
if (navigator.geolocation) {
    var opts = {
                    timeout: (5 * 1000),
                    maximumAge: (1000 * 60 * 15),
                    enableHighAccuracy: true
                };
  navigator.geolocation.getCurrentPosition(success, errorHandler, opts);
  var positionTimer = navigator.geolocation.watchPosition(updatepos);
} else {
  error('Geo Location is not supported');
}

</script>

