var DEFAULT_OPTIONS = {
	backdrop: 'static',
	keyboard: false
}

$(document).ready(function ($) {
	$("#searchModal").on("shown.bs.modal", function () {
		$("#bc-product-search").focus();
	});
});

window.onscroll = function () {
	stickyHeader();
};

function stickyHeader() {
	var header = document.getElementById("header");
	var sticky = header.offsetTop;
	if (window.pageYOffset > sticky) {
		header.classList.add("sticky");
	} else {
		header.classList.remove("sticky");
	}
}

$(".signup-btn").click(function() {
	$('#signinModal').modal('hide');
	$('#signupModal').modal(DEFAULT_OPTIONS);
})
$(".forget-btn").click(function() {
	$('#signinModal').modal('hide');
	$('#forgetModal').modal(DEFAULT_OPTIONS);
})

$(".signin-btn").click(function() {
	$('#forgetModal').modal('hide');
	$('#signinModal').modal(DEFAULT_OPTIONS);
})