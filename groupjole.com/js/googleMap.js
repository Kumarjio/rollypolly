// JavaScript Document

var geocoder;
var map;
var marker;
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