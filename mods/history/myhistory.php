<?php
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'My History';
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
$layoutStructure = 'timeline';
?>
<?php
$searchTerm = '';
if (!empty($_GET['keyword'])) {
  $searchTerm .= "AND (z_history.history_title LIKE '%".$_GET['keyword']."%' OR z_history.history_description LIKE '%".$_GET['keyword']."%')";
}
$action = !empty($_GET['action']) ? $_GET['action'] : '';

  switch ($action) {
    case 'all':
      $action = 'Show All History';
      break;
    case 'private':
      $action = 'Show Private History';
      $searchTerm .= ' AND z_history.is_private = 1';
      break;
    case 'active':
      $action = 'Show Active History';
      $searchTerm .= ' AND z_history.history_status = 1';
      break;
    case 'inactive':
      $action = 'Show InActive History';
      $searchTerm .= ' AND z_history.history_status = 0';
      break;
    case 'approved':
      $action = 'Show Approved History';
      $searchTerm .= ' AND z_history.history_approved = 1';
      break;
    case 'unapproved':
      $action = 'Show UnApproved History';
      $searchTerm .= ' AND z_history.history_approved = 0';
      break;
    default:
      $searchTerm .= ' AND z_history.is_private = 0';
      break;
  }
        
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsView = 10;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT z_history.*, google_auth.email, google_auth.gender, google_auth.name, google_auth.picture, google_auth.link FROM z_history LEFT JOIN google_auth ON z_history.uid = google_auth.uid WHERE z_history.uid = ".$modelGeneral->qstr($_SESSION['user']['id'])." $searchTerm ORDER BY z_history.history_date DESC";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$colname_rsSettings = "-1";
if (isset($_SESSION['user']['id'])) {
  $colname_rsSettings = $_SESSION['user']['id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsSettings = sprintf("SELECT * FROM settings LEFT JOIN geo_cities ON settings.birth_city_id = geo_cities.cty_id WHERE settings.`uid` = %s", GetSQLValueString($colname_rsSettings, "text"));
$rsSettings = mysql_query($query_rsSettings, $connMain) or die(mysql_error());
$row_rsSettings = mysql_fetch_assoc($rsSettings);
$totalRows_rsSettings = mysql_num_rows($rsSettings);

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
//getString
$get_rsView = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsView);
//getString Ends

$result = array();
if ($totalRows_rsView > 0) {
  $data = array();
  do {
    if (empty($row_rsSettings['latitude']) || empty($row_rsSettings['longitude']) || empty($row_rsSettings['dob'])) {
      continue;
    }
    if (!empty($row_rsView['history_points'])) {
      continue;
    }
    $params = array();
    $params['id'] = $row_rsView['history_id'];
    $params['fromlat'] = $row_rsSettings['latitude'];
    $params['fromlng'] = $row_rsSettings['longitude'];
    $params['fromdate'] = $row_rsSettings['dob'];
    $params['tolat'] = $row_rsView['history_lat'];
    $params['tolng'] = $row_rsView['history_lng'];
    $params['todate'] = $row_rsView['history_date'];
    $data['params'][] = $params;
  } while ($row_rsView = mysql_fetch_assoc($rsView));
  $rows = mysql_num_rows($rsView);
  if($rows > 0) {
      mysql_data_seek($rsView, 0);
	  $row_rsView = mysql_fetch_assoc($rsView);
  }
  if (!empty($data['params'])) {
    $content = curlget('http://horo.mkgalaxy.com/api/matchMultiLatLonDays', 1, http_build_query($data));
    $result = json_decode($content, 1);
    if (!empty($result) && empty($result['error'])) {
      foreach ($result as $k => $v) {
        $d = array();
        $d['history_points'] = $v['points']['points'];
        $d['history_points_results'] = $v['points']['result'];
        $where = sprintf('history_id = %s', $modelGeneral->qstr($k));
        $modelGeneral->updateDetails('z_history', $d, $where);
      }
    }
  }
}
?>
<div class="page-header">
  <h1>My History</h1>
  <form name="frmSearch" id="frmSearch" action="" method="get">
    <label for="keyword">Keyword:</label>
    <input type="text" name="keyword" id="keyword" value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" style="width:90%"><br /><br />
    <label for="address">Near:</label>
    <input id="address" name="address" placeholder="Enter your address" value=""
                                       onFocus="geolocate()" type="text" style="width:94%"><br /><br />
    <input type="submit" name="submit" id="submit" value="Submit" style="width: 100%">
    <input type="hidden" name="lat" id="lat" value="" />
    <input type="hidden" name="lng" id="lng" value="" />
    <input type="hidden" name="action" id="action" value="all" />
    <input type="hidden" name="radius" id="radius" value="<?php echo isset($_GET['radius']) ? $_GET['radius'] : '30'; ?>" />
  </form>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="dropdown">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
        <?php if (!empty($action)) {
          echo $action;
        } else { ?>
        Actions
        <?php } ?>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory">Show Public History</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=private">Show Private History</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=all">Show Public/Private History</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=active">Show Active History</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=inactive">Show Inactive History</a></li>
        <li role="presentation" class="divider"></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=approved">Show Approved History</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory?action=unapproved">Show UnApproved History</a></li>
      </ul>
    </div>
  </div>
</div>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <div class="row">
    <div class="[ col-xs-12 col-sm-offset-0 col-sm-12 ]">
      <form action="" method="post" name="form1" id="form1">
      <ul class="event-list">
        
          <?php do { 
          $rowResult = $row_rsView;
          include('inc_content.php');
          } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
        </ul>
        </form>
      </div>
    </div>
<p>Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?>
  </p>
  <ul class="pager">
    <?php if ($pageNum_rsView > 0) { // Show if not first page ?>
    <li class="previous"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/history/myhistory', max(0, $pageNum_rsView - 1), $get_rsView); ?>">&larr; Previous</a></li>
    <?php } // Show if not first page ?>
    <?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
    <li class="next"><a href="<?php printf("%s?pageNum_rsView=%d%s", $currentURL.'/history/myhistory', min($totalPages_rsView, $pageNum_rsView + 1), $get_rsView); ?>">Next &rarr;</a></li>
    <?php } // Show if not last page ?>
  </ul>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<div>No History Data Found.</div>
<?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsView);

mysql_free_result($rsSettings);
?>