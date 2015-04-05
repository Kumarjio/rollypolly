<?php
check_login();
$pageTitle = 'My Cities';
include(SITEDIR.'/libraries/addresses/nearby.php');
$Models_Geo = new Models_Geo();
$mycities = $Models_Geo->myOwnedCities($_SESSION['user']['id']);
?>
<h3>My Cities</h3>
<?php if (empty($mycities)) { ?>
<div>No Cities in your account.</div>
<?php } else { ?>
<?php foreach ($mycities as $k => $v) {
  ?>
  <a href="<?php echo makecityurl($v['id'], $v['name']); ?>/manage/main"><?php echo $v['name']; ?>, <?php echo $v['state']; ?>, <?php echo $v['country']; ?></a><br>
  <strong>Expiry:</strong> <?php echo $v['expiry_date']; ?><br>
  <strong>Status:</strong> <?php echo $v['status']; ?><br><br>
  <?php
} ?>
<?php } ?>