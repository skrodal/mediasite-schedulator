<?php
	/**
	 * Routes for interaction with the Mediasite REST API.
	 *
	 *
	 * @author Simon Skrodal
	 * @since  02.06.14
	 */
	session_start();
	header('content-type: application/json; charset=utf-8');
	date_default_timezone_set('UTC');
	// API CREDENTIALS AND PATHS
	require_once('etc/config.php');

	if(!isset($_SESSION['mediasite_auth']) || $_SESSION['mediasite_auth'] == false) {
		exit(json_encode(array(
			"status"  => false,
			'message' => 'Missing API Auth!.'
		)));
	}

	//
	switch($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			require_once('lib/api_post.php');
			break;
		case 'GET':
			require_once('lib/api_get.php');
			break;
		default:
			exit(json_encode(array(
				"status"  => false,
				'message' => 'Request method not allowed.'
			)));
	}


	/*************** HELPER FUNCTIONS ********************/


	function checkHttpCode($curl) {
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($code >= 200 && $code < 300) {
			// All good
			return;
		} else {
			// HTTP error code in response
			exit(json_encode(array(
				'status'  => false,
				'message' => $code
			)));
		}
	}

	/**
	 * Deal with missing arguments in method calls and respond accordingly.
	 *
	 * @author Simon Skrodal
	 * @since  12.06.2014
	 *
	 * @param       $function_name
	 * @param array $args
	 */
	function checkArgs($function_name, array $args) {
		foreach($args as $arg) {
			if(!isset($arg) || empty($arg) || is_null($arg)) {
				exit(json_encode(array(
					'status'  => false,
					'method'  => $function_name,
					'message' => 'One or more required arguments are missing from the method call.'
				)));
			}
		}
	}

	/**
	 * Constructs the response array to be returned by this API.
	 *
	 * @author Simon Skrodal
	 * @since  19.06.2014
	 *
	 * @param $status
	 * @param $meta
	 * @param $data
	 *
	 * @return array
	 */
	function response($status, $meta, $data) {
		$response           = array();
		$response['status'] = $status;
		$response['meta']   = $meta;
		if($status) {
			$response['response'] = $data;
		} else {
			$response['message'] = $data;
		}

		return $response;
	}