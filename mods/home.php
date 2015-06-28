<?php
include(SITEDIR.'/includes/navLeftSideVars.php');
include(SITEDIR.'/libraries/rss.php');
$nearby = getNearbyCities($globalCity['nearby']);
$nm = $globalCity['city'].', '.$globalCity['statename'].', '.$globalCity['countryname'];
$pageTitle = $nm;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  currentActivity('shout_added', $_SERVER['REQUEST_URI'], $globalCity['id'], 'A New Shout is added');

  $_POST['uid'] = $_SESSION['user']['id'];
  $insertSQL = sprintf("INSERT INTO z_shout (`uid`, shout, city_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['uid'], "text"),
                       GetSQLValueString($_POST['shout'], "text"),
                       GetSQLValueString($_GET['city_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());

  mail(ADMIN_EMAIL, 'New Shout Request', 'New shout request has been created at location '.HTTPPATH.'/superadmin/manage/shout. Please visit there and approve the shout', 'From:<mkgxy@mkgalaxy.com>');
  $msg = 'Your shout is submitted successfully. Please wait for few hours till admin approves your comment.';
  $insertGoTo = $currentURL.'?shoutMsg='.urlencode($msg);
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}

currentActivity('city_visited', $_SERVER['REQUEST_URI'], $globalCity['id'], 'User has visited '.$globalCity['city'].' city');

$maxRows_rsViewShout = 25;
$pageNum_rsViewShout = 0;
if (isset($_GET['pageNum_rsViewShout'])) {
  $pageNum_rsViewShout = $_GET['pageNum_rsViewShout'];
}
$startRow_rsViewShout = $pageNum_rsViewShout * $maxRows_rsViewShout;

$colname_rsViewShout = "-1";
if (isset($_GET['city_id'])) {
  $colname_rsViewShout = $_GET['city_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsViewShout = sprintf("SELECT z_shout.*, google_auth.*, geo_cities.name as city FROM z_shout LEFT JOIN google_auth ON z_shout.uid = google_auth.uid LEFT JOIN geo_cities ON z_shout.city_id = geo_cities.cty_id WHERE z_shout.city_id IN (".$nearby[1].") AND z_shout.shout_status = 1 AND z_shout.shout_deleted = 0 ORDER BY shout_id DESC");
$query_limit_rsViewShout = sprintf("%s LIMIT %d, %d", $query_rsViewShout, $startRow_rsViewShout, $maxRows_rsViewShout);
$rsViewShout = mysql_query($query_limit_rsViewShout, $connMain) or die(mysql_error());
$row_rsViewShout = mysql_fetch_assoc($rsViewShout);

if (isset($_GET['totalRows_rsViewShout'])) {
  $totalRows_rsViewShout = $_GET['totalRows_rsViewShout'];
} else {
  $all_rsViewShout = mysql_query($query_rsViewShout);
  $totalRows_rsViewShout = mysql_num_rows($all_rsViewShout);
}
$totalPages_rsViewShout = ceil($totalRows_rsViewShout/$maxRows_rsViewShout)-1;

$queryString_rsViewShout = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsViewShout") == false && 
        stristr($param, "totalRows_rsViewShout") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsViewShout = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsViewShout = sprintf("&totalRows_rsViewShout=%d%s", $totalRows_rsViewShout, $queryString_rsViewShout);
 
$myRss = new RSSParser("http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=".urlencode($nm)."&cf=all&output=rss"); 
$itemNum=0;
$myRss_RSSmax=0;
if($myRss_RSSmax==0 || $myRss_RSSmax>count($myRss->titles))$myRss_RSSmax=count($myRss->titles);
?>
<link href="<?php echo HTTPPATH; ?>/styles/accordian.css" rel="stylesheet" type="text/css">


<h1><?php echo $pageTitle; ?></h1>

<style type="text/css">
.divRow {
  float: left;
  text-align:center;
  padding-right: 5px;
  padding-bottom: 25px;
}
</style>
<?php include(SITEDIR.'/includes/custom_ads.php'); ?>
      <?php if ($myRss_RSSmax > 0) {
                    ?>
<div class="row">
    <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $globalCity['city']; ?> News</h3>
                </div>
                <div class="panel-body" style="height:250px; overflow:scroll;">
                    <ul>
                    <?php
                    for($itemNum=0;$itemNum<$myRss_RSSmax;$itemNum++){
                        ?>
                        <li><a href="<?php echo $myRss->links[$itemNum]; ?>" target="_blank"><?php echo $myRss->titles[$itemNum]; ?></a><br>
                        <small><?php $desc = $myRss->descriptions[$itemNum]; echo strip_tags($desc, '<b><strong><br>'); /*if (isset($_GET['t'])) { echo htmlentities($myRss->descriptions[$itemNum]); }*/?></small>
                        </li>
                 <?php } ?>
                    </ul>
                </div>
              </div>
    </div>
</div>
                    <?php } ?>
<div>
<div id="mapCanvas"></div>
  <fieldset><legend><?php echo $globalCity['city']; ?></legend>
  
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=1"><img src="<?php echo HTTPPATH; ?>/images/jobs.png" alt="<?php echo $globalCity['city']; ?> Jobs" /></a><br />
    <small><?php echo $globalCity['city']; ?> Jobs</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=23"><img src="<?php echo HTTPPATH; ?>/images/business.png" alt="<?php echo $globalCity['city']; ?> Businesses" /></a><br />
    <small><?php echo $globalCity['city']; ?> Businesses</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=13"><img src="<?php echo HTTPPATH; ?>/images/dating.png" alt="<?php echo $globalCity['city']; ?> Dating" /></a><br />
    <small><?php echo $globalCity['city']; ?> Dating</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=16"><img src="<?php echo HTTPPATH; ?>/images/matrimony.png" alt="<?php echo $globalCity['city']; ?> Matrimony" /></a><br />
    <small><?php echo $globalCity['city']; ?> Matrimony</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=8"><img src="<?php echo HTTPPATH; ?>/images/buysell.png" alt="<?php echo $globalCity['city']; ?> Buy/Sell" /></a><br />
    <small><?php echo $globalCity['city']; ?> Buy/Sell</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=15"><img src="<?php echo HTTPPATH; ?>/images/entertainment.png" alt="<?php echo $globalCity['city']; ?> Entertainment" /></a><br />
    <small><?php echo $globalCity['city']; ?> Entertainment</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=17"><img src="<?php echo HTTPPATH; ?>/images/tour.png" alt="<?php echo $globalCity['city']; ?> Tour" /></a><br />
    <small><?php echo $globalCity['city']; ?> Tour</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=6"><img src="<?php echo HTTPPATH; ?>/images/services.png" alt="<?php echo $globalCity['city']; ?> Services" /></a><br />
    <small><?php echo $globalCity['city']; ?> Services</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=7"><img src="<?php echo HTTPPATH; ?>/images/freelance.png" alt="<?php echo $globalCity['city']; ?> Jobs" /></a><br />
    <small><?php echo $globalCity['city']; ?> Freelance</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=12"><img src="<?php echo HTTPPATH; ?>/images/housing.png" alt="<?php echo $globalCity['city']; ?> Rentals" /></a><br />
    <small><?php echo $globalCity['city']; ?> Rentals</small>
  </div>
  </fieldset>
  <fieldset><legend>General</legend>
  
  <div class="divRow">
    <a href="<?php echo HTTPPATH; ?>/users/settings"><img src="<?php echo HTTPPATH; ?>/images/settings.png" alt="Settings" /></a><br />
    <small>Settings</small>
  </div>
  <div class="divRow">
    <a href="<?php echo HTTPPATH; ?>/horo/main"><img src="<?php echo HTTPPATH; ?>/images/horomatch.png" alt="Settings" /></a><br />
    <small>Horoscope Matching</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/my?module_id=19"><img src="<?php echo HTTPPATH; ?>/images/lifereminder.png" alt="Life Reminder" /></a><br />
    <small>Life Reminder</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/my?module_id=27"><img src="<?php echo HTTPPATH; ?>/images/history.png" alt="Personal History" /></a><br />
    <small>Personal History</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=14"><img src="<?php echo HTTPPATH; ?>/images/howto.png" alt="HowTo" /></a><br />
    <small>HowTo</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=20"><img src="<?php echo HTTPPATH; ?>/images/notes.png" alt="Notes" /></a><br />
    <small>Notes</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=21"><img src="<?php echo HTTPPATH; ?>/images/key.png" alt="Passwords" /></a><br />
    <small>Passwords</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/browse?module_id=24"><img src="<?php echo HTTPPATH; ?>/images/mastercard.png" alt="Credit Cards" /></a><br />
    <small>Credit Cards</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/my?module_id=3"><img src="<?php echo HTTPPATH; ?>/images/diabetes.jpg" alt="Diabetes Monitoring" /></a><br />
    <small>Diabetes Monitoring</small>
  </div>
  <div class="divRow">
    <a href="<?php echo $currentURL; ?>/auto/my?module_id=29"><img src="<?php echo HTTPPATH; ?>/images/bloodpressure.jpg" alt="Blood Pressure Monitoring" /></a><br />
    <small>Blood Pressure Monitoring</small>
  </div>
  </fieldset>
</div>
<script language="javascript">
function valdiateHomeForm(user)
{
  if (!user) {
    alert('please login first to submit this?');
    return false;
  }
  if (!$('#shout').val()) {
    alert('please enter some value in shout box?');
    return false;
  }
  return true;
}
</script>
<div class="row">
    <div class="col-lg-12">
      <form action="" id="form1" name="form1" method="POST" onSubmit="return valdiateHomeForm('<?php echo (!empty($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : ''; ?>');">
        <p><label for="shout">Shout:</label><br />
      <?php if (!empty($_GET['shoutMsg'])) { ?>
      <div class="error"><?php echo $_GET['shoutMsg']; ?></div>
      <?php } ?>
        <input type="text" name="shout" id="shout" style="width:100%" placeholder="Say anything about people, place, business in this city..."></p>
        <p>
          <input type="submit" name="submit" id="submit" value="Submit" style="width:100%">
          <input type="hidden" name="uid" id="uid" value="" />
        </p>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
    </div>
</div>

<?php if ($totalRows_rsViewShout > 0) { // Show if recordset not empty ?>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
      <div class="list-group">
        <?php do { ?>
          <div class="list-group-item">
            <h4 class="list-group-item-heading"><?php echo $row_rsViewShout['shout']; ?></h4>
            <p class="list-group-item-text" style="font-size:11px; float:right;">-By <strong><?php echo $row_rsViewShout['name']; ?></strong></p>
            <p class="list-group-item-text" style="font-size:11px;">Created on <strong><?php echo ago(strtotime($row_rsViewShout['shout_dt'])); ?></strong></p>
            <div style="clear:both;"></div>
            <p class="list-group-item-text" style="font-size:11px;">City: <strong><?php echo $row_rsViewShout['city']; ?></strong></p>
            <div style="clear:both;"></div>
            </div>
          <?php } while ($row_rsViewShout = mysql_fetch_assoc($rsViewShout)); ?>
      </div>
    </div>
  </div>

    <div class="col-lg-12">
      <ul class="pager">
        <?php if ($pageNum_rsViewShout > 0) { // Show if not first page ?>
        <li class="previous"><a href="<?php echo $currentURL; ?>?<?php printf("pageNum_rsViewShout=%d&totalRows_rsViewShout=%d", max(0, $pageNum_rsViewShout - 1), $totalRows_rsViewShout); ?>">&larr; Newer</a></li>
        <?php } ?>
        <?php if ($pageNum_rsViewShout < $totalPages_rsViewShout) { // Show if not last page  ?>
        <li class="next"><a href="<?php echo $currentURL; ?>?<?php printf("pageNum_rsViewShout=%d&totalRows_rsViewShout=%d", min($totalPages_rsViewShout, $pageNum_rsViewShout + 1), $totalRows_rsViewShout); ?>">Older &rarr;</a></li>
        <?php } ?>
      </ul>
    </div>
</div>

<?php } // Show if recordset not empty ?>


<!--<div class="row">
    <div class="col-lg-12">
    <div id="st-accordion" class="st-accordion">
    <ul>
        <li>
            <a href="#">Coming soon<span class="st-arrow">Open or Close</span></a>
            <div class="st-content">
                <a href="<?php echo $currentURL; ?>" class="btn btn-primary">Browse</a>
            </div>
        </li>
    </ul>
      </div>
    </div>
</div>-->
<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo $globalCity['latitude']; ?>';
var longitude = '<?php echo $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>
<script type="text/javascript" src="<?php echo HTTPPATH; ?>/scripts/accordian/jquery.accordion.js"></script>
<script type="text/javascript" src="<?php echo HTTPPATH; ?>/scripts/accordian/jquery.easing.1.3.js"></script>
<?php
mysql_free_result($rsViewShout);
?>
<script type="text/javascript">
$(function() {
  $('#st-accordion').accordion();
});
</script>
<script type="text/javascript">
//<![CDATA[
  (function() {
    var shr = document.createElement('script');
    shr.setAttribute('data-cfasync', 'false');
    shr.src = '//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js';
    shr.type = 'text/javascript'; shr.async = 'true';
    shr.onload = shr.onreadystatechange = function() {
      var rs = this.readyState;
      if (rs && rs != 'complete' && rs != 'loaded') return;
      var site_id = '1b65f721e5c0648ac772cc8327a63173';
      try { Shareaholic.init(site_id); } catch (e) {}
    };
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(shr, s);
  })();
//]]>
</script>
