
<!DOCTYPE html>
<!-- saved from url http://www.bootstraptor.com ##########################################################################
Don't remove this attribution!
This template build on Bootstrap 3 Developer  Kit v.3.0. by @Bootstraptor
Built with Bootstrap 3.0. stable version/ part of Bootstraptor KIT
Read usage license on for this template on http://www.bootstraptor.com 
##########################################################################
-->
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Base page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
<!-- GOOGLE FONT-->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!-- /GOOGLE FONT-->


<!-- Le styles -->
<!-- Latest compiled and minified CSS BS 3.0. -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
      }
	  
	header .jumbotron{
		background:#358cce;
		color:#fff;
		border-radius:0px;
	}
	header .jumbotron small{
		color:#fff;
	}
	.main-color {
		color:#358cce;
	}
	 #map {
		width:100%;
		height:250px;
		display:block;
	 }
		 /* Fix Google Maps canvas
	 *
	 * Wrap your Google Maps embed in a `.google-map-canvas` to reset Bootstrap's
	 * global `box-sizing` changes. You may optionally need to reset the `max-width`
	 * on images in case you've applied that anywhere else. (That shouldn't be as
	 * necessary with Bootstrap 3 though as that behavior is relegated to the
	 * `.img-responsive` class.)
	 */

	.google-map-canvas,
	.google-map-canvas * { .box-sizing(content-box); }

	/* Optional responsive image override */
	img { max-width: none; }
	
	@media (max-width: 768px) {
		
		.nav-tabs.nav-justified > li{
			float:left;
		}
	}
    </style>

<!--[if lt IE 7]>
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
	<![endif]-->
    <!-- Fav and touch icons -->


<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
    <![endif]-->
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

  <body>
	<header>
   <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
		<div class="container">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#">YOURSITE.COM</a>
	  </div>

	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
		   <li><a href="#">Link 1</a></li>
		  <li><a href="#">Link 2</a></li>
		  <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
			<ul class="dropdown-menu">
			  <li><a href="#">Action</a></li>
			  <li><a href="#">Another action</a></li>
			  <li><a href="#">Something else here</a></li>
			  <li><a href="#">Separated link</a></li>
			  <li><a href="#">One more separated link</a></li>
			</ul>
		  </li>
		</ul>
		<form class="navbar-form navbar-right visible-lg" role="search">
		  <div class="form-group">
			<input type="text" class="form-control" placeholder="Search">
		  </div>
		  <button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div><!-- /.navbar-collapse --> 
	</div>	
</nav>
    

      <!-- Main hero unit for a primary marketing message or call to action -->
<div class="jumbotron">     
	
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-lg-12">
					<h1>Contact us <small>Contact page with working PHP contact form & jQuery</small></h1>
			
       
				</div>
			</div>
		</div>
