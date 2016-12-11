// Javascript for BookBeat
// December 2016, Level 2 Team, Harvard CSCI-71

// UI Tabs
jQuery(document).ready(function($) {
$('#tabs').tabs();

//hover states on the static widgets
$('#dialog_link, ul#icons li').hover(
function() { $(this).addClass('ui-state-hover'); },
function() { $(this).removeClass('ui-state-hover'); }
);

// Tablesorters
$("#booklist").tablesorter();
$("#booklist2").tablesorter();
$("#booklist3").tablesorter();
});

