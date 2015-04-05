<?php
//http://world.mkgalaxy.com/api/help/bid/mybids?uid=
class App_bid_mybids extends App_base
{
    const PERIOD_BIDS = 'Jan 2015 To Dec 2015';
    public function execute()
    {
      $request = $_GET;
      $data = array();
      if (empty($request['uid'])) {
        throw new Exception('Missing Uid');
      }
      $Models_Basedata = new Models_Basedata();
      $result = $Models_Basedata->myBids($request['uid'], self::PERIOD_BIDS);

      $this->return = array('result' => $result);
    }
}