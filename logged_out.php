<?php
	session_start();
	$_SESSION = array(); // Unset all of the session variables.

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	// Finally, destroy the session.
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Mediasite Schedulator</title>
	<link rel="shortcut icon" href="favicon.ico">
	<meta charset="UTF-8">
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<!-- ALL UNINETT and app-specific styles, compiled with Koala -->
	<link href="css/app.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>
<body class="fixed skin-black">

	<header class="header">
		<a class="logo" style="cursor: default;">
			<img src="images/uninett/UNINETT_logo_dark_gray.svg" alt="UNINETT logo" type="image/svg+xml">
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar" role="navigation">
			<span id="uninett" class="department">&nbsp;&nbsp;Mediasite Schedulator</span>
		</nav>
	</header>

	<div class="wrapper row-offcanvas row-offcanvas-left">

		<aside class="left-side sidebar-offcanvas">
			<section class="sidebar">
			</section>
		</aside>

		<aside class="right-side">
			<section class="content">
				<div class="row">
				    <div class="col-lg-12">
				        <div class="jumbotron">
				            <h1><i class="ion ion-log-out"></i> Takk for i dag!</h1>
				            <p class="lead"><code>Du</code> er logget ut av Feide.</p>
					        <p class="lead"><code>Schedulator</code> er logget ut av Mediasite.</p>

					        <ul class="uninett-ul">
						        <li class="uninett-ul-li"><a href="index.php">Logg inn p&aring; nytt</a></li>
						        <li class="uninett-ul-li"><a href="http://www.dagbladet.no/tegneserie/lunch/">Ta lunch</a></li>
					        </ul>
				        </div>
				    </div>
				</div>
			</section>
		</aside>
	</div>
</body>
</html>
