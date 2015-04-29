<?php
include('../config.php');
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/groupjole_theme2.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Registration Confirmed</title>
<!-- InstanceEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<!-- GOOGLE FONT-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- /GOOGLE FONT-->


<!-- Le styles -->
<!-- Latest compiled and minified CSS BS 3.0. -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/theme2.css" rel="stylesheet">
<link href="../assets/css/site.css" rel="stylesheet">



<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!-- Fav and touch icons -->


<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="//maps.google.com/maps/api/js?sensor=false&libraries=places"></script>

<script src="../assets/js/ang/app.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="../assets/js/bootstrap.js"></script>

<script src="../assets/js/googleMap.js"></script>

<!-- InstanceBeginEditable name="head" -->
<meta charset="UTF-8">

<!-- InstanceEndEditable -->
</head>
<body data-ng-app="GroupJole">
<div class="wrap">
	<section>
		<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">GroupJole.Com</a>
				</div>
				<?php include('../includes/topMenu.php'); ?>
			</div>
		</nav>
	</section>
	<section class="top-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<h4>
						(Create Your Groups & Events)
					</h4>
				</div>
				<div class="col-lg-8">
					<form class="navbar-form ng-pristine ng-valid pull-right" role="search" action="../index.php" method="get">
						<div class="form-group">
							<input type="text" name="keyword" id="keyword" class="form-control widthAuto" placeholder="Enter Keyword ...." value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" />
							<input type="text" name="addressID" id="addressID" class="form-control widthAuto addressBox" placeholder="Enter City Name ...."  onFocus="geolocate()" value="<?php echo !empty($_GET['addressID']) ? $_GET['addressID'] : ''; ?>" /><input type="hidden" name="s_lat" id="s_lat" value="<?php echo !empty($_GET['s_lat']) ? $_GET['s_lat'] : ''; ?>" /><input type="hidden" name="s_lng" id="s_lng" value="<?php echo !empty($_GET['s_lng']) ? $_GET['s_lng'] : ''; ?>" />
						</div> <button type="submit" class="btn btn-default">Search</button>
					</form>
				</div>
			</div>
       
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
			    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
					<?php include('../includes/offers.php'); ?>
					<?php include('../includes/more.php'); ?>
				</div>
                
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-9 hidden-xs">
                    <div class="page-header">
                    <!-- InstanceBeginEditable name="EditRegionSubHead" -->
                        <h3> Register Confirmation<small></small> </h3>
                    <!-- InstanceEndEditable -->
                    </div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<p>You are successfully registered to our website.</p>

<!-- InstanceEndEditable -->
		        </div>
				
			</div>
			<hr>
		</div>
	</section>
	<section class="custom-footer">
		<div class="container">
			<div class="row">
				
				<?php include('../includes/footerLinks.php'); ?>
				<?php include('../includes/bottomAddress.php'); ?>
			</div>
		</div>
	</section>
</div>

</body>
<!-- InstanceEnd --></html>