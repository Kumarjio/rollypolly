<div class="well">
    <div class="media">
      <a class="pull-left" href="#">
      <img class="media-object imglist" src="<?php echo $rowResult['picture']; ?>">
    </a>
    <div class="media-body">
      <h4 class="media-heading"><?php echo $rowResult['name']; ?></h4>
        <?php if (!empty($rowResult['distance'])) { ?>
        <p class="text-right"><strong>Distance:</strong> <?php echo $rowResult['distance']; ?> mi</p>
        <?php } ?>
        <?php if (!empty($rowResult['showAddress'])) {  ?>
        <p><?php echo $rowResult['address']; ?></p>
        <?php } ?>
        <ul class="list-inline list-unstyled">
      <li><span><i class="glyphicon glyphicon-calendar"></i><?php echo ago(strtotime($rowResult['residential_updated_dt'])); ?></span></li>
          <?php if (!empty($_SESSION['user']['id']) && $_SESSION['user']['id'] == $rowResult['uid']) { ?>
          <li>|</li>
            <li>
               <a href="<?php echo $currentURL; ?>/residential/edit?id=<?php echo $rowResult['residential_id']; ?>"><span class="fa fa-globe"></span> Edit</a> | <a onClick="var x=confirm('do you really want to delete this post?'); return x;" href="<?php echo $currentURL; ?>/residential/delete?id=<?php echo $rowResult['residential_id']; ?>"><span class="fa fa-times"></span> Delete</a>
            </li>
          <?php } else { ?>
          <li>|</li>
            <li>
               <a href="<?php echo HTTPPATH; ?>/messages/chat?id=<?php echo $rowResult['uid']; ?>"><span class="fa fa-envelope-o"></span> Message</a>
            </li>
          <?php } ?>
            <li>|</li>
          <li>
          <!-- Use Font Awesome http://fortawesome.github.io/Font-Awesome/ -->
          <?php $detailURL = HTTPPATH.'/user/detail?uid='.$rowResult['uid']; ?>
            <a href="javascript:;" onClick="fb('<?php echo urlencode($detailURL); ?>');"><span><i class="fa fa-facebook-square"></i></span></a>
            <a href="javascript:;" onClick="MM_openBrWindow('https://twitter.com/home?status=<?php echo urlencode($rowResult['name'].' at '.$detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span><i class="fa fa-twitter-square"></i></span></a>
            <a href="javascript:;" onClick="MM_openBrWindow('https://plus.google.com/share?url=<?php echo urlencode($detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span><i class="fa fa-google-plus-square"></i></span></a>
          </li>
       </ul>
     </div>
  </div>
</div>
<?php if (!empty($rowResult['showAddress'])) { ?>
<script language="javascript">
var latlng = new google.maps.LatLng(<?php echo $rowResult['residential_lat']; ?>, <?php echo $rowResult['residential_lng']; ?>);
marker = new google.maps.Marker({
    position: latlng,
    map: map,
    title: '<?php echo $rowResult['address']; ?>'
});
google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent('<h3><?php echo $rowResult['name']; ?></h3><p><?php echo $rowResult['address']; ?></p>');
    infowindow.open(map, this);
});
</script>
<?php } ?>