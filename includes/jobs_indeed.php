<?php
//yelp addition
  if (!empty($_GET['lat']) && !empty($_GET['lng'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lng'];
    $kw = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
    $location = $globalCity['city'].', '.$globalCity['statename'].', '.$globalCity['countryname'];
    if (!empty($_GET['geo_s']) && !empty($_GET['geo_c']) && !empty($_GET['geo_co'])) {
      $location = $_GET['geo_c'].', '.$_GET['geo_s'].', '.$_GET['geo_co'];
    }
    $url = 'http://api.indeed.com/ads/apisearch?publisher=5171288687589967&l='.urlencode($location).'&sort=&radius=&st=&jt=&start='.$startRow_rsView.'&limit=25&fromage=&filter=&latlong=1&co=us&chnl=&userip='.$_SERVER['REMOTE_ADDR'].'&v=2';
    if (!empty($kw)) {
      $url .= '&q='.urlencode($kw);
    }
    //echo $url;
    $searchQuery = md5($url.date('Y-m-d'));
    $check1 = $modelGeneral->fetchRow("select * from auto_pre_biz WHERE biz_query = ? AND biz_type = 'Indeed'", array($searchQuery), 0);
    if (empty($check1)) {
        $modelGeneral->clearCache($modelGeneral->sql);
        $res = curlget($url);
        $results = simplexml_load_string($res);
        //add in biz table
        $d = array();
        $d['biz_type'] = 'Indeed';
        $d['biz_query'] = $searchQuery;
        $d['biz_results'] = $res;
        $d['module_id'] = $colname_rsModule;
        $modelGeneral->addDetails('auto_pre_biz', $d);
        if (!empty($results->results->result)) {
          foreach ($results->results->result as $k => $v) {
            $date = sprintf("%s", $v->date);
            $biz = array(
                'title' => sprintf("%s", $v->jobtitle),
                'company' => sprintf("%s", $v->company),
                'description' => sprintf("%s", $v->snippet),
                'category' => 33,
                'photos' => array(),
                'videos' => array(),
                'urls' => array(sprintf("%s", $v->url)),
                'address' => sprintf("%s", $v->formattedLocationFull),
                'address2' => sprintf("%s", $v->formattedLocationFull),
                'showAddress' => 1,
                'lat' => sprintf("%s", $v->latitude),
                'lng' => sprintf("%s", $v->longitude),
                'jobkey' => sprintf("%s", $v->jobkey),
                'custom_date' => date('Y-m-d H:i:s', strtotime($date)),
                'rc_approved' => 1
            );
            $checkRow = $modelGeneral->fetchRow("select * from ".$tablename." WHERE jobkey = ?", array($v->jobkey), 0);
            if (empty($checkRow)) {
              $return = createNewPost($biz, $currentURL, $globalCity, $colname_rsModule, $resultModule, $resultModuleFields, $modelGeneral, $tablename, 'System0000', 1);
            }
          }
        }
        $modelGeneral->clearCache($query_limit_rsView, array());
        $modelGeneral->clearCache($queryTotalRows, array());
        $indeedCheck = 1;
    }//check1
  }//lat, lng