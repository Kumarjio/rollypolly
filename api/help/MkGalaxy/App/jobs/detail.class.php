<?php
//http://world.mkgalaxy.com/api/help/jobs/detail?job_id=1
class App_jobs_detail extends App_base
{
    public function execute()
    {
      $request = $_GET;
      $data = array();
      if (empty($request['job_id'])) {
        throw new Exception('Job ID not found');
      }
      $job_id = $request['job_id'];
      $Models_Jobs = new Models_Jobs();
      $data = $Models_Jobs->detail($job_id);
      $this->return = $data;
    }
}