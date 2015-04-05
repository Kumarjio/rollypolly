<?php
//http://world.mkgalaxy.com/api/help/jobs/view?category=a,cb,
class App_jobs_view extends App_base
{
    public function execute()
    {
      $request = $_GET;
      $data = array();
      $category = '';
      if (!empty($request['category'])) {
        $cat = explode(',', $request['category']);
        $cat = array_filter($cat);
        $category = "'".implode("', '", $cat)."'";
      }
      $city_ids = '';
      if (!empty($request['city_id'])) {
        $city_ids = $request['city_id'];
      }
      $Models_Jobs = new Models_Jobs();
      $data = $Models_Jobs->view($category, $city_ids);
      $this->return = $data;
    }
}