$(document).ready(function () {
	// on click sigh up, hide login and show registration form
	$("#signup").click(function () {
		$("#first").slideUp("slow", function() {
			$("#second").slideDown("slow");
		})
	});

	// on click sigh in, hide registration and show login form
	$("#signin").click(function () {
		$("#second").slideUp("slow", function() {
			$("#first").slideDown("slow");
		})
	});

});