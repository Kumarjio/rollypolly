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
    $insertSQL = sprintf("INSERT INTO image_details (id, coordinates) VALUES (%s, %s)",
                         GetSQLValueString($_POST['id'], "int"),
                         GetSQLValueString($areas, "text"));
  
    mysql_select_db($database_connP2, $connP2);
    $Result1 = mysql_query($insertSQL, $connP2) or die(mysql_error());
  }

  $insertGoTo = "area.php";
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
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Area Markup</title>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../jquery.maphilight.js"></script>
<style type="text/css">
body {
 font-family:Verdana;
 font-size: 11px; 
}

.imglist {
  max-height: 70px;
}
</style>
<script language="javascript">
var counter = 1;
var coordsLength = 0;
var textarea = '';
function setCoordinates(e, status) {
	var x = e.pageX;
	var y = e.pageY;

	$('#dots').append('<img class="dot" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAADCAYAAABWKLW/AAAABGdBTUEAALGPC/xhBQAAABh0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjM2qefiJQAAACFJREFUGFdj9J/6KomBgUEYiN8yADmlQPwfRIM4SVCBJAAiRREoec4ImAAAAABJRU5ErkJggg==" style="left: '+ (x-1) +'px; top: '+ (y-1) +'px;" />');

	var offset = $('#imagemap4posis img').offset();
	x -= parseInt(offset.left);
	y -= parseInt(offset.top);
	if(x < 0) { x = 0; }
	if(y < 0) { y = 0; }
	
	var value = $('#coordsText').val();
	if(value == '') {
		value = x+','+y;
		coordsLength = value.length;
		counter++;
	} else {
		value = value+','+x+','+y;
		coordsLength = value.length;
	}
	if(status)
		$('#coordsText').val(value);
	
	if($('#area'+counter).length != 0)
		$('#area'+counter).remove();
	var countKomma = value.split(',').length;
	var shape = (countKomma <= 4) ? 'rect' : 'poly';
	if(countKomma >= 4) {
		var html = '<area shape="'+shape+'" id="area'+counter+'" class="area" coords="'+value+'" href="#" alt="" title="">';
		$('map').append(html);
	}
	
	$('#mapContainer').append($('.imgmapMainImage'));
	$('#mapContainer').children('div').remove();
	$('.imgmapMainImage').removeClass('maphilighted');
	//$('canvas').remove();

	hightlight();
	var mboxArea = '<input type="text" name="areas[]" value="'+value+'" size="50" style="display:block">';
	var area = '<area alt="" title="" href="#" shape="'+shape+'" coords="'+value+'" />\n';
	//var textarea = $('#areaText').val();
	if(textarea == '')
	{
		// create textarea context
		textarea = '<img src="url/to/your/image.jpg" alt="" usemap="#Map" />\n'
				+ '<map name="Map" id="Map">\n'
				+ '    ' + area
				+ '    [...]\n'
				+ '</map>';
    $('#maskedArea').append(mboxArea);
	}
	else 
	{
		if(countKomma == 2)
		{
			// new <area> context
			textarea = textarea.replace('[...]', area + '    [...]');
      $('#maskedArea').append(mboxArea);
		}
		else 
		{
			// edit / update <area> context
			var arr = value.split(',');
			var oldCoor = '';
			for(var i = 0; i < arr.length -2; i++)
			{
				if(i >= 1)
					oldCoor += ',';
				oldCoor += arr[i];
			}
			textarea = textarea.replace('shape="rect" coords="'+oldCoor, 'shape="'+shape+'" coords="'+oldCoor);
			textarea = textarea.replace(oldCoor, value);
      $('#maskedArea').find('input:last').remove();
    $('#maskedArea').append(mboxArea);
		}
	}
	$('#areaText').val(textarea);
}
function hightlight() {
	$('.imgmapMainImage').maphilight({
		strokeColor: '4F95EA',
		alwaysOn: true,
		fillColor: '365E71',
		fillOpacity: 0.2,
		shadow: true,
		shadowColor: '000000',
		shadowRadius: 5,
		shadowOpacity: 0.6,
		shadowPosition: 'outside'
	});
}

