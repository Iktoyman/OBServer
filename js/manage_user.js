var filtered_users = [];

$(document).ready(function() {
	$('#team_select').on('change', function() {
		var selected_team = $(this).val();

		if (selected_team != '') {
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {
					action: 'change_team',
					team: selected_team
				},
				dataType: 'json'
			})
			.done(function(data) {
				filtered_users = data;
				$('#manage-user-table-tbody').html("");

				data.forEach(function(item) {
					document.getElementById('manage-user-table-tbody').innerHTML += "<tr>"
					+ "<td width=5% style='text-align: center'><input type='checkbox' class='user-checkbox' id='user-row_" + item['user_id'] + "' ></td>"
	        + "<td width=47.5%>" + item['name'] + "</td>"
	        + "<td width=40%>" + item['team_name'] + "</td>"
	        + "<td width=7.5% style='text-align: center'>"
	            + "<a id='edit-btn_" + item['user_id'] + "' onclick='editUser(this.id)'><span class='edit-btn glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;"
	            + "<a id='delete-btn_" + item['user_id'] + "' onclick='deleteUser(this.id)'><span class='delete-btn glyphicon glyphicon-trash'></span></a>"
	        + "</td>"
	        + "</tr>";
				});
			});
		}
	});

	$('#search-user').on('keyup', function() {
		var search_term = $(this).val();
		var filter = $('#team_select').val();

		if (filter != '' && filter > 0)
			var is_filtered = 1;
		else
			var is_filtered = 0;

		setTimeout(function() {
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {
					action: 'search_user',
					is_filtered: is_filtered,
					filter: filter,
					search_term: search_term
				},
				dataType: 'json'
			})
			.done(function(data) {
				filtered_users = data;
				$('#manage-user-table-tbody').html("");

				if (data.length) {
					data.forEach(function(item) {
						document.getElementById('manage-user-table-tbody').innerHTML += "<tr>"
						+ "<td width=5% style='text-align: center'><input type='checkbox' class='user-checkbox' id='user-row_" + item['user_id'] + "' ></td>"
		        + "<td width=47.5%>" + item['name'] + "</td>"
		        + "<td width=40%>" + item['team_name'] + "</td>"
		        + "<td width=7.5% style='text-align: center'>"
		            + "<a id='edit-btn_" + item['user_id'] + "' onclick='editUser(this.id)'><span class='edit-btn glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;"
		            + "<a id='delete-btn_" + item['user_id'] + "' onclick='deleteUser(this.id)'><span class='delete-btn glyphicon glyphicon-trash'></span></a>"
		        + "</td>"
		        + "</tr>";
					});
				}
				else {
					document.getElementById('manage-user-table-tbody').innerHTML += "<tr>"
					+ "<td colspan=4> No records found </td>"
					+ "</td>"
					+ "</tr>";
				}
			});
		}, 500);
	});
});

function editUser(id) {
	var selected_users = [];
	if ($('.user-checkbox').is(':checked')) {
		$('.user-checkbox:checked').each(function() {
			var selected_id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);
			selected_users.push(selected_id);
		});
	}
	else
		selected_users.push(id.substring(id.indexOf('_') + 1));

	console.log(selected_users);
}

function deleteUser(id) {
	var selected_users = [];
	if ($('.user-checkbox').is(':checked')) {
		$('.user-checkbox:checked').each(function() {
			var selected_id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);
			selected_users.push(selected_id);
		});
	}
	else
		selected_users.push(id.substring(id.indexOf('_') + 1));

	console.log(selected_users);
}