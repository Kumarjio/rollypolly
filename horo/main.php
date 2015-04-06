<?php
//check_login();
//include(SITEDIR.'/libraries/addresses/nearby.php');
$uid = '';
$uid = !empty($_GET['uid']) ? $_GET['uid'] : (!empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '');
if (empty($uid)) {
  check_login();
}
currentActivity('horo_page', $_SERVER['REQUEST_URI'], null, 'User has browsed the Horo Match page.');

$Models_Googleauth = new Models_Googleauth();
$userDetails = $Models_Googleauth->getUser($uid);
$userSql = $Models_Googleauth->sql;

//matching naks
$Kundali = new Library_Kundali();
$horo = new Models_Horo();
$userHoroInfo = findHoroInfo($userDetails);
if (!empty($userHoroInfo)) {
  $points = $Kundali->points();
  $specific_points = $points[$userHoroInfo[9]];
  arsort($specific_points);
  $matchingNaks = array();
  foreach ($specific_points as $k => $v) {
    $matchingNaks[$k]['number'] = $k;
    $matchingNaks[$k]['nakshatra'] = $Kundali->getnaksfromnumber($k);
    $matchingNaks[$k]['points'] = $v;
  }
}
//matching naks ends
$horo_cities = array();
if (!empty($userDetails['horo_cities'])) {
  $horo_cities = json_decode($userDetails['horo_cities'], 1);
}

$pageTitle = 'Horo Match Making';
if (!empty($userDetails['fullname'])) {
  $pageTitle .= ' :: '.$userDetails['fullname'];
}

if (!empty($userDetails) && !empty($uid) && !empty($_GET['city_id']) && !empty($_GET['from_date']) && !empty($_GET['no_days'])) {
  //$horo = new Models_Horo();
  $city = findCity($_GET['city_id']);
  //clearing user sql
  $modelGeneral->clearCache($userSql);
  //updating the city
  if ($uid == $_SESSION['user']['id']) {
      $data = array();
      $md5Check = md5($userDetails['horo_cities']);
      $horo_cities[$city['id']] = $city['name'];
      $data['horo_cities'] = json_encode($horo_cities);
      $md5Check2 = md5($data['horo_cities']);
      if ($md5Check != $md5Check2) {
        updateSettings($_SESSION['user']['id'], $data);
      }
  }
  $fromDate = getDateTime($_GET['from_date']);
  $date = $fromDate['year'].'-'.$fromDate['month'].'-'.$fromDate['day'];
  $past = '';
  $returnResult = array();
  for($counter = 0; $counter < $_GET['no_days']; $counter++) {
      $day = date('d', strtotime("$date +$counter day"));
      $month = date('m', strtotime("$date +$counter day"));
      $year = date('Y', strtotime("$date +$counter day"));
      for ($j = 0; $j < 24; $j = $j + 1) {
        $hour = $j;
        $dob = $year.'-'.$month.'-'.$day.' '.$hour.':00:00';
        $data2 = array_merge($city, array('dob' => $dob));
        $result = $horo->match($userDetails, $data2);
        if ($past == $result[1][9]) {
            continue;
        } else {
            $past = $result[1][9];
        }
        $returnResult[] = array('date' => $dob, 'result' => $result);
        
      }
  }
}

function chooseClass($pts) {
  $class = array('bg-success', 'bg-primary', 'bg-secondary', 'bg-info', 'bg-warning');
  if ($pts < 11) {
			return $class[4];
		} else if ($pts < 18) {
			return $class[3];
		} else if ($pts < 25) {
			return $class[2];
		} else if ($pts < 31) {
			return $class[1];
		} else if ($pts < 36) {
			return $class[0];
		}
}

asort($horo_cities);
$currentPoints = array();
if (!empty($horo_cities)) {
  foreach ($horo_cities as $k => $v) {
    $d = findCity($k);
    $d['dob'] = date('Y-m-d H:i:s');
    $info = findHoroInfo($d);
    $currentPoints[$k] = array('points' => $specific_points[$info[9]], 'nakshatra' => $info['7']);
  }
}
?>

<!-- Date time picker -->
<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/styles/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.datetimepicker.js"></script>
<!-- Date time picker -->

<div class="jumbotron">
  <h3><?php echo $pageTitle; ?></h3>
  <p>Match your horo with any other person or any day in your life.</p>
  <?php if (empty($userDetails['birth_city_id']) && empty($userDetails['dob'])) { ?>
  <p><strong>Requirements:</strong> Put your birth details in <a href="<?php echo HTTPPATH; ?>/users/settings">settings</a> tab</p>
  <?php } ?>
