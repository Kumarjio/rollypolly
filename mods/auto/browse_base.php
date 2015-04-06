<?php

  if (!empty($_GET['my'])) {
    $my = true;
  }
if (empty($my)) {
  $my = false;
}

if (!empty($my)) {
  check_login();
}

include(SITEDIR.'/includes/navLeftSideVars.php');

if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}


$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$t = (3600*24);

$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}

if ($resultModule['module_status'] == 0) {
  throw new Exception('module is inactive');
}

if ($resultModule['browse_page'] == 0 && !$my) {
  if ($resultModule['my_page'] == 1) {
    header("Location: ".$currentURL."/auto/my?module_id=".$colname_rsModule);
    exit;
  }
  throw new Exception('browse page is not accessible');
}

if ($resultModule['my_page'] == 0 && $my) {
  if ($resultModule['browse_page'] == 1) {
    header("Location: ".$currentURL."/auto/browse?module_id=".$colname_rsModule);
    exit;
  }
  throw new Exception('my page is not accessible');
}


//pr($resultModule);
$layoutStructure = 'autoTimeline';
$tablename = 'auto_'.$resultModule['module_name'];

$query = "SELECT * FROM z_modules_fields WHERE module_id = ? ORDER BY sorting ASC";
$resultModuleFields = $modelGeneral->fetchAll($query, array($colname_rsModule), $t);
if (empty($resultModuleFields)) {
  throw new Exception('Could not find the module fields');
}
//pr($resultModuleFields);

//getting records
$mutilselectFrom = '';
$locationBox = false;
$cacheTime = 300;
$maxRows_rsView = $resultModule['default_max_rows'];
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;
$distanceFrom = '';
$distanceWhere = '';
$searchCriteria = '';
if (!empty($my)) {
  $cacheTime = 0;
  $searchCriteria .= ' AND a.rc_deleted = 0 AND a.uid = '.$modelGeneral->qstr($_SESSION['user']['id']);
} else {
    $searchCriteria .= ' AND a.rc_approved = 1 AND a.rc_status = 1 AND a.rc_deleted = 0';
}

$orderBy = 'ORDER BY a.'.$resultModule['default_sorting_field'].' '.$resultModule['default_sorting_type'];

//image field name
$imageFieldName = 'images';
//image field name ends

$resultModuleFields2 = array();
foreach ($resultModuleFields as $k => $v) {
  $resultModuleFields2[$v['field_name']] = $v;
}

foreach ($resultModuleFields as $k => $v) {
  //image field name
  if ($v['field_type'] === 'images') {
    $imageFieldName = $v['field_name'];
  }
  //searchable
  if ($v['searchable'] == 0) {
    continue;
  }
  if ($v['field_type'] == 'double' || $v['field_type'] == 'int') {
    if (!empty($_GET[$v['field_name']]['min'])) {
      $value = $_GET[$v['field_name']]['min'];
      $searchCriteria .= ' AND a.'.$v['field_name'].' >= '.GetSQLValueString($value, 'double');
    }
    if (!empty($_GET[$v['field_name']]['max'])) {
      $value = $_GET[$v['field_name']]['max'];
      $searchCriteria .= ' AND a.'.$v['field_name'].' <= '.GetSQLValueString($value, 'double');
    }
  } else if ($v['field_type'] == 'addressbox') {
    $locationBox = true;
    if (empty($_GET['radius'])) {
      $_GET['radius'] = 30;
    }
    if (empty($_GET['lat']) && empty($_GET['lng'])) {
      $_GET['lat'] = $globalCity['latitude'];
      $_GET['lng'] = $globalCity['longitude'];
    }
    $latitude = $_GET['lat'];
    $longitude = $_GET['lng'];
    $orderBy = ' ORDER BY distance ASC, a.rc_created_dt DESC';
    $distanceFrom = ", (ROUND(DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * SIN(RADIANS(a.clatitude)) + COS(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * COS(RADIANS(a.clatitude)) * COS(RADIANS(".GetSQLValueString($_GET['lng'], 'double')." -(a.clongitude)))))*60*1.1515,2)) as distance";
    if (empty($_GET['wholeworld'])) {
      $distanceWhere = " AND (ROUND(DEGREES(ACOS(SIN(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * SIN(RADIANS(a.clatitude)) + COS(RADIANS(".GetSQLValueString($_GET['lat'], 'double').")) * COS(RADIANS(a.clatitude)) * COS(RADIANS(".GetSQLValueString($_GET['lng'], 'double')." -(a.clongitude)))))*60*1.1515,2)) <= ".GetSQLValueString($_GET['radius'], 'int');
    }
  } else if ($v['field_type'] == 'selectbox') {
    if (!empty($_GET[$v['field_name']])) {
      $tmp = array();
      foreach ($_GET[$v['field_name']] as $sel => $value) {
        $tmp[] = $value;
      }
      $value = "'".implode("','", $tmp)."'";
      $searchCriteria .= ' AND a.'.$v['field_name'].' IN ('.$value.')';
    }
  } else if ($v['field_type'] == 'multipleselectbox') {
    $mutilselectFrom = ' LEFT JOIN auto_pre_multiselectcats as mc ON a.id = mc.id';
    if (!empty($_GET[$v['field_name']])) {
      $tmp = array();
      foreach ($_GET[$v['field_name']] as $sel => $value) {
        $tmp[] = '(mc.col_name = \''.$v['field_name'].'\' AND mc.category_id = \''.$value.'\')';
      }
      $value = implode(" OR ", $tmp);
      $searchCriteria .= ' AND ('.$value.')';
    }
  } else if ($v['field_type'] == 'checkbox') {
    if (isset($_GET[$v['field_name']])) {
      $searchCriteria .= " AND a.".$v['field_name']." = ".GetSQLValueString($_GET[$v['field_name']], 'int');
    }
  } 
}

