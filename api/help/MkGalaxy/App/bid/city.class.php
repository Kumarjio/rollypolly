<?php
//http://world.mkgalaxy.com/api/help/bid/city?uid=&city=&bid=
class App_bid_city extends App_base
{
    const PERIOD_BIDS = 'Jan 2015 To Dec 2015';
    public function execute()
    {
      $request = $_GET;
      $data = array();
      if (empty($request['uid'])) {
        throw new Exception('Missing Uid');
      }
      if (empty($request['city'])) {
        throw new Exception('Missing city');
      }
      if (empty($request['bid'])) {
        throw new Exception('Missing bid amount');
      }
      $data['location_id'] = !empty($request['city']) ? $request['city'] : '';
      $data['owner_id'] = !empty($request['uid']) ? $request['uid'] : '';
      $data['location_type'] = 'City';
      $data['period_bids'] = self::PERIOD_BIDS;
      $data['bid_amount'] = !empty($request['bid']) ? $request['bid'] : '';
      $Models_Basedata = new Models_Basedata();
      $Models_Basedata->addBid($data);

      $this->return = array('confirm' => 'New bid created successfully');
    }
}