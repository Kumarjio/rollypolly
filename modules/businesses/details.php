<?php
if (empty($_GET['place_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
//https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4&key=AddYourOwnKeyHere
$url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$_GET['place_id'].'&key='.PLACESAPIKEY;
$data = curlget($url);
$content = json_decode($data, 1);
$error = '';
if ($content['status'] === 'OK') {
    $result = $content['result'];
    $placeName = !empty($result['name']) ? $result['name'] : '';
    include(SITEDIR.'/modules/businesses/categories.php');
} else {
    $error = $content['status'];
    $placeName = 'No Place Found';
}
?>
<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH."/locations/country");
  exit;
}
include(SITEDIR.'/modules/navLeftSideVars.php');
$city_id = $_GET['city_id'];
$pageTitle = $placeName;
?>
        <div class="row">
          <div class="col-lg-12">
            <div class="bs-component">
              <?php if (!empty($error)) { ?>
                <?php echo $error; ?>
              <?php } else { ?>
              <ul class="nav nav-tabs">
                <li class="active"><a href="#contactInfo" data-toggle="tab">Contact</a></li>
                <?php if (!empty($result['reviews'])) { ?>
                <li><a href="#reviews" data-toggle="tab">Reviews</a></li>
                <?php } ?>
                <li><a href="#categories" data-toggle="tab">Categories</a></li>
                <?php if (!empty($result['opening_hours'])) { ?>
                <li><a href="#opening_hours" data-toggle="tab">Opening Hours</a></li>
                <?php } ?>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="contactInfo">
                  <div style="float:left;"><img src="<?php echo !empty($result['icon']) ? $result['icon'] : ''; ?>" /></div>
                      <div style="float:left;margin-left:20px;">
                        <p><b>Address: </b><?php echo !empty($result['adr_address']) ? $result['adr_address'] : ''; ?></p>
                        <p><b>Phone: </b><?php echo !empty($result['international_phone_number']) ? $result['international_phone_number'] : ''; ?></p>
                        <p><b>Latitude: </b><?php echo !empty($result['geometry']['location']['lat']) ? $result['geometry']['location']['lat'] : ''; ?></p>
                        <p><b>Longitude: </b><?php echo !empty($result['geometry']['location']['lng']) ? $result['geometry']['location']['lng'] : ''; ?></p>
                        <p><b>User Rating: </b><?php echo !empty($result['user_ratings_total']) ? $result['user_ratings_total'] : ''; ?></p>
                        <?php if (!empty($result['website'])) { ?>
                        <p><b>Website: </b><a href="<?php echo $result['website']; ?>" target="_blank" rel="nofollow"><?php echo $result['website']; ?></a></p>
                        <?php } ?>
                    </div>
                    <br style="clear:both" />
                </div>
                <?php if (!empty($result['reviews'])) { ?>
                <div class="tab-pane fade" id="reviews">
                  <?php foreach ($result['reviews'] as $reviews) { ?>
                    <p><b>Description: </b><?php echo $reviews['text']; ?>
                    <br />
                    <b>Author: </b><a href="<?php echo $reviews['author_url']; ?>" target="_blank" rel="nofollow"><?php echo $reviews['author_name']; ?></a>
                    <br />
                    <b>Rating: </b><?php echo number_format($reviews['rating'], 2); ?>
                    <br />
                    <b>Date: </b><?php echo date('r', $reviews['time']); ?>
                    </p>
                  <?php } ?>
                </div>
                <?php } ?>
                <div class="tab-pane fade" id="categories">
                  <?php foreach ($result['types'] as $k => $v) {
                      echo isset($categories[$v]) ? $categories[$v] : $v;
                      echo '<br>';
                  } ?>
                </div>
                <?php if (!empty($result['opening_hours'])) {
                    $days = array(1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                  ?>
                <div class="tab-pane fade" id="opening_hours">
                    <p><b>Open Now: </b> <?php echo !empty($result['opening_hours']['open_now']) ? 'Yes' : 'No'; ?></p>
                    <p><?php if (!empty($result['opening_hours']['periods'])) { 
                      foreach ($result['opening_hours']['periods'] as $periods) { ?>
                        <b><?php echo !empty($periods['open']['day']) ? $days[$periods['open']['day']] : ''; ?></b>
                        <?php echo !empty($periods['open']['time']) ? substr($periods['open']['time'], 0, 2).':'.substr($periods['open']['time'], 2) : ''; ?> - <?php echo !empty($periods['close']['time']) ? substr($periods['close']['time'], 0, 2).':'.substr($periods['close']['time'], 2) : ''; ?>
                        <br />
                    <?php } } ?></p>
                </div>
                <?php } ?>
              </div>
              <div style="text-align:right; width:100%;">
                <b>Are you owner of this place, </b><a href="#">Click Here</a> to manage this place.
              </div>
                <?php if (isset($_GET['debug'])) pr($result); ?>
              <?php } ?>
            </div>
          </div>
      </div>