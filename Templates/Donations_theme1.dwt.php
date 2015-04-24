<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Base page</title>
<!-- TemplateEndEditable -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>


<link href="../donations/assets/css/bootstrap.css" rel="stylesheet">
<link href="../donations/assets/css/theme1.css" rel="stylesheet">
<link href="../donations/assets/css/site.css" rel="stylesheet">


<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">

<!--[if lt IE 7]>
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="shortcut icon" href="../donations/assets/ico/favicon.ico" type="image/x-icon">
<link rel="icon" href="../donations/assets/ico/favicon.ico" type="image/x-icon">

<?php 
require_once('../donations/inc_category.php'); 

?>

<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<div class="wrap">
<section>
<nav class="navbar-default navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="/">godonateme.com</a>
    </div>
    
    <?php include('../donations/inc_menu.php'); ?>
    
  </div>
</nav>
</section>
<section class="top-section">
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php include('../donations/inc_googleadsense.php'); ?>
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
<!-- TemplateBeginEditable name="EditRegion5" -->
    <div id="carousel-299058" class="carousel slide">
        <ol class="carousel-indicators">
            <li data-target="#carousel-299058" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-299058" data-slide-to="1"></li>
            <li data-target="#carousel-299058" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="item active"> <img class="img-responsive" src="../donations/images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item"> <img class="img-responsive" src="../donations/images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption 2. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item"> <img class="img-responsive" src="../donations/images/carouselthumb.jpg" alt="thumb" />
                <div class="carousel-caption"> Carousel caption 3. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
        </div>
        <a class="left carousel-control" href="#carousel-299058" data-slide="prev"><span class="icon-prev"></span></a> <a class="right carousel-control" href="#carousel-299058" data-slide="next"><span class="icon-next"></span></a> </div>
<!-- TemplateEndEditable -->


<!-- TemplateBeginEditable name="EditRegion4" -->
<div class="page-header">
<h3>Store catalog <small>Subtext for header</small></h3>
</div>
<!-- TemplateEndEditable -->

<div class="row">
<div class="col-lg-12">
<!-- TemplateBeginEditable name="EditRegion3" -->
<p>Body</p>
<!-- TemplateEndEditable -->
</div>
</div>


</div><!-- middle col -->

<?php include('../donations/inc_featured.php'); ?>

</div><!-- / inner .row -->
</div>
</section>

<section class="custom-footer">
<?php include('../donations/inc_footer.php'); ?>
</section>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../donations/assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="../donations/assets/js/bootstrap.js"></script>
<!-- TemplateBeginEditable name="EditRegionJS" -->

<!-- TemplateEndEditable -->
</body>
</html>