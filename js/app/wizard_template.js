/**
 * @author Simon Skrødal
 */

function getMediasiteTemplates(templates_folder_id) {

	table_templates = $('#table_templates').DataTable({
		'responsive': true,
		'processing': true,
		'paging': true,
		'ordering': true,
		'info': true,
		'searching': true,
		'language': {
			'lengthMenu': 'Vis _MENU_ templates per side',
			'zeroRecords': 'Viser 0 templates',
			'info': 'Viser side _PAGE_ av _PAGES_',
			'infoEmpty': 'Fant ingen templates',
			'infoFiltered': '(filtrert fra totalt _MAX_ templates)',
			'search': 'Snars&oslash;k ',
			'processing': '<span class="ion ion-loading-c"></span> Venligst vent...',
			'paginate': {
				'first': 'F&oslash;rste',
				'previous': '&larr;',
				'next': '&rarr;',
				'last': 'Siste'
			}
		},
		'order': [
			[1, 'asc']
		],
		'ajax': {
			'url': 'api/?method=getMediasiteTemplates&folder_id=' + templates_folder_id,
			'dataSrc': 'response.templates'
		},
		'columnDefs': [
			{'bSortable': false, 'aTargets': [0]},
			{
				'targets': 0,
				'data': 'Id',
				'render': function (data, type, full, meta) {
					return '<input type="radio" name="templates_select" value="' + data + '" data-template-id="' + full.Id + '" data-template-name="' + full.Name + '" data-template-description="' + full.Description + '">';
				}
			}
		],
		'columns': [
			{'data': 'Id'},
			{'data': 'Name'},
			{'data': 'Description'}//,
			//{ 'data': 'Id' }
		]
	});
}

// Update selected template
$(document).on('change', 'input[name=templates_select]:radio', function () {
	SCHEDULES.template = {};
	SCHEDULES.template.id = $(this).attr('data-template-id');
	SCHEDULES.template.name = $(this).attr('data-template-name');
	SCHEDULES.template.description = $(this).attr('data-template-description');

	$('#template_next').removeClass('disabled');
});
