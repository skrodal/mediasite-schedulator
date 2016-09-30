/**
 * Load calendar feed by calling the API
 *
 * @author Simon Skrødal
 */
$('#btn_get_calendar').on('click', function () {
	$('#lecture_select_count').text("0");
	$('#subjects_next').addClass('disabled');
	//
	$('#get_calendar').find('.ajax').toggleClass('hidden');
	//
	$.getJSON('api/', {
		method: "getCalendarAsJSON",
		ics: $('#input_calendar_url').val(),
		format: "json"
	}).done(function (data) {
		//
		$('#get_calendar').find('.ajax').toggleClass('hidden');

		if (data.status && data.response.length > 0) {
			if (table_lectures !== false) {
				table_lectures.destroy();
			}
			//
			SCHEDULES.meta.ical = $('#input_calendar_url').val();
			//
			slide_alert_info('&Aring;lbings!', '<span class="badge bg-orange bold">' + data.response.length + '</span> kalenderinnslag importert!<br>P&aring; tide &aring; velge forelesninger :-)');
			$('#ical_next').removeClass('disabled');
			// Go to next page automatically. The [0] is a quirk, won't work without for some reason I can't be bothered to find out.
			$('#ical_next a').trigger('click');
			//
			SCHEDULES.meta.ical_feed = data.response;
			// LECTURES SELECT
			table_lectures = $('#table_lectures').DataTable({
				'responsive': true,
				'processing': true,
				'paging': true,
				'ordering': true,
				'info': true,
				'searching': true,
				'language': {
					'lengthMenu': 'Vis _MENU_ innslag per side',
					'zeroRecords': 'Fant ingen forelesninger',
					'info': 'Viser side _PAGE_ av _PAGES_',
					'infoEmpty': 'Ingen forelesninger',
					'infoFiltered': '(filtrert fra totalt _MAX_ forelesninger)',
					'search': 'Snars&oslash;k  ',
					'processing': 'Venligst vent...',
					'paginate': {
						'first': 'F&oslash;rste',
						'previous': '&larr;',
						'next': '&rarr;',
						'last': 'Siste'
					}
				},
				'order': [
					[3, 'asc']
				],
				'data': SCHEDULES.meta.ical_feed,
				'columnDefs': [
					{'bSortable': false, 'aTargets': [0]},
					{
						'targets': 0,
						'data': 'uid',
						'render': function (data, type, full, meta) {
							var disabled = '';
							if (moment.unix(full.start).tz('Europe/Oslo') < moment().tz('Europe/Oslo')) {
								disabled = 'disabled';
							}
							// Added stringify fix in case of apostrophes {\'}
							return '<input type="checkbox" name="lectures_select" ' + disabled + ' value="' + data + '" data-entry=\'' + JSON.stringify(full).replace(/'/g, "\\u0027") + '\'>';
						}
					},
					{
						'targets': 3,
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
						'targets': 4,
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
					{'data': 'uid'},
					{'data': 'summary'},
					{'data': 'description'},
					{'data': 'start'},
					{'data': 'end'},
					{'data': 'location'}
				]
			});

			if ($('input[name=lectures_select]:checkbox:enabled').length == 0) {
				slide_alert_error('Huff da...', 'Alle forelesningene har g&aring;tt ut p&aring; dato jo.<br/>Schedulator kan ikke gj&oslash;re opptak av fortida ;-)');
			}

		} else {
			slide_alert_error('Farsken!', data.message + '<br/>Vennligst pr&oslash;v igjen.');
			$('#ical_next').addClass('disabled');
		}
		//console.log(data);
	});
});

// Single change voids 'Select all'
$(document).on('click', 'input[name=lectures_select]:checkbox', function () {
	$('input[name=check_all_lectures]:checkbox').prop('checked', false);
	var selectCount = table_lectures.column(0).nodes().to$().find('input[name=lectures_select]:checkbox:checked').length;
	$('#lecture_select_count').text(selectCount);

	if (selectCount > 0) {
		$('#subjects_next').removeClass('disabled');
	} else {
		$('#subjects_next').addClass('disabled');
	}
});
// Use datatables API to loop all checkboxes and set state
$('input[name=check_all_lectures]:checkbox').on('click', function () {
	// $('input[name=lectures_select]:checkbox').not(this).prop('checked', this.checked);
	var selectAll = this.checked;
	// Make sure both, top and bottom 'select all' boxes, are updated.
	$('input[name=check_all_lectures]:checkbox').prop('checked', selectAll);

	// Uncheck everything to start off with
	table_lectures.column(0).nodes().to$().find('input[name=lectures_select]:checkbox').each(function (value, index) {
		$(this).prop('checked', false);
	});

	// If "select all" is checked, check all, otherwise we'll just leave it as everything unchecked
	if (selectAll) {
		// Find only rows that are visible (part of filter)
		table_lectures.$('tr', {"filter": "applied"}).find('input[name=lectures_select]:checkbox').each(function (value, index) {
			//console.log('Data in index: ' + index + ' is: ' + value);
			// If row is not disabled (due to expired datetime), update checked property
			if (!$(this).prop('disabled')) {
				$(this).prop('checked', selectAll);
			}
		});
	}
	// Update counter and info
	var selectCount = table_lectures.column(0).nodes().to$().find('input[name=lectures_select]:checkbox:checked').length;
	$('#lecture_select_count').text(selectCount);
	// Enable/disable next button
	if (selectCount > 0) {
		$('#subjects_next').removeClass('disabled');
	} else {
		$('#subjects_next').addClass('disabled');
	}
});

