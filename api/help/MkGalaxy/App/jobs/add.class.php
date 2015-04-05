<?php
//http://world.mkgalaxy.com/api/help/jobs/add?uid=&title=&description=
class App_jobs_add extends App_base
{
    public function execute()
    {
      $request = $_REQUEST;
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
      $job_id = guid();
      $data['job_id'] = $job_id;
      $data['uid'] = !empty($request['uid']) ? $request['uid'] : '';
      $data['city_id'] = !empty($request['city_id']) ? $request['city_id'] : '';
      $data['title'] = !empty($request['title']) ? $request['title'] : '';
      $data['description'] = !empty($request['description']) ? $request['description'] : '';
      $data['job_lat'] = !empty($request['lat']) ? $request['lat'] : '';
      $data['job_lng'] = !empty($request['lng']) ? $request['lng'] : '';
      $data['job_address'] = !empty($request['address']) ? $request['address'] : '';
      $data['showAddress'] = !empty($request['showAddress']) ? $request['showAddress'] : '';
      $data['totalCost'] = !empty($request['totalCost']) ? $request['totalCost'] : '';
      $data['compensation'] = !empty($request['compensation']) ? $request['compensation'] : '';
      $data['contactbyphone'] = !empty($request['contactbyphone']) ? $request['contactbyphone'] : '';
      $data['contactbytext'] = !empty($request['contactbytext']) ? $request['contactbytext'] : '';
      $data['phonenumber'] = !empty($request['phonenumber']) ? $request['phonenumber'] : '';
      $data['contactname'] = !empty($request['contactname']) ? $request['contactname'] : '';
      
      $data['telecommuting'] = !empty($request['telecommuting']) ? $request['telecommuting'] : '';
      $data['part_time'] = !empty($request['part_time']) ? $request['part_time'] : '';
      $data['contract'] = !empty($request['contract']) ? $request['contract'] : '';
      $data['non_profit'] = !empty($request['non_profit']) ? $request['non_profit'] : '';
      $data['internship'] = !empty($request['internship']) ? $request['internship'] : '';
      $data['direct_contact'] = !empty($request['direct_contact']) ? $request['direct_contact'] : '';
      $data['disabilities'] = !empty($request['disabilities']) ? $request['disabilities'] : '';
      
      $data['job_created_dt'] = date('Y-m-d H:i:s');
      $data['job_status'] = !empty($request['status']) ? $request['status'] : '1';
      $Models_Jobs = new Models_Jobs();
      $Models_Jobs->add($data);
      foreach ($request['categories'] as $k => $v) {
        $arr = array();
        $arr['job_id'] = $job_id;
        $arr['category'] = $v;
        $arr['category_id'] = guid();
        $Models_Jobs->add_category($arr);
        $cat_id[] = $arr['category_id'];
      }
      $this->return = array('confirm' => 'New job posting created successfully', 'job_id' => $job_id, 'cat_ids' => $cat_id);
    }
}