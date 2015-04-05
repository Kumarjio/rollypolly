<?php
//http://mkgalaxy.com/api/help/users/detail?uid=100546875099861959996&cache=1
class App_users_detail extends App_base
{
    public function execute()
    {
      if (empty($_GET['uid'])) {
        throw new Exception('Enter uid');
      }
      $uid = $_GET['uid'];
      $cache = isset($_GET['cache']) ? $_GET['cache'] : 1;
      $Models_Googleauth = new Models_Googleauth();
      $return = $Models_Googleauth->getUser($uid, $cache);
      $this->return = $return;
    }
}