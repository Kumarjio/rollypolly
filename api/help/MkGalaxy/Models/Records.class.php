<?php
class Models_Records extends App_base
{
    public function add($data=array())
    {
      if (empty($data['user_id'])) {
        throw new Exception('user id is missing');
      }
      $this->checkUser($data['user_id']);
      $insertSQL = $this->_connMain->AutoExecute('records', $data, 'INSERT');
      return true;
    }
    public function add_category($record_id, $categories=array())
    {
        if (empty($categories)) {
          return false;
        }
        if (!is_array($categories)) {
          $categories = array($categories);
        }
        foreach ($categories as $k => $v) {
          $arr = array();
          $arr['record_id'] = $record_id;
          $arr['category_id'] = $v;
          $insertSQL = $this->_connMain->AutoExecute('records_category', $arr, 'INSERT');
        }
        return true;
    }


    public function records_view($params=array(), $offset=0, $nrows=100, $cache=1)
    {
      $this->_connMain->Execute("SET NAMES utf8");
      $where = '';
      $where .= "records.soft_delete = 0 AND records.hard_delete = 0";
      $from = "FROM records left join records_category ON records.record_id = records_category.record_id LEFT JOIN geo_cities ON records.city_id = geo_cities.cty_id LEFT JOIN geo_states ON geo_cities.sta_id = geo_states.sta_id LEFT JOIN  geo_countries ON geo_states.con_id = geo_countries.con_id";
      $sql = sprintf("SELECT records.*, geo_cities.*, geo_states.name as state, geo_countries.name as country ".$from." WHERE ".$where);
      $sql2 = sprintf("SELECT count(records.record_id) as cnt ".$from." WHERE ".$where);

      $record_id = !empty($params['record_id']) ? $params['record_id'] : '';
      if (!empty($record_id)) {
        $sql .= " AND records.record_id = ".$this->qstr($record_id);
        $sql2 .= " AND records.record_id = ".$this->qstr($record_id);
      }

      $category = !empty($params['category']) ? $params['category'] : '';
      if (!empty($category)) {
        if (is_array($category)) {
          $city_ids = implode(', ', $category);
        }
        $sql .= " AND records_category.category_id IN (".$category.")";
        $sql2 .= " AND records_category.category_id IN (".$category.")";
      }

      $city_ids = !empty($params['city_ids']) ? $params['city_ids'] : '';
      if (!empty($city_ids)) {
        if (is_array($city_ids)) {
          $city_ids = implode(', ', $city_ids);
        }
        $sql .= " AND records.city_id IN (".$city_ids.")";
        $sql2 .= " AND records.city_id IN (".$city_ids.")";
      }

      $exclude_city_ids = !empty($params['exclude_city_ids']) ? $params['exclude_city_ids'] : '';
      if (!empty($exclude_city_ids)) {
        if (is_array($exclude_city_ids)) {
          $exclude_city_ids = implode(', ', $exclude_city_ids);
        }
        $sql .= " AND records.city_id NOT IN (".$exclude_city_ids.")";
        $sql2 .= " AND records.city_id NOT IN (".$exclude_city_ids.")";
      }

      if (isset($params['admin_status'])) {
        $sql .= sprintf(" AND records.admin_status = %s", $this->qstr($params['admin_status']));
        $sql2 .= sprintf(" AND records.admin_status = %s", $this->qstr($params['admin_status']));
      }

      $groupBY = ' GROUP BY records.record_id';
      $sql .= $groupBY;
      $sql2 .= $groupBY;
      $sql .= " ORDER BY record_updated_date DESC";
      if (!empty($cache)) {
          $result = $this->_connMain->CacheSelectLimit(5, $sql, $nrows, $offset); //change to 1000 after some time
          $resultTotal = $this->_connMain->CacheExecute(5, $sql2);
      } else {
          $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
          $resultTotal = $this->_connMain->Execute($sql2);
      }
      if (empty($result)) throw new Exception ("No posting found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       $data = array('total' => $resultTotal->RecordCount(), 'start' => $offset, 'result' => $return, 'sql' => $sql, 'sql2' => $sql2);
       return $data;
    }

}