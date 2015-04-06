<?php
//yelp addition
  if (!empty($_GET['lat']) && !empty($_GET['lng'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lng'];
    $kw = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
    $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$_GET['lat'].','.$_GET['lng'].'&radius=500&key='.DEVELOPERKEY;
    if (!empty($kw)) {
      $url .= '&keyword='.urlencode($kw);
    }
    $google_query = md5($kw.$latitude.$longitude);
    $check1 = $modelGeneral->fetchRow("select * from auto_pre_biz WHERE biz_query = ? AND biz_type = 'Google'", array($google_query), 3600);
    if (empty($check1)) {
        $modelGeneral->clearCache($modelGeneral->sql);
        $res = curlget($url);
        $results = json_decode($res, 1);
        //add in yelp table
        $d = array();
        $d['biz_type'] = 'Google';
        $d['biz_query'] = $google_query;
        $d['biz_results'] = $res;
        $d['module_id'] = $colname_rsModule;
        $modelGeneral->addDetails('auto_pre_biz', $d);
        //adding ends
        $businesses = array();
        if (!empty($results['results'])) {
          foreach ($results['results'] as $k => $v) {
            $detailJson = curlget('https://maps.googleapis.com/maps/api/place/details/json?placeid='.$v['place_id'].'&key='.DEVELOPERKEY);
            $details = json_decode($detailJson, 1);
            $detail = $details['result'];
            $biz = array(
                'title' => $v['name'],
                'description' => $v['name'],
                'category' => $v['types'],
                'photos' => array(),
                'videos' => array(),
                'urls' => array($detail['url']),
                'address' => $detail['formatted_address'],
                'address2' => $detail['formatted_address'],
                'showAddress' => 1,
                'lat' => $v['geometry']['location']['lat'],
                'lng' => $v['geometry']['location']['lng'],
                'phone_number' => !empty($detail['international_phone_number']) ? $detail['international_phone_number'] : NULL,
                'place_id' => $v['place_id'],
                'place_id_type' => 'Google',
                'rc_approved' => 1
            );
            /*
            $imgShack = new Library_Imageshack();
            $imgUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$v['geometry']['location']['lat'].','.$v['geometry']['location']['lng'].'&maptype=roadmap&markers=color:blue%7Clabel:S%7C'.$v['geometry']['location']['lat'].','.$v['geometry']['location']['lng'].'&zoom=16&size=400x400&key='.DEVELOPERKEY; //'https://maps.googleapis.com/maps/api/streetview?size=400x400&location='.$v['geometry']['location']['lat'].','.$v['geometry']['location']['lng'].'&fov=90&heading=100&pitch=10';
            echo $imgUrl;
            exit;
            $imgs = $imgShack->upload($imgUrl);
            pr($imgs);
            exit;*/
            $checkRow = $modelGeneral->fetchRow("select * from ".$tablename." WHERE place_id = ? AND place_id_type = ?", array($v['id'], 'Google'), 0);
            if (empty($checkRow)) {
              $return = createNewPost($biz, $currentURL, $globalCity, $colname_rsModule, $resultModule, $resultModuleFields, $modelGeneral, $tablename, 'System0000', 1);
            }
          }
        }
        $modelGeneral->clearCache($query_limit_rsView, array());
        $googleCheck = 1;
    }//check1
  }//lat, lng