</div>
<?php if (!empty($userDetails['birth_city_id']) && !empty($userDetails['dob'])) { 
  if (!empty($_GET['uid']) && $_SESSION['user']['uid'] != $_GET['uid'] && $userDetails['share_horo'] == 0) {
    echo 'still pending code';
  }
?>
<div class="row">
  <div class="col-lg-12">
    <?php include(SITEDIR.'/includes/user.php'); ?>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
  </div>
  <div class="col-lg-6">
    <div class="social pull-right">
                <?php $detailURL = HTTPPATH.$_SERVER['REQUEST_URI'];
                ?>
                <a href="javascript:;" onClick="fb('<?php echo $detailURL; ?>');">Facebook Share</a> | 
                <a href="javascript:;" onClick="MM_openBrWindow('https://twitter.com/home?status=<?php echo urlencode($pageTitle.' at '.$detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')">Twitter</a> | 
                <a href="javascript:;" onClick="MM_openBrWindow('https://plus.google.com/share?url=<?php echo urlencode($detailURL); ?>','twitter','location=yes,status=yes,scrollbars=yes,resizable=yes,width=400,height=300')">Google+</a>
            </div>
  </div>
</div>
<script language="javascript">
function updateVal(id, val) {
  $('#city').val(val);
  $('#city_id').val(id);
}
</script>
<div class="row">
  <div class="col-lg-8">
    <div class="page-header">
      <h2>Match Horo</h2>
    </div>
      <form name="form1" id="form1" method="get" action="">
          <div class="form-group">
                  <strong>From Date:</strong> <br />
                  <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo!empty($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d H:i'); ?>" placeholder="Enter From Date" />
          </div>
          <div class="form-group">
                  <strong>Number of Days:</strong> <br />
                  <input type="text" name="no_days" id="no_days" class="form-control" value="<?php echo!empty($_GET['no_days']) ? $_GET['no_days'] : 5; ?>" placeholder="Enter Number of Days" />
          </div>
          <div class="form-group">
                  <strong>City:</strong> <br />
                  <?php include(SITEDIR.'/locations/searchcity.inc.php'); ?>
          </div>
          <p>
          <input type="hidden" name="uid" id="uid" value="<?php echo $uid; ?>" />
  <input type="submit" name="submit" id="submit" value="Analyse" class="inputText">
</p>
      </form>
      <?php if (!empty($returnResult)) { ?>
      <div>
<link href="<?php echo HTTPPATH; ?>/styles/single_column_timeline_dotted.css" rel="stylesheet" type="text/css">
      <div class="row">
    
        <div class="timeline-centered">
        <?php foreach ($returnResult as $key => $value) { ?>
        <article class="timeline-entry">

            <div class="timeline-entry-inner">

                <div class="timeline-icon <?php echo chooseClass($value['result'][2]['points']); ?>">
                    <i class="entypo-feather"></i>
                </div>

                <div class="timeline-label">
                    <h2><?php echo date('D F j, Y g:i a', strtotime($value['date'])); ?></h2>
                    <p><strong>Points:</strong> <?php echo $value['result'][2]['points']; ?> (<?php echo $value['result'][2]['result']; ?>)</p>
                    <p><strong>Nakshatra:</strong> <?php echo $value['result'][1][7]; ?></p>
                </div>
            </div>

        </article>
        <?php } ?>
    </div>

    
	</div>
      </div>
      <?php } ?>
  </div>
  <div class="col-lg-4">
    <div class="page-header">
      <h3>Current Time</h3>
      <ul><li><?php echo date('Y-m-d H:i:s'); ?></li></ul>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="page-header">
      <h3>Cities</h3>
      <?php if (!empty($horo_cities)) { 
      ?>
      <ul>
        <?php foreach ($horo_cities as $cityID => $cityName) { ?>
        <li><a href="javascript:;" onClick="updateVal('<?php echo $cityID; ?>', '<?php echo $cityName; ?>');"><?php echo $cityName; ?></a><br />
          <strong>Current Matching Points:</strong> <?php echo !empty($currentPoints[$cityID]['points']) ? $currentPoints[$cityID]['points'] : ''; ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <div class="page-header">
      <h3>Best Matches</h3>
      <ul>
        <?php foreach ($matchingNaks as $naks) { ?>
        <li><?php echo $naks['nakshatra']; ?> (Pts: <?php echo $naks['points']; ?>)</li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>

<script language="javascript">
  //date time picker
  $('#from_date').datetimepicker({
    format:'Y-m-d H:i',
  });
  //date time picker
</script>
<?php } ?>