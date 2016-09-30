<!-- header logo: style can be found in header.less -->
<header class="header">
	<a class="logo" style="cursor: default;">
		<img src="images/uninett/UNINETT_logo_dark_gray.svg" alt="UNINETT logo" type="image/svg+xml">
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar" role="navigation">

		<!-- Sidebar toggle button-->
		<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Meny</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>

		<span id="uninett" class="department">Mediasite Schedulator</span>

		<div class="navbar-right">
			<ul class="nav navbar-nav">
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="line-height: 5px;">
						<i class="glyphicon glyphicon-user"></i>
						<span><?php echo $auth->feide_firstname(); ?> <i class="caret"></i></span>
					</a>

					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header bg-blue">
							<h3><i class="ion ion-android-social-user"></i></h3>

							<p>
								<?php
									echo $auth->feide_name();
									if($auth->is_superuser()){
										echo("<small>Mediasite Kontaktperson</small>");
									}
								?>

							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-right">
								<a href="app/auth/logout.php" class="btn btn-default btn-flat">Logg ut</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>