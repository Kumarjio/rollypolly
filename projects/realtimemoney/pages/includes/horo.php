<?php $check = (!empty($returnSettings['bday']) && !empty($returnSettings['bmonth']) && !empty($returnSettings['byear']) && !empty($returnSettings['btime']) && !empty($returnSettings['blatitude']) && !empty($returnSettings['blongitude'])); ?>
<?php if ($check) { ?>
<?php
include_once(SITEDIR.'/Kundali.class.php');
$kundali = new Kundali;

if (!empty($_POST['MM_UpdateLocation'])) {
    $arr = array();
    $arr['currentLocation'] = $_POST['currentLocation'];
    $arr['currentLocationLatitude'] = $_POST['currentLocationLatitude'];
    $arr['currentLocationLongitude'] = $_POST['currentLocationLongitude'];
    $arr['details'] = getXtraDetails($arr['currentLocationLatitude'], $arr['currentLocationLongitude']);
    $data = array();
    $data['currentLocation'] = json_encode($arr); 
    $where = sprintf("user_id = %s", $General->qstr($_SESSION['user']['user_id']));
    $General->updateDetails('dcomerce_settings', $data, $where);
    clearUserSettings($General);
    $returnSettings = getUserSettings($General);
}

$currentLocation = array('currentLocation' => '', 'currentLocationLatitude' => '', 'currentLocationLongitude' => '');
if (!empty($returnSettings['currentLocation'])) {
    $currentLocation = json_decode($returnSettings['currentLocation'], true);
}

$placeLocation = array();
if (!empty($returnSettings['placeLocation'])) {
    $placeLocation = json_decode($returnSettings['placeLocation'], true);
    $tmp = explode(':', $returnSettings['btime']);
    $hour = $tmp[0];
    $minute = $tmp[1];
    $placeLocation['horo'] = findHoroInfo($kundali, $returnSettings['bmonth'], $returnSettings['bday'], $returnSettings['byear'], $hour, $minute, $placeLocation['location']);
}

$horoData = array();
$horoDataToday = array();
if (!empty($currentLocation['details']['location']) && !empty($placeLocation['horo'])) {
    for ($i = -5; $i < 12; $i++) {
        $date = strtotime("+$i day");
        $tmp = array();
        $tmp = findHoroInfo($kundali, date('m', $date), date('d', $date), date('Y', $date), 12, 0, $currentLocation['details']['location']);
        $tmp['points'] = $kundali->getpoints($placeLocation['horo'][9], $tmp[9]);
        $tmp['results'] = $kundali->interpret($tmp['points']);
        $horoData[] = $tmp;
        if (date('m') == date('m', $date) && date('d') == date('d', $date) && date('Y') == date('Y', $date)) {
            for ($j = 0; $j < 24; $j++) {
                $tmp = array();
                $tmp = findHoroInfo($kundali, date('m', $date), date('d', $date), date('Y', $date), $j, 0, $currentLocation['details']['location']);
                $tmp['points'] = $kundali->getpoints($placeLocation['horo'][9], $tmp[9]);
                $tmp['results'] = $kundali->interpret($tmp['points']);
                $horoDataToday[] = $tmp;
            }
        }
    }
}


?>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                How's My Future
            </div>
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#horo_home" data-toggle="tab">Home</a>
                    </li>
                    <li><a href="#horo_location" data-toggle="tab">Location</a>
                    </li>
                </ul>
            
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="horo_home" style="font-size:11px; font-family:Verdana;">
                            <br />
                        <?php if (!empty($currentLocation['currentLocation'])) { ?>
                            <strong>Location:</strong> <?php echo $currentLocation['currentLocation']; ?>
                            <br /><br />
                            <?php if (!empty($horoData)) { ?>
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-bar-chart"></div>
                            </div>
                            <br /><br />
                            <h3>Today Hourly</h3>
                            <?php foreach ($horoDataToday as $k => $v) { ?>
                            <div id="today_<?php echo date("Y_m_d_h_i", strtotime($v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute'])); ?>"><strong>Date:</strong> <?php echo date("D M j G:i Y", strtotime($v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute'])); //echo $v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute']; ?>, <strong>Points:</strong> <?php echo $v['points']; ?> (<?php echo $v['results']; ?>)</div>
                            <?php } ?>
                            <br /><br />
                            <h3>Daily</h3>
                            <script language="javascript">
                                var horoData = new Array();
                            </script>
                            <?php foreach ($horoData as $k => $v) { ?>
                            <strong>Date:</strong> <?php echo date("D M j G:i Y", strtotime($v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute'])); //echo $v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute']; ?>, <strong>Points:</strong> <?php echo $v['points']; ?> (<?php echo $v['results']; ?>)<br />
                            <script language="javascript">
                                var d = new Date("<?php echo $v[12]['year'].'-'.$v[12]['month'].'-'.$v[12]['day'].' '.$v[12]['hour'].':'.$v[12]['minute']; ?>");
                                var n = d.toString();
                                var tmp = new Array();
                                tmp[0] = d.getTime();
                                tmp[1] = parseInt("<?php echo $v['points']; ?>");
                                horoData.push(tmp);
                            </script>
                            <?php } ?>
    <!-- Flot Charts JavaScript -->
    <script src="bower_components/flot/excanvas.min.js"></script>
    <script src="bower_components/flot/jquery.flot.js"></script>
    <script src="bower_components/flot/jquery.flot.pie.js"></script>
    <script src="bower_components/flot/jquery.flot.resize.js"></script>
    <script src="bower_components/flot/jquery.flot.time.js"></script>
    <script src="bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script language="javascript">
    // JavaScript Document

//Flot Bar Chart

$(function() {

    var barOptions = {
        series: {
            bars: {
                show: true,
                barWidth: 43200000
            }
        },
        xaxis: {
            mode: "time",
            timeformat: "%m/%d",
            minTickSize: [1, "day"]
        },
        grid: {
            hoverable: true
        },
        legend: {
            show: true
        },
        tooltip: true,
        tooltipOpts: {
            content: "x: %x, y: %y"
        }
    };
    var barData = {
        label: "bar",
        data: horoData
    };
    $.plot($("#flot-bar-chart"), [barData], barOptions);

});
    </script>
                            <?php } ?>
                        <?php } else { ?>
                        <p><strong>Note:</strong> Update location in location tab to see the latest points.</p>
                        <?php } ?>
                    </div>
                    <div class="tab-pane fade" id="horo_location">
                        <form role="form" method="post" name="formHoroLocation">
                            <br />
                            
                            <div class="form-group">
                                <input class="form-control addressBox" type="text" name="currentLocation" id="currentLocation" placeholder="Enter Current Location" value="<?php echo $currentLocation['currentLocation']; ?>">
                                <input type="hidden" value="<?php echo $currentLocation['currentLocationLatitude']; ?>" name="currentLocationLatitude" id="currentLocationLatitude" />
                                <input type="hidden" value="<?php echo $currentLocation['currentLocationLongitude']; ?>" name="currentLocationLongitude" id="currentLocationLongitude" />
                            </div>
                            <script language="javascript">
                                initializeHome();
                            </script>
                            
                            <div class="form-group">
                                <input type="hidden" value="1" name="MM_UpdateLocation" id="MM_UpdateLocation" />
                                <input class="btn btn-primary btn-lg btn-block" type="submit" name="updateHoro" id="updateHoro" value="Update">
                            </div>
                        </form>
                        <!-- end form group -->
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
<?php } ?>