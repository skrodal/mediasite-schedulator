<?php
	/**
	 * All GET interactions with the Mediasite REST API.
	 *
	 * @author Simon Skrodal
	 * @since  02.06.14
	 */

	// REQUEST PARAMS
	$method       = isset($_GET['method']) ? filter_var($_GET['method'], FILTER_SANITIZE_STRING) : exit(json_encode(array(
		"status"  => false,
		'message' => 'No method provided.'
	)));
	$feide_login  = isset($_GET['feide_login']) ? filter_var($_GET['feide_login'], FILTER_SANITIZE_STRING) : NULL;
	$feide_org    = isset($_GET['feide_org']) ? filter_var($_GET['feide_org'], FILTER_SANITIZE_STRING) : NULL;
	$pres_id      = isset($_GET['pres_id']) ? filter_var($_GET['pres_id'], FILTER_SANITIZE_STRING) : NULL;
	$folder_id    = isset($_GET['folder_id']) ? filter_var($_GET['folder_id'], FILTER_SANITIZE_STRING) : NULL;
	$pres_new_val = isset($_GET['pres_new_val']) ? filter_var($_GET['pres_new_val'], FILTER_SANITIZE_STRING) : NULL;
	$ics          = isset($_GET['ics']) ? filter_var($_GET['ics'], FILTER_SANITIZE_URL) : NULL;


	// FUNCTION ACCESS CONTROLLER
	try {
		switch($method) {
			case 'getMediasiteVersion':
				$response = getMediasiteVersion();
				break;

			case 'getCalendarAsJSON':
				$response = getCalendarAsJSON($ics);
				break;

			case 'getMediasiteFolders':
				$response = getMediasiteFolders();
				break;

			case 'getMediasitePresenters':
				$response = getMediasitePresenters();
				break;

			case 'getMediasiteRecorders':
				$response = getMediasiteRecorders();
				break;
			/*
			case 'getMediasiteSchedules':
				$response = getMediasiteSchedules($folder_id);
				break;
			*/

			case 'getMediasiteTemplates':
				$response = getMediasiteTemplates($folder_id);
				break;

			default:
				$response = array('status'  => false,
				                  'message' => 'You\'re calling a method that does not exist.'
				);
				break;
		}
	} catch(Exception $e) {
		exit(json_encode(array(
			'status'  => false,
			'message' => 'An error occurred, exception as follows: ' . $e
		)));
	}
	// DONE
	exit(json_encode($response));

	/******************** API ACCESSIBLE FUNCTIONS ********************/

	/**
	 * @return array
	 */
	function getMediasiteVersion() {
		$meta     = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		$response = _getAPI("Home");
		$response = array(
			'api'   => $response->ApiVersion,
			'site'  => $response->SiteVersion,
			'build' => $response->SiteBuildNumber
		);

		return response(true, $meta, $response);
	}

	/**
	 * @param $ics
	 *
	 * @return array
	 */
	function getCalendarAsJSON($ics) {
		checkArgs(__FUNCTION__, func_get_args());
		require_once('ical_parser/SG_iCal.php');
		$meta = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		//
		$ical = new SG_iCalReader($ics);
		//
		if($ical->getParseStatus()) {
			$evts = $ical->getEvents();
			if(is_null($evts)) {
				return response(false, $meta, 'Kalenderfeeden var tom.');
			}

			return response(true, $meta, $evts);
		}

		return response(false, $meta, 'Fant ingen kalenderfeed i URLen du oppga.');
	}

	/**
	 * Get a full list of all folders and extract important folders
	 * from the list, e.g. Mediasite Root and Templates folder.
	 *
	 * @return array
	 */
	function getMediasiteFolders() {
		// checkArgs(__FUNCTION__, func_get_args());
		$meta = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		//
		$response['root_folder_id']      = _getAPI("Home")->RootFolderId;
		$response['templates_folder_id'] = NULL;
		//
		$response['folders'] = _getAPI("Folders" . '?$top=10000')->value;
		//
		if(sizeof($response['folders']) == 0) {
			return response(false, $meta, 'No folders found.');
		}
		// Find templates folder
		foreach($response['folders'] as $key => $folder) {
			if(strcasecmp($folder->Name, CONF::$TEMPLATES_FOLDER) == 0) {
				$response['templates_folder_id'] = $folder->Id;
				break;
			}
		}

		//
		return response(true, $meta, $response);
	}

	/**
	 * @return array
	 */
	function getMediasitePresenters() {
		checkArgs(__FUNCTION__, func_get_args());
		$meta                   = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		$api_request            = "Presenters";
		$response['presenters'] = _getAPI($api_request)->value;

		if(sizeof($response['presenters']) >= 1) {
			return response(true, $meta, $response);
		}

		return response(false, $meta, 'No presenters found.');
	}

	/**
	 * @return array
	 */
	function getMediasiteRecorders() {
		checkArgs(__FUNCTION__, func_get_args());
		$meta                            = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		$api_request                     = "Recorders";
		$response['mediasite_recorders'] = _getAPI($api_request)->value;

		if(sizeof($response['mediasite_recorders']) >= 1) {
			return response(true, $meta, $response);
		}

		return response(false, $meta, 'No mediasite_recorders found.');
	}

	/**
	 * @param $folder_id
	 *
	 * @return array
	 */
	function getMediasiteTemplates($folder_id) {
		checkArgs(__FUNCTION__, func_get_args());
		$meta                  = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);
		$response['templates'] = NULL;
		$api_request           = "Templates";
		$templates_response    = _getAPI($api_request . '?$top=10000')->value;

		if(sizeof($templates_response) >= 1) {
			foreach($templates_response as $key => $template) {
				// Get templates in template folder only
				if(strcasecmp($template->ParentFolderId, $folder_id) == 0) {
					$response['templates'][] = $template;
				}
			}

			return response(true, $meta, $response);
		}

		return response(false, $meta, 'No templates found.');
	}

	/******************** HELPER FUNCTIONS ********************/


	/**
	 * @param $request
	 *
	 * @return mixed
	 */
	function _getAPI($request) {
		$url = $_SESSION['mediasite_api_url'] . $request;
		if(strpos(strtolower($request), strtolower($_SESSION['mediasite_api_url'])) !== false) {
			$url = $request;
		}

		// All the curl stuff
		$ch = curl_init($url);
		// Headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"sfapikey: " . $_SESSION['mediasite_api_key'],
			"Content-Type: application/json",
		));
		// Auth
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $_SESSION['mediasite_api_user'] . ":" . $_SESSION['mediasite_api_pass']);
		// Options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false); // Set to true for all return headers


		$response = curl_exec($ch);
		// API call will gracefully die with checkHeaders if response code is not successful
		checkHttpCode($ch);
		curl_close($ch);

		// If response is a JSON string, convert to PHP var
		if(json_decode($response) != NULL) {
			return json_decode($response);
		}

		// If response is simple_xml
		return $response;
	}