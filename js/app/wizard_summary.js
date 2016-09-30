/**
 * @author Simon Skrødal
 */


function updateSummary() {
	// Merge input fields if only one presenter (means that it has been entered manually and not in the array).
	if (SCHEDULES.subject.presenters.length <= 1) {
		SCHEDULES.subject.presenters[0] = $('#subject_presenter_fname').val() + " " + $('#subject_presenter_mname').val() + " " + $('#subject_presenter_lname').val();
		// If input fields were blank...
		if (SCHEDULES.subject.presenters[0].trim() == "") SCHEDULES.subject.presenters = [];
	}

	if (SCHEDULES.meta.lectures.length > 0) {
		// Update fields on summary page
		$('#summary_schedule_title').val(SCHEDULES.subject.title);
		$('#summary_schedule_description').val(SCHEDULES.subject.description);
		$('#summary_subject_code').val(SCHEDULES.subject.code);
		if (SCHEDULES.subject.presenters.length <= 0) {
			$('#summary_subject_presenters').empty().html('<ul><li>Mangler</li></ul>');
		} else {
			$('#summary_subject_presenters').empty().html('<ul></ul>');
			$.each(SCHEDULES.subject.presenters, function (index, value) {
				$('#summary_subject_presenters').find('ul').append('<li>' + value + '</li>');
			});
		}
		//
		$('#summary_template').val(SCHEDULES.template.name + ' (ID: ' + SCHEDULES.template.id + ')');
		//
		$('#summary_folder').val(SCHEDULES.folder.name + ' (ID: ' + SCHEDULES.folder.id + ')');

		// Recorder/operations/room summary table
		var $recorder_summary_tbody = $('#summary_recorder').find('tbody');
		$recorder_summary_tbody.empty();

		$.each(SCHEDULES['schedules'], function (room_name, recorder_obj) {
			$recorder_summary_tbody.append(
				'<tr>' +
				'<td>' + room_name + '</td>' +
				'<td>' + recorder_obj.recorder_name + '</td>' +
				'<td>' + recorder_obj.recorder_operation_name + '</td>' +
				'</tr>'
			);
		});

		// Lectures/recurrence summary table
		if (table_lectures_summary !== false) {
			table_lectures_summary.destroy();
		}
		table_lectures_summary = $('#summary_table_lectures').DataTable({
			processing: true,
			'language': {
				'lengthMenu': 'Vis _MENU_ presentasjoner per side',
				'zeroRecords': 'Fant ingen presentasjoner',
				'info': 'Viser side _PAGE_ av _PAGES_',
				'infoEmpty': 'Ingen presentasjoner',
				'infoFiltered': '(filtrert fra totalt _MAX_ presentasjoner)',
				'search': 'Snars&oslash;k  ',
				'processing': 'Venligst vent...',
				'paginate': {
					'first': 'F&oslash;rste',
					'previous': 'Forrige',
					'next': 'Neste',
					'last': 'Siste'
				}
			},
			'order': [
				[3, 'asc']
			],
			'data': SCHEDULES.meta.lectures,
			'columnDefs': [
				{
					'targets': 2,
					'data': 'start',
					'render': function (data, type, full, meta) {
						var expired = '';
						if (moment.unix(full.start).tz('Europe/Oslo') < moment().tz('Europe/Oslo')) {
							expired = 'text-red';
						}
						return '<span class="' + expired + '" data-timestamp="' + data + '">' + moment.unix(data).tz('Europe/Oslo').format("DD-MM-YYYY, HH:mm") + '</span>';
					}
				},
				{
					'targets': 3,
					'data': 'end',
					'render': function (data, type, full, meta) {
						var expired = '';
						if (moment.unix(full.start).tz('Europe/Oslo') < moment().tz('Europe/Oslo')) {
							expired = 'text-red';
						}
						return '<span class="' + expired + '" data-timestamp="' + data + '">' + moment.unix(data).tz('Europe/Oslo').format("DD-MM-YYYY, HH:mm") + '</span>';
					}
				}
			],
			'columns': [
				{'data': 'summary'},
				{'data': 'description'},
				{'data': 'start'},
				{'data': 'end'},
				{'data': 'location'}
			]
		});
	} else {
		SCHEDULES = false;
	}
}

/**
 * DO AJAX
 */
