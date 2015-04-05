<?php
//http://world.mkgalaxy.com/api/help/records/add?user_id=&title=&description=
class App_records_add extends App_base
{
    public function execute($request=array())
    {
      //coming soon
      if (empty($request)) {
          $request = $_REQUEST;
      }
      $data = array();
      if (empty($request['module_id'])) {
        throw new Exception('Missing module_id');
      }
      if (empty($request['user_id'])) {
        throw new Exception('Missing user_id');
      }
      if (empty($request['city_id'])) {
        throw new Exception('Missing City ID');
      }
      if (empty($request['title'])) {
        throw new Exception('Missing title');
      }
      $data['record_id'] = guid();
      $data['module_id'] = !empty($request['module_id']) ? $request['module_id'] : '';
      $data['user_id'] = !empty($request['user_id']) ? $request['user_id'] : '';
      $data['city_id'] = !empty($request['city_id']) ? $request['city_id'] : '';
      $data['title'] = !empty($request['title']) ? $request['title'] : '';

      $data['lat'] = !empty($request['lat']) ? $request['lat'] : '';
      $data['lon'] = !empty($request['lng']) ? $request['lng'] : '';
      $data['address'] = !empty($request['address']) ? $request['address'] : '';
      $data['showAddress'] = !empty($request['showAddress']) ? $request['showAddress'] : 0;
      $data['record_updated_date'] = date('Y-m-d H:i:s');
      for ($i = 1; $i <= 10; $i++) {
        $field = 'ifield'.$i;
        $data[$field] = !empty($request[$field]) ? $request[$field] : NULL;
      }
      for ($i = 1; $i <= 2; $i++) {
        $field = 'ffield'.$i;
        $data[$field] = !empty($request[$field]) ? $request[$field] : NULL;
      }



      $data['details'] = json_encode($request['details']);

      $model = new Models_Records();
      $returnData = $model->add($data);
      //add categories
      $model->add_category($data['record_id'], $request['categories']);
      $this->return = array('confirm' => 'New posting created successfully', 'record_id' => $data['record_id'], 'data' => $data, 'returnData' => $returnData);
      return $this->return;
    }
}