<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/bootswatch.min.css" media="screen">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/styles/site.css" media="screen">
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/scripts/guaw/jquery.guaw.css" media="screen">


<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.number.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.cookie.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/guaw/jquery.guaw.js"></script>

</head>

<body>
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="<?php echo HTTPPATH; ?>/" class="navbar-brand"><?php echo SITENAME; ?></a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li>
              <a href="<?php echo HTTPPATH; ?>/locations/bid">Bid</a>
            </li>
            <li>
              <a href="<?php echo HTTPPATH; ?>/locations/country">Locations</a>
            </li>
            <?php if (!empty($globalCity)) { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Modules<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="<?php echo $globalCity['url']; ?>/businesses">Businesses</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/residential">Residential</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo $globalCity['url']; ?>/news">News</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/sell">Sell</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/buy">Buy</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/housing">Housing</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/personals">Personals</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/jobs">Jobs</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/help">Help</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/services">Services</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/matrimony">Matrimony</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/meetups">Meetups</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/barter">Barter</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/freestuff">Free Stuff</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/history">History</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/complaintbox">Complaint Box</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/weather">Weather</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/placestotravel">Places to travel</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/photos">Photos</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/videos">Videos</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/likes_dislikes">Likes/Dislikes</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/emergency_contacts">Emergency Contacts</a></li>
                <li><a href="<?php echo $globalCity['url']; ?>/movies">Movies</a></li>
              </ul>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="manage">Manage <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Advertisements</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo HTTPPATH; ?>/admin/manage">Admin</a></li>
              </ul>
            </li>
            <?php if (!empty($_SESSION['user']['access_level']) && $_SESSION['user']['access_level'] === 'admin') { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="superadmin">Super Admin <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/manage">Manage</a></li>
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/bid">Current Bids</a></li>
              </ul>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="manage">About <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">About Us</a></li>
                <li><a href="#">How it works</a></li>
                <li class="divider"></li>
                <li><a href="#">Suggestions</a></li>
              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <?php if (!empty($_SESSION['user'])) { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="myaccount">My Account <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">My Bids</a></li>
                <li><a href="#">Points (10)</a></li>
                <li><a href="#">My Favorites</a></li>
                <li class="divider"></li>
                <li><a href="#">My Job Postings</a></li>
              </ul>
            </li>
            <li><a href="<?php echo HTTPPATH; ?>/messages/my">Messages</a></li>
            <li><a href="<?php echo HTTPPATH; ?>/users/login?logout=1">Logout</a></li>
            <?php } else { ?>
            <li><a href="<?php echo HTTPPATH; ?>/users/login">Login</a></li>
            <?php } ?>
          </ul>

        </div>
      </div>
    </div>
    <div class="container">
    
    
      <div class="page-header">
        <div class="row">
          <div class="col-lg-6 col-md-7 col-sm-6">
            <h2><?php echo $pageTitle; ?></h2>
            <p class="lead">Cities, States, Countries</p>
          </div>
          <?php if (!empty($_SESSION['user'])) { ?>
          <div class="col-lg-3 pull-right">
            <p><b>Welcome </b><?php echo $_SESSION['user']['name']; ?> (<?php echo $_SESSION['user']['access_level']; ?>)</p>
            <p><img src="<?php echo $_SESSION['user']['picture']; ?>"  style="max-width:50px;" /></p>
          </div>
          <?php } ?>
        </div>
      </div>

        <div class="row">
          <div class="col-sm-2">
            <div class="bs-component">
            
            
            
              <div class="panel panel-default">
                <div class="panel-heading">Search City</div>
                <div class="panel-body">
                  <?php include(SITEDIR.'/locations/searchcity.php'); ?>
                </div>
              </div>
              
              <?php if (!empty($pageDynamicNavigationItem)) { ?>
              <div class="panel panel-default">
                <div class="panel-heading">Information</div>
                <div class="panel-body">
                  <?php echo $pageDynamicNavigationItem; ?>
                </div>
              </div>
              <?php } ?>
              
              <?php if (!empty($pageDynamicNearby)) { ?>
              <div class="panel panel-default">
                <div class="panel-heading">Nearby Cities</div>
                <div class="panel-body">
                  <?php echo $pageDynamicNearby; ?>
                </div>
              </div>
              <?php } else { ?>
              
                <div id="homepagenearby" style="display:none;" class="panel panel-default">
                  <div class="panel-heading">Nearby Cities</div>
                  <div class="panel-body" id="homepagenearbycontent">
                    
                  </div>
                </div>
              <?php } ?>
              

            </div>
          </div>
          <div class="col-lg-5">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><?php echo $pageTitle; ?></h3>
                </div>
                <div class="panel-body">
                    <?php echo $contentForTemplate; ?>
                </div>
              </div>
            </div>
          </div>
            
            
          <div class="col-lg-2">
            <div class="bs-component">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Activities</h3>
                </div>
                <div class="panel-body">
                </div>
              </div>
            </div>
          </div>
            
            
          <div class="col-sm-3 pull-right">
            <div class="bs-component">

              
<?php
if (empty($city_id)) {
?>
                <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Home</h3>
                </div>
                <div class="panel-body">
                </div>
              </div>
<?php
} else {
?>
                <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Useful Links</h3>
                </div>
                <div class="panel-body">
                </div>
              </div>
<?php
}
?>          
            </div>
          </div>
        </div>
  </div>
<!-- August 23, 2014-->
<script src="<?php echo HTTPPATH; ?>/scripts/bootstrap.min.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/bootswatch.js"></script>
</body>
</html>