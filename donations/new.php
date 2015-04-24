<?php require_once('../Connections/connWork.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php

if (!function_exists("guid")) {
function guid()
{
    mt_srand((double) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $guid = substr($charid, 0, 8) . '-' .
            substr($charid, 8, 4) . '-' .
            substr($charid, 12, 4) . '-' .
            substr($charid, 16, 4) . '-' .
            substr($charid, 20, 12);
   return $guid;
}
}

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (empty($_FILES["fileToUpload"]["name"])) {
    $error = "Please choose the file to upload.";
    unset($_POST["MM_insert"]);
  }
}

$target_dir = 'images/'.$_SESSION['MM_UserId'].'/';
$target_dirThumbs = 'images/'.$_SESSION['MM_UserId'].'/thumbs/';
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755);
    chmod($target_dir, 0755);
  }
  if (!is_dir($target_dirThumbs)) {
    mkdir($target_dirThumbs, 0755);
    chmod($target_dirThumbs, 0755);
  }
  $filename = 'f_'.time().'_'.basename($_FILES["fileToUpload"]["name"]);
  $_POST['fileToUpload'] = $filename;
  $target_file = $target_dir . $filename;
  $target_fileThumbs = $target_dirThumbs . $filename;
  $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if(empty($check)) {
      $error = "File is not an image.";
      unset($_POST["MM_insert"]);
  }
  // Check if file already exists
  //if (file_exists($target_file)) {
      //$error = "Sorry, file already exists.";
      //unset($_POST["MM_insert"]);
  //}
  // Check file size
  /*if ($_FILES["fileToUpload"]["size"] > 500000) {
      $error = "Sorry, your file is too large.";
      unset($_POST["MM_insert"]);
  }*/
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      unset($_POST["MM_insert"]);
  }
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
  include('SimpleImage.php');
  $image = new SimpleImage();
  $image->load($target_file);
  $image->resize(230,153);
  $image->save($target_fileThumbs);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $did = guid();
  $insertSQL = sprintf("INSERT INTO donations (did, user_id, donation_title, donation_desc, donation_needed, donation_category_id, donation_image, donation_paypal_email, city, state, country, lat, lng) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($did, "text"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['donation_title'], "text"),
                       GetSQLValueString($_POST['donation_desc'], "text"),
                       GetSQLValueString($_POST['donation_needed'], "double"),
                       GetSQLValueString($_POST['donation_category_id'], "int"),
                       GetSQLValueString($_POST['fileToUpload'], "text"),
                       GetSQLValueString($_POST['donation_paypal_email'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lng'], "double"));

  mysql_select_db($database_connWork, $connWork);
  $Result1 = mysql_query($insertSQL, $connWork) or die(mysql_error());

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertGoTo = "newConfirm.php?did=".$did;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  exit;
}

mysql_select_db($database_connWork, $connWork);
$query_rsCategory = "SELECT * FROM donations_category ORDER BY category ASC";
$rsCategory = mysql_query($query_rsCategory, $connWork) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);

$colname_rsUser = "-1";
if (isset($_SESSION['MM_UserId'])) {
  $colname_rsUser = $_SESSION['MM_UserId'];
}
mysql_select_db($database_connWork, $connWork);
$query_rsUser = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUser, "int"));
$rsUser = mysql_query($query_rsUser, $connWork) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/Donations_theme1.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>New Donation Request</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/theme1.css" rel="stylesheet">
<link href="assets/css/site.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="shortcut icon" href="assets/ico/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/ico/favicon.ico" type="image/x-icon">

<?php 
require_once('inc_category.php'); 

?>

<!-- InstanceBeginEditable name="head" -->
<meta charset="UTF-8">

<!-- InstanceEndEditable -->
</head>
<body>
<div class="wrap">
<section>
<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">godonateme.com</a>
    </div>
    
    <?php include('inc_menu.php'); ?>
    
  </div>
</nav>
</section>
<section class="top-section">
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php include('inc_googleadsense.php'); ?>
    </div>
  </div>
</div>
</section>

<section>
<div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
        <?php echo $leftSideCategoryLink; ?>
      </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<!-- InstanceBeginEditable name="EditRegion5" -->

<!-- InstanceEndEditable -->


<!-- InstanceBeginEditable name="EditRegion4" -->

