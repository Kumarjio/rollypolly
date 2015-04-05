<script>
var lata;
var lona;
var useragent;
var latitude;
var longitude;
function displayLocation( position ) {
  lata = position.coords.latitude;
  lona = position.coords.longitude;
  getnearby(lata, lona);
}
function handleError( error ) {
  fallback();
  return false;
	var errorMessage = [ 
		'We are not quite sure what happened.',
		'Sorry. Permission to find your location has been denied.',
		'Sorry. Your position could not be determined.',
		'Sorry. Timed out.'
	];

	alert( errorMessage[ error.code ] );
}

function fallback()
{
  
  $.get( "<?php echo APIDIR; ?>/locations/iptocity.php", function( data ) {
    var obj = JSON.parse(data);
    var str = "";
    var first = false;
    for (var key in obj.nearby) {
      var c = obj.nearby[key];
      if (!first) {
        latitude = c.latitude;
        longitude = c.longitude;
        first = true;
        $.cookie('latitude', latitude);
        $.cookie('longitude', longitude);
      }
      str = str + "<a href='<?php echo HTTPPATH; ?>"+c.url+"'>"+c.name+"</a> ("+c.distance+" mi)<br />";
    }
    $('#homepagenearby').show();
    $('#homepagenearbycontent').html(str);
    initializeGoogleMap('mapCanvas')
  });
}

function getnearby(lat, lon)
{
  
  $.get( "<?php echo APIDIR; ?>/locations/nearby.php?lat="+lat+"&lon="+lon, function( data ) {
    var obj = JSON.parse(data);
    var str = "";
    var first = false;
    for (var key in obj) {
      var c = obj[key];
      if (!first) {
        latitude = c.latitude;
        longitude = c.longitude;
        first = true;
      }
      str = str + "<a href='<?php echo HTTPPATH; ?>"+c.url+"'>"+c.name+"</a> ("+c.distance+" mi)<br />";
    }
    $('#homepagenearby').show();
    $('#homepagenearbycontent').html(str);
    initializeGoogleMap('mapCanvas')
  });
}

function initialize() {
  if ($.cookie('latitude') && $.cookie('longitude')) {
    getnearby($.cookie('latitude'), $.cookie('longitude'));
  } else if ( navigator.geolocation ) {
    navigator.geolocation.getCurrentPosition( displayLocation, handleError );
  } else {
    fallback();
  }
  
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>