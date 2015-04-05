<?php
class Models_Api extends Models_General
{
  public function listOwnedCities($max=25, $page=0)
  {
    $maxRows_rsView = $max;
    $pageNum_rsView = $page;
    $startRow_rsView = $pageNum_rsView * $maxRows_rsView;
    $query_rsView = 'select c.*, s.name as state, co.name as country from geo_city_owners as o LEFT JOIN geo_cities as c ON o.cty_id = c.cty_id LEFT JOIN geo_states as s ON s.sta_id = c.sta_id LEFT JOIN geo_countries as co ON co.con_id = c.con_id ORDER BY country, state, c.name';
    $query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
    $results = $this->fetchAll($query_limit_rsView, array(), 3600);
    $queryTotalRows = 'select COUNT(c.name) AS cnt from geo_city_owners as o LEFT JOIN geo_cities as c ON o.cty_id = c.cty_id';
    $rowCountResult = $this->fetchRow($queryTotalRows, array(), 3600);
    $totalRows_rsView = $rowCountResult['cnt'];
    $totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;
    $return = array('results' => $results, 'max' => $max, 'page' => $page, 'totalRows' => $totalRows_rsView, 'totalPages' => $totalPages_rsView);
    return $return;
  }
}