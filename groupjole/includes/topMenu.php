<div class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav ">
                        <?php if (empty($_SESSION['MM_UserId'])) { ?>
						<li>
							<a href="/users/login.php">Login</a>
						</li>
						<li>
							<a href="/users/register.php">Register</a>
						</li>
                        <?php } ?>
                        <?php if (!empty($_SESSION['MM_UserId'])) { ?>
						<li>
							<a href="/groups/create.php">Create New Group</a>
						</li>
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Groups<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="#">Action</a>
								</li>
								<li>
									<a href="#">Another action</a>
								</li>
								<li>
									<a href="#">Something else here</a>
								</li>
								<li>
									<a href="#">Separated link</a>
								</li>
								<li>
									<a href="#">One more separated link</a>
								</li>
							</ul>
						</li>
                        <?php } ?>
					</ul>
					
					<ul class="nav navbar-nav navbar-right">
                        <?php if (!empty($_SESSION['MM_UserId'])) { ?>
						<li>
							<a href="#">Messages</a>
						</li>
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">User<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="/users/logout.php">Logout</a>
								</li>
							</ul>
						</li>
                        <?php } ?>
					</ul>
				</div>