</div>
</header>
<div class="container">
      <!-- Example row of columns -->
      <div class="row">
			<div class="col-sm-12 col-lg-12">
				<div class="panel">
					<div class="panel-heading">
						<h3><i class="icon-map-marker main-color"></i> Location</h3>
					</div>
					<div class="panel-body">
					  <!-- gMap script container !Do not remove!! -->
							  <div id="map"></div>	 
					  <!-- gMap script container !Do not remove!! -->
					</div>
				  </div>
			</div>
		</div>
	<hr>
	<div class="row">
			<div class="col-sm-4 col-lg-4">
				<div class="panel">
					<div class="panel-heading">
						<h3><i class="icon-pushpin main-color"></i> Our office</h3>
					</div>
					<div class="panel-body">
						<address>
						<strong>VERSO, Inc.</strong><br>
						795 Folsom Ave, Suite 600<br>
						San Francisco, CA 94107<br>
						<i class="icon-phone-sign"></i> + 4 (123) 456-7890
						</address>
					</div>
				</div>
			
			<div class="panel">
				<div class="panel-heading">
				  <h3><i class="icon-time main-color"></i> Business hours</h3>
				</div>
				<div class="panel-body">
					<table class="table table-hover">
					  <thead>
						<tr>
						  
						  <th>Day</th>
						  <th>Time</th>
						</tr>
					  </thead>
					  <tbody>
						<tr class="success">
						  <td>Monday</td>
						  <td>9:00 to 18:00</td>
						</tr>
						<tr class="success">
						  <td>Tuesday</td>
						  <td>9:00 to 18:00</td>
						</tr>
						<tr class="success">
						 
						  <td>Wednesday</td>
						  <td>9:00 to 18:00</td>
						</tr>
						<tr class="success">
						 
						  <td>Thursday</td>
						  <td>9:00 to 18:00</td>
						</tr>
						<tr class="success">
						 
						  <td>Friday</td>
						  <td>9:00 to 18:00</td>
						</tr>
						<tr class="warning">
						
						  <td>Saturday</td>
						  <td>9:00 to 14:00</td>
						</tr>
						<tr class="danger">
						
						  <td>Sunday</td>
						  <td>day off</td>
						</tr>
					  </tbody>
					</table>
					</div>
				</div>
			</div>
       <div class="col-12 col-lg-8">
	  
			<div class="panel">
					<div class="panel-heading">	
			<h3 class="">
				<i class="icon-envelope main-color"></i>
				Feel free to contact us
			</h3>
			</div>
			<div class="panel-body">
			<!-- CONTACT FORM https://github.com/jonmbake/bootstrap3-contact-form -->
			
			<form role="form" id="feedbackForm">
			      <div class="form-group">
				<input type="text" class="form-control" id="name" name="name" placeholder="Name">
				<span class="help-block" style="display: none;">Please enter your name.</span>
			      </div>
			      <div class="form-group">
				<input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
				<span class="help-block" style="display: none;">Please enter a valid e-mail address.</span>
			      </div>
			      <div class="form-group">
				<textarea rows="10" cols="100" class="form-control" id="message" name="message" placeholder="Message"></textarea>
				<span class="help-block" style="display: none;">Please enter a message.</span>
			      </div>
			      <img id="captcha" src="library/vender/securimage/securimage_show.php" alt="CAPTCHA Image" />
			      <a href="#" onclick="document.getElementById('captcha').src = 'library/vender/securimage/securimage_show.php?' + Math.random(); return false" class="btn btn-info btn-sm">Show a Different Image</a><br/>
			      <div class="form-group" style="margin-top: 10px;">
				<input type="text" class="form-control" name="captcha_code" id="captcha_code" placeholder="For security, please enter the code displayed in the box." />
				<span class="help-block" style="display: none;">Please enter the code displayed within the image.</span>
			      </div>
			      
			      <span class="help-block" style="display: none;">Please enter a the security code.</span>
			      <button type="submit" id="feedbackSubmit" class="btn btn-primary btn-lg" style="display: block; margin-top: 10px;">Send Feedback</button>
			    </form>
			<!-- END CONTACT FORM -->
			</div>
			</div>			
		</div>
      </div>

      <hr>

      <footer>
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-12">
					<p><a href="http://www.bootstraptor.com" title="bootstrap 3 templates">Bootstraptor.com © Company 2013</a></p>
				</div>
			</div>
		</div>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="assets/js/bootstrap.min.js"></script>

 <script src="assets/js/contact-form.js"></script>



<!-- gMap PLUGIN -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="assets/js/jquery.gmap.js"></script>

<!-- CONTACTS SCRIPT-->
 <script src="assets/js/contact-form.js"></script>
<!-- / CONTACTS SCRIPT-->


<script type="text/javascript">
		
				jQuery(document).ready(function(){

				jQuery('#map').gMap({ address: 'Sidney',
							   panControl: true,
						   zoomControl: true,
							   zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
							   },
						   mapTypeControl: true,
						   scaleControl: true,
						   streetViewControl: false,
						   overviewMapControl: true,
							   scrollwheel: true,
							   icon: {
						image: "http://www.google.com/mapfiles/marker.png",
						shadow: "http://www.google.com/mapfiles/shadow50.png",
						iconsize: [20, 34],
						shadowsize: [37, 34],
						iconanchor: [9, 34],
						shadowanchor: [19, 34]
					},
						zoom:14,
							   markers: [
							{ 'address' : 'Sidney'}
						]
							   
							   
							   }); 
				});

</script>


</body>
</html>