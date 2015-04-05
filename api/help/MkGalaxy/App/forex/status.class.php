<?php
//http://mkgalaxy.com/api/help/forex/status?account=1237758
class App_forex_status extends App_base
{
  public function execute()
    {
      try {
          $request = $_GET;
          $data = array();
          $Models_General = new Models_General();
          if (empty($request['account'])) {
            throw new Exception(0);
          }
          $arr = array();
          if (!empty($request['b'])) {
            $arr['account_balance'] = $request['b'];
          }
          if (!empty($request['e'])) {
            $arr['account_equity'] = $request['e'];
          }
          if (!empty($request['f'])) {
            $arr['account_free_margin'] = $request['f'];
          }
          if (!empty($request['c'])) {
            $arr['account_company'] = $request['c'];
          }
          if (!empty($arr)) {
            $where = sprintf('account_number = %s', $Models_General->qstr($request['account']));
            $Models_General->updateDetails('forex_users', $arr, $where);
          }
          
          //history
          $arr = array();
          if (!empty($request['b'])) {
            $arr['account_balance'] = $request['b'];
          }
          if (!empty($request['e'])) {
            $arr['account_equity'] = $request['e'];
          }
          if (!empty($request['f'])) {
            $arr['account_free_margin'] = $request['f'];
          }
          if (!empty($arr) && !empty($request['account'])) {
            $arr['account_number'] = $request['account'];
            $Models_General->addDetails('forex_balance_history', $arr);
          }

          $cache = 1;
          if (isset($_GET['cache'])) {
            $cache = $_GET['cache'];
          }
          $params = array();
          $params['where'] = sprintf(' AND account_number = %s', $this->qstr($request['account']));
          $params['fields'] = '*'; //'forex_user_id as id, account_number, status, open_trades, close_trades';
          $params['cacheTime'] = 3600;
          $result = $Models_General->getDetails('forex_users', $cache, $params);
          if (empty($result[0])) {
            throw new Exception(1);
          }
          foreach ($result[0] as $value) {
            echo $value.'|';
          }
      } catch (Exception $e) {
          echo $e->getMessage();
      }
      exit;
    }
}