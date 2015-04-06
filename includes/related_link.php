<?php if (!empty($resultModuleRelated)) { ?>
  <a href="<?php echo $currentURL; ?>/auto/new?module_id=<?php echo $resultModuleRelated['module_id']; ?>&rel_id=<?php echo $rowResult['module_id']; ?>&detail_id=<?php echo $_GET['id']; ?>"><?php echo $resultModuleRelated['menu_display_name']; ?></a>
<br />
<?php } ?>