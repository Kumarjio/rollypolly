<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
check_city_owner($globalCity['id'], $_SESSION['user']['id']);

$Models_General = new Models_General();
if (isset($_GET['change']) && !empty($_GET['change_id'])) {
  $arr = array();
  $arr['admin_status'] = $_GET['change'];
  $where = sprintf('record_id = %s', $Models_General->qstr($_GET['change_id']));
  $Models_General->updateDetails('records', $arr, $where);
}
include(SITEDIR.'/includes/showmap.php');
//getting records
$Models_Records = new Models_Records();
$params = array();
$params['city_ids'] = $globalCity['id'];
$params['admin_status'] = !empty($_GET['status']) ? 1 : 0;
$data = $Models_Records->records_view($params);
?>
<div class="row">
  <div class="col-lg-12">
    <h2>Browse <?php echo !empty($params['admin_status']) ? 'Approved' : 'Pending'; ?> Status Lawyers</h2>
    <p><a href="<?php echo $currentURL; ?>/manage/lawyers/browse?status=0">Pending Lawyers</a> | <a href="<?php echo $currentURL; ?>/manage/lawyers/browse?status=1">Approved Lawyers</a></p>
    <?php if (empty($data['result'])) { ?>
    <div class="error">No more lawyers in list for status <?php echo (!empty($params['admin_status'])) ? 'Approved' : 'Pending'; ?>.</div>
    <?php } ?>
  </div>
</div>
<?php if (!empty($data['result'])) { ?>
<div class="row" style="font-size:11px;">
    <div class="col-lg-12">
<div class="togglediv" style="display: block;">
<?php foreach ($data['result'] as $k => $v) { 
$details = json_decode($v['details'], 1);
?>
<div class="tile mb20 mr20">
        <a href="#" class="pho">
                <img src="<?php echo $details['image']; ?>" title="<?php echo $details['description']; ?>">
        </a>
        <div class="amt">Consultation: $ <?php echo $details['charges']; ?></div>
        <div class="pro">
                <span class="fill" style="width: 100%;"></span>
        </div>
        <a href="#" class="title"><?php echo $v['title']; ?></a>

        <div class="loc" title="<?php echo $v['address']; ?>"><?php echo $v['name']; ?></div>
        <div class="loc"><a href="#">Change City</a> | <a href="<?php echo $currentURL; ?>/manage/lawyers/browse?status=<?php echo $params['admin_status']; ?>&change=<?php echo ($v['admin_status'] == 1) ? 0 : 1; ?>&change_id=<?php echo $v['record_id']; ?>"><?php echo ($v['admin_status'] == 1) ? 'Disapprove' : 'Approve'; ?></a></div>
        </div>
<?php } ?>
<div style="clear:both;"></div>

<div class="close_list"></div></div>

      </div>
</div>
<?php } ?>
<?php } catch (Exception $e) {
  ?>
<h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>