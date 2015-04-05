<?php
//http://world.mkgalaxy.com/api/help/personals/add?uid=&title=&description=
class App_personals_add extends App_base
{
    public function execute()
    {
      $request = $_REQUEST;
      /*
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
      $base_id = guid();
      $data['base_id'] = $base_id;
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
      
      $data['base_created_dt'] = date('Y-m-d H:i:s');
      $data['base_status'] = !empty($request['status']) ? $request['status'] : '1';
      $Models_Basedata = new Models_Basedata();
      $Models_Basedata->add($data);
      */
      $Models_Basedata = new Models_Basedata();
      $data = $this->baseDataCheck($request);
      
      $personalData = array();
      $personalData['personal_id'] = guid();
      $personalData['base_id'] = $data['base_id'];
      $personalData['age'] = !empty($request['age']) ? $request['age'] : '';
      $personalData['other_details'] = !empty($request['other_details']) ? json_encode($request['other_details']) : '';
      $Models_Basedata->personals_add($personalData);
      
      $this->return = array('confirm' => 'New posting created successfully', 'personal_id' => $personalData['personal_id']);
    }
}