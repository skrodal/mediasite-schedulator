/**
 * @author Simon Skrødal
 */

// Fires when user clicks 'next' on previous page
$('#subjects_next').on('click', function () {
	// As long as next button is not disabled, populate dropdowns
	if (!$('#subjects_next').hasClass('disabled')) {
		populateAutoMetaFields();
		buildRecorderSelects();
		// Reset content/visibility from previous session
		$('#presenter_list_auto_select_container').addClass('hidden');
		$('#presenter_manual_input_container').remove('hidden');
		$('#selected_presenter_list_container').addClass('hidden');

		$('#schedule_title').val('Schedule opprettet ' + ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth()+1)).slice(-2) + '.' + date.getFullYear());
		$('#schedule_description').val('Opprettet med Mediasite Schedulator');
		$('#subject_code').val('');

		$('#subject_presenter_fname').val('');
		$('#subject_presenter_mname').val('');
		$('#subject_presenter_lname').val('');
	}
});


/**
 *
 */
function populateAutoMetaFields() {
	SCHEDULES['schedules'] = {};
	SCHEDULES.meta.lectures = [];
	SCHEDULES.subject = {};
	SCHEDULES.subject.presenters = [];

	// Get the data for all selected rows in the table
	table_lectures.column(0).nodes().to$().find('input[name=lectures_select]:checkbox:checked').each(function () {
		SCHEDULES.meta.lectures.push(JSON.parse($(this).attr('data-entry')));
	});

	// Let's just take the first entry - all entries should follow the same format anyways
	// Replace every possible separator to comma, then explode by comma to array
	var select_title_options = SCHEDULES.meta.lectures[0].summary.replace(/(?:\r\n|\r|\n|\,|,)/g, ",").split(',');
	var select_description_options = SCHEDULES.meta.lectures[0].description.replace(/(?:\r\n|\r|\n|\,|,)/g, ",").split(',');
	var select_location_options = SCHEDULES.meta.lectures[0].location.replace(/(?:\r\n|\r|\n|\,|,)/g, ",").split(',');
	//
	var select_disabled_dom = "<option disabled selected>Velg felt</option>";
	var select_title_dom = "";
	var select_description_dom = "";
	var select_location_dom = "";
	// Update the sample preview of a single entry
	var $cal_entry_preview = $('#cal_entry_preview');
	$cal_entry_preview.empty();
	// Loop title and description arrays and populate the DOM for select entries
	$.each(select_title_options, function (index, value) {
		var val = value.trim();
		if(val !== ""){
			select_title_dom += "<option data-column='summary' data-position='" + (index) + "'>" + val + "</option>";
			$cal_entry_preview.append(' | ' + val);
		}
	});
	$cal_entry_preview.append(' | ');
	$.each(select_description_options, function (index, value) {
		var val = value.trim();
		if(val !== ""){
			select_description_dom += "<option data-column='description' data-position='" + (index) + "'>" + val + "</option>";
			$cal_entry_preview.append(val + ' | ');
		}
	});

	$.each(select_location_options, function (index, value) {
		var val = value.trim();
		if(val !== ""){
			select_location_dom += "<option data-column='location' data-position='" + (index) + "'>" + val + "</option>";
			$cal_entry_preview.append(val + ' | ');
		}
	});

	var select_dom = select_disabled_dom + select_title_dom + select_description_dom + select_location_dom;
	var select_presenter_dom = select_disabled_dom + select_title_dom + select_description_dom;

	$('#schedule_auto_title').html(select_dom);
	$('#subject_auto_code').html(select_dom);
	$('#schedule_auto_description').html(select_dom);
	$('#subject_auto_presenter_name').html(select_presenter_dom);
}



/**
 * When selection changed on any dropdown
 */
