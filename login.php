<?php
	session_start();
	// SimpleSAMLphp (FEIDE)
	require('app/auth/auth.php');

	if($auth->isAuthenticated())
	{
		header('Location: index.php');
	} else {
		// $_SESSION['mediasite_auth'] = false;
		session_destroy();
	}