<?php
//yelp addition
  if (!empty($_GET['lat']) && !empty($_GET['lng'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lng'];
    $kw = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
    if (!empty($_GET['address'])) {
      $tmp = explode(',', $_GET['address']);
      $location = $tmp[0];
    } else {
      $location = $globalCity['city'];
    }
    $yelp_query = md5($kw.$location.$latitude.$longitude);
    $check1 = $modelGeneral->fetchRow("select * from auto_pre_biz WHERE biz_query = ? AND biz_type = 'Yelp'", array($yelp_query), 3600);
    if (empty($check1)) {
        $modelGeneral->clearCache($modelGeneral->sql);
        $yelp = new Library_Yelp();
        $results = $yelp->search($kw, $location, $latitude, $longitude);
        //add in yelp table
        $d = array();
        $d['biz_type'] = 'Yelp';
        $d['biz_query'] = $yelp_query;
        $d['biz_results'] = json_encode($results);
        $d['module_id'] = $colname_rsModule;
        $modelGeneral->addDetails('auto_pre_biz', $d);
        //adding ends
        $businesses = array();
        if ($results['total'] > 0) {
          foreach ($results['businesses'] as $k => $v) {
            $categories = array();
            if (!empty($v['categories'])) {
              foreach ($v['categories'] as $cats) {
                $categories[] = $cats[1];
              }
            }
            $biz = array(
                'title' => $v['name'],
                'description' => $v['name'],
                'category' => $categories,
                'photos' => array($v['image_url']),
                'videos' => array(),
                'urls' => array(),
                'address' => $v['location']['display_address'][0].', '.$v['location']['display_address'][1].', '.$v['location']['country_code'],
                'address2' => $v['location']['display_address'][0].', '.$v['location']['display_address'][1].', '.$v['location']['country_code'],
                'showAddress' => 1,
                'lat' => $v['location']['coordinate']['latitude'],
                'lng' => $v['location']['coordinate']['longitude'],
                'phone_number' => $v['display_phone'],
                'place_id' => $v['id'],
                'place_id_type' => 'Yelp',
                'rc_approved' => 1
            );
            $checkRow = $modelGeneral->fetchRow("select * from ".$tablename." WHERE place_id = ? AND place_id_type = ?", array($v['id'], 'Yelp'), 0);
            if (empty($checkRow)) {
              $return = createNewPost($biz, $currentURL, $globalCity, $colname_rsModule, $resultModule, $resultModuleFields, $modelGeneral, $tablename, 'System0000', 1);
            }
          }
        }
        $modelGeneral->clearCache($query_limit_rsView, array());
        $yelpCheck = 1;
    }//check1
  }//lat, lng