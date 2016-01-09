// JavaScript Document

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




// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var autocompleteHome;

function initializeHome() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocompleteHome = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('currentLocation')),
      { types: ['(cities)'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocompleteHome, 'place_changed', function() {
    fillInAddressHome();
  });
}

// [START region_fillform]
function fillInAddressHome() {
  // Get the place details from the autocomplete object.
  var place = autocompleteHome.getPlace();
  $('#currentLocationLatitude').val(place.geometry.location.lat());
  $('#currentLocationLongitude').val(place.geometry.location.lng());
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocateHome() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocompleteHome.setBounds(circle.getBounds());
    });
  }
}
// [END region_geolocation]