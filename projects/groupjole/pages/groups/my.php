<?php
checkLogin();
$return = array();
$userId = !empty($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : '';
$query = "select * from groups WHERE user_id = ?";
$returnGroups = $General->fetchAll($query, array($userId), CACHETIME);
if (empty($returnGroups)) {

}
//pr($returnGroups);
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            My Owned <?php echo TITLENAME; ?>s
        </h1>
    </div>
    <div class="col-lg-12">
        <?php foreach ($returnGroups as $k => $v) { ?>
            <?php include(SITEDIR.'/pages/includes/groupList.php'); ?>
        <?php } ?>
    </div>
</div>