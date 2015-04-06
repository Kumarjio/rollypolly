<?php require_once(SITEDIR.'/Connections/connMain.php'); ?>
<?php
check_super_admin();
$pageTitle = 'Pending City Owners';

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
if (!empty($_GET['cty'])) {
  mysql_query(sprintf("update geo_city_owners set status = 1 where cty_id = %s", GetSQLValueString($_GET['cty'], 'int')));
}

mysql_select_db($database_connMain, $connMain);
$query_rsCityOwners = "SELECT geo_city_owners.owner_id, geo_city_owners.status, geo_city_owners.expiry_date, geo_city_owners.subs_expiry_date, geo_cities.cty_id as id, geo_cities.name as name, geo_cities.con_id as country_id, geo_cities.sta_id as state_id, geo_states.name as state, geo_countries.name as country FROM geo_city_owners LEFT JOIN geo_cities ON geo_city_owners.cty_id = geo_cities.cty_id LEFT JOIN geo_states ON geo_cities.sta_id = geo_states.sta_id LEFT JOIN  geo_countries ON geo_states.con_id = geo_countries.con_id WHERE geo_city_owners.status = 0";
$rsCityOwners = mysql_query($query_rsCityOwners, $connMain) or die(mysql_error());
$row_rsCityOwners = mysql_fetch_assoc($rsCityOwners);
$totalRows_rsCityOwners = mysql_num_rows($rsCityOwners);
?>
<div  style="font-size:11px;">
<h1>Pending City Owners</h1>
<p><a href="/superadmin/manage">Back</a></p>
<?php if ($totalRows_rsCityOwners > 0) { // Show if recordset not empty ?>
    <table border="1" cellpadding="5" cellspacing="0">
      <tr>
        <td><strong>owner_id</strong></td>
        <td><strong>status</strong></td>
        <td><strong>expiry_date</strong></td>
        <td><strong>subs_expiry_date</strong></td>
        <td><strong>id</strong></td>
        <td><strong>name</strong></td>
        <td><strong>Action</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rsCityOwners['owner_id']; ?></td>
          <td><?php echo $row_rsCityOwners['status']; ?></td>
          <td><?php echo $row_rsCityOwners['expiry_date']; ?></td>
          <td><?php echo $row_rsCityOwners['subs_expiry_date']; ?></td>
          <td><?php echo $row_rsCityOwners['id']; ?></td>
          <td><?php echo $row_rsCityOwners['name']; ?>, <?php echo $row_rsCityOwners['state']; ?>, <?php echo $row_rsCityOwners['country']; ?></td>
          <td><a href="/superadmin/locations/city_owners?cty=<?php echo $row_rsCityOwners['id']; ?>&status=1">Make Active</a></td>
        </tr>
        <?php } while ($row_rsCityOwners = mysql_fetch_assoc($rsCityOwners)); ?>
    </table>
<?php } // Show if recordset not empty ?>
</div>
<?php
mysql_free_result($rsCityOwners);
?>
