<?php
class Models_Iptocity extends Models_General
{
  public function getCity($ip)
  {
    $record = $this->fetchRow("select * from auto_pre_iptocity WHERE ip = ?", array($ip), 3600);
    if (!empty($record)) {
      $rec = json_decode($record['details'], 1);
      return $rec;
    }
    if (empty($record)) {
      $this->clearCache($this->sql, array($ip));
      $details = iptocity($ip);
      if (empty($details) || $details['status'] != 'OK') {
        return false;
      }
      $geo = new Models_Geo();
      $nearby = $geo->get_nearby_cities($details['lat'], $details['lon']);
      $rec = array();
      foreach ($nearby as $v) {
        $rec = $v;
        break;
      }
      if (empty($rec)) {
        return false;
      }
      $d = array();
      $d['ip'] = $ip;
      $d['details'] = json_encode($rec);
      $this->addDetails('auto_pre_iptocity', $d);
      return $rec;
    }

    return false;
  }
}