<!-- InstanceEndEditable -->

<div class="row">
<div class="col-lg-12">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">

<div class="row">
    <div class="col-lg-12">
        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">New Fund Request</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
<?php if (!empty($error)) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>
                      <div class="form-group">
                          <strong>Fund Title</strong> <br />
                          <input type="text" name="donation_title" id="donation_title" required class="inputText" />
                      </div>

                      <div class="form-group">
                          <strong>Why you need Fund?</strong> <br />
                          <textarea name="donation_desc" cols="50" rows="5" required class="inputText"></textarea>
                      </div>

                      <div class="form-group">
                          <strong>How much funds you need?</strong> <br />
                          <input type="text" name="donation_needed" value="" size="32" required class="inputText">
                      </div>

                      <div class="form-group">
                          <strong>Category</strong> <br />
                          <select name="donation_category_id" required>
                              <option value="">Select</option>
                              <?php do { ?>
                                  <option value="<?php echo $row_rsCategory['category_id']?>"><?php echo $row_rsCategory['category']?></option>
                              <?php
                                } while ($row_rsCategory = mysql_fetch_assoc($rsCategory));
                                  $rows = mysql_num_rows($rsCategory);
                                  if($rows > 0) {
                                      mysql_data_seek($rsCategory, 0);
                                    $row_rsCategory = mysql_fetch_assoc($rsCategory);
                                  }
                                ?>
                          </select>
                      </div>

                      <div class="form-group">
                          <strong>Image</strong> <br />
                          <input type="file" name="fileToUpload" id="fileToUpload" required class="inputText">
                      </div>

                      <div class="form-group">
                          <strong>Paypal Email Address</strong> <br />
                          <input name="donation_paypal_email" type="text" id="donation_paypal_email" value="<?php echo $row_rsUser['paypal_email']; ?>" class="inputText">
                      </div>

                      <div class="form-group">
                          <strong>Your Location</strong> <br />
                          <input name="location" type="text" id="location" value="" onFocus="geolocate()" class="inputText addressBox" required>
                          <input type="hidden" id="city" name="city" value="" />
                          <input type="hidden" id="state" name="state" value="" />
                          <input type="hidden" id="country" name="country" value="" />
                          <input type="hidden" id="lat" name="lat" value="" />
                          <input type="hidden" id="lng" name="lng" value="" />
                      </div>

                  </div>
               </div>
            </div>

        </div>
     </div>
</div>

<input type="submit" value="Create New Fund Request" class="inputText">
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['MM_UserId']; ?>">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<!-- InstanceEndEditable -->
</div>
</div>


</div><!-- middle col -->

<?php include('inc_featured.php'); ?>

</div><!-- / inner .row -->
</div>
</section>

<section class="custom-footer">
<?php include('inc_footer.php'); ?>
</section>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.js"></script>
<!-- InstanceBeginEditable name="EditRegionJS" -->
<script src="//maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
<script language="javascript">

//autocomplete

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};
function init() {
      // Create the autocomplete object, restricting the search
      // to geographical location types.
      autocomplete = new google.maps.places.Autocomplete(
          /** @type {HTMLInputElement} */(document.getElementById('location')),
          { types: ['geocode'] });
      // When the user selects an address from the dropdown,
      // populate the address fields in the form.
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        fillInAddress();
      });
    }

function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace();
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();
      $('#lat').val(lat);
      $('#lng').val(lng);
      for (key in place.address_components) {
        var loc = place.address_components[key];
        if (loc.types[0] === "locality") {
          $('#city').val(loc.long_name);
        } else if (loc.types[0] === "administrative_area_level_1") {
          $('#state').val(loc.long_name);
        } else if (loc.types[0] === "country") {
          $('#country').val(loc.long_name);
        } else {
          continue;
        }
      }
      return;
    }

function geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var geolocation = new google.maps.LatLng(
              position.coords.latitude, position.coords.longitude);
          autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
              geolocation));
        });
      }
    }
google.maps.event.addDomListener(window, 'load', init);

$(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        var str = e.target.className;
        var n = str.indexOf("addressBox");
        if (n === -1) {
          return true;
        } else {
          return false;
        }
        return true;
    }
});
</script>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsCategory);

mysql_free_result($rsUser);
?>
