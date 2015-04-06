<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
include(SITEDIR.'/includes/showmap.php');
check_city_owner($globalCity['id'], $_SESSION['user']['id']);
?>
<h3>Manage City <?php echo $globalCity['city']; ?></h3>
<ul class="breadcrumb">
  <li><a href="<?php echo $currentURL; ?>">Home</a></li>
  <li class="active">Manage <?php echo $globalCity['city']; ?></li>
</ul>
<p>
  <ul>
    <li><strong>Lawyers</strong>
      <ul>
        <li><a href="<?php echo $currentURL; ?>/manage/lawyers/browse?status=0">View Pending Lawyers</a></li>
        <li><a href="<?php echo $currentURL; ?>/manage/lawyers/browse?status=1">View Approved Lawyers</a></li>
      </ul>
    </li>
  </ul>
</p>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>