$('#submit_schedule').on('click', function () {
	// For result status display
	var $response_container = $('#api_response_json');
	//
	if (!TESTING) {
		if (SCHEDULES !== false) {
			SCHEDULES.method = "createSchedule";
			// Make a clone and then ditch some data not needed to POST (ref. PHP Warning:  Unknown: Input variables exceeded 1000.)
			var POSTDATA = JSON.parse(JSON.stringify(SCHEDULES));
			delete POSTDATA.meta;
			// POST to API
			var posting = $.post('api/', POSTDATA);
			// Put the results in a div
			posting.done(function (data) {
				$(".ajaxloader").hide();
				$response_container.empty();
				if (data.status == true) {
					var num_schedules = Object.keys(data.response).length;
					var schedules_text = num_schedules > 1 ?
					"<span class='badge bg-orange'>" + num_schedules + "</span> schedules ble opprettet i Mediasite, én for hvert rom :-)" :
						"Din Schedule ble opprettet i Mediasite :-)";

					$response_container.html(
						'<div id="schedule_summary" class="callout callout-info">' +
						'<h4>Schedule opprettet!</h4>' +
						'<p>' + schedules_text + '</p>' +
						'</div>'
					);

					var subject_tag = false;
					var lecture_count = 0;
					$.each(data.response, function (key, value) {
						if (value.subject_tag.Tag !== undefined) {
							subject_tag = value.subject_tag.Tag;
						}
						lecture_count += value.recurrence.length;
					});

					//  subject_tag (if set, report name)
					if (subject_tag) {
						$response_container.find('#schedule_summary').append('<p>Fagkode: <code>' + subject_tag + '</code></p>');
					}
					//  recurrence (lectures) array (report array count and success)
					$response_container.find('#schedule_summary').append(
						'<p>' +
						'Til sammen <span class="badge bg-orange">' + lecture_count + '</span>' +
						' forelesninger ble schedulert.' +
						'</p>'
					);
					//  recurrence (lectures) array (report array count and success)
					$response_container.find('#schedule_summary').append(
						'<br><p>' +
						'Vi anbefaler at du n&aring; dobbeltsjekker din nye Schedule i Mediasite Management portal.' +
						'</p>'
					);
				}
				// False status returned from API...
				else {
					$("#schedule_modal_box").removeClass("bg-dark-gray").addClass("bg-red");
					$response_container.html('<div class="alert alert-danger"><p class="lead"><i class="ion ion-alert-circled"></i>&nbsp;&nbsp;En feil har oppst&aring;tt: </p>' +
						'<br><code>' + data.message + '</code><br><br>' +
						'<p>Du kan varsle om feilen ved &aring; kopiere r&aring;data nedenfor og sende til <a href="mailto:support@ecampus.no">support@ecampus.no</a>.</p></div>'
					);
				}
				// Output RAW JSON:
				$response_container.append('<p class="text-size-medium uninett-fontColor-white">R&aring;data fra Schedulator:</p>');
				$response_container.append('<textarea class="uninett-fontColor-black" style="width: 100%; height: 300px;">' + JSON.stringify(data, null, '\t') + '</textarea>');
				// Forces browser reload on button close
				$response_container.append('<a href="app/auth/logout.php" class="btn btn-sm btn-danger">Ferdig, logg av</a>');
				$response_container.append('<button type="button" class="btn btn-lg btn-primary pull-right" onclick="location.reload(true)">Schedulér nytt emne</button>');
				$response_container.append('<span class="clearfix"></span>');
			});
			/*
			 ERROR HANDLING
			 */
			posting.fail(function (jqXHR, textStatus, errorThrown) {
				$("#schedule_modal_box").removeClass("bg-dark-gray").addClass("bg-red");
				$response_container.html('<div class="alert alert-danger"><p class="lead"><i class="ion ion-alert-circled"></i>&nbsp;&nbsp;En feil har oppst&aring;tt: </p>' +
					'<br><code>' + textStatus + ': ' + errorThrown + '</code><br><br>' +
					'<p>Du kan varsle om feilen ved &aring; kopiere r&aring;data nedenfor og sende til <a href="mailto:support@ecampus.no">support@ecampus.no</a>.</p></div>'
				);
				$response_container.append('<p class="text-size-medium uninett-fontColor-white">R&aring;data fra Schedulator:</p>');
				$response_container.append('<textarea class="uninett-fontColor-black" style="width: 100%; height: 300px;">' + JSON.stringify(jqXHR, null, '\t') + '</textarea>');
				// Forces browser reload on button close
				$response_container.append('<a href="app/auth/logout.php" class="btn btn-sm btn-danger">Ferdig, logg av</a>');
				$response_container.append('<button type="button" class="btn btn-lg btn-primary pull-right" onclick="location.reload(true)">Schedulér nytt emne</button>');
				$response_container.append('<span class="clearfix"></span>');
			});
		}
	}
});