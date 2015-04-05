<?php
class App_base
{
    protected $_connMain;

    public $return = array();

    const CACHESECS_MESSAGES = 5;//5 secs

    const CACHESECS_MESSAGESDETAIL = 5;//5 secs

    const CACHESECS_GOOGLEAUTH_USER = 1;

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
    

    public function getUser($uid, $useCache=true)
    {
      $sql = sprintf("SELECT * FROM google_auth WHERE google_auth.uid = %s", $this->qstr($uid));
      if ($useCache)
          $result = $this->_connMain->CacheExecute(self::CACHESECS_GOOGLEAUTH_USER, $sql);
      else
          $result = $this->_connMain->Execute($sql);
      $return = $result->fields;
      if (empty($result) || $result->EOF || empty($return)) throw new Exception ("No User Found.");
       return $return;
    }

    public function checkUser($uid, $cache=1)
    {
      $user = $this->getUser($uid, $cache);
      if (empty($user)) {
        throw new Exception('User not found');
      }
    }

    protected function baseDataCheck($request)
    {
      $data = array();
      if (empty($request['uid'])) {
        throw new Exception('Missing Uid');
      }
      if (empty($request['city_id'])) {
        throw new Exception('Missing City ID');
      }
      if (empty($request['title'])) {
        throw new Exception('Missing title');
      }
      if (empty($request['description'])) {
        throw new Exception('Missing description');
      }
      if (empty($request['categories'])) {
        throw new Exception('Missing category');
      }
      $data['base_id'] = guid();
      $data['uid'] = !empty($request['uid']) ? $request['uid'] : '';
      $data['city_id'] = !empty($request['city_id']) ? $request['city_id'] : '';
      $data['title'] = !empty($request['title']) ? $request['title'] : '';
      $data['description'] = !empty($request['description']) ? $request['description'] : '';
      $data['base_lat'] = !empty($request['lat']) ? $request['lat'] : '';
      $data['base_lng'] = !empty($request['lng']) ? $request['lng'] : '';
      $data['base_address'] = !empty($request['address']) ? $request['address'] : '';
      $data['showAddress'] = !empty($request['showAddress']) ? $request['showAddress'] : '';
      $data['contactbyphone'] = !empty($request['contactbyphone']) ? $request['contactbyphone'] : '';
      $data['contactbytext'] = !empty($request['contactbytext']) ? $request['contactbytext'] : '';
      $data['phonenumber'] = !empty($request['phonenumber']) ? $request['phonenumber'] : '';
      $data['contactname'] = !empty($request['contactname']) ? $request['contactname'] : '';
      $data['images'] = !empty($request['images']) ? json_encode($request['images']) : '';
      
      $data['base_created_dt'] = date('Y-m-d H:i:s');
      $data['base_status'] = !empty($request['status']) ? $request['status'] : '1';
      $Models_Basedata = new Models_Basedata();
      $Models_Basedata->add($data);

      $arr = array();
      $arr['base_id'] = $data['base_id'];
      $arr['category'] = $request['categories'];
      $arr['category_id'] = guid();
      $Models_Basedata->add_category($arr);
      $data['cat_id']= $arr['category_id'];
      return $data;
    }
}