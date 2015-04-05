<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?php echo $pageTitle; ?></title>
    <base href="<?php echo HTTPPATH; ?>/layouts/themeBase/" />
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="<?php echo HTTPPATH; ?>/styles/site.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
    <link href="<?php echo HTTPPATH; ?>/styles/multidropdown.css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.number.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.cookie.js"></script>

<script type="text/javascript" src="<?php echo HTTPPATH.'/scripts/map.js'; ?>"></script>

<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&libraries=places"></script>

<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

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
        /*str = e.currentTarget.className;
        n = str.indexOf("formMEnter");
        if (n === -1) {
            e.preventDefault();
            return false;
        }*/
        return true;
    }
});
</script>

<script language="javascript">
var infowindow = new google.maps.InfoWindow();
</script>
	</head>
	<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=168072164626&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- begin template -->
<?php include(SITEDIR.'/includes/menu.php'); ?>
<?php 
if (!empty($layoutStructure)) {
  include(SITEDIR.'/layouts/themeBase/'.$layoutStructure.'.php');
} else {
  include(SITEDIR.'/layouts/themeBase/main.php');
}
?>
<!-- end template -->
<script type="text/javascript">
window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
</script>

<script language="javascript">
function fb(url)
{
  FB.ui({
    method: 'share',
    href: url,
  }, function(response){});
}
</script>

	<!-- script references <script src="http://maps.googleapis.com/maps/api/js?sensor=false&extension=.js&output=embed"></script>-->
<script src="js/bootstrap.min.js"></script>
<?php include(SITEDIR.'/includes/analyticstracking.php'); ?>
	</body>
</html>