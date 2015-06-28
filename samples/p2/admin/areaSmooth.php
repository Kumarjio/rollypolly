<?php require_once('../../../Connections/connP2.php'); ?>
<?php
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

require_once('../config.php');

$id = !empty($_GET['id']) ? $_GET['id'] : '';
$target_dir = SUBIMAGEDIR.$id.'/';

if ((isset($_GET['delete_id'])) && ($_GET['delete_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM image_details WHERE detail_id=%s",
                       GetSQLValueString($_GET['delete_id'], "int"));

  mysql_select_db($database_connP2, $connP2);
  $Result1 = mysql_query($deleteSQL, $connP2) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (empty($_POST['areas'])) {
    unset($_POST["MM_insert"]);
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  foreach ($_POST['areas'] as $areas) {
    if (empty($areas)) {
      continue;
    }
    $insertSQL = sprintf("INSERT INTO image_details (id, coordinates) VALUES (%s, %s)",
                         GetSQLValueString($_POST['id'], "int"),
                         GetSQLValueString($areas, "text"));
  
    mysql_select_db($database_connP2, $connP2);
    $Result1 = mysql_query($insertSQL, $connP2) or die(mysql_error());
  }

  $insertGoTo = "areaSmooth.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$colname_rsView = "-1";
if (isset($_GET['id'])) {
  $colname_rsView = $_GET['id'];
}
mysql_select_db($database_connP2, $connP2);
$query_rsView = sprintf("SELECT * FROM main_image WHERE id = %s", GetSQLValueString($colname_rsView, "int"));
$rsView = mysql_query($query_rsView, $connP2) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

if (!empty($row_rsView['resizeImg'])){
    $imageDir = IMAGEDIRNEW;
} else {
    $imageDir = IMAGEDIR;
}


$colname_rsDetail = "-1";
if (isset($_GET['id'])) {
  $colname_rsDetail = $_GET['id'];
}
mysql_select_db($database_connP2, $connP2);
$query_rsDetail = sprintf("SELECT * FROM image_details WHERE id = %s", GetSQLValueString($colname_rsDetail, "int"));
$rsDetail = mysql_query($query_rsDetail, $connP2) or die(mysql_error());
$row_rsDetail = mysql_fetch_assoc($rsDetail);
$totalRows_rsDetail = mysql_num_rows($rsDetail);

$imageFile = $imageDir.$row_rsView['fileName'];
$imageSize = getimagesize($imageFile);
?>
<?php 
$areaMarker = array();
if ($totalRows_rsDetail > 0) { // Show if recordset not empty 
  do {
    $areaMarker[] = $row_rsDetail;
  } while ($row_rsDetail = mysql_fetch_assoc($rsDetail));
} // Show if recordset not empty ?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Area Markup</title>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../jquery.maphilight.js"></script>
<script src="./areaSmooth.js"></script>
<style type="text/css">
body {
 font-family:Verdana;
 font-size: 11px; 
}

.imglist {
  max-height: 70px;
}

.pointer {
    cursor: pointer;
}

</style>
<script language="javascript">
$(document).ready(function(){
  //added
  init('<?php echo $imageDir.$row_rsView['fileName']; ?>');
  <?php if (!empty($areaMarker)) { // Show if recordset not empty
  foreach ($areaMarker as $row_rsDetail) {
          $arr = explode(',', $row_rsDetail['coordinates']);
          $arr2 = array();
          foreach ($arr as $k => $v) {
            if ($k % 2 == 0) {
              $arr2[] = array('x' => $v, 'y' => $arr[$k + 1]);
            }
          }
          foreach ($arr2 as $k => $v) {
            if (isset($arr2[$k + 1])) {
              ?>
              //console.log('<?php echo $k; ?> - <?php echo $v['x']; ?>, <?php echo $v['y']; ?>, <?php echo $arr2[$k+1]['x']; ?>, <?php echo $arr2[$k+1]['y']; ?>');
             drawPast('<?php echo $v['x']; ?>', '<?php echo $v['y']; ?>', '<?php echo $arr2[$k+1]['x']; ?>', '<?php echo $arr2[$k+1]['y']; ?>');
              <?php
            } else {
              ?>
              <?php
            }
          }
          ?>
    <?php } 
        } // Show if recordset not empty ?>
   setTimeout(resetSettings, 3000);
   
});
</script>
<style>
      .canvasMap{
        width : <?php echo $imageSize[0]; ?>px;
        height : <?php echo $imageSize[1]; ?>px;
      }
    </style>
</head>

<body>
          
<h1>Area and Details Manager</h1>
<p><a href="main.php">Back To Main</a> | <a href="area.php?id=<?php echo $_GET['id']; ?>">Back To Normal Area Selection</a></p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top">
    
      <div id="newContainer">
        <canvas id="can" width="<?php echo $imageSize[0]; ?>" height="<?php echo $imageSize[1]; ?>" class="pointer canvasMap" style="border:2px solid;"></canvas>
      <img id="image" class="canvasMap" src="trans.gif" usemap="#map"/>
        <map name="map">
          <?php if (!empty($areaMarker)) { // Show if recordset not empty ?>
            <?php foreach ($areaMarker as $row_rsDetail) { ?>
            <area shape="poly" id="area_<?php echo $row_rsDetail['detail_id']; ?>" class="area" coords="<?php echo $row_rsDetail['coordinates']; ?>" href="editArea.php?detail_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>" alt="" title="">
            <?php } ?>
            <?php } // Show if recordset not empty ?>
      </map>
      </div>
      </td>
    <td valign="top"><p>
        <input id="coordsText" class="effect" name="" type="text" value="" size="50" placeholder="&laquo; Coordinates &raquo;" />
      </p>
      <p>
        <input type="submit" name="submit" id="submit" value="Submit">
        <input type="hidden" name="coordinates" id="coordinates">
        <input name="id" type="hidden" id="id" value="<?php echo $_GET['id']; ?>">
      </p>
      <div id="maskedArea"></div>
      <?php if (!empty($areaMarker)) { // Show if recordset not empty ?>
      <table width="100%" border="1" cellpadding="5" cellspacing="1">
        <tr>
          <td>&nbsp;</td>
          <td><strong>Coordinates</strong></td>
          <td><strong>Edit Details</strong></td>
          <td><strong>Delete</strong></td>
        </tr>
            <?php foreach ($areaMarker as $row_rsDetail) { ?>
        <tr>
          <td><?php if (!empty($row_rsDetail['imageFile'])) { ?><img src="<?php echo $target_dir.$row_rsDetail['imageFile']; ?>" class="imglist" /><?php } ?>&nbsp;</td>
          <td><?php echo substr($row_rsDetail['coordinates'], 0, 20).'...'; ?>
          </td>
          <td><a href="editArea.php?detail_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>">Edit Details</a></td>
          <td><a href="areaSmooth.php?delete_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>" onClick="var a = confirm('Do you really want to delete this area?'); return a;">Delete</a></td>
        </tr>
            <?php } ?>
      </table>
            <?php } // Show if recordset not empty ?>
     </td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>

</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsDetail);
?>
