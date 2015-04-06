<?php
try {
include(SITEDIR.'/includes/navLeftSideVars.php');
$layoutStructure = 'main';
if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}

if (empty($_GET['id'])) {
  throw new Exception('Incorrect ID');
}
if (!empty($_GET['my'])) {
  $my = true;
}

$uid = !empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';

$t = (3600*24);

$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}

if ($resultModule['module_status'] == 0) {
  throw new Exception('module is inactive');
}


if ($resultModule['detail_page'] == 0 && !$my) {
  throw new Exception('detail page is not accessible');
}

if ($resultModule['my_page'] == 0 && $my) {
  throw new Exception('my detail page is not accessible');
}

/*if ($resultModule['browse_page'] == 0 && !$my) {
  throw new Exception('page is not accessible');
}*/

/*
if ($resultModule['feature_related_module'] == 1) {
  $query = "SELECT * FROM z_modules WHERE module_id = ?";
  $resultModuleRelated = $modelGeneral->fetchRow($query, array($resultModule['feature_related_module_id']), $t);
  if (empty($resultModuleRelated)) {
    throw new Exception('Could not find the related module');
  }
}*/


$tablename = 'auto_'.$resultModule['module_name'];

$query = "SELECT * FROM z_modules_fields WHERE module_id = ? ORDER BY sorting ASC";
$resultModuleFields = $modelGeneral->fetchAll($query, array($colname_rsModule), $t);
if (empty($resultModuleFields)) {
  throw new Exception('Could not find the module fields');
}
//pr($resultModuleFields);
$resultModuleFields2 = array();
foreach ($resultModuleFields as $k => $v) {
  $resultModuleFields2[$v['field_name']] = $v;
}
//pr($resultModuleFields2);
$locationBar = false;
$distanceFrom = '';
$distanceWhere = '';
$searchCriteria = '';
$searchCriteria .= ' AND a.id = ? AND a.module_id = ? AND a.rc_deleted = 0';
foreach ($resultModuleFields as $k => $v) {
  if ($v['field_type'] == 'addressbox') {
    $locationBar = true;
    if (empty($_GET['lat']) && empty($_GET['lng'])) {
      $_GET['lat'] = $globalCity['latitude'];
      $_GET['lng'] = $globalCity['longitude'];
    }
    $distanceFrom = ", (ROUND(DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * SIN(RADIANS(a.clatitude)) + COS(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * COS(RADIANS(a.clatitude)) * COS(RADIANS(".GetSQLValueString($_GET['lng'], 'double')." -(a.clongitude)))))*60*1.1515,2)) as distance";
  }
}
$query_rowResult = "SELECT a.*, u.name as fullname, u.*, c.name as city, c.* $distanceFrom FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid LEFT JOIN geo_cities as c ON a.city_id = c.cty_id WHERE 1 $searchCriteria $distanceWhere";
$rowResult = $modelGeneral->fetchRow($query_rowResult, array($_GET['id'], $colname_rsModule), $t);
if (empty($rowResult)) {
  throw new Exception('could not find the record');
}
//authentication to access page
if ($my == 1) {
  if (empty($_SESSION['user']['id'])) {
    throw new Exception('user not logged in');
  }
  if ($_SESSION['user']['id'] != $rowResult['uid']) {
    throw new Exception('user not authorized to view this page');
  }
}
if ($_SESSION['user']['id'] != $rowResult['uid']) {
  if ($rowResult['rc_approved'] == 0) {
    throw new Exception('invalid record');
  }
  if ($rowResult['rc_status'] == 0) {
    throw new Exception('invalid record status');
  }
}
//authentication to access page

//image
$images = array();
$videos = array();
$urls = array();
$image = $rowResult['picture'];

//image

//decryption
foreach ($resultModuleFields as $k => $v) {
  if (!empty($rowResult[$v['field_name']]) && $v['encrypted'] == 1) {
    $rowResult[$v['field_name']] = decryptText($rowResult[$v['field_name']]);
  }
  if ($v['field_type'] == 'images') {
    if (!empty($rowResult[$v['field_name']])) {
      $images = json_decode($rowResult[$v['field_name']], 1);
      if (!empty($images)) {
        $image = $images[0];
      }
    }
  }
  if ($v['field_type'] == 'videos') {
    if (!empty($rowResult[$v['field_name']])) {
      $videos = json_decode($rowResult[$v['field_name']], 1);
    }
  }
  if ($v['field_type'] == 'urls') {
    if (!empty($rowResult[$v['field_name']])) {
      $urls = json_decode($rowResult[$v['field_name']], 1);
    }
  }
}

