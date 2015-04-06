<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}
include(SITEDIR.'/mods/admin/common/store/config.php');
} catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>