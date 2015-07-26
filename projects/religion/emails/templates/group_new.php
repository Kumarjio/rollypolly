Dear <?php echo $params['name']; ?>,
<br />
You have successfully created a new group called "<?php echo $params['group_name']; ?>". Click below to manage it. <a href="<?php echo HTTPPATH; ?>/manage.php?group_id=<?php echo $params['group_id']; ?>"><?php echo HTTPPATH; ?>/groups/manage.php?group_id=<?php echo $params['group_id']; ?></a>
<br />
<br />
Thanks
<br />
Admin
<br />
<a href="http://<?php echo SITENAME; ?>"><?php echo SITENAME; ?></a>