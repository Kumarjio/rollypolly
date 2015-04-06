<?php

/*$params = array();
$params['cacheTime'] = 60*60*24*7;
$params['where'] = sprintf(' AND parent_id = 0 AND cat_status = 1', $modelGeneral->qstr($id));
$categoryClassifieds = $modelGeneral->getDetails('z_classifieds_category', 1, $params);*/

$vegetableMenuLink = '';
if (!empty($globalCity['id'])) {
  $query = "SELECT * FROM my_store WHERE store_city_id = ?";
  $resultVegetableStore = $modelGeneral->fetchRow($query, array($globalCity['id']), 3600);
}

?>
<div class="navbar navbar-custom navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
           <span><a href="<?php echo HTTPPATH; ?>/?main=1" class="navbar-brand"><img src="<?php echo HTTPPATH; ?>/images/home.png" /></a><a href="<?php echo HTTPPATH; ?>" class="navbar-brand"><?php echo SITENAME; ?></a> </span>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <?php if (!empty($_SESSION['sub_city_location'])) {
              ?>
            <li>
              <a href="<?php echo $_SESSION['sub_city_location']['url']; ?>"><?php echo $_SESSION['sub_city_location']['title']; ?></a>
            </li>
              <?php
            } ?>
            <?php if (!empty($_SESSION['last_location']) && $_SESSION['last_location']['url'] != $_SESSION['sub_city_location']['url']) {
              ?>
            <li>
              <a href="<?php echo $_SESSION['last_location']['url']; ?>"><?php echo $_SESSION['last_location']['title']; ?></a>
            </li>
              <?php
            } ?>
            <!--<li>
              <a href="<?php echo HTTPPATH; ?>/locations/country">Locations</a>
            </li>-->
            <?php if (!empty($globalCity)) { ?>
            <li class="dropdown">
                <a id="moduleBtn" role="button" data-toggle="dropdown" class="btn btn-primary" data-target="#" href="javascript:;">
                    City <span class="caret"></span>
                </a>
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                <?php if (!empty($_SESSION['user'])) { ?>
                <!--<li><a href="<?php echo $currentURL; ?>/auto/purchases">My Purchases</a></li>-->
                <?php } ?>
                <?php if (!empty($resultVegetableStore['store_status']) && $resultVegetableStore['store_status'] == 1) { ?>
                <li><a href="<?php echo $currentURL; ?>/">Online Store</a></li>
                <?php } ?>
                <?php
                //AUTO Started
                $t = (3600*24);
                $query = "SELECT * FROM z_modules WHERE module_status = 1 and menu_hidden = 0 ORDER BY module_sorting ASC";
                $tmp2 = $modelGeneral->fetchAll($query, array(), $t);
                $tmpMenu = array();
                foreach ($tmp2 as $v) {
                  $tmpMenu[$v['module_name']] = $v;
                }
                //pr($tmpMenu);
                $menuModule = array();
                foreach ($tmpMenu as $v) {
                  $menuModule[$v['parent']][] = $v;
                }
                ?>
                <?php if (!empty($menuModule['Root'])) { ?>
                    <?php foreach ($menuModule['Root'] as $k => $v) { ?>
                      <?php if (!isset($menuModule[$v['module_name']])) { ?>
                      <li class="dropdown-submenu">
                        <a tabindex="-1" href="javascript:;"><?php echo $v['menu_display_name']; ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $currentURL; ?>/auto/info?module_id=<?php echo $v['module_id']; ?>">About <?php echo $v['menu_display_name']; ?></a></li>
                            <?php if ($v['new_page'] == 1) { ?>
                            <li><a href="<?php echo $currentURL; ?>/auto/new?module_id=<?php echo $v['module_id']; ?>">Create New <?php echo $v['menu_display_name']; ?></a></li>
                            <?php } ?>
                            <?php if ($v['browse_page'] == 1) { ?>
                                <li><a href="<?php echo $currentURL; ?>/auto/browse?module_id=<?php echo $v['module_id']; ?>">Search/browse <?php echo $v['menu_display_name']; ?></a></li>
                            <?php } ?>
                            <?php if ($v['my_page'] == 1) { ?>
                                <li><a href="<?php echo $currentURL; ?>/auto/my?module_id=<?php echo $v['module_id']; ?>">My <?php echo $v['menu_display_name']; ?></a></li>
                             <?php } ?>
                        </ul>
                      </li>
                      <?php } //end if (isset($menuModule[$v['module_name'])) { ?>
                    <?php } //end foreach ($menuModule['Root'] as $k => $v) { ?>
                <?php } ?>
                <!-- start if -->
                <?php if (!empty($menuModule)) { ?>
                    <?php foreach ($menuModule as $k => $v) { 
                      if ($k === 'Root') continue;
                    ?>
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="javascript:;"><?php echo $k; ?></a>
                      <!-- start if -->
                      <?php if (!empty($menuModule)) { ?>
                      <ul class="dropdown-menu">
                          <!-- start foreach -->
                          <?php foreach ($v as $k1 => $v1) { ?>
                          <li class="dropdown-submenu"><a tabindex="-1" href="javascript:;"><?php echo $v1['menu_display_name']; ?></a>
                              <ul class="dropdown-menu">
                                  <li><a href="<?php echo $currentURL; ?>/auto/info?module_id=<?php echo $v1['module_id']; ?>">About <?php echo $v1['menu_display_name']; ?></a></li>
                                  <li><a href="<?php echo $currentURL; ?>/auto/new?module_id=<?php echo $v1['module_id']; ?>">Create New <?php echo $v1['menu_display_name']; ?></a></li>
                                  <?php if ($v1['browse_page'] == 1) { ?>
                                      <li><a href="<?php echo $currentURL; ?>/auto/browse?module_id=<?php echo $v1['module_id']; ?>">Search/browse <?php echo $v1['menu_display_name']; ?></a></li>
                                  <?php } ?>
                                  <?php if ($v1['my_page'] == 1) { ?>
                                      <li><a href="<?php echo $currentURL; ?>/auto/my?module_id=<?php echo $v1['module_id']; ?>">My <?php echo $v1['menu_display_name']; ?></a></li>
                                  <?php } ?>
                              </ul>
                          </li>
                          <?php } ?>
                          <!-- end foreach -->
                      </ul>
                      <?php } ?>
                      <!-- end if -->
                    </li>
                    <?php } ?>
                    <!-- start foreach -->
                <?php } ?>
                <!-- end if -->
                <?php
                //AUTO Ended
                ?>
                  <?php if (!empty($categoryClassifieds)) { ?>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">Classifieds</a>
                    <ul class="dropdown-menu">
                      <?php foreach ($categoryClassifieds as $k => $v) { ?>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/classifieds/browse?cat_id=<?php echo $v['cat_id']; ?>"><?php echo $v['category']; ?></a></li>
                      <?php } ?>
                    </ul>
                  </li>
                  <?php } ?>
                  <?php if (isset($_GET['m'])) { ?>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">History</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/history/about">About</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/history/browse">Search/Browse</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/history/new">Create New</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/history/myhistory">My History</a></li>
                    </ul>
                  </li>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">Residential</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/residential/about">About</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/residential/browse">Search/Browse</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/residential/new">Create New</a></li>
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/residential/myplace">My Place</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                </ul>
            </li>
            <!--<li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Modules<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="themes">
                
                <li><a href="<?php echo $globalCity['url']; ?>/lawyers">Lawyers</a></li>
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
            </li>-->
            <?php } ?>
            <!--<li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="manage">Manage <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Advertisements</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo HTTPPATH; ?>/admin/manage">Admin</a></li>
              </ul>
            </li>-->
            <?php if (!empty($_SESSION['user']['access_level']) && $_SESSION['user']['access_level'] === 'admin') { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="superadmin">Super Admin <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/manage">Manage</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/manager/shout">Manage Shouts</a></li>
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/manager/history">Manage History</a></li>
                <li><a href="<?php echo HTTPPATH; ?>/superadmin/locations/city_owners">City Owners</a></li>
              </ul>
            </li>
            <?php } ?>
            <?php if (!empty($cityOwner)) { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="admin">Admin <span class="caret"></span></a>
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                  <?php if (!empty($cityOwner) || is_super_admin()) { ?>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">Manage City</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="<?php echo $currentURL; ?>/admin/city/store/configure">Store Configuration</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                  <?php if (!empty($stateOwner)) { ?>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">Manage State</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="javascript:;">Menu1</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                  <?php if (!empty($countryOwner)) { ?>
                  <li class="dropdown-submenu">
                    <a tabindex="-1" href="javascript:;">Manage Country</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="javascript:;">Menu1</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;" id="manage">Site<span class="caret"></span></a>
              
              <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
              <li>
                <a href="<?php echo HTTPPATH; ?>/about/sitemap">SiteMap</a>
              </li>
              <li>
                <a href="<?php echo HTTPPATH; ?>/locations/country">Locations</a>
              </li>
                <!--<li><a href="<?php echo HTTPPATH; ?>/users/memberPlans">Become Member</a></li>-->
                <li><a href="http://cms.mkgalaxy.com/" target="_blank">CMS</a></li>
                <li class="dropdown-submenu">
                  <a tabindex="-1" href="javascript:;">Chess</a>
                  <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/chess/repertorie/my">Opening Repertorie</a></li>
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/chess/analyse/move.php" target="_blank">Chess Analysis</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a tabindex="-1" href="javascript:;">Social</a>
                  <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/horo/main">Horo</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a tabindex="-1" href="javascript:;">About</a>
                  <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/about/aboutus">About Us</a></li>
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/about/howitworks">How it works</a></li>
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/about/suggestions">Suggestions</a></li>
                    <li><a tabindex="-1" href="<?php echo HTTPPATH; ?>/about/terms">Terms & Conditions</a></li>
                  </ul>
                </li>
                <!--<li class="divider"></li>
                <li><a href="<?php echo HTTPPATH; ?>/forex/buy">Forex Software</a></li>
                <li><a href="<?php echo HTTPPATH; ?>/products/search">Product Search</a></li>-->
              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <?php if (!empty($_SESSION['user'])) { ?>
            <!--<li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;" id="myaccount">My Account <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo HTTPPATH; ?>/users/settings">Settings</a></li>
              </ul>
            </li>
            <li><a href="<?php echo HTTPPATH; ?>/users/login?logout=1">Logout</a></li>
            <li><a href="<?php echo $currentURL; ?>/auto/messages/">Messages</a></li>-->
            <?php include(SITEDIR.'/includes/account_in_navbar.php'); ?>
            <?php } else { ?>
            <li><a href="<?php echo HTTPPATH; ?>/users/login">Login</a></li>
            <?php } ?>
          </ul>

        </div>
      </div>
    </div>