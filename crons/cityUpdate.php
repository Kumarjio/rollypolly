<?php
include_once('../init.php');
$maxRows_rsView = 5;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT * FROM geo_cities WHERE extraDetails IS NULL AND con_id = 223 ORDER BY rand()";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td>cty_id</td>
      <td>sta_id</td>
      <td>con_id</td>
      <td>name</td>
      <td>latitude</td>
      <td>longitude</td>
      <td>extraDetails</td>
      <td>lat_h</td>
      <td>lat_m</td>
      <td>lat_s</td>
      <td>lon_h</td>
      <td>lon_m</td>
      <td>lon_e</td>
      <td>zone_h</td>
      <td>zone_m</td>
      <td>dst</td>
      <td>rawOffset</td>
      <td>dstOffset</td>
    </tr>
    <?php do { 
    $row_rsView2 = findCity($row_rsView['cty_id']);
    ?>
      <tr>
        <td><?php echo $row_rsView2['cty_id']; ?></td>
        <td><?php echo $row_rsView2['sta_id']; ?></td>
        <td><?php echo $row_rsView2['con_id']; ?></td>
        <td><?php echo $row_rsView2['name']; ?></td>
        <td><?php echo $row_rsView2['latitude']; ?></td>
        <td><?php echo $row_rsView2['longitude']; ?></td>
        <td><?php echo $row_rsView2['extraDetails']; ?></td>
        <td><?php echo $row_rsView2['lat_h']; ?></td>
        <td><?php echo $row_rsView2['lat_m']; ?></td>
        <td><?php echo $row_rsView2['lat_s']; ?></td>
        <td><?php echo $row_rsView2['lon_h']; ?></td>
        <td><?php echo $row_rsView2['lon_m']; ?></td>
        <td><?php echo $row_rsView2['lon_e']; ?></td>
        <td><?php echo $row_rsView2['zone_h']; ?></td>
        <td><?php echo $row_rsView2['zone_m']; ?></td>
        <td><?php echo $row_rsView2['dst']; ?></td>
        <td><?php echo $row_rsView['rawOffset']; ?></td>
        <td><?php echo $row_rsView2['dstOffset']; ?></td>
      </tr>
      <?php 
    sleep(5);
    } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <meta http-equiv="refresh" content="500">

</body>
</html>
<?php
mysql_free_result($rsView);
?>
