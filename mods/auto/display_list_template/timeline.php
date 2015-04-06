<?php
$layoutStructure = 'autoTimeline';

?>
<div class="row">
  <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
    <ul class="event-list">
      <?php foreach ($rsView as $key => $rowResult) {

//decryption
foreach ($resultModuleFields as $k => $v) {
  if (!empty($rowResult[$v['field_name']]) && $v['encrypted'] == 1) {
    $rowResult[$v['field_name']] = decryptText($rowResult[$v['field_name']]);
  }
}
//decryption

//point system
if ($resultModule['user_points_matching'] == 1 && !empty($_SESSION['user']['id'])) {
  $id = !empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';
  $id2 = $rowResult['uid'];
  $pointSystem = updatePoints($id, $id2);
  $points = '';
  $points_result = '';
  
  if (!empty($pointSystem)) {
    
      $points = $pointSystem[$id][$id2]['points'];
      $points_result = $pointSystem[$id][$id2]['results'];
  }
}

if ($resultModule['custom_points_matching'] == 1 && !empty($_SESSION['user']['id'])) {
  $matchResult = matchUserDate($_SESSION['user']['id'], $rowResult['clatitude'], $rowResult['clongitude'], $rowResult['custom_date']);
  if (!empty($matchResult)) {
    $pointsCustom = $matchResult['points'];
    $points_result_custom = $matchResult['results'];
  }//end if
}

//point system ends
//image
//$image = 'https://maps.googleapis.com/maps/api/staticmap?center='.$rowResult['clatitude'].','.$rowResult['clongitude'].'&maptype=roadmap&markers=color:blue%7Clabel:S%7C'.$rowResult['clatitude'].','.$rowResult['clongitude'].'&zoom=18&size=400x400&key='.DEVELOPERKEY; //$rowResult['picture'];
//$image = 'https://maps.googleapis.com/maps/api/streetview?size=200x200&location='.number_format($rowResult['clatitude'], 2).','.number_format($rowResult['clongitude'], 2).'&fov=90&heading=235&pitch=10';
$image = '';
if (!empty($rowResult['showAddress']) && $resultModule['showmappic'] == 1) {
  $image = 'https://maps.googleapis.com/maps/api/staticmap?center='.$rowResult['clatitude'].','.$rowResult['clongitude'].'&maptype=roadmap&markers=color:blue%7Clabel:S%7C'.$rowResult['clatitude'].','.$rowResult['clongitude'].'&zoom=16&size=200x200&key='.DEVELOPERKEY; 
} else if ($resultModule['showprofilepic'] == 1) {
  $image = $rowResult['picture'];
}
if (!empty($rowResult[$imageFieldName])) {
  $images = json_decode($rowResult[$imageFieldName], 1);
  if (!empty($images)) {
    $image = $images[0];
  }
}
//image

$dt = !empty($rowResult['custom_date']) ? $rowResult['custom_date'] : $rowResult['rc_created_dt'];
$title = !empty($rowResult[$resultModule['title_field']]) ? $rowResult[$resultModule['title_field']] : '';

$address = '';
if (!empty($rowResult['showAddress'])) {
  $address = !empty($rowResult['address2']) ? $rowResult['address2'] : $rowResult['address'];
}



$tmp = explode(' ', $dt);
$date = $tmp[0];
$time = $tmp[1];
$tmp = explode('-', $date);
$year = $tmp[0];
$month = $tmp[1];
$day = $tmp[2];

?>
<li>
<?php if ($resultModule['showDate'] == 1) { ?>
<time datetime="<?php echo $dt; ?>">
  <span class="day"><?php echo $day; ?></span>
  <span class="month"><?php echo monthString($month); ?></span>
  <span class="year"><?php echo $year; ?></span>
  <span class="time"><?php echo $time; ?></span>
</time>
<?php } ?>
<?php if ($resultModule['showprofilepic'] == 1 || $resultModule['showmappic'] == 1) { ?>
<img src="<?php echo $image; ?>" />
<?php } ?>
<div class="info">
    <?php $detailURL = $currentURL.'/auto/detail?module_id='.$colname_rsModule.'&id='.$rowResult['id'].'&my='.$my.$get_rsView.'&pageNum_rsView='.$pageNum_rsView; ?>
    <h2 class="title"><a href="<?php echo $detailURL; ?>"><?php echo empty($title) ? $dt : $title; ?></a></h2>
    <p class="desc" style="font-size:11px">
        <?php if ($resultModule['showDate'] == 1) { ?>
        <strong>Date: </strong><?php echo $dt; ?>
        <?php } ?>
        <?php if (!empty($rowResult['distance']) && !empty($rowResult['showAddress'])) { ?><span class="pull-right"><strong>Distance: </strong><?php echo $rowResult['distance']; ?> mi</span><?php } ?><br>
        <?php if (!empty($rowResult['showAddress'])) { ?>
        <?php echo $address; ?><br>
        <?php } ?>
        <?php if (!empty($points) && !empty($points_result) && $resultModule['user_points_matching'] == 1) { ?>
            <strong>User Matching Points *:</strong> <?php echo $points; ?> (<?php echo $points_result; ?>)
        <?php } ?>
        <?php if (!empty($pointsCustom) && !empty($points_result_custom) && $resultModule['custom_points_matching'] == 1) { ?>
            <strong>Custom Matching Points *:</strong> <?php echo $pointsCustom; ?> (<?php echo $points_result_custom; ?>)
        <?php } ?>
        <i><?php echo ($rowResult['rc_status'] == 1) ? '' : '(InActive)'; ?></i>
        <i><?php echo ($rowResult['rc_approved'] == 1) ? '' : '(Approval Pending)'; ?></i>
    </p>
    <?php if (!empty($rowResult['showAddress'])) { ?>
        <script language="javascript">
            var latlng = new google.maps.LatLng(<?php echo $rowResult['clatitude']; ?>, <?php echo $rowResult['clongitude']; ?>);
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: '<?php echo addslashes($title); ?>'
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<h3><a href="<?php echo $detailURL; ?>"><?php echo addslashes($title); ?></a></h3><p style="font-size:11px"><strong>Date: </strong><?php echo $dt; ?></p><p style="font-size:11px"><?php echo $rowResult['address']; ?></p>');
                infowindow.open(map, this);
            });
        </script>
    <?php } ?>
    <ul>
    <!--<?php //echo $get_rsView;?>&pageNum_rsView=<?php //echo $pageNum_rsView; ?>-->
    <?php if (!empty($_SESSION['user']['id']) && $_SESSION['user']['id'] == $rowResult['uid']) { ?>
        <li style="width:33%;"><a href="<?php echo $currentURL; ?>/auto/edit?id=<?php echo $rowResult['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&my=<?php echo $my; ?>"><span class="fa fa-globe"></span> Edit</a></li>
        <li style="width:33%;"><a href="<?php echo $currentURL; ?>/auto/delete?module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $rowResult['id']; ?>&my=<?php echo $my; ?>" onClick="var a = confirm('do you really want to delete this record. you wont be able to recover it again.'); return a;"><span class="fa fa-times"></span> Delete</a></li>
    <?php } else { ?>
      <!--<li style="width:50%;"><a href="<?php echo $currentURL; ?>/auto/fav?id=<?php echo $rowResult['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&my=<?php echo $my; ?>"><span class="fa fa-globe"></span> Add To List</a></li>-->
      <li style="width:50%;"><a href="<?php echo $currentURL; ?>/auto/detail?id=<?php echo $rowResult['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&my=<?php echo $my; ?><?php echo $get_rsView;?>&pageNum_rsView=<?php echo $pageNum_rsView; ?>"><span class="fa fa-globe"></span> Details</a></li>
    <?php } ?>
    </ul>
</div>

<!-- social -->
<?php if (empty($my)) { ?>
<div class="social">
  <ul>
    <li class="facebook" style="width:33%;"><a href="javascript:;" onClick="fb('<?php echo $currentURL.'/auto/detail?module_id='.$colname_rsModule.'&id='.$rowResult['id']; ?>');"><span class="fa fa-facebook"></span></a></li>
    <li class="twitter" style="width:34%;"><a href="javascript:;" onClick="MM_openBrWindow('https://twitter.com/home?status=<?php echo urlencode($rowResult['title'].' at '.$currentURL.'/auto/detail?module_id='.$colname_rsModule.'&id='.$rowResult['id']); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-twitter"></span></a></li>
    <li class="google-plus" style="width:33%;"><a href="javascript:;" onClick="MM_openBrWindow('https://plus.google.com/share?url=<?php echo urlencode($currentURL.'/auto/detail?module_id='.$colname_rsModule.'&id='.$rowResult['id']); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-google-plus"></span></a></li>
  </ul>
</div>
<?php } ?>
<!-- social -->

</li>






<?php
        }
        
      ?>
    </ul>
  </div>
</div>