$(document).ready(function(){
  // set a coordinate point
	$(document.body).on('click', '.mapContainer', (function(e) {
		setCoordinates(e, 1);
		e.preventDefault();
	}));
  
	// Imagemap Generator Buttons
	$('.clearButton').click(function() {
		$('#coordsText').val('');
		$('#mapContainer').addClass('mapContainer');
	});
	$('.done').click(function() {
		$('#mapContainer').removeClass('mapContainer');
	});
  
	$('.clearCurrentButton').click(function() {
		$('#coordsText').val('');
		$('#mapContainer').find('area:last').remove();
		hightlight();
		
		// update textarea
		
		var textareaVal = $('#areaText').val();
		var tmpArr = textareaVal.split('<area');
		var lastCoords = tmpArr[tmpArr.length - 1].split('/>')[0];
		textarea = textareaVal.replace('<area' + lastCoords+'/>\n    ', '');
		$('#areaText').val(textarea);
    $('#maskedArea').find('input:last').remove();
	});

	// ...
	$('.clearAllButton').click(function() {
		$('#coordsText').val('');
		$('#areaText').val('');
		$('#mapContainer').find('map').empty();
		$('#dots').empty();
		hightlight();
		textarea = "";
    $('#maskedArea').html('');
	});
  hightlight();
});
</script>
</head>

<body>
<?php 
$areaMarker = array();
if ($totalRows_rsDetail > 0) { // Show if recordset not empty 
  do {
    $areaMarker[] = $row_rsDetail;
  } while ($row_rsDetail = mysql_fetch_assoc($rsDetail));
} // Show if recordset not empty ?>
          
<h1>Area and Details Manager</h1>
<p><a href="index.php">Back to Home Page</a> | <a href="main.php">Back To Main</a> | <a href="areaSmooth.php?id=<?php echo $_GET['id']; ?>">Back To Smooth Area Selection</a></p>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<table border="0" cellspacing="1" cellpadding="5">
  <tr>
    <td valign="top">
      <input type="button" name="btnNewArea" id="btnNewArea" value="Add New Area" class="clearButton">
      <input type="button" name="btnClearLast" id="btnClearLast" value="Clear Last" class="clearCurrentButton">
      <input type="button" name="btnClearAll" id="btnClearAll" value="Clear All" class="clearAllButton">
      <input type="button" name="btnDone" id="btnDone" value="Done" class="done">
      <br><br>
      <div id="imagemap4posis">
        <div id="mapContainer" class="effect">
          <img src="<?php echo $imageDir.$row_rsView['fileName']; ?>" id="main" class="imgmapMainImage" alt="" usemap="#map" />
          <map name="map" id="map">
            <?php if (!empty($areaMarker)) { // Show if recordset not empty ?>
            <?php foreach ($areaMarker as $row_rsDetail) { ?>
            <area shape="poly" id="area_<?php echo $row_rsDetail['detail_id']; ?>" class="area" coords="<?php echo $row_rsDetail['coordinates']; ?>" href="editArea.php?detail_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>" alt="" title="">
            <?php } ?>
            <?php } // Show if recordset not empty ?>
            
            </map>
          </div>
        </div>
    </td>
    <td valign="top"><p>
        <input id="coordsText" class="effect" name="" type="text" value="" size="50" placeholder="&laquo; Coordinates &raquo;" />
      </p>
      <p>
        <input type="text" name="areaText" size="50" id="areaText"></textarea>
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
          <td><strong>Tracking</strong></td>
          <td><strong>Edit Details</strong></td>
          <td><strong>Delete</strong></td>
        </tr>
            <?php foreach ($areaMarker as $row_rsDetail) { ?>
        <tr>
          <td><?php if (!empty($row_rsDetail['imageFile'])) { ?><img src="<?php echo $target_dir.$row_rsDetail['imageFile']; ?>" class="imglist" /><?php } ?>&nbsp;</td>
          <td><?php echo substr($row_rsDetail['coordinates'], 0, 20).'...'; ?></td>
          <td><a href="tracking.php?detail_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>">Tracking</a></td>
          <td><a href="editArea.php?detail_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>">Edit Details</a></td>
          <td><a href="area.php?delete_id=<?php echo $row_rsDetail['detail_id']; ?>&id=<?php echo $row_rsDetail['id']; ?>" onClick="var a = confirm('Do you really want to delete this area?'); return a;">Delete</a></td>
        </tr>
            <?php } ?>
      </table>
            <?php } // Show if recordset not empty ?>
     </td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<div id="dots"></div>

</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsDetail);
?>
