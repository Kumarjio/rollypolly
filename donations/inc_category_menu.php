<?php require_once('../donations/inc_category_base.php'); ?>
<?php if ($totalRows_rsCategoryLink > 0) { // Show if recordset not empty ?>
  <ul id="cat-navi" class="nav nav-list">
    <li><a href="view.php" class="list-group-item active"> Categories</a></li>
    <?php foreach ($rsCategoryDetails as $row_rsCategoryLink) { ?>
      <li><a href="view.php?cid=<?php echo $row_rsCategoryLink['category_id']; ?>&cat=<?php echo urlencode($row_rsCategoryLink['category']); ?>" class="list-group-item"> <?php echo $row_rsCategoryLink['category']; ?></a></li>
      <?php } ?>
  </ul>
<?php } // Show if recordset not empty ?>
<?php
mysql_free_result($rsCategoryLink);
?>