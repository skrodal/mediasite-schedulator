<?php
	/**
	 * All POST (write) interactions with the Mediasite REST API.
	 *
	 * @author Simon Skrodal
	 * @since  02.06.14
	 */

	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	// Ensure an available method was POSTed with the request
	$method = isset($_POST['method']) ? filter_var($_POST['method'], FILTER_SANITIZE_STRING) : exit(json_encode(array(
		"status"  => false,
		'message' => 'No method provided.'
	)));

	// Container for POST data (not all will be used, but collected for possible future development)
	$request_data              = NULL;
	$request_data['schedules'] = isset($_POST['schedules']) ? $_POST['schedules'] : NULL;
	$request_data['template']  = isset($_POST['template']) ? $_POST['template'] : NULL;
	$request_data['folder']    = isset($_POST['folder']) ? $_POST['folder'] : NULL;
	$request_data['subject']   = isset($_POST['subject']) ? $_POST['subject'] : NULL;

	// FUNCTION ACCESS CONTROLLER
	try {
		switch($method) {
			case 'createSchedule':
				$response = createSchedule($request_data);
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
	 *
	 * @param $request_data
	 *
	 * @return array
	 */
	function createSchedule($request_data) {
		checkArgs(__FUNCTION__, func_get_args());
		$meta = array(
			'method'      => __FUNCTION__,
			'description' => CONF::$DESC[__FUNCTION__]
		);

		// Target method on the Mediasite API
		$api_request = "Schedules";

		// Build request object, mostly from POSTed data
		$schedules_post_data['Name']               = $request_data['subject']['title'];
		$schedules_post_data['Description']        = $request_data['subject']['description'];
		$schedules_post_data['TitleType']          = "ScheduleNameAndAirDateTime";
		$schedules_post_data['FolderId']           = $request_data['folder']['id'];
		$schedules_post_data['ScheduleTemplateId'] = $request_data['template']['id'];
		$schedules_post_data['IsUploadAutomatic']  = true;
		$schedules_post_data['DeleteInactive']     = true;
		$i                                         = 1;
		// MAKE SCHEDULE(S)!
		foreach($request_data['schedules'] as $key => $value) {
			// Recorder info is stored per schedule (room)
			$schedules_post_data['RecorderId']         = $value['recorder_id'];
			$schedules_post_data['CreatePresentation'] = $value['create_presentation'];
			$schedules_post_data['LoadPresentation']   = $value['load_presentation'];
			$schedules_post_data['AutoStart']          = $value['auto_start'];
			$schedules_post_data['AutoStop']           = $value['auto_stop'];

			if(sizeof($request_data['schedules']) > 1) {
				$schedules_post_data['Description'] = $request_data['subject']['description'] .
					' [dette er schedule ' . $i . ' av ' . sizeof($request_data['schedules']) .
					' og er knyttet til rom "' . $key . '"]';
			}
			$i++;

			// Make request!
			$schedule_response = _postAPI($api_request, $schedules_post_data);
			// Store response
			$response[$key] = $schedule_response;

			// If Schedule was created, update the object
			if(isset($schedule_response->Id)) {
				$response[$key]->subject_tag = "Not set";
				$response[$key]->presenters  = "Not set";
				$response[$key]->recurrence  = NULL;
				// Add subject code as Tag (if set)
				if(isset($request_data['subject']['code']) && $request_data['subject']['code'] !== "") {
					$response[$key]->subject_tag = _addTagToSchedule($schedule_response->{'Tags@odata.navigationLinkUrl'}, $request_data['subject']['code']);
				}
				// Add presenter(s) (if set)
				if(isset($request_data['subject']['presenters'])) {
					$response[$key]->presenters = _addPresentersToSchedule($schedule_response->{'Presenters@odata.navigationLinkUrl'}, $request_data['subject']['presenters']);
				}
				// Add recurrences (lectures)
				$response[$key]->recurrence = _addRecurrencesToSchedule($schedule_response->{'Recurrences@odata.navigationLinkUrl'}, $value['lectures']);

			} else {
				return response(false, $meta, 'No Schedule was created.');
			}
		}

		return response(true, $meta, $response);
	}

	/**
	 * @param $tag_post_url
	 * @param $tag
	 *
	 * @return mixed|string
	 */
	function _addTagToSchedule($tag_post_url, $tag) {
		return _postAPI($tag_post_url, array('Tag' => $tag));
	}

	/**
	 * Add presenter(s) from array
	 *
	 * @param $presenter_post_url
	 * @param $presenters_arr
	 *
	 * @return array|null
	 */
	function _addPresentersToSchedule($presenter_post_url, $presenters_arr) {
		$presenters_response = NULL;
		foreach($presenters_arr as $presenter) {
			$fname     = "";
			$mname     = "";
			$lname     = "";
			$presenter = explode(' ', $presenter);
			if(sizeof($presenter) > 0) {
				switch(sizeof($presenter)) {
					case 1:
						$fname = $presenter[0];
						break;
					case 2:
						$fname = $presenter[0];
						$lname = $presenter[1];
						break;
					default:
						$fname = $presenter[0];
						$mname = $presenter[1];
						$lname = $presenter[2];
						break;
				}

				$presenters_response[] = _postAPI($presenter_post_url, array(
					'FirstName'  => $fname,
					'MiddleName' => $mname,
					'LastName'   => $lname,
				));
			}
		}

		return $presenters_response;
	}

	/**
	 * @param $recurrences_post_url
	 * @param $lectures
	 *
	 * @return array|null
	 */
	function _addRecurrencesToSchedule($recurrences_post_url, $lectures) {
		$recurrence_response = NULL;
		foreach($lectures as $index => $lecture) {
			$datetime_start = date('Y-m-d\TH:i:s', $lecture['start']);
			$datetime_end   = date('Y-m-d\TH:i:s', $lecture['end']);
			$duration_ms    = ($lecture['end'] - $lecture['start']) * 1000;

			$post_data = array(
				'StartRecordDateTime' => $datetime_start,
				'RecordDuration'      => $duration_ms,
				'RecurrencePattern'   => 'None'
			);

			$recurrence_response[] = _postAPI($recurrences_post_url, $post_data);
		}

		return $recurrence_response;
	}


	/******************** HELPER FUNCTIONS ********************/

	function _postAPI($request, $request_data) {
		$url = $_SESSION['mediasite_api_url'] . $request;
		if(strpos(strtolower($request), strtolower($_SESSION['mediasite_api_url'])) !== false) {
			$url = $request;
		}
		$request_data = json_encode($request_data);
		$auth_header  = "Authorization: Basic " . base64_encode($_SESSION['mediasite_api_user'] . ":" . $_SESSION['mediasite_api_pass']);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		// Headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"sfapikey: " . $_SESSION['mediasite_api_key'],
			$auth_header,
		));

		// Body
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
		// Send synchronously
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);

		// Failure
		if($response === false) {
			return (json_encode(array(
				'status'  => false,
				'message' => curl_error($ch)
			)));
		}

		curl_close($ch);

		return json_decode($response);
	}