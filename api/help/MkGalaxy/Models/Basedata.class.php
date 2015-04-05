<?php
class Models_Basedata extends App_base
{
    public function add($data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($data['uid']);
      if (empty($user)) {
        throw new Exception('User not found');
      }
      $insertSQL = $this->_connMain->AutoExecute('base_data', $data, 'INSERT');
      return true;
    }
    protected function checkUser($uid)
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($uid);
      if (empty($user)) {
        throw new Exception('User not found');
      }
    }
    public function add_category($data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute('base_data_category', $data, 'INSERT');
      return true;
    }
    public function community_add($data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute('community', $data, 'INSERT');
      return true;
    }
    public function personals_add($data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute('personals', $data, 'INSERT');
      return true;
    }
    public function services_add($data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute('services', $data, 'INSERT');
      return true;
    }
    public function addBid($data=array())
    {
      $this->checkUser($data['owner_id']);
      $sql = sprintf("SELECT * FROM location_bids WHERE location_id=%s AND owner_id=%s AND location_type=%s AND period_bids=%s", $this->qstr($data['location_id']), $this->qstr($data['owner_id']), $this->qstr($data['location_type']), $this->qstr($data['period_bids']));
      $result = $this->_connMain->Execute($sql);
      if ($result->RecordCount() > 0) {
        throw new Exception('You have already bidded for current location.');
      }
      $insertSQL = $this->_connMain->AutoExecute('location_bids', $data, 'INSERT');
      $error = $this->_connMain->ErrorMsg();
      if (!empty($error)) {
        throw new Exception($error);
      }
      return true;
    }
    public function myBids($uid, $period)
    {
      $this->checkUser($uid);
      $sql = sprintf("SELECT * FROM location_bids WHERE owner_id=%s AND period_bids=%s order by bid_created_dt asc", $this->qstr($uid), $this->qstr($period));
      $result = $this->_connMain->Execute($sql);
      if ($result->RecordCount() == 0) {
        throw new Exception('No Bids available.');
      }
      $return = array();
      $Models_Geo = new Models_Geo();
      $return['country'] = array();
      $return['state'] = array();
      $return['city'] = array();
      while (!$result->EOF) {
          $location = array();
          switch ($result->fields['location_type']) {
            case 'Country':
              $location = $Models_Geo->getCountryDetails($result->fields['location_id'], 0);
              $return['country'][] = array('bidDetail' => $result->fields, 'location' => $location);
              break;
            case 'State':
              $location = $Models_Geo->getStateDetails($result->fields['location_id'], 0);
              $return['state'][] = array('bidDetail' => $result->fields, 'location' => $location);
              break;
            case 'City':
              $location = $Models_Geo->getCityDetails($result->fields['location_id'], 0);
              $return['city'][] = array('bidDetail' => $result->fields, 'location' => $location);
              break;
          }
          $result->MoveNext();
       }
      return $return;
    }


    public function community_view($category='', $city_ids='', $offset=0, $nrows=100, $cache=1)
    {
      $this->_connMain->Execute("SET NAMES utf8");
      $where = '';
      $where .= "base_data.base_status = 1 AND  base_data.base_soft_delete = 0 AND  base_data.base_hard_delete = 0";
      $sql = sprintf("SELECT * FROM community left join base_data ON community.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      $sql2 = sprintf("SELECT count(base_data.base_id) as cnt FROM community left join base_data ON community.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      if (!empty($category)) {
        $sql .= " AND base_data_category.category IN (".$category.")";
        $sql2 .= " AND base_data_category.category IN (".$category.")";
      }
      if (!empty($city_ids)) {
        $sql .= " AND base_data.city_id IN (".$city_ids.")";
        $sql2 .= " AND base_data.city_id IN (".$city_ids.")";
      }
      $sql .= ' GROUP BY base_data_category.base_id';
      $sql2 .= ' GROUP BY base_data_category.base_id';
      $sql .= " ORDER BY base_updated_dt DESC";
      if (!empty($cache)) {
          $result = $this->_connMain->CacheSelectLimit(5, $sql, $nrows, $offset); //change to 1000 after some time
          $resultTotal = $this->_connMain->CacheExecute(5, $sql2);
      } else {
          $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
          $resultTotal = $this->_connMain->Execute($sql2);
      }
      if (empty($result) || $result->EOF) throw new Exception ("No posting found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       $data = array('total' => $resultTotal->RecordCount(), 'start' => $offset, 'result' => $return, 'sql' => $sql, 'sql2' => $sql2);
       return $data;
    }


    public function personals_view($category='', $city_ids='', $offset=0, $nrows=100, $cache=1)
    {
      $this->_connMain->Execute("SET NAMES utf8");
      $where = '';
      $where .= "base_data.base_status = 1 AND  base_data.base_soft_delete = 0 AND  base_data.base_hard_delete = 0";
      $sql = sprintf("SELECT * FROM personals left join base_data ON personals.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      $sql2 = sprintf("SELECT count(base_data.base_id) as cnt FROM personals left join base_data ON personals.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      if (!empty($category)) {
        $sql .= " AND base_data_category.category IN (".$category.")";
        $sql2 .= " AND base_data_category.category IN (".$category.")";
      }
      if (!empty($city_ids)) {
        $sql .= " AND base_data.city_id IN (".$city_ids.")";
        $sql2 .= " AND base_data.city_id IN (".$city_ids.")";
      }
      $sql .= ' GROUP BY base_data_category.base_id';
      $sql2 .= ' GROUP BY base_data_category.base_id';
      $sql .= " ORDER BY base_updated_dt DESC";
      if (!empty($cache)) {
          $result = $this->_connMain->CacheSelectLimit(5, $sql, $nrows, $offset); //change to 1000 after some time
          $resultTotal = $this->_connMain->CacheExecute(5, $sql2);
      } else {
          $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
          $resultTotal = $this->_connMain->Execute($sql2);
      }
      if (empty($result) || $result->EOF) throw new Exception ("No posting found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       $data = array('total' => $resultTotal->RecordCount(), 'start' => $offset, 'result' => $return, 'sql' => $sql, 'sql2' => $sql2);
       return $data;
    }

    public function services_view($category='', $city_ids='', $offset=0, $nrows=100, $cache=1)
    {
      $this->_connMain->Execute("SET NAMES utf8");
      $where = '';
      $where .= "base_data.base_status = 1 AND  base_data.base_soft_delete = 0 AND  base_data.base_hard_delete = 0";
      $sql = sprintf("SELECT * FROM services left join base_data ON services.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      $sql2 = sprintf("SELECT count(base_data.base_id) as cnt FROM services left join base_data ON services.base_id = base_data.base_id left join base_data_category ON base_data.base_id = base_data_category.base_id LEFT JOIN geo_cities ON base_data.city_id = geo_cities.cty_id WHERE ".$where);
      if (!empty($category)) {
        $sql .= " AND base_data_category.category IN (".$category.")";
        $sql2 .= " AND base_data_category.category IN (".$category.")";
      }
      if (!empty($city_ids)) {
        $sql .= " AND base_data.city_id IN (".$city_ids.")";
        $sql2 .= " AND base_data.city_id IN (".$city_ids.")";
      }
      $sql .= ' GROUP BY base_data_category.base_id';
      $sql2 .= ' GROUP BY base_data_category.base_id';
      $sql .= " ORDER BY base_updated_dt DESC";
      if (!empty($cache)) {
          $result = $this->_connMain->CacheSelectLimit(5, $sql, $nrows, $offset); //change to 1000 after some time
          $resultTotal = $this->_connMain->CacheExecute(5, $sql2);
      } else {
          $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
          $resultTotal = $this->_connMain->Execute($sql2);
      }
      if (empty($result) || $result->EOF) throw new Exception ("No posting found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       $data = array('total' => $resultTotal->RecordCount(), 'start' => $offset, 'result' => $return, 'sql' => $sql, 'sql2' => $sql2);
       return $data;
    }
}