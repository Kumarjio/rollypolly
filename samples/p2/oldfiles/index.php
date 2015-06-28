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
$query_rsView = sprintf("SELECT * FROM main_image WHERE id LIKE %s ORDER BY defaultRecord DESC", GetSQLValueString("%" . $colname_rsView . "%", "text"));
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


$initialArrayPosition = array(array('my' => 'left', 'at' => 'right top'), array('my' => 'left', 'at' => 'right center'), array('my' => 'left', 'at' => 'right bottom'), array('my' => 'left', 'at' => 'left top'), array('my' => 'left', 'at' => 'left center'), array('my' => 'left', 'at' => 'left bottom'));
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

<?php if (!empty($return)) { ?>
<?php foreach ($return as $k => $v) { ?>
<?php 
$target_dir = SUBIMAGEDIR.$row_rsView['id'].'/';
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
$url = !empty($dataDetails['url']) ? $dataDetails['url'] : '';
?>
<div id="dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" title="<?php echo $dataDetails['modelNumber']; ?>">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top"><img src="<?php echo $target_dir.$v['imageFile']; ?>" class="imglist" /></td>
    <td valign="top"><b><?php echo $dataDetails['itemDescription']; ?></b><br /><a href="redirect.php?id=<?php echo $row_rsView['id']; ?>&did=<?php echo $v['detail_id']; ?>&url=<?php echo urlencode($url); ?>" target="_blank" class="titleText"><?php echo $url; ?></a></td>
  </tr>
</table>
</div>
<?php } ?>
<?php } ?>



<img src="<?php echo $imageDir.$row_rsView['fileName']; ?>" width="<?php echo $check[0]; ?>" height="<?php echo $check[1]; ?>" usemap="#Map_<?php echo $row_rsView['id']; ?>" id="mapMainImage" class="map" />
<?php if (!empty($return)) { ?>
<map name="Map_<?php echo $row_rsView['id']; ?>">
<?php foreach ($return as $k => $v) { 
$target_dir = SUBIMAGEDIR.$row_rsView['id'].'/';
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
$url = !empty($dataDetails['url']) ? $dataDetails['url'] : '';
?>
  <area shape="poly" coords="<?php echo $v['coordinates']; ?>" href="<?php echo $url; ?>" target="_blank" id="pos_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>"  data-maphilight='{"strokeColor":"0000ff","strokeWidth":2,"fillColor":"ff0000","fillOpacity":0.3,"alwaysOn":false}'>
<?php }//end foreach ?>
</map>
<?php }//end if return ?>
<style type="text/css">
/*.ui-icon-closethick {
    background-image: url(http://png-4.findicons.com/files/icons/1686/led/16/pin.png) !important;
    background-position: left top !important;
    margin: 0 !important;
    left: 0 !important;
    top: 0 !important;
}*/
</style>
<script>
   $(function() {
    var selwidth = 300;
    var lifetime = 4000;
      var timeoutStr = {};
      var btnState = {};
      var dailogState = {};
      <?php if (!empty($return)) { ?>
      <?php foreach ($return as $k => $v) { ?>
      <?php
        //checking array position to show
        if (empty($arrayPosition)) {
            $arrayPosition = $initialArrayPosition;   
        }
        $position = array_shift($arrayPosition);
        ?>
        timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
        btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
        dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
      $( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog({
            autoOpen: false,
            position: { my: "<?php echo $position['my']; ?>", at: "<?php echo $position['at']; ?>", of: '#mapMainImage' },
            width: selwidth,
            closeOnEscape: false,
            hide: { effect: "fade", duration: 1000 },
            buttons: [
                {
                  id: "btn_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>",
                  text: "Pin",
                  icons: {
                    primary: "ui-icon-pin-s"//ui-icon-pin-s
                  },
                  click: function() {
                    if (!btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']) {
						//console.log('1');
						dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = 1;
						//console.log('dailog: ' + dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
                        clearTimeout(timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
                        btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = 1;
                        $("#btn_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?> span")
                          .removeClass("ui-icon-pin-s")
                          .addClass("ui-icon-pin-w");
                    } else {
						//console.log('2');
						dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
						//console.log('dailog: ' + dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
                       btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null; 
                       timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = setTimeout(function() {$( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "close" )}, lifetime);
                       $("#btn_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?> span")
                          .removeClass("ui-icon-pin-w")
                          .addClass("ui-icon-pin-s");
                    }
                    //console.log('state: ' + btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
                  },
                
                  // Uncommenting the following line would hide the text,
                  // resulting in the label being used as a tooltip
                  showText: false
                }
            ],
			beforeClose: function () {
				//console.log('dailog1: ' + dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
				dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
				//console.log('dailog2: ' + dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
				clearTimeout(timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
                btnState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = null;
				$("#btn_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?> span")
                          .removeClass("ui-icon-pin-w")
                          .addClass("ui-icon-pin-s");
			}
            /*beforeClose: function () {
                return false;
            }
            create: function(event, ui) { 
                var widget = $(this).dialog("widget");
                $(".ui-dialog-titlebar-close span", widget)
                  .removeClass("ui-icon-closethick")
                  .addClass("ui-icon-minusthick");
            }*/
      });
      $( "#pos_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).mouseover(function() {
	  		if (dailogState['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] == 1) {
				//console.log('dailog no move');
				return false;
			}
			//console.log('dailog move');
            clearTimeout(timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
            $( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "open" );
            timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = setTimeout(function() {$( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "close" )}, lifetime);
      });
      $( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" )
          .mouseenter(function() {
            //console.log('mouseenter div');
            clearTimeout(timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>']);
          })
          .mouseleave(function() {
            //console.log('mouseleave div');
            timeoutStr['timeout_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>'] = setTimeout(function() {$( "#dialog_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>" ).dialog( "close" )}, lifetime);
          });
      <?php } ?>
      <?php } ?>
   });
</script>
<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
<script type="text/javascript">
    $(function () {
        //$('.mapHiLight').maphilight({ stroke: false, fillColor: '009DDF', fillOpacity: 1 });
        $('.map').maphilight();//,"alwaysOn":true
    });

</script>
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

