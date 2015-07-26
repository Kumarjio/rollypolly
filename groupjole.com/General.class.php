<?php
class General
{
    protected $_connMain;

    public $return = array();

    public $sql;

    public function __construct()
    {
        global $connMainAdodb;
        $this->_connMain = $connMainAdodb;
        $this->_connMain->SetFetchMode(ADODB_FETCH_ASSOC);
        if (!empty($_GET['debug']) && $_GET['debug'] == 1) {
          $this->_connMain->debug = true;
        }
        //$this->_connMain->debug = true;
    }

    public function qstr($value)
    {
        return $this->_connMain->qstr($value);
    }
    

    public function getUser($uid, $field='google', $useCache=true)
    {
      $sql = sprintf("SELECT * FROM users WHERE {$field}_id = %s AND {$field}_auth = 1", $this->qstr($uid));
      if ($useCache)
          $result = $this->_connMain->CacheExecute(300, $sql);
      else
          $result = $this->_connMain->Execute($sql);
      $return = $result->fields;
       return $return;
    }

    public function checkUser($uid, $cache=1)
    {
      $user = $this->getUser($uid, $cache);
      if (empty($user)) {
        throw new Exception('User not found');
      }
    }

    
    public function addDetails($tableName, $data=array())
    {
      $insertSQL = $this->_connMain->AutoExecute($tableName, $data, 'INSERT');
      $id = $this->_connMain->Insert_ID();
      return $id;
    }

    public function updateDetails($tableName, $data=array(), $where='')
    {
      if (empty($where)) {
          throw new Exception('could not update');
      }
      $updateSQL = $this->_connMain->AutoExecute($tableName, $data, 'UPDATE', $where);
      return true;
    }

    public function deleteDetails($query, $params=array())
    {
      $delete = $this->_connMain->Execute($query, $params);
      return true;
    }

  public function getDetails($tableName, $cache=1, $params=array(), $cacheTime=300)
  {
    if (empty($cacheTime)) {
        $cacheTime = !empty($params['cacheTime']) ? $params['cacheTime'] : '300';
    }
    if (!empty($params['query']) && isset($params['parameters'])) {
      $this->sql = $params['query'];

      if ($cache) {
        $result = $this->_connMain->CacheExecute($cacheTime, $params['query'], $params['parameters']);
      } else {
        $result = $this->_connMain->Execute($params['query'], $params['parameters']);
      }
    } else {
      $where = !empty($params['where']) ? $params['where'] : '';
      $group = !empty($params['group']) ? $params['group'] : '';
      $order = !empty($params['order']) ? $params['order'] : '';
      $fields = !empty($params['fields']) ? $params['fields'] : '*';
      $limit = !empty($params['limit']) ? $params['limit'] : '';
      $sql = "SELECT $fields FROM $tableName WHERE 1 $where $group $order $limit";
      $this->sql = $sql;
      if ($cache) {
        $result = $this->_connMain->CacheExecute($cacheTime, $sql);
      } else {
        $result = $this->_connMain->Execute($sql);
      }
    }
    $return = array();
    while (!$result->EOF) {
        $return[] = $result->fields;
        $result->MoveNext();
     }
    return $return;
  }

  public function clearCache($sql, $inputArr=array())
  {
      $this->_connMain->CacheFlush($sql, $inputArr);
      return true;
  }
  
  public function fetchRow($query, $params, $cacheTime=300)
  {
    $data = array();
    $data['query'] = $query;
    $data['parameters'] = $params;
    $row = $this->getDetails('', ($cacheTime > 0), $data, $cacheTime);
    if (!empty($row)) {
      $row = $row[0];
    }
    return $row;
  }
  
  public function fetchAll($query, $params, $cacheTime=300)
  {
    $data = array();
    $data['query'] = $query;
    $data['parameters'] = $params;
    $result = $this->getDetails('', ($cacheTime > 0), $data, $cacheTime);
    return $result;
  }
}