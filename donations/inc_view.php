<div class="row">
    <?php do { ?>
  <?php $percentage = $row_rsView['total_amount'] * (100 / $row_rsView['donation_needed']); ?>
        <!-- for loop starts -->
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <img class="img-responsive" style="width: 100%;" src="images/<?php echo $row_rsView['user_id']; ?>/thumbs/<?php echo $row_rsView['donation_image']; ?>" alt="" />
                <div class="panel-body text-center" style="height: 120px;">
                    <h4>
                        <a href="detailList.php?did=<?php echo $row_rsView['did']; ?>"><?php echo $row_rsView['donation_title']; ?></a>
                    </h4>
                    <p class="text-danger">
                      $ <?php echo $row_rsView['donation_needed'];?>
                      <?php if ($row_rsView['total_amount'] > 0) { ?>
                       / $ <?php echo $row_rsView['total_amount']; ?>
                      / $ <?php echo $row_rsView['donation_needed'] - $row_rsView['total_amount']; ?>
                      <?php } ?>
                    </p>
                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
                        <?php echo $percentage; ?>%
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- for loop ends -->
    <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</div>