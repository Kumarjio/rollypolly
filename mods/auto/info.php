<?php
try {
include(SITEDIR.'/includes/navLeftSideVars.php');
$layoutStructure = 'main';
if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}

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

$pageTitle .= ' '.$resultModule['menu_display_name'];

include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
?>
<div class="jumbotron">
  <h1>About <?php echo $resultModule['menu_display_name']; ?></h1>
  <p><?php echo $resultModule['module_info_page']; ?></p>
</div>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>