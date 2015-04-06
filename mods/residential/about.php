<?php
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'About Residential';
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
?>
<div class="jumbotron">
  <h1><?php echo $pageTitle; ?></h1>
  <p>Residential is social meetup among the people living near to each other. If you live in particular house and you want to communicate with your neighbors then this place is for you.</p>
  <p>If you put your birth details in <a href="<?php echo HTTPPATH; ?>/users/settings">settings</a> tab then you can see astro matching points with your neighbors.</p>
</div>