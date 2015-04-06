<?php
         
         if (!empty($rowResult['history_image'])) {
            $images = json_decode($rowResult['history_image'], 1);
            $image = !empty($images[0]) ? $images[0] : $rowResult['picture'];
          } else {
            $image = $rowResult['picture'];
          }
          $tmp = explode(' ', $rowResult['history_date']);
          $date = $tmp[0];
          $time = $tmp[1];
          $tmp = explode('-', $date);
          $year = $tmp[0];
          $month = $tmp[1];
          $day = $tmp[2];
          ?>
          <li>
            <time datetime="<?php echo $rowResult['history_date']; ?>">
              <span class="day"><?php echo $day; ?></span>
              <span class="month"><?php echo monthString($month); ?></span>
              <span class="year"><?php echo $year; ?></span>
              <span class="time"><?php echo $time; ?></span>
            </time>
            <img src="<?php echo $image; ?>" />
            <div class="info">
              <h2 class="title"><?php echo $rowResult['history_title']; ?></h2>
              <p class="desc" style="font-size:11px"><strong>Date: </strong><?php echo $rowResult['history_date']; ?><br>
              <?php echo !empty($rowResult['address2']) ? $rowResult['address2'] : $rowResult['address']; ?><br>
              By <?php echo $rowResult['name']; ?><br>
              <?php if (!empty($rowResult['history_points'])) { ?>
                    <strong>Points *:</strong> <?php echo $rowResult['history_points']; ?> (<?php echo $rowResult['history_points_results']; ?>)
                    <?php } else if (!empty($result[$rowResult['history_id']]['points'])) { ?>
                    <strong>Points:</strong> <?php echo $result[$rowResult['history_id']]['points']['points']; ?> (<?php echo $result[$rowResult['history_id']]['points']['result']; ?>)
                    <?php } ?> 
                    <i><?php echo ($rowResult['is_private'] == 1) ? 'Private' : 'Public'; ?></i> 
                    <i><?php echo ($rowResult['history_status'] == 1) ? '' : '(InActive)'; ?></i>
                    <i><?php echo ($rowResult['history_approved'] == 1) ? '' : '(Approval Pending)'; ?></i>
                    </p>
                    <?php if (!empty($rowResult['showAddress'])) { ?>
              <script language="javascript">
                    var latlng = new google.maps.LatLng(<?php echo $rowResult['history_lat']; ?>, <?php echo $rowResult['history_lng']; ?>);
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        title: '<?php echo $rowResult['history_title']; ?>'
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent('<h3><?php echo $rowResult['history_title']; ?></h3><div><?php echo $rowResult['history_description']; ?></div><p style="font-size:11px"><strong>Date: </strong><?php echo $rowResult['history_date']; ?></p><p style="font-size:11px"><?php echo $rowResult['address']; ?></p><p style="font-size:11px">By <a href="<?php echo $rowResult['link']; ?>" rel="nofollow" target="_blank"><?php echo $rowResult['name']; ?></a></p>');
                        infowindow.open(map, this);
                    });
                    </script>
                    <?php } ?>
              <ul>
                <li style="width:33%;"><a href="<?php echo $currentURL; ?>/history/detail?id=<?php echo $rowResult['history_id']; ?>"><span class="fa fa-globe"></span> Details</a></li>
                <?php if (!empty($_SESSION['user']['id']) && $_SESSION['user']['id'] == $rowResult['uid']) { ?>
                <li style="width:33%;"><a href="<?php echo $currentURL; ?>/history/edit?id=<?php echo $rowResult['history_id']; ?>"><span class="fa fa-globe"></span> Edit</a></li>
                <li style="width:33%;"><a href="<?php echo $currentURL; ?>/history/delete?id=<?php echo $rowResult['history_id']; ?>"><span class="fa fa-times"></span> Delete</a></li>
                <?php } ?>
              </ul>
            </div>
            <div class="social">
              <ul>
                <?php $detailURL = $currentURL.'/history/detail?id='.$rowResult['history_id']; ?>
                <li class="facebook" style="width:33%;"><a href="javascript:;" onClick="fb('<?php echo $currentURL; ?>/history/detail?id=<?php echo $rowResult['history_id']; ?>');"><span class="fa fa-facebook"></span></a></li>
                <li class="twitter" style="width:34%;"><a href="javascript:;" onClick="MM_openBrWindow('https://twitter.com/home?status=<?php echo urlencode($rowResult['history_title'].' at '.$detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-twitter"></span></a></li>
                <li class="google-plus" style="width:33%;"><a href="javascript:;" onClick="MM_openBrWindow('https://plus.google.com/share?url=<?php echo urlencode($detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')"><span class="fa fa-google-plus"></span></a></li>
              </ul>
            </div>
          </li>