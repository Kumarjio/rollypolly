<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH);
  exit;
}
include(SITEDIR.'/includes/navLeftSideVars.php');

$nearbyCities = getNearbyCities($globalCity['nearby']);

ob_start();
include(SITEDIR.'/mods/lawyers/subNavigation.php');
$subNavigation = ob_get_clean();

$nm = 'Lawyers in '.$globalCity['city'].' & Nearby Cities';
$pageTitle = $nm;
$pageTitle2 = 'Lawyers in Whole World';
//getting records
$Models_Records = new Models_Records();
$params = array();
$params['city_ids'] = $nearbyCities[1];
$params['admin_status'] = 1;
$data = $Models_Records->records_view($params);
$params2 = array();
$params2['exclude_city_ids'] = $nearbyCities[1];
$params2['admin_status'] = 1;
$data2 = $Models_Records->records_view($params2);
?>
<script language="javascript">
//initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>
<h3><?php echo $pageTitle; ?></h3>

<?php if (!empty($data['result'])) { ?>
<div class="row" style="font-size:11px;">
    <div class="col-lg-12">
<div class="togglediv" style="display: block;">
<?php foreach ($data['result'] as $k => $v) { 
$details = json_decode($v['details'], 1);
$url = makecityurl($v['cty_id'], $v['name']).'/lawyers/detail?id='.$v['record_id'];
?>
<div class="tile mb20 mr20">
        <a href="<?php echo $url; ?>" class="pho">
                <img src="<?php echo $details['image']; ?>" title="<?php echo $details['description']; ?>">
        </a>
        <div class="amt">Consultation: $ <?php echo $details['charges']; ?></div>
        <div class="pro">
                <span class="fill" style="width: 100%;"></span>
        </div>
        <a href="<?php echo $url; ?>" class="title"><?php echo $v['title']; ?></a>

        <div class="loc" title="<?php echo $v['address']; ?>"><?php echo $v['name']; ?>, <?php echo $v['state']; ?>, <?php echo $v['country']; ?></div>
        </div>
<?php } ?>
<div style="clear:both;"></div>

<div class="close_list"></div></div>

      </div>
</div>
<?php } else { ?>
<div class="error">No records found.</div>
<?php } ?>
<?php if (!empty($data2['result'])) { ?>
<hr />
<h3><?php echo $pageTitle2; ?></h3>

<div class="row" style="font-size:11px;">
    <div class="col-lg-12">
<div class="togglediv" style="display: block;">
<?php foreach ($data2['result'] as $k => $v) { 
$details = json_decode($v['details'], 1);
$url = makecityurl($v['cty_id'], $v['name']).'/lawyers/detail?id='.$v['record_id'];
?>
<div class="tile mb20 mr20">
        <a href="<?php echo $url; ?>" class="pho">
                <img src="<?php echo $details['image']; ?>" title="<?php echo $details['description']; ?>">
        </a>
        <div class="amt">Consultation: $ <?php echo $details['charges']; ?></div>
        <div class="pro">
                <span class="fill" style="width: 100%;"></span>
        </div>
        <a href="<?php echo $url; ?>" class="title"><?php echo $v['title']; ?></a>

        <div class="loc" title="<?php echo $v['address']; ?>"><?php echo $v['name']; ?>, <?php echo $v['state']; ?>, <?php echo $v['country']; ?></div>
        </div>
<?php } ?>
<div style="clear:both;"></div>

<div class="close_list"></div></div>

      </div>
</div>
<?php } ?>