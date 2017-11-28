$(document).ready(function() {
	$('#first_name').focus();

	$('#save-user-btn').on('click', function() {
		if (checkAllFields() && confirm("Add this user?")) {
			addUser();
		}
		else if (!checkAllFields()) {
			alert("Error! All fields are required!");
		}
	});
});

function checkAllFields() {
	var is_complete = false;
	$('.add-user-table input').each(function() {
		is_complete = $(this).val() != '';
	});

	var team_selected = $('#team').val() != 0;
	return (is_complete && team_selected);
}

function addUser() {
	var inputs = [];
	$('.add-user-table input').each(function() {
		inputs.push($(this).val());
	});
	var team = $('#team').val();

	inputs[3] = inputs[3].replace(/(..).(..).(....)/, "$3-$1-$2");
	inputs[4] = inputs[4].replace(/(..).(..).(....)/, "$3-$1-$2");

	$.ajax({
		type: "POST",
		url: "process.php",
		data: {
			action: 'add_user',
			inputs: inputs,
			team: team
		},
		dataType: 'json'
	})
	.done(function(data) {
		if (data == 1) {
			alert("User successfully added!");
		}
		else if (data == 2) {
			alert("User already exists!");
		}
		else if (data == 0) {
			alert("Error, unable to add user! Please double check your input.");
		}
	});
}
