<?php
class Models_Horo extends App_base
{
  public function match($data1, $data2)
  {
      $Kundali = new Library_Kundali();
      $fromDate = getDateTime($data1['dob']);
      $toDate = getDateTime($data2['dob']);
      $returnArrFrom = $Kundali->precalculate($fromDate['month'], $fromDate['day'], $fromDate['year'], $fromDate['hour'], $fromDate['minute'], $data1['zone_h'], $data1['zone_m'], $data1['lon_h'], $data1['lon_m'], $data1['lat_h'], $data1['lat_m'], $data1['dst'], $data1['lon_e'], $data1['lat_s']);
      $returnArrTo = $Kundali->precalculate($toDate['month'], $toDate['day'], $toDate['year'], $toDate['hour'], $toDate['minute'], $data2['zone_h'], $data2['zone_m'], $data2['lon_h'], $data2['lon_m'], $data2['lat_h'], $data2['lat_m'], $data2['dst'], $data2['lon_e'], $data2['lat_s']);
      $pts = $Kundali->getpoints($returnArrFrom[9], $returnArrTo[9]);
      $finalPoints = array('points' => $pts, 'result' => $Kundali->interpret($pts));
      return array($returnArrFrom, $returnArrTo, $finalPoints);
  }
}