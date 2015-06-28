<?php require_once('../../Connections/connP2.php'); ?>
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

require_once('config.php');

$rsSettings = mysql_query("select * from settings WHERE setting_id = 1");
$recSettings = mysql_fetch_array($rsSettings);

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsView = $recSettings['maxRecordPerPage'];
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$colname_rsView = "%";
if (isset($_GET['id'])) {
  $colname_rsView = $_GET['id'];
}
mysql_select_db($database_connP2, $connP2);
$query_rsView = sprintf("SELECT * FROM main_image WHERE id LIKE %s", GetSQLValueString("%" . $colname_rsView . "%", "text"));
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connP2) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Front End</title>
</head>

<body>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<script type="text/javascript" src="js/jquery.maphilight.min.js"></script>
<script type="text/javascript">
    $(function () {
        //$('.mapHiLight').maphilight({ stroke: false, fillColor: '009DDF', fillOpacity: 1 });
        $('.map').maphilight();//,"alwaysOn":true
    });

var selwidth = 300;
var timeout;
$( document ).ready(function() {
/*
        $( "#contentData" )
          .mouseenter(function() {
            //console.log('mouseenter div');
              clearTimeout(timeout);
          })
          .mouseleave(function() {
            //console.log('mouseleave div');
            timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
          });
        
      $( "#contentData" ).dialog({
          autoOpen: false,
          position: { my: "left", at: "right", of: '#mainImage' },
          width: selwidth
      });*/
      /*$( "#position_1" ).mouseover(function() {
         clearTimeout(timeout);
         $('#contentbody').html('new text 1');
         $( "#contentData" ).dialog( "close" );
         $( "#contentData" ).dialog( "open" );
         timeout = setTimeout(function() {$( "#contentData" ).dialog( "close" )}, 4000);
      });*/
});
</script>

<style type="text/css">
.imglist {
  max-width: 100px;
}
.titleText {
  font-size:11px;
}
</style>
<style type="text/css">
body {
 font-family:Verdana;
 font-size: 11px; 
}
</style>

<h3>View Details</h3>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<p>No Record Found.</p>
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>

<?php do { ?>

<?php
if (!empty($row_rsView['resizeImg'])){
    $imageDir = IMAGEDIRNEW;
    $targetDir = IMAGEUPLOADDIRNEW;
} else {
    $imageDir = IMAGEDIR;
    $targetDir = IMAGEUPLOADDIR;
}
$imageSitePath = $targetDir.$row_rsView['fileName'];
$imageHttpPath = $imageDir.$row_rsView['fileName'];
$check = getimagesize($imageSitePath);


$rs = mysql_query("select * from image_details WHERE id = '".$row_rsView['id']."'");
$return = array();
if (mysql_num_rows($rs) > 0) {
    while ($rec = mysql_fetch_array($rs)) {
        $return[] = $rec;   
    }
}
?>
<p>
<img id="mainImage" src="<?php echo $imageDir.$row_rsView['fileName']; ?>" width="<?php echo $check[0]; ?>" height="<?php echo $check[1]; ?>" class="map" usemap="#Map_<?php echo $row_rsView['id']; ?>" />
</p>
<?php if (!empty($return)) { ?>
<?php
$initialArrayPosition = array('right', 'right bottom', 'left', 'left bottom');
?>
<map name="Map_<?php echo $row_rsView['id']; ?>">
<?php foreach ($return as $k => $v) { 
$target_dir = SUBIMAGEDIR.$row_rsView['id'].'/';
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
$url = !empty($dataDetails['url']) ? $dataDetails['url'] : '';
?>
<area shape="poly" coords="<?php echo $v['coordinates']; ?>" href="<?php echo $url; ?>" target="_blank" alt="Earings" id="position_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>">
<?php } ?>
</map>
<?php } ?>
<!-- div tags -->
<?php if (!empty($return)) { ?>
<?php foreach ($return as $k => $v) { 
$target_dir = SUBIMAGEDIR.$row_rsView['id'].'/';
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
$url = !empty($dataDetails['url']) ? $dataDetails['url'] : '';
?>
<div id="dailog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" title="<?php echo $dataDetails['modelNumber']; ?>">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="<?php echo $target_dir.$v['imageFile']; ?>" class="imglist" /></td>
    <td valign="top"><b><?php echo $dataDetails['itemDescription']; ?></b><br /><a href="<?php echo $url; ?>" target="_blank" class="titleText"><?php echo $url; ?></a></td>
  </tr>
</table>
</div>
<?php } ?>
<?php } ?>
<!-- div tags ends -->

<div id="dialog-hat" title="Hat">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/womenshat2.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Women's Helen Kaminski '9 Kaelo' Raffia Straw Hat</b></a><br /><a href="http://nordstrom.com" target="_blank" class="titleText">nordstrom.com</a></td>
  </tr>
</table>
</div>
<div id="dialog-earing" title="Earrings">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/square-earrings-gold2.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Gold Square Dangle Set</b></a><br /><a href="http://gilt.com" target="_blank" class="titleText">gilt.com</a></td>
  </tr>
</table>
</div>
<div id="dialog-pinktop" title="Pink Tank Top">

<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="../p1/docs/pinktop.jpg" class="imglist" /></td>
    <td valign="top"><a href="#" class="titleText"><b>Bella - Ladies' Flowy Racerback Tank -8800</b></a><br /><a href="http://customtshirtprinting.com" target="_blank" class="titleText">customtshirtprinting.com</a></td>
  </tr>
</table>
</div>


<!-- js tags -->
<?php if (!empty($return)) { ?>
<?php foreach ($return as $k => $v) { 
//checking array position to show
if (empty($arrayPosition)) {
    $arrayPosition = $initialArrayPosition;   
}
$position = array_shift($arrayPosition);
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
?>
<script>
   $(function() {
       $( "#dailog<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog({
          autoOpen: false,
          position: { my: "center", at: "<?php echo $position; ?>", of: window },
          width: selwidth
      });
      $( "#position_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).mouseover(function() {
         //clearTimeout(timeout);
         $( "#dailog<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "open" );
         //timeout = setTimeout(function() {$( "#dailog<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "close" )}, 4000);
      });
   });
</script>
<?php } ?>
<?php } ?>
<!-- js tags ends -->

