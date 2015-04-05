<?php
class Models_Jobs extends App_base
{
    public function add($data=array())
    {
      $Models_Googleauth = new Models_Googleauth();
      $user = $Models_Googleauth->getUser($data['uid']);
      if (empty($user)) {
        throw new Exception('User not found');
      }
      $insertSQL = $this->_connMain->AutoExecute('jobs', $data, 'INSERT');
      //$jobs_id = $this->_connMain->Insert_ID();
      return true;
    }
    public function add_category($data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute('jobs_category', $data, 'INSERT');
      //$cat_id = $this->_connMain->Insert_ID();
      return true;
    }

    public function view($category='', $city_ids='', $offset=0, $nrows=100, $cache=1)
    {
      $this->_connMain->Execute("SET NAMES utf8");
      $where = '';
      $where .= "jobs.job_status = 1 AND  jobs.job_soft_delete = 0 AND  jobs.job_hard_delete = 0";
      $sql = sprintf("SELECT * FROM jobs left join jobs_category ON jobs.job_id = jobs_category.job_id LEFT JOIN geo_cities ON jobs.city_id = geo_cities.cty_id WHERE ".$where);
      $sql2 = sprintf("SELECT count(jobs.job_id) as cnt FROM jobs left join jobs_category ON jobs.job_id = jobs_category.job_id LEFT JOIN geo_cities ON jobs.city_id = geo_cities.cty_id WHERE ".$where);
      if (!empty($category)) {
        $sql .= " AND jobs_category.category IN (".$category.")";
        $sql2 .= " AND jobs_category.category IN (".$category.")";
      }
      if (!empty($city_ids)) {
        $sql .= " AND jobs.city_id IN (".$city_ids.")";
        $sql2 .= " AND jobs.city_id IN (".$city_ids.")";
      }
      $sql .= ' GROUP BY jobs.job_id';
      $sql2 .= ' GROUP BY jobs.job_id';
      $sql .= " ORDER BY job_updated_dt DESC";
      if (!empty($cache)) {
          $result = $this->_connMain->CacheSelectLimit(5, $sql, $nrows, $offset); //change to 1000 after some time
          $resultTotal = $this->_connMain->CacheExecute(5, $sql2);
      } else {
          $result = $this->_connMain->SelectLimit($sql, $nrows, $offset);
          $resultTotal = $this->_connMain->Execute($sql2);
      }
      if (empty($result) || $result->EOF) throw new Exception ("No jobs Found.");
      $return = array();
      while (!$result->EOF) {
          $return[] = $result->fields;
          $result->MoveNext();
       }
       $data = array('total' => $resultTotal->RecordCount(), 'start' => $offset, 'result' => $return, 'sql' => $sql, 'sql2' => $sql2);
       return $data;
    }

    public function detail($job_id, $cache=1)
    {
      $where = '';
      $where .= "jobs.job_status = 1 AND  jobs.job_soft_delete = 0 AND  jobs.job_hard_delete = 0";
      $sql = sprintf("SELECT * FROM jobs left join jobs_category ON jobs.job_id = jobs_category.job_id LEFT JOIN geo_cities ON jobs.city_id = geo_cities.cty_id WHERE ".$where." AND jobs.job_id=%s", $this->qstr($job_id));
      if (!empty($cache)) {
          $result = $this->_connMain->CacheExecute(10, $sql);
      } else {
          $result = $this->_connMain->Execute($sql);
      }
      if (empty($result) || $result->EOF) throw new Exception ("No jobs Found.");
       return $result->fields;
    }
}