<?php
checkLogin();
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Horo Match Making</h1>
    </div>
</div>
<div class="row">
    <?php if (!empty($_SESSION['user'])) { ?>
        <?php include(SITEDIR.'/pages/includes/horo.php'); ?>
    <?php } ?>
</div>