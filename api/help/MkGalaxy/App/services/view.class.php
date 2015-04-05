<?php
//http://world.mkgalaxy.com/api/help/services/view?category=a,cb,
class App_services_view extends App_base
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
      $Models_Basedata = new Models_Basedata();
      $data = $Models_Basedata->services_view($category, $city_ids);
      
      $this->return = $data;
    }
}