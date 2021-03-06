<?php
class Models_Googleauth extends App_base
{
    public $sql;
    public function getUser($uid, $useCache=true)
    {
      $sql = sprintf("SELECT *, google_auth.name as fullname FROM google_auth LEFT JOIN settings ON google_auth.uid = settings.uid LEFT JOIN geo_cities ON settings.birth_city_id = geo_cities.cty_id WHERE google_auth.uid = %s", $this->qstr($uid));
      $this->sql = $sql;
      if ($useCache)
          $result = $this->_connMain->CacheExecute(self::CACHESECS_GOOGLEAUTH_USER, $sql);
      else
          $result = $this->_connMain->Execute($sql);
      $return = $result->fields;
      if (!empty($return['birth_city_id']) && empty($return['extraDetails'])) {
        $city = findCity($return['birth_city_id']);
        $return = array_merge($return, $city);
      }
      if (empty($result) || $result->EOF || empty($return)) throw new Exception ("No User Found.");
       return $return;
    }
}