/**
 * Compiled with Koala
 *
 * @author Simon Skrødal
 */

// @koala-prepend "app.js"
// @koala-append "wizard_folder.js"
// @koala-append "wizard_recorder.js"
// @koala-append "wizard_template.js"
// @koala-append "wizard_lectures.js"
// @koala-append "wizard_summary.js"

// Everything sent to server for schedule creation
// var POSTDATA = false;
//
var SCHEDULES = {};
//
var date = new Date();
// DataTable data
var table_templates = table_lectures = table_lectures_summary = false;
// Keep track of pages
var pagenum = 0;
// For randomness
var bg_colours = ["bg-light-blue","bg-olive","bg-orange","bg-blue","bg-red","bg-yellow","bg-aqua","bg-green","bg-navy","bg-teal","bg-fuchsia","bg-purple","bg-maroon","bg-light-blue","bg-olive","bg-orange","bg-blue","bg-red","bg-yellow","bg-aqua","bg-green","bg-navy","bg-teal","bg-fuchsia","bg-purple","bg-maroon"];

$(document).ready(function () {
    var totalpages = $('ul.pager').length;
	SCHEDULES.meta = {};

    if (TESTING) {
        $("#test-alert").removeClass("hidden");
    } else {
        // getMediasiteTemplates and getMediasiteRecorders are called by below function
        getMediasiteFolders();
    }

    // PAGINATION
    $('.pager a').on('click', function () {
        if (!$(this).parent().hasClass('disabled')) {
            switch (this.text.toLowerCase()) {
                case 'start':
                case 'neste':
                    $(this).closest('section.wizard_page').toggleClass('hidden');
                    $(this).closest('section.wizard_page').next('section.wizard_page').toggleClass('hidden');
                    pagenum++;
                    break;
                case 'forrige':
                    $(this).closest('section.wizard_page').toggleClass('hidden');
                    $(this).closest('section.wizard_page').prev('section.wizard_page').toggleClass('hidden');
                    pagenum--;
                    break;
            }
            $('ul.treeview-menu').find('li').removeClass('active');
            $('ul.treeview-menu li:nth-child(' + pagenum + ')').toggleClass('active');
            if (pagenum == totalpages - 1) {
                // Update every time we reach the last page
                updateSummary();
            }
        } else {
	        slide_alert_error('Mangler input', 'Kan ikke g&aring; videre f&oslash;r alle p&aring;krevde felter er satt.');
        }
    });
});

function slide_alert_info(title, message){
	$alert_info = $('#slide_alert_info');

	$alert_info.find('.title').empty().html(title);
	$alert_info.find('.message').empty().html(message);
	$alert_info.toggleClass('in');
	setTimeout(function() {
		$alert_info.toggleClass('in');
	}, 3000);
}
function slide_alert_error(title, message){
	$alert_error = $('#slide_alert_error');
	$alert_error.find('.title').empty().html(title);
	$alert_error.find('.message').empty().html(message);
	$alert_error.toggleClass('in');
	setTimeout(function() {
		$alert_error.toggleClass('in');
	}, 3000);
}

/**** WINDOW FUNCTIONS ****/

var confirmOnPageExit = function (e) {
	// If we haven't been passed the event get the window.event
	e = e || window.event;
	var message = 'Ved refresh eller navigering frem/tilbake vil du miste progresjon. Sikker på at du vil dette?';
	// For IE6-8 and Firefox prior to version 4
	if (e) {
		e.returnValue = message;
	}
	// For Chrome, Safari, IE8+ and Opera 12+
	return message;
};

// Turn it on - assign the function that returns the string
if (!TESTING) {
	window.onbeforeunload = confirmOnPageExit;
}