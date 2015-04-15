<?php
session_start();
?>
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
    
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav ">
          <?php if (!empty($_SESSION['MM_UserId'])) { ?>
        <li class="active">
          <a href="../donations/logout.php">Logout</a>
        </li>
          <?php } ?>
          <?php if (empty($_SESSION['MM_UserId'])) { ?>
        <li class="active">
          <a href="../donations/login.php">Login</a>
        </li>
          <?php } ?>
          <?php if (empty($_SESSION['MM_UserId'])) { ?>
        <li>
          <a href="../donations/register.php">Register</a>
        </li>
          <?php } ?>
        <li>
          <a href="../donations/new.php">Create</a>
        </li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <?php if (!empty($_SESSION['MM_UserId'])) { ?>
        <li>
          <a href="javascript:;">Welcome, <?php echo $_SESSION['MM_Name']; ?></a>
        </li>
          <?php } ?>
        <li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown<strong class="caret"></strong></a>
          <ul class="dropdown-menu">
            <li>
              <a href="#">Contact Us</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    
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
        <?php include('../donations/inc_category.php'); ?>
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
<div class="container">
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-7">
      <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4  col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
          <div>
            <ul class="list-unstyled">
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
              <li>
                 <a>Link anchor</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-5">
       <span class="text-right"><?php include('../donations/inc_siteaddr.php'); ?></span>
    </div>
  </div>
</div>
</section>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../donations/assets/js/jquery.js" type="text/javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="../donations/assets/js/bootstrap.js"></script>

</body>
</html>