<?php

/*
if ($resultModule['feature_businesses'] == 1) {
  if (!empty($_GET['lat']) && !empty($_GET['lng'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lng'];
    include(SITEDIR.'/includes/business.php');
  }
}
*/
?>
<script language="javascript">
function findBusiness(lat, lng, keyword)
{
  if (lat == "") {
    console.log("missing lat");
    return false;
  }
  if (lng == "") {
    console.log("missing lng");
    return false;
  }
  var loc = new google.maps.LatLng(lat, lng);
  var request = {
    location: loc,
    rankby: google.maps.places.RankBy.DISTANCE,
    radius: '500'
  };
  if (keyword != "") {
    request.keyword = keyword;
  }
  console.log(request);
  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, businessCallback);
}

function businessCallback(results, status)
{
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    console.log('results');
    console.log(results);
    service = new google.maps.places.PlacesService(map);
    for (i = 0; i < results.length; i++) {
      var placeRequest = {
        placeId: results[i].place_id
      };
      service.getDetails(placeRequest, callbackBusinessDetails);
    }
  } else {
    alert('No result found.');
  }
}

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

function callbackBusinessDetails(place, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    console.log('place details');
    console.log(place);
    var returnVars = {
      'city_id': '<?php echo $globalCity['cty_id']; ?>',
      'uid': 'System0000',
      'module_id': '<?php echo $colname_rsModule; ?>',
      'title': place.name,
      'description': '',
      'category': [],
      'photos': [],
      'urls': [place.website],
      'address': place.formatted_address,
      'address2': place.formatted_address,
      'showAddress': 1,
      'phone_number': place.formatted_phone_number,
      'lat': place.geometry.location.lat(),
      'lng': place.geometry.location.lng(),
      'rc_status': 1,
      'tags': place.name,
      'MM_Insert': 'form1'
    }
    console.log(returnVars);
  }
}

$( document ).ready(function() {
  findBusiness('<?php echo !empty($latitude) ? $latitude : ''; ?>', '<?php echo !empty($longitude) ? $longitude : ''; ?>', '<?php echo !empty($_GET['keyword']) ? ($_GET['keyword']) : ''; ?>');
});
/*
var placeRequest = {
    placeId: res.place_id
  };

  service = new google.maps.places.PlacesService(map);
  service.getDetails(placeRequest, callbackDetails);
  
  function callbackDetails(place, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
      console.log(place);
      $('#phone').val(place.formatted_phone_number);
      $('#website').val(place.website);
      $('#googleplus').val(place.url);
      if (place.reviews) {
          var reviews = place.reviews;
          reviewsStr = '';
          if (reviews.length > 0) {
            $('#reviewsDisplay').show();
            for (i = 0; i < reviews.length; i++) {
              reviewsStr = reviewsStr + '<p>';
              reviewsStr = reviewsStr + '<input type="checkbox" name="reviews['+i+'][show]" value="1" checked />Show This Review To User<br />';
              if (reviews[i].text) {
                reviewsStr = reviewsStr + reviews[i].text+'<br />';
              }
              reviewsStr = reviewsStr + '<b>Rating: </b>' + reviews[i].rating;
              reviewsStr = reviewsStr + '<input type="hidden" name="reviews['+i+'][author_name]" value="'+reviews[i].author_name+'" />';
              reviewsStr = reviewsStr + '<input type="hidden" name="reviews['+i+'][rating]" value="'+reviews[i].rating+'" />';
              reviewsStr = reviewsStr + '<input type="hidden" name="reviews['+i+'][text]" value="'+reviews[i].text+'" />';
              reviewsStr = reviewsStr + '<input type="hidden" name="reviews['+i+'][time]" value="'+reviews[i].time+'" />';
              reviewsStr = reviewsStr + '<input type="hidden" name="reviews['+i+'][language]" value="'+reviews[i].language+'" />';
              reviewsStr = reviewsStr + '</p>';
            }
            $('#reviewsShow').html(reviewsStr);
          } else {
            $('#reviewsDisplay').hide();
          }
      }
    }
  }
  */
</script>