Dear <?php echo $params['name']; ?>,
<br />
You have successfully created a new proposal called "<?php echo $params['title']; ?>". Click below to manage it. <a href="<?php echo HTTPPATH; ?>/manage?proposal_id=<?php echo $params['proposal_id']; ?>"><?php echo HTTPPATH; ?>/manage?proposal_id=<?php echo $params['proposal_id']; ?></a>
<br />
<br />
Thanks
<br />
Admin
<br />
<a href="http://<?php echo SITENAME; ?>"><?php echo SITENAME; ?></a>