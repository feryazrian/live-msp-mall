$(document).ready(function() {
    // Hide floating button live streaming 
    var pathName = window.location.pathname;

    if (pathName === "/streaming/live") {
        $('#streamBtn').hide();
    }

    // Draggable button live streaming
    $('#streamBtn .draggable').draggable({ cancel: false});
});