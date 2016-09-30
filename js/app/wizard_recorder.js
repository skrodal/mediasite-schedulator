/**
 * @author Simon Skrødal
 */

function getMediasiteRecorders() {
	var recorders_request = $.getJSON(
		"api/",
		{method: "getMediasiteRecorders"}
	).done(function (recorders) {
		if (recorders.status == true) {
			// Global var to hold all folders
			SCHEDULES.meta.mediasite_recorders = recorders.response.mediasite_recorders;
		} else {
			SCHEDULES.meta.mediasite_recorders = false;
			slide_alert_error('Feil med henting av mediasite_recorders', 'Fikk ingen mediasite_recorders i svar fra Mediasite');
		}
	})
		.fail(function (jqxhr, textStatus, error) {
			slide_alert_error('Feil med henting av mediasite_recorders', textStatus + ": " + error);
		});
}

/**
 * Fires when recorders are fetched from server
 */
var unique_locations_lectures;
function buildRecorderSelects() {
	$('#recorder_next').addClass('disabled');
	// Update global object
	unique_locations_lectures = fetchUniqueLocations();
	var num_rooms = Object.keys(unique_locations_lectures).length;
	// Give an info box if more than one location is detected from calendar
	if (num_rooms > 1) {
		slide_alert_info(
			"INFO: Mer enn ett rom oppdaget",
			"Valgte forelesninger skjer p&aring; <span class='badge bg-orange'>" + num_rooms + "</span> forskjellige rom."
		);
	}

	// Select with all recorder names (and ID in data attrib)
	var recorders_options = '<option disabled selected>Velg</option>';
	$.each(SCHEDULES.meta.mediasite_recorders, function (index, value) {
		recorders_options += '<option data-recorder_id="' + value.Id + '" data-recorder_name="' + value.Name + '" data-recorder_version="' + value.Version + '">' + value.Name + '</option>';
	});
	//
	$recorders_select = $('#recorder_select_div').empty();
	var i = 0;
	// Build one recorder div per room
	$.each(unique_locations_lectures, function (key, value) {

		$recorders_select.append(
			'<div data-room_name="' + key + '" class="recorder_select_container col-lg-3 col-md-4 col-sm-4 col-xs-12 margin ' + bg_colours[i] + '">' +
			'<h3>' + key + '</h3>' +
			'<label>1. Velg recorder</label> <br>' +
			'<select id="recorder_select_' + i + '" name="recorder_select" class="recorder_select recorder_page_select" data-identifier="' + key + '">' +
			'<optgroup label="' + key + '">' +
			recorders_options +
			'</optgroup>' +
			'</select><br><br>' +
			'<label>2. Angi recorder funksjon</label><br>' +
			'<select id="recorder_operation_' + i + '" name="recorder_operation_select" class="recorder_operation_select recorder_page_select" data-identifier="' + key + '">' +
			'<optgroup label="' + key + '">' +
			'<option selected disabled>Velg</option>' +
			'<option data-value="man">Manually generate presentations</option>' +
			'<option data-value="co">Create and open</option>' +
			'<option data-value="cos">Create, open and start recording</option>' +
			'<option data-value="coss">Create, open, start and stop recording</option>' +
			'</optgroup>' +
			'</select>' +
			'<br><br><br></div>'
		);
		i++;
	});
}

/**
 * Selecting a recorder for a specific room. Will also add the array of lectures to the
 * schedule object for this room.
 */
$(document).on('change', 'select.recorder_select', function () {
	var select = $(this);
	var option = $("option:selected", this);
	var room_name = select.parent('div').data('room_name');

	if (SCHEDULES['schedules'][room_name] == undefined) {
		SCHEDULES['schedules'][room_name] = {};
	}

	SCHEDULES['schedules'][room_name]['recorder_id'] = option.data('recorder_id');
	SCHEDULES['schedules'][room_name]['recorder_name'] = option.data('recorder_name');
	SCHEDULES['schedules'][room_name]['recorder_version'] = option.data('recorder_version');
	SCHEDULES['schedules'][room_name]['lectures'] = unique_locations_lectures[room_name];
});


/**
 * Update selected operation for recorder/room and add to room's schedule object
 */
$(document).on('change', 'select.recorder_operation_select', function () {
	var select = $(this);
	var option = $("option:selected", this);
	var room_name = select.parent('div').data('room_name');
	if (SCHEDULES['schedules'][room_name] == undefined) {
		SCHEDULES['schedules'][room_name] = {};
	}
	SCHEDULES['schedules'][room_name]['recorder_operation_name'] = select.val();

	switch (option.data('value')) {
		case "man": // "manually generate presentations"
			SCHEDULES['schedules'][room_name]['create_presentation'] =
				SCHEDULES['schedules'][room_name]['load_presentation'] =
					SCHEDULES['schedules'][room_name]['auto_start'] =
						SCHEDULES['schedules'][room_name]['auto_stop'] = false;
			break;
		case "co": // "create and open"
			SCHEDULES['schedules'][room_name]['create_presentation'] =
				SCHEDULES['schedules'][room_name]['load_presentation'] = true;

			SCHEDULES['schedules'][room_name]['auto_start'] =
				SCHEDULES['schedules'][room_name]['auto_stop'] = false;
			break;
		case "cos": // "create, open and start recording"
			SCHEDULES['schedules'][room_name]['create_presentation'] =
				SCHEDULES['schedules'][room_name]['load_presentation'] =
					SCHEDULES['schedules'][room_name]['auto_start'] = true;

			SCHEDULES['schedules'][room_name]['auto_stop'] = false;

			break;
		case "coss": // "create, open, start and stop recording"
			SCHEDULES['schedules'][room_name]['create_presentation'] =
				SCHEDULES['schedules'][room_name]['load_presentation'] =
					SCHEDULES['schedules'][room_name]['auto_start'] =
						SCHEDULES['schedules'][room_name]['auto_stop'] = true;
			break;
		default:
			SCHEDULES['schedules'][room_name]['create_presentation'] =
				SCHEDULES['schedules'][room_name]['load_presentation'] =
					SCHEDULES['schedules'][room_name]['auto_start'] =
						SCHEDULES['schedules'][room_name]['auto_stop'] = false;
			break;
	}
});

/**
 * Find number of location and if more than one, deal with it.
 *
 * ASSUMPTION HERE IS THAT LOCATION HAS ONLY ONE FIELD IN IT (NO COMMA SEPARATION).
 *
 * @returns {Array|*}
 */
function fetchUniqueLocations() {
	// Room array with each entry containing array of lectures
	var lectures_arr = {};
	// Loop all selected presentations and grab locations
	$.each(SCHEDULES.meta.lectures, function (index, value) {
		// Room name
		tmp_location = value.location.trim();
		// If name missing, set a default name
		if (tmp_location == "") tmp_location = "Ukjent rom";
		// Init array
		if (lectures_arr[tmp_location] == undefined) {
			lectures_arr[tmp_location] = [];
		}
		// Add a lecture array as-is to the location
		lectures_arr[tmp_location].push(value);
	});

	return lectures_arr;
}


/**
 * Logic to control next button. Enabled if all selects have been changed, disabled otherwise.
 */
$(document).on('change', 'select.recorder_page_select', function () {
	var select_count = $('select.recorder_page_select').length;
	var selected_count = $('select.recorder_page_select :selected').not(':disabled').length;
	if (select_count == selected_count) {
		$('#recorder_next').removeClass('disabled');
	} else {
		$('#recorder_next').addClass('disabled');
	}
});