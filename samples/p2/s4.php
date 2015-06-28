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
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Freelancer - Start Bootstrap Theme</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#page-top">Domain.com</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#portfolio">Portfolio</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#about">About</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">


<h3>View Details</h3>
<?php if ($totalRows_rsView == 0) { // Show if recordset empty ?>
<p>No Record Found.</p>
<?php } // Show if recordset empty ?>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
<?php do { ?>
<?php
$arrayPosition = $initialArrayPosition; 
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



<center><img src="<?php echo $imageDir.$row_rsView['fileName']; ?>" width="<?php echo $check[0]; ?>" height="<?php echo $check[1]; ?>" usemap="#Map_<?php echo $row_rsView['id']; ?>" id="mapMainImage_<?php echo $row_rsView['id']; ?>" class="map" /></center>
<?php if (!empty($return)) { ?>
<map name="Map_<?php echo $row_rsView['id']; ?>">
<?php foreach ($return as $k => $v) { 
$target_dir = SUBIMAGEDIR.$row_rsView['id'].'/';
$dataDetails = !empty($v['extraFields']) ? json_decode($v['extraFields'], 1): null;
$url = !empty($dataDetails['url']) ? $dataDetails['url'] : '';
?>
  <area shape="poly" coords="<?php echo $v['coordinates']; ?>" href="<?php echo $url; ?>" target="_blank" id="pos_<?php echo $row_rsView['id']; ?>_<?php echo $v['detail_id']; ?>"  data-maphilight='{"fill":false,"stroke":false}'>
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
            position: { my: "<?php echo $position['my']; ?>", at: "<?php echo $position['at']; ?>", of: '#mapMainImage_<?php echo $row_rsView['id']; ?>', collision: "flipfit"},
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
<br><br><br>
<br><br><br>
<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
<script type="text/javascript">
    $(function () {
        //$('.mapHiLight').maphilight({ stroke: false, fillColor: '009DDF', fillOpacity: 1 });
        $('.map').maphilight();//,"alwaysOn":true
    });

</script>
<p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?> &nbsp;
</p>
<table border="0" align="center">
    <tr>
        <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>" style="color:#00F">First</a>
                <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>" style="color:#00F">Previous</a>
                <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>" style="color:#00F">Next</a>
                <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>" style="color:#00F">Last</a>
                <?php } // Show if not last page ?></td>
    </tr>
</table>
<?php } // Show if recordset not empty ?>


                </div>
                <div class="col-lg-12">
                    <img class="img-responsive" src="img/profile.png" alt="">
                    <div class="intro-text">
                        <span class="name">Domain.com</span>
                        <hr class="star-light">
                        <span class="skills">Web Developer - Graphic Artist - User Experience Designer</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Portfolio</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/cabin.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/cake.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/circus.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/game.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/safe.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/submarine.png" class="img-responsive" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>About</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-2">
                    <p>Freelancer is a free bootstrap theme created by Start Bootstrap. The download includes the complete source files including HTML, CSS, and JavaScript as well as optional LESS stylesheets for easy customization.</p>
                </div>
                <div class="col-lg-4">
                    <p>Whether you're a student looking to showcase your work, a professional looking to attract clients, or a graphic artist looking to share your projects, this template is the perfect starting point!</p>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="#" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i> Download Theme
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Contact Me</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" id="name" required data-validation-required-message="Please enter your name.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Email Address</label>
                                <input type="email" class="form-control" placeholder="Email Address" id="email" required data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Phone Number" id="phone" required data-validation-required-message="Please enter your phone number.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Message</label>
                                <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>Location</h3>
                        <p>3481 Melrose Place<br>Beverly Hills, CA 90210</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>About Freelancer</h3>
                        <p>Freelance is a free to use, open source Bootstrap theme created by <a href="http://startbootstrap.com">Start Bootstrap</a>.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Your Website 2014
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visble-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- Portfolio Modals -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/cabin.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/cake.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/circus.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/game.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/safe.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Title</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/submarine.png" class="img-responsive img-centered" alt="">
                            <p>Use this area of the page to describe your project. The icon above is part of a free icon set by <a href="https://sellfy.com/p/8Q9P/jV3VZ/">Flat Icons</a>. On their website, you can download their free set with 16 icons, or you can purchase the entire set with 146 icons for only $12!</p>
                            <ul class="list-inline item-details">
                                <li>Client:
                                    <strong><a href="http://startbootstrap.com">Start Bootstrap</a>
                                    </strong>
                                </li>
                                <li>Date:
                                    <strong><a href="http://startbootstrap.com">April 2014</a>
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong><a href="http://startbootstrap.com">Web Development</a>
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