if (!empty($rowResult['showAddress']) && $resultModule['showallmappics'] == 1) {
  $images[] = 'https://maps.googleapis.com/maps/api/staticmap?center='.$rowResult['clatitude'].','.$rowResult['clongitude'].'&maptype=roadmap&markers=color:blue%7Clabel:S%7C'.$rowResult['clatitude'].','.$rowResult['clongitude'].'&zoom=16&size=600x600&key='.DEVELOPERKEY;
  /*
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=0&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=45&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=90&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=135&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=180&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=235&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=280&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=315&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=360&pitch=10';
  $images[] = 'https://maps.googleapis.com/maps/api/streetview?size=600x600&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=15&pitch=10';*/
}
//decryption
//pr($rowResult);
$latitude = ($rowResult['showAddress'] == 1 && !empty($rowResult['clatitude'])) ? $rowResult['clatitude']: $globalCity['latitude'];
$longitude = ($rowResult['showAddress'] == 1 && !empty($rowResult['clongitude'])) ? $rowResult['clongitude']: $globalCity['longitude'];

//point system
if ($resultModule['user_points_matching'] == 1 && !empty($_SESSION['user']['id'])) {
    $id = !empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';
    $id2 = $rowResult['uid'];
    $pointSystem = updatePoints($id, $id2);
    $points = '';
    $points_result = '';
    if (!empty($pointSystem)) {
        $points = $pointSystem[$id][$id2]['points'];
        $points_result = $pointSystem[$id][$id2]['results'];
    }
}

if ($resultModule['custom_points_matching'] == 1 && !empty($_SESSION['user']['id'])) {
  $matchResult = matchUserDate($_SESSION['user']['id'], $rowResult['clatitude'], $rowResult['clongitude'], $rowResult['custom_date']);
  if (!empty($matchResult)) {
    $pointsCustom = $matchResult['points'];
    $points_result_custom = $matchResult['results'];
  }//end if
}
//point system ends

$address = '';
if (!empty($rowResult['showAddress'])) {
  $address = !empty($rowResult['address2']) ? $rowResult['address2'] : $rowResult['address'];
}

$get_rsView = getString(array('id'));
$pageTitle = $resultModule['menu_display_name'].' :: '.$rowResult['title'];

//orders
if (!empty($_GET['checkoutId'])) {
  if (!empty($_GET['error'])) {
    $message = $_GET['error_description'];
  } else {
      $message = 'We did not received your money. Please contact the administrator at '.ADMIN_EMAIL;
      $d = array();
      $d['checkoutId'] = $_GET['checkoutId'];
      $d['orderId'] = $_GET['orderId'];
      $d['test'] = $_GET['test'];
      $d['transaction'] = $_GET['transaction'];
      $d['postback'] = $_GET['postback'];
      $d['amount'] = $_GET['amount'];
      $d['signature'] = $_GET['signature'];
      $d['clearingDate'] = $_GET['clearingDate'];
      $d['status'] = $_GET['status'];
      $d['internal_status'] = 0;
      if (verifyGatewaySignature($d['signature'], $d['checkoutId'], $d['amount'])) {
          $d['module_id'] = $colname_rsModule;
          $d['id'] = $_GET['id'];
          $d['redeemed'] = 0;
          $randomNumber = rand(1, 20000);
          $coupon_code = substr(md5($_GET['id'].$_SESSION['user']['id'].time().$randomNumber), 3, 7);
          $d['coupon_code'] = $coupon_code;
          if (isset($rowResult['expiry']) && $rowResult['expiry'] > 0) {
            $days = $rowResult['expiry'];
            $d['expiry_date_time'] = date('Y-m-d H:i:s', strtotime("+$days day"));
          }
          $d['transaction_date'] = date('Y-m-d H:i:s');
          $d['user_id'] = $_SESSION['user']['id'];
          $d['transaction_date'] = date('Y-m-d H:i:s');
          $d['comments'] = '';
          $d['transaction_details'] = json_encode($_GET);
          if ($_GET['status'] === 'Completed') {
              $d['internal_status'] = 1;
              $message = 'We have recieved your money. Please note down the Coupon code: '.$coupon_code.', Please email the business owner ('.$rowResult['email'].') or go to the business address to redeem your coupon, If you need any help then please contact the administrator at '.ADMIN_EMAIL;
              //send email
          }
          $d['payment_type'] = 'dwolla';
          $modelGeneral->addDetails('auto_pre_transactions', $d);
      }//end verify if
  }//end error if
}
//orders ends
?>
<div id="mapCanvas"></div>
<?php
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
?>
<!-- GOOGLE FONT-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- /GOOGLE FONT-->


