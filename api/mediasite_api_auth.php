<?php
	/**
	 * Used for initial Mediasite API user authentication.
	 *
	 * Provides the means to check if logged on Feide user
	 * can provide the correct Mediasite API credentials (userid, password, api key)
	 * before starting the scheduling process.
	 *
	 * @author Simon Skrødal
	 * @since  28JAN2015
	 */
	session_start();

	$_SESSION['mediasite_auth']        = false;
	$_SESSION['mediasite_api_user']    = false;
	$_SESSION['mediasite_api_pass']    = false;
	$_SESSION['mediasite_api_key']     = false;
	$_SESSION['mediasite_api_url']     = false;
	$_SESSION['mediasite_service_url'] = false;

	$status  = false;
	$message = "";

	$username    = !empty($_POST["username"]) ? $_POST["username"] : NULL;
	$password    = !empty($_POST["password"]) ? $_POST["password"] : NULL;
	$api_key     = !empty($_POST["api_key"]) ? $_POST["api_key"] : NULL;
	$service_url = !empty($_POST["service_url"]) ? $_POST["service_url"] : NULL;
	$api_url     = !empty($_POST["api_url"]) ? $_POST["api_url"] : NULL;


	if(is_null($username) || is_null($password) || is_null($api_key) || is_null($api_url) || is_null($service_url)) {
		$message = "Brukernavn, passord, nøkkel eller Mediasite URLs mangler!";
	} else {
		$username    = $_POST['username'];
		$password    = $_POST['password'];
		$api_key     = $_POST['api_key'];
		$api_url     = $_POST['api_url'];
		$service_url = $_POST['service_url'];

		// Test credentials
		$auth = _checkAuth($username, $password, $api_key, $api_url);

		if($auth) {
			$_SESSION['mediasite_auth']        = true;
			$_SESSION['mediasite_api_user']    = $username;
			$_SESSION['mediasite_api_pass']    = $password;
			$_SESSION['mediasite_api_key']     = $api_key;
			$_SESSION['mediasite_api_url']     = $api_url;
			$_SESSION['mediasite_service_url'] = $service_url;

			$status  = true;
			$message = "Mediasite API Auth: OK!";
		}
	}

	$response = array(
		"status"   => $status,
		"message"  => $message,
		"user"     => $username,
		"password" => "******",
		"api_key"  => $api_key,
		"api_url"  => $api_url,
		"service_url"  => $service_url,
	);

	exit(json_encode($response));

	/***********************************/

	function _checkAuth($username, $password, $api_key, $api_url) {
		// Use a call that requires auth AND API Key to test credentials
		$url = $api_url . "Recorders";
		// All the curl stuff
		$ch = curl_init($url);
		// Headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"sfapikey: " . $api_key,
			"Content-Type: application/json",
		));
		// Auth
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		// Options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false); // Set to true for all return headers
		//
		$response = curl_exec($ch);
		// API call will gracefully die with checkHeaders if response code is not successful
		_checkHttpCode($ch);
		curl_close($ch);

		// All passed
		return true;
	}


	/**
	 * @param $curl
	 */
	function _checkHttpCode($curl) {
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($code >= 200 && $code < 300) {
			// All good
			return;
		} else {
			// HTTP error code in response
			exit(json_encode(array(
				'status'  => false,
				'message' => 'HTTP Code: ' . $code
			)));
		}
	}
