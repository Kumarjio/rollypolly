<?php

include(SITEDIR.'/modules/navLeftSideVars.php');
include(SITEDIR.'/modules/services/categoriesServices.php');

$pageTitle = 'View Services Postings in '.$pageTitle;
try {
    $error = '';
    $result = array();
    $url = APIHTTPPATH.'/help/services/view?';
    if (!empty($_GET['category'])) {
      if (!is_array($_GET['category'])) {
        $_GET['category'] = array($_GET['category']);
      }
      $url .= '&category='.implode(',', $_GET['category']);
    }
    
    if (!empty($_GET['city'])) {
      $url .= '&city_id='.implode(',', $_GET['city']);
    } else {
      $nearbyCities = getNearbyCities($globalCity['nearby']);
      $url .= '&city_id='.$nearbyCities[1];
    }
    $return = curlget($url);
    $data = json_decode($return, 1);
    if ($data['success'] == 0) {
      $error = $data['msg'];
    } else {
      $result = $data['data'];
      if ($result['total'] == 0) {
        $error = 'No Result Found.';
      }
    }
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>
<div class="row">
        <div class="col-lg-12">
            <div class="bs-component">
              <ul class="breadcrumb">
                <li><a href="<?php echo $currentURL; ?>">Home</a></li>
                <li><a href="<?php echo $currentURL; ?>/services/new">Post Service</a></li>
                <li class="active">View</li>
              </ul>
            </div>
        </div>
</div>
  <form name="form1" id="form1" method="get">
<div class="row">
        <div class="col-lg-6" style="padding-bottom: 12px">
            <div class="bs-component">
  <b>Category: </b><br />
  <select name="category[]" size="7" multiple="MULTIPLE" id="category">
      <option value="">all categories....</option>
  <?php foreach ($categoriesServices as $key => $value) {
                    ?>
    <option value="<?php echo $key; ?>" <?php if (!empty($_GET['category']) && in_array($key, $_GET['category'])) echo 'selected'; ?>><?php echo $value; ?></option>
  <?php } ?>
  </select>
            </div>
        </div>
        <div class="col-lg-6" style="padding-bottom: 12px">
            <div class="bs-component">
            <b>Cities: </b><br />
  <select name="city[]" size="7" multiple="MULTIPLE" id="city">
      <option value="">all cities....</option>
  <?php 
  $sortingNearby = array();
  foreach ($globalCity['nearby'] as $nearby) {
    $sortingNearby[$nearby['cty_id']] = $nearby['name'];
  }
  asort($sortingNearby);
  foreach ($sortingNearby as $cid => $name) {
                    ?>
    <option value="<?php echo $cid; ?>" <?php if (!empty($_GET['city']) && in_array($cid, $_GET['city'])) echo 'selected'; ?>><?php echo $name; ?></option>
  <?php } ?>
  </select>
            </div>
        </div>
</div>
<div class="row">
        <div class="col-lg-12" style="padding-bottom: 12px">
            <div class="bs-component">
  <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Search" style="width:100%" />
            </div>
        </div>
</div>
  </form>
<?php
if (!empty($error)) {
  ?>
        <div class="col-lg-12" style="padding-bottom: 12px">
            <div class="bs-component">
  <div class="error"><?php echo $error; ?></div>
            </div>
        </div>
  <?php
} else {
  ?>
<div class="row">
          <div class="col-lg-12">
            <div class="bs-component">
              <div class="list-group">
              <?php
  foreach ($result['result'] as $k => $v) {
      $link = $currentURL.'/services/detail?service_id='.$v['service_id'];
      echo viewDisplay($link, $v['title'], $v['name'], $v['base_updated_dt']);
    ?>
    <?php
  }
  ?>
              </div>
            </div>
          </div>
</div>
          <?php
}
?>