<img src="../p1/docs/main.jpg" width="800" height="527" usemap="#Map" />
<map name="Map">
  <area shape="poly" coords="164,224,212,170,206,77,276,28,324,19,372,28,391,54,404,87,415,111,451,143,497,173,475,195,442,212,416,227,414,184,427,159,389,151,316,162,260,172" href="http://google.com" target="_blank" alt="Hat" id="hat">
  <area shape="poly" coords="260,205,258,228,264,254,267,268,274,265,269,258" href="http://yahoo.com" target="_blank" alt="Earings" id="earing">
  <area shape="poly" coords="103,342,153,331,147,375,150,400,165,433,188,461,228,473,272,470,312,460,340,437,355,415,367,391,383,360,398,337,407,316,438,325,454,333,453,360,444,387,434,431,428,475,422,509,423,523,381,523,310,522,230,524,140,522,124,525,123,498,115,461,109,438,103,402,99,376" href="http://msn.com" target="_blank" alt="Pink Top" id="pinktop">
</map>
<script>
   $(function() {
      selwidth = 300;
      $( "#dialog-hat" ).dialog({
          autoOpen: false,
          position: { my: "center", at: "right", of: window },
          width: selwidth
      });
      $( "#hat" ).mouseover(function() {
         $( "#dialog-hat" ).dialog( "open" );
      });
      $( "#dialog-earing" ).dialog({
         autoOpen: false,
          position: { my: "center", at: "right bottom", of: window },
          width: selwidth
      });
      $( "#earing" ).mouseover(function() {
         $( "#dialog-earing" ).dialog( "open" );
      });
      $( "#dialog-pinktop" ).dialog({
         autoOpen: false,
          position: { my: "center", at: "left", of: window },
          width: selwidth
      });
      $( "#pinktop" ).mouseover(function() {
         $( "#dialog-pinktop" ).dialog( "open" );
      });
   });
</script>

    <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
<p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?> &nbsp;
</p>
<table border="0">
    <tr>
        <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
                <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
                <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
                <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
                <?php } // Show if not last page ?></td>
    </tr>
</table>
<?php } // Show if recordset not empty ?>

</body>
</html>
<?php
mysql_free_result($rsView);
?>
