<div class="span4 well">
    <div class="row">
    <div class="span1"><a href="<?php echo HTTPPATH; ?>/users/detail?id=<?php echo $userDetails['uid']; ?>" class="thumbnail"><img src="<?php echo $userDetails['picture']; ?>" alt="" class="imglist"></a></div>
    <div class="span3">
      <!--<p>admin</p>-->
      <p><strong><?php echo $userDetails['fullname']; ?></strong></p>
      <p><strong>Gender: </strong> <?php echo $userDetails['gender'];?></p>
      <p><strong>Date of Birth: </strong> <?php echo substr($userDetails['dob'], 0, -3);?></p>
      <?php $horoInfo = findHoroInfo($userDetails);
      if (!empty($horoInfo[7])) { ?>
      <p><strong>Nakshatra: </strong> <?php echo $horoInfo[7];?></p>
      <?php } ?>
    </div>
  </div>
</div>