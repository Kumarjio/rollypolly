<?php
checkLogin();
$return = array();
$userId = !empty($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : '';
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$query = "select * from groups WHERE user_id = ? AND group_id = ?";
$returnGroups = $General->fetchRow($query, array($userId, $id), CACHETIME);

if (empty($returnGroups)) {
    header("Location: new");
    exit;    
}
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Details for <?php echo $returnGroups['group_name']; ?>
        </h1>
    </div>
    </div>
</div>