//sorting
if (!empty($_GET['sort'])) {
  $sorttype = 'ASC';
  if (!empty($_GET['sorttype'])) {
    $sorttype = $_GET['sorttype'];
  }
  $sort = $_GET['sort'];
  if ($sort !== 'distance') {
    $sort = 'a.'.$sort;
  }
  $orderBy = " ORDER BY $sort $sorttype";
}
//sorting

$tagsTable = '';
$tagsWhere = '';
if (!empty($_GET['keyword'])) {
    $tmp = array();
    $tmp2 = array();
    foreach ($resultModuleFields as $k => $v) {
      if ($v['searchable'] == 1 && ($v['field_type'] == 'varchar' || $v['field_type'] == 'text')) {
        $tagsTable = ' LEFT JOIN auto_pre_tags as tg ON a.id = tg.id';
        $tmp[] = "a.".$v['field_name']." LIKE ".GetSQLValueString('%'.$_GET['keyword'].'%', 'text');
        $tmp2[] = "tg.tag = ".GetSQLValueString($_GET['keyword'], 'text');
      }
    }
    if (!empty($tmp)) {
      $tmp2[] = implode(' OR ', $tmp);
    }
    if (!empty($tmp2)) {
      $tagsWhere = ' AND ('.implode(' OR ', $tmp2).')';
    }
}

$query_rsView = "SELECT a.*, u.name as fullname, u.* $distanceFrom FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid $mutilselectFrom $tagsTable WHERE a.module_id = ".$colname_rsModule." $searchCriteria $distanceWhere $tagsWhere GROUP BY a.id $orderBy";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
log_log($query_limit_rsView);
$rsView = $modelGeneral->fetchAll($query_limit_rsView, array(), $cacheTime);
log_log($rsView);
//echo $query_rsView;
//pr($rsView);

//getting rowCount
$queryTotalRows = "SELECT COUNT(a.id) as cnt FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid $mutilselectFrom $tagsTable WHERE a.module_id = ".$colname_rsModule." $searchCriteria $distanceWhere $tagsWhere GROUP BY a.id";
if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $rowCountResult = $modelGeneral->fetchAll($queryTotalRows, array(), $cacheTime);
  $totalRows_rsView = count($rowCountResult);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

//getString
$get_rsViewRaw = getString(array('pageNum_rsView', 'totalRows_rsView'));
$get_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $get_rsViewRaw);
log_log($get_rsView, 'get_rsView');
//getString Ends

if (!empty($_GET['clear'])) {
  $modelGeneral->clearCache($query_limit_rsView, array());
  $modelGeneral->clearCache($queryTotalRows, array());
  $getURL = getString(array('totalRows_rsView', 'clear'));
  if ($my)
    header("Location: ".$currentURL.'/auto/my?'.$getURL);
  else
    header("Location: ".$currentURL.'/auto/browse?'.$getURL);
  exit;
}

//end browse
//yelp, google, indeed, craigslist addition
if ($resultModule['module_name'] == 'jobs') {
  include(SITEDIR.'/includes/jobs_indeed.php');
  if (!empty($indeedCheck)) {
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
  }
}
if ($resultModule['feature_businesses'] == 1) {
  include(SITEDIR.'/includes/biz_google.php');
  include(SITEDIR.'/includes/biz_yelp.php');
  if (!empty($googleCheck) || !empty($yelpCheck)) {
    //header("Location: ".$_SERVER['REQUEST_URI']);
    //exit;
  }
}
//search box
ob_start();
include(SITEDIR.'/mods/auto/searchbox.php');
$search_box = ob_get_clean();
//search box ends

currentActivity('browse_page', $_SERVER['REQUEST_URI'], $globalCity['id'], 'User has browsed the '.$resultModule['module_name'].' page.');
?>