<style type="text/css">
.panel-heading{padding:10px 15px;background-color:#f5f5f5;border-bottom:1px solid #ddd;border-top-right-radius:3px;border-top-left-radius:3px}
.panel{margin-bottom:20px;background-color:#fff;border:1px solid #ddd;border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,0.05);}
</style>

<div class="page-header">
  <h1><i class="icon-<?php echo strtolower($rowResult['gender']); ?> main-color"></i> <?php echo $pageTitle; ?></h1>
  <?php if ($locationBar) { ?><span><?php echo $address; ?></span><?php } ?>
  <?php if (!empty($rowResult['distance'])) { ?><span class="pull-right"><b>Distance: </b><?php echo $rowResult['distance'].' mi'; ?></span><?php } ?>
  <?php if (!empty($my)) {
    $url = $currentURL.'/auto/my?'.$get_rsView;
  } else if (empty($my)) {
    $url = $currentURL.'/auto/browse?'.$get_rsView;
  }
  ?>
  <p>
  <?php if ($resultModule['browse_page'] == 1 || $resultModule['my_page'] == 1) { ?><a href="<?php echo $currentURL; ?>/auto/browse?<?php echo $get_rsView; ?>">Back To Browse</a><?php } ?>
  <?php if ($resultModule['new_page'] == 1) { ?>  | <a href="<?php echo $currentURL; ?>/auto/new?module_id=<?php echo $colname_rsModule; ?>">Create New</a><?php } ?>
  <?php if ($resultModule['messages'] == 1) { ?>
    | 
      <?php if ($rowResult['uid'] != $uid ) { ?>
          <a href="<?php echo $currentURL; ?>/auto/messages?uid=<?php echo $rowResult['uid']; ?>&module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $rowResult['id']; ?>&uid=<?php echo $rowResult['uid']; ?>">Send Message</a>
      <?php } else { ?>
          <a href="<?php echo $currentURL; ?>/auto/messages?module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $rowResult['id']; ?>&uid=<?php echo $rowResult['uid']; ?>">Messages</a>
       <?php } ?>
   <?php } ?>
   </p>
  <br class="clear">
  <?php
    //showing message
    if (!empty($message)) { ?>
    <div class="error"><?php echo $message; ?></div>
  <?php } ?>
</div>

<?php if (!empty($images) || !empty($videos)) { ?>
 <!-- Fotorama -->
  <link href="<?php echo HTTPPATH; ?>/scripts/fotorama/fotorama.css" rel="stylesheet">
  <script src="<?php echo HTTPPATH; ?>/scripts/fotorama/fotorama.js"></script>
<?php } ?>

<?php if (!empty($images)) { ?>
<div class="row">
  <div class="col-lg-12">
    <div class="panel">
      <div class="panel-heading">
        <h3><i class="icon-picture main-color"></i> Pictures</h3>
      </div>
      <div class="panel-body">


<!-- Fotorama -->
<div class="fotorama" data-width="100%" data-ratio="700/467" data-max-width="100%" data-nav="thumbs">
  <?php foreach ($images as $img) { ?>
  <img src="<?php echo $img; ?>">
  <?php } ?>
</div>
<!-- Fotorama ends -->



      </div>
    </div>
    </div>
</div>
<?php } ?>


<?php if (!empty($videos)) { ?>
<div class="row">
  <div class="col-lg-12">
    <div class="panel">
      <div class="panel-heading">
        <h3><i class="icon-youtube main-color"></i> Videos</h3>
      </div>
      <div class="panel-body">


<!-- Fotorama -->
<div class="fotorama" data-width="100%" data-ratio="700/467" data-max-width="100%" data-nav="thumbs">
  <?php foreach ($videos as $video) { ?>
  <a href="<?php echo $video; ?>">Video</a>
  <?php } ?>
</div>
<!-- Fotorama ends -->



      </div>
    </div>
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-beer main-color"></i> Description</h3>
          </div>
          <div class="panel-body">
            <?php echo !empty($rowResult['description']) ? nl2br($rowResult['description']) : 'No description available'; ?>
          </div>
        </div>
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-beer main-color"></i> Meta Data</h3>
          </div>
          <div class="panel-body">
            <!--<b>Created By: </b><?php //echo $rowResult['fullname']; ?><br />-->
            <b>Created On: </b><?php echo $rowResult['rc_created_dt']; ?><br />
            <b>Updated On: </b><?php echo $rowResult['rc_updated_dt']; ?><br />
            <?php if (!empty($_SESSION['user']['id']) && $_SESSION['user']['id'] == $rowResult['uid']) { ?>
                <b>Approved: </b><?php echo ($rowResult['rc_approved'] == 1) ? 'Yes' : 'No'; ?><br />
                <b>Status: </b><?php echo ($rowResult['rc_status'] == 1) ? 'Yes' : 'No'; ?><br />
            <?php } ?>
          </div>
        </div>
    </div>
   <div class="col-12 col-lg-6">
        <?php if (!empty($points) && !empty($points_result) && $resultModule['user_points_matching'] == 1) { ?>
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-time main-color"></i> User Matching Points</h3>
          </div>
          <div class="panel-body">
              <b>Points: <?php echo $points; ?></b> (<?php echo $points_result; ?>)
            </div>
         </div>
        <?php } ?>
        <?php if (!empty($pointsCustom) && !empty($points_result_custom) && $resultModule['custom_points_matching'] == 1) { ?>
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-time main-color"></i> Custom Matching Points</h3>
          </div>
          <div class="panel-body">
              <b>Points: <?php echo $pointsCustom; ?></b> (<?php echo $points_result_custom; ?>)
            </div>
         </div>
        <?php } ?>
        <?php if (!empty($urls)) { ?>
          <div class="panel">
            <div class="panel-heading">
              <h3><i class="icon-external-link main-color"></i> URLS</h3>
            </div>
            <div class="panel-body" style="overflow: hidden;">
              <ul>
                <?php foreach ($urls as $url) { ?>
                <li><a href="<?php echo $url; ?>" target="_blank" rel="nofollow"><?php echo $url; ?></a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        <?php } ?>
        <?php
        $fields = array();
        foreach ($resultModuleFields2 as $k => $field) { 
                  if ($field['field_name'] === 'title'
                    || $field['field_name'] === 'description'
                    || $field['field_type'] === 'videos'
                    || $field['field_type'] === 'urls'
                    || $field['field_type'] === 'images'
                    || $field['field_type'] === 'addressbox'
                    ) {
                      continue;
                    }
                    $fields[$k] = $field;
        }
        ?>
        <?php if (!empty($fields)) { ?>
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-time main-color"></i> Additional</h3>
          </div>
          <div class="panel-body">
          <?php foreach ($fields as $k => $field) {
                  //select box
                  if ($field['field_type'] === 'selectbox') {
                    ?>
                    <?php if (!empty($field['related_information'])) { ?>
                        <b><?php echo $field['field_display_name']; ?>: </b> 
                        <?php
                            $options = json_decode($field['related_information'], 1);
                            echo $options[$rowResult[$field['field_name']]];
                        ?>
                        <br />
                    <?php } ?>
                    <?php
                  }//selectbox ends
                  //varchar
                  else if ($field['field_type'] === 'varchar' || $field['field_type'] === 'int' || $field['field_type'] === 'double'|| $field['field_type'] === 'text') {
                    ?>
                    <b><?php echo $field['field_display_name']; ?>: </b> 
                    <?php
                        echo $field['field_prefix'];
                        echo $rowResult[$field['field_name']];
                        echo $field['field_suffix'];
                    ?>
                    <br />
                    <?php
                  }//end varchar
                  //checkbox
                  else if ($field['field_type'] === 'checkbox') {
                    ?>
                    <b><?php echo $field['field_display_name']; ?>: </b> 
                    <?php
                        echo (!empty($rowResult[$field['field_name']])) ? 'Yes' : 'No';
                    ?>
                    <br />
                    <?php
                  }//end checkbox
                  else {
                    log_log($field);
                  }
          ?>
         <?php } ?>
            </div>
       </div>
       <?php } ?>
       <?php if ($resultModule['custom_user_payment'] == 1) { ?>
       <script src="https://www.dwolla.com/scripts/button.min.js" 
    class="dwolla_button" 
    type="text/javascript" 
    data-key="IVQZVEDpwzJiECGzWKczZZ0pUa6TXqMylcJCd3pBTR3IaLWT0h" 
    data-redirect="<?php echo $currentURL; ?>/auto/detail?id=<?php echo $_GET['id']; ?>&module_id=<?php echo $colname_rsModule; ?>" 
    data-label="Buy Now (<?php echo !empty($resultModuleFields2[$resultModule['custom_user_payment_field']]['field_prefix']) ? $resultModuleFields2[$resultModule['custom_user_payment_field']]['field_prefix'] : ''; ?> <?php echo $rowResult[$resultModule['custom_user_payment_field']]; ?>)" 
    data-name="<?php echo $rowResult['title']; ?>" 
    data-description="<?php echo $rowResult['description']; ?>" 
    data-amount="<?php echo $rowResult[$resultModule['custom_user_payment_field']]; ?>" 
    data-shipping="0"
    data-tax="0"
    data-guest-checkout="true"
    data-orderid="<?php echo guid(); ?>"
    data-type="simple">
</script>
<!--https://developers.dwolla.com/dev/pages/button#redirect
    data-test="true"-->
       <?php } ?>
   </div>
</div>
<?php
/*$fn = SITEDIR.'/mods/auto/display_detail_template/'.$tablename.'.php';
if (file_exists($fn)) {
  include($fn);
}*/
?>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>