$("select.schedule_auto_select").on('change', function () {
	// The parent <select>
	var select = $(this);
	// The <option> selected
	var option = $("option:selected", this);
	// The value
	var valueSelected = this.value;
	// Which manual input the selection controls/updates
	var option_controls = select.data().controls;
	// Update manual input fields
	if(option_controls !== 'subject_presenter') {
		// Update manual input field
		$('#'+option_controls).val(valueSelected);
	}
	// Presenter was changed
	else {
		// If field is presenter, we need to find all of them in this position and split
		// data-column and data-position attributes tells us where to look for the presenter name
		SCHEDULES.subject.presenters = fetchUniquePresenters(option.data().column, option.data().position);
		// Info field underneath select-input for > 1 presenters
		$selected_presenter_list_container = $('#selected_presenter_list_container');
		// Info fields on manual input-tab
		$presenter_list_auto_select_container = $('#presenter_list_auto_select_container').empty();
		$presenter_manual_input_container = $('#presenter_manual_input_container');

		// Will be 0/empty if issues with missing data fields in table cells
		if(SCHEDULES.subject.presenters.length > 0){
			if(SCHEDULES.subject.presenters.length > 1) {
				// Update info fields
				$selected_presenter_list_container.find('#presenter_count').html(SCHEDULES.subject.presenters.length);
				$selected_presenter_list_container.find('#presenter_ul').empty();
				$presenter_list_auto_select_container.html('<ul></ul>');
				$.each(SCHEDULES.subject.presenters, function(index, value){
					$selected_presenter_list_container.find('#presenter_ul').append('<li>' + value + '</li>');
					$presenter_list_auto_select_container.find('ul').append('<li>' + value + '</li>');
				});
				// Visibility
				$selected_presenter_list_container.removeClass('hidden');
				$presenter_list_auto_select_container.removeClass('hidden');
				$presenter_manual_input_container.addClass('hidden');
				// Only one presenter
			} else {
				// Hide presenter list in auto-tab
				$selected_presenter_list_container.addClass('hidden');
				// Update fields in manual tab
				presenter_name_split = SCHEDULES.subject.presenters[0].trim().split(" ");
				$('#subject_presenter_fname').val(presenter_name_split[0]);
				if(presenter_name_split.length == 2) {
					$('#subject_presenter_lname').val(presenter_name_split[1]);
				}
				if(presenter_name_split.length > 2) {
					$('#subject_presenter_mname').val(presenter_name_split[1]);
					$('#subject_presenter_lname').val(presenter_name_split[2]);
				}
				//
				$presenter_list_auto_select_container.addClass('hidden');
				$presenter_manual_input_container.removeClass('hidden');
			}
		}
	}
});

/**
 * Activated when user selects field dedicated for presenters in calendar.
 *
 * @param presenter_column
 * @param presenter_position
 */
function fetchUniquePresenters(presenter_column, presenter_position) {
	// To store unique presenters
	var presenters = [];
	var tmp_location;
	//
	switch (presenter_column) {
		// Look for presenter name in summary field of calendar
		case 'summary':
			// Loop each selected presentation from calendar
			$.each(SCHEDULES.meta.lectures, function(index, value){
				// Turn string into array, split by comma
				tmp_location = value.summary.replace(/(?:\r\n|\r|\n|\,|,)/g, ",").split(',');
				if(tmp_location[presenter_position] !== undefined) {
					// Pick position in array, as selected by user in the UI
					presenters.push(tmp_location[presenter_position].trim());
				} else {
					// Cell position does not exist, exit loop and function with empty presenter array and display warning
					alertUniquePresenterError(presenter_column, presenter_position);
					presenters = [];
					return false;
				}
			});
			break;
		// Look for presenter name in description field of calendar
		case 'description':
			$.each(SCHEDULES.meta.lectures, function(index, value){
				tmp_location = value.description.replace(/(?:\r\n|\r|\n|\,|,)/g, ",").split(',');
				if(tmp_location[presenter_position] !== undefined) {
					presenters.push(tmp_location[presenter_position].trim());
				} else {
					// Cell position does not exist, exit loop and function with empty presenter array and display warning
					alertUniquePresenterError(presenter_column, presenter_position);
					presenters = [];
					return false;
				}
			});
			break;
	}
	// Make array unique and return
	return presenters = presenters.filter(function(item, i, ar){ return ar.indexOf(item) === i; });
}

function alertUniquePresenterError(presenter_column, presenter_position){
	// Position index does not exist for all cells
	slide_alert_error(
		'Foreleser ikke satt!',
		'Navn mangler i én eller flere rader i kolonne <code>' + presenter_column + '</code>, posisjon <code>' + (presenter_position+1) + '</code><br><br>' +
		'Du kan alltids legge til foreleser manuelt...'
	);
}


/**
 * Update our schedule object when moving forward
 */
$('#metadata_next').on('click', function () {
	SCHEDULES.subject.code = $('#subject_code').val();
	SCHEDULES.subject.title = $('#schedule_title').val();
	SCHEDULES.subject.description = $('#schedule_description').val();
});