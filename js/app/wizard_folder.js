/**
 *
 * @author Simon Skrødal
 */


function getMediasiteFolders() {
	// Folders
	var folders_request = $.getJSON("api/", {
		method: "getMediasiteFolders"
	})
		.done(function (folders) {
			// Global var to hold all folders
			var mediasite_folders = folders;

			if (mediasite_folders.status == true) {
				SCHEDULES.meta.mediasite_root_folder_id = mediasite_folders.response.root_folder_id;
				SCHEDULES.meta.mediasite_templates_folder_id = mediasite_folders.response.templates_folder_id;
				SCHEDULES.meta.mediasite_folders = mediasite_folders.response.folders;

				// Safe to make calls for templates and mediasite_recorders:
				getMediasiteRecorders();
				getMediasiteTemplates(SCHEDULES.meta.mediasite_templates_folder_id);
			} else {
				slide_alert_error('Feil med lesing av mapper', 'Fikk ingen mapper i svar fra Mediasite');
			}
		})
		.fail(function (jqxhr, textStatus, error) {
			var err = textStatus + ": " + error;
			slide_alert_error('Feil med lesing av mapper', err);
		});

	/**
	 * Build directory tree from AJAX response and make folders selectable
	 */
	folders_request.complete(function () {
		$('#folder_container .ajaxloader').fadeOut();
		/******
		 console.log( mediasite_folders );
		 console.log( "Root: " + mediasite_root_folder_id );
		 console.log( "Templates: " + mediasite_templates_folder_id );
		 /******/

			// List all of roots immediate subfolders and then drill down
		$.each(SCHEDULES.meta.mediasite_folders, function (key, value) {
			if (value.ParentFolderId == SCHEDULES.meta.mediasite_root_folder_id) {
				$('#folder_listing').append(
					'<p id="' + value.Id + '" class="parent_folder" style="padding-left: 0px">' +
						'<i class="ion ion-ios7-folder-outline folder"></i>&nbsp;&nbsp;' +
						'<a class="folder_select" ' +
						'id="' + value.Id + '" ' +
						'data-folder-id="' + value.Id + '" ' +
						'data-folder-name="' + value.Name + '" ' +
						'data-folder-description="' + value.Description + '" ' +
						'data-folder-index="' + key + '">' + value.Name +
						'</a>' +
						'</p>' +
						'<div id="' + value.Id + '" class="child_folders"></div>'
				);

				getSubfolders(value.Id, value.Id);
			}
		});
	});
}

function getSubfolders(parent_folder_id, parent_div_id) {
	var padding = 0;
	var padding_increment = 20;
	var $parent_div = $('#folder_listing').find('div#' + parent_div_id);

	// List all of roots immediate subfolders
	$.each(SCHEDULES.meta.mediasite_folders, function (key, value) {
		// If parent_folder_id is parent of this folder
		if (value.ParentFolderId == parent_folder_id) {
			var $current_p = $('p#' + parent_folder_id);
			padding = $current_p.css("padding-left");
			padding = parseInt(padding.substring(0, padding.length - 2));
			padding += padding_increment;
			$parent_div.append(
				'<p id="' + value.Id + '" style="padding-left: ' + padding + 'px">' +
					'<i class="ion ion-ios7-folder-outline folder"></i>&nbsp;&nbsp;' +
					'<a class="folder_select" ' +
					'data-folder-id="' + value.Id + '" ' +
					'data-folder-name="' + value.Name + '" ' +
					'data-folder-description="' + value.Description + '" ' +
					'data-folder-index="' + key + '">' + value.Name +
					'</a>' +
					'</p>'
			);
			// Get subfolders of last added folder
			getSubfolders(value.Id, parent_div_id);
		}
	});
	$parent_div.slideUp();
}

$(document).on('click', 'p.parent_folder', function () {
	var $child_folders = $(this).next('div.child_folders');
	if (!$child_folders.hasClass('open_folder')) {
		$('div.open_folder').removeClass('open_folder').slideUp();
		$child_folders.addClass('open_folder').slideDown();
		$('html, body').animate({
			scrollTop: $('#root_folder_anchor').offset().top - 60
		}, 500);
	}
});


// Update selected folder
$(document).on('click', 'a.folder_select', function () {
	SCHEDULES.folder = {};
	SCHEDULES.folder.id = $(this).attr('data-folder-id');
	SCHEDULES.folder.name = $(this).attr('data-folder-name');
	SCHEDULES.folder.description = $(this).attr('data-folder-description');

	$('span#folder_select_info').html("Du har valgt mappe <code>" + SCHEDULES.folder.name + "</code>");

	$("i.folder").removeClass("ion-ios7-folder").addClass("ion-ios7-folder-outline");
	$(this).prev("i").removeClass("ion-ios7-folder-outline").addClass("ion-ios7-folder");

	$("a.folder_select").removeClass("bold text-red");
	$(this).addClass("bold text-red");
	// Selection made - allow moving to next page
	$('#folder_next').removeClass('disabled');
});