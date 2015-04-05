<?php
class App_cities_listOwnedCities
{
    public function execute()
    {
        $maxRows_rsView = 25;
        $pageNum_rsView = 0;
        if (isset($_GET['pageNum_rsView'])) {
          $pageNum_rsView = $_GET['pageNum_rsView'];
        }
        $api = new Models_Api();
        $return = $api->listOwnedCities($maxRows_rsView, $pageNum_rsView);
        $this->return = $return;
    }
}