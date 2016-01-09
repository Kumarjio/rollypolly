<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <img src="<?php echo !empty($v['group_image_url']) ? $v['group_image_url'] : SAMPLEIMAGE; ?>" alt="...">
        <div class="caption">
        <h3><?php echo $v['group_name']; ?></h3>
        <p><?php echo $v['location']; ?></p>
        <p><a href="<?php echo HTTPPATH; ?>/<?php echo $v['group_id']; ?>" class="btn btn-primary" role="button">View Details</a> <?php if ($v['payment_status'] === 'Pending') { ?><a href="<?php echo HTTPPATH; ?>/groups/step2?id=<?php echo $v['group_id']; ?>" class="btn btn-default" role="button">Pay</a><?php } ?> <a href="<?php echo HTTPPATH; ?>/groups/manage?id=<?php echo $v['group_id']; ?>" class="btn btn-default" role="button">Manage</a></p>
        </div>
    </div>
</div>