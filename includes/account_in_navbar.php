
    <style type="text/css">
.navbar-login
{
    width: 305px;
    padding: 10px;
    padding-bottom: 0px;
}

.navbar-login-session
{
    padding: 10px;
    padding-bottom: 0px;
    padding-top: 0px;
}

.icon-size
{
    font-size: 87px;
}
</style>
  <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!--<span class="glyphicon glyphicon-user"></span>-->
          <img src="<?php echo $_SESSION['user']['picture']; ?>" width="14" height="14" />
          <strong><?php echo $_SESSION['user']['given_name']; ?></strong>
          <span class="glyphicon glyphicon-chevron-down"></span>
      </a>
      <ul class="dropdown-menu">
          <li>
              <div class="navbar-login">
                  <div class="row">
                      <div class="col-lg-4">
                          <p class="text-center">
                              <!--<span class="glyphicon glyphicon-user icon-size"></span>-->
                              <img src="<?php echo $_SESSION['user']['picture']; ?>" width="87" height="87" />
                          </p>
                      </div>
                      <div class="col-lg-8" style="color:black;">
                          <p class="text-left"><strong><?php echo $_SESSION['user']['name']; ?></strong></p>
                          <p class="text-left small"><?php echo $_SESSION['user']['email']; ?></p>
                          <p class="text-left">
                              <a href="<?php echo HTTPPATH; ?>/users/settings" class="btn btn-primary btn-block btn-sm">Settings</a>
                          </p>
                      </div>
                  </div>
              </div>
          </li>
          <li class="divider"></li>
          <li>
              <div class="navbar-login navbar-login-session">
                  <div class="row">
                      <div class="col-lg-12">
                          <p>
                              <a href="<?php echo HTTPPATH; ?>/users/login?logout=1" class="btn btn-danger btn-block">Logout</a>
                          </p>
                      </div>
                  </div>
              </div>
          </li>
      </ul>
  </li>