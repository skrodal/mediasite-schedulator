<?php
	session_start();
	// SimpleSAMLphp (auth with FEIDE)
	require('app/auth/auth.php');
	if(!isset($_SESSION['mediasite_auth'])) $_SESSION['mediasite_auth'] = false;

	if(!$auth->isAuthenticated()) {
		$_SESSION['mediasite_auth'] = false;
		header('Location: login.php');
	}
	date_default_timezone_set('CET');
	// Pages config (title, icon...)
	require_once('etc/config.php');

	$_SESSION['app_url'] = get_current_url();


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

	<script type="text/javascript">
		// Turn on to turn API calls off
		var TESTING = false;

		<?php
			// Assign global JS vars from PHP
			echo "var service_url = '" . $auth->service_url() . "';";
			echo "var api_url = '" . $auth->api_url() . "';";
		 ?>
	</script>
</head>
<body class="fixed skin-black">

	<?php include('app/index_header.php'); ?>

	<div class="wrapper row-offcanvas row-offcanvas-left">

		<?php
			include('app/index_sidebar.php');
		?>

		<aside class="right-side">
			<!-- Right side column. Contains the navbar and content of the page -->
			<section id="main_content" class="content">

				<?php
					if($_SESSION['mediasite_auth'] !== true) {
						// Only intro is visible from the get-go
						include_once("app/wizard_login.php");
					} else {
						// Show intro by default
						include_once("app/wizard_intro.php");
						// Include html for all other (by default hidden) pages
						foreach($PAGES_CONFIG as $page => $config) {
							include_once("app/" . $config['file']);
						}
					}
				?>
			</section>
		</aside>

		<div id="slide_alert_info" class="alert alert-success alert-dismissable flyover">
		    <p class="bold title"></p>
			<p class="message"></p>
		</div>

		<div id="slide_alert_error" class="alert alert-danger alert-dismissable flyover">
		    <p class="bold title"></p>
			<p class="message"></p>
		</div>
	</div>

	<!-- ALL scripts, compiled with Koala -->
	<script src="js/lib.min.js"></script>
	<?php
		if($_SESSION['mediasite_auth'] == true) {
			// echo '<script src="js/wizard.min.js"></script>';
			echo '<script src="js/app/app.js"></script>';
			echo '<script src="js/app/wizard.js"></script>';
			echo '<script src="js/app/wizard_lectures.js"></script>';
			echo '<script src="js/app/wizard_metadata.js"></script>';
			echo '<script src="js/app/wizard_folder.js"></script>';
			echo '<script src="js/app/wizard_recorder.js"></script>';
			echo '<script src="js/app/wizard_template.js"></script>';
			echo '<script src="js/app/wizard_summary.js"></script>';

		} else {
			echo '<script src="js/wizard_login.js"></script>';
		}
	?>

</body>
</html>

<?php

	function get_current_url() {
		$url = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		if(strpos($url, 'index.php') === false) $url = $url . 'index.php';
		return dirname($url).'/';
		//return dirname('http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'/');
	}

?>