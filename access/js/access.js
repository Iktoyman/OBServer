var edit_span_buffer = "";
var url_buffer = "";
var check_kms = false;

$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();

	$('.add-item-btn').on('click', function() {
		$('#add_item_modal').modal('toggle');
		var id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);

		$('#access_class_select').val(id);
		$('#access_class_select').trigger('change');
	});

	$('#edit-all-items').on('click', function() {
		if ($('.class-edit-btn').css('display') == 'none') {
			$('.class-edit-btn').css('display', 'block');
			$('.edit-span').css('display', 'block');
		}
		else {
			$('.class-edit-btn').css('display', 'none');
			$('.edit-span').css('display', 'none');
		}
	});

	$('#access_class_select').on('change', function() {
		if ($(this).val() == 'new') {
			$('.team-specific-option-td').css("display", 'table-cell');
		}
		else
			$('.team-specific-option-td').css("display", 'none');
	});

	$('#access_type').on('change', function() {
		if($(this).val() == 'team')
			$('.team-specific-option-subtd').css('display', 'table-cell');
		else
			$('.team-specific-option-subtd').css('display', 'none');
	});

	/*
	$('#access_link').on('paste keydown change', function() {
		setTimeout(function() {
			checkIfKMSExists();
		}, 10);
	});
	*/

	$('#add-item_save').on('click', function() {
		// var item_url = $('#access_link').val();

		//if (checkIfComplete() && check_kms && confirm("Are you sure you wish to add this access item?")) {
		if (checkIfComplete() && confirm("Are you sure you wish to add this access item?")) {
			saveItem();
		}
		/*
		else if (item_url != '' && !check_kms) {
			alert("KMS link invalid! KMS entry does not exist!");
		}
		*/
		else if (!checkIfComplete()) {
			alert("All the fields are required and must have valid values!");
		}
	});

	$('#logo_name').on('keydown', function(e) {
		if ((e.which < 48 && e.which != 8) || (e.which > 57 && e.which < 65) || (e.which > 90 && e.which < 97) || e.which > 122)
			e.preventDefault();
	});

	$('.class-edit-btn').on('click', function() {
		var link_id = $(this).attr('id');
		var id = link_id.substring(link_id.indexOf('_') + 1);

		$('.edit-span').css('display', 'none');
		$('.class-edit-btn').css('visibility', 'hidden');
		$('#class-name_' + id).css('display', 'none');
		$('#class-name-input-div_' + id).css('display', 'block');
		$('#class-name-input_' + id).focus();
		$('#class-name-input_' + id).val($('#class-name_' + id).html());
	});

	$('.edit-btn').on('click', function() {
		var span_id = $(this).parent().attr('id');
		var id = span_id.substring(span_id.indexOf('_') + 1);
		var name = $('#itemname_' + id).html();
		//var url_buffer = $('#edit-link' + id).val();

		$('#edit-td' + id).css('display', 'table-cell');
		$('#item-td' + id).css('display', 'none');
		$('.edit-span').css('display', 'none');
	});

	$('.delete-btn').on('click', function() {
		var span_id = $(this).parent().attr('id');
		var id = span_id.substring(span_id.indexOf('_') + 1);
		
		if (confirm("Are you sure you really wish to delete this item? \n\rNOTE: This WILL delete the tracked item information for all employees with this access item!")) {
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {
					action: 'delete_access_item',
					id: id
				},
				dataType: 'json'
			})
			.done(function(data) {
				if (data) {
					alert("Item deleted!");
					$('#' + span_id).parent().remove();	
				}
				else {
					alert("Error, item not deleted.");
				}
			});
		}
	});

	$('.class-name-input').on('keydown', function(e) {
		if (e.keyCode === 13) {
			saveClassName($(this).attr('id'));
		}
	});

	$('.edit-name').on('keydown', function(e) {
		var id = $(this).attr('id');
		var item_id = id.substring(id.indexOf('-name') + 5);

		if (e.keyCode === 13) {
			saveEdit('save-edit-btn' + item_id);
		}
	});
});

function checkIfComplete() {
	if ($('#access_class_select').val() != 'new') {
		console.log($('#access_class_select').val() != '' && $('#access_name').val() != '' && $('#days_completion').val() > 0);
		return ($('#access_class_select').val() != '' && $('#access_name').val() != '' && $('#days_completion').val() > 0);
	}
	else if ($('#access_class_select').val() == 'new')
		return ($('#new_classification_name') != '' && $('#logo_name').val() != '' && $('input[name=class_logo]:checked').val() && $('#access_name').val() != '' && $('#days_completion').val() > 0);
}

/*
function checkIfKMSExists() {
	var url = $('#access_link').val();
	if (url.indexOf("'") >= 0)
		var id = url.substring(url.indexOf("'") + 1);
	else if (url.indexOf("%27") >= 0)
		var id = url.substring(url.indexOf("%27") + 3);

	id = id.substring(0, 16);
	$.ajax({
		type: "POST",
		url: "process.php",
		data: {
			action: 'check_kms',
			id: id
		},
		dataType: 'json'
	})
	.done(function(data) {
		if (data)
			check_kms = true;
		else 
			check_kms = false;
	});
}
*/

function saveItem() {
	var item_class = $('#access_class_select').val();
	var item_name = $('#access_name').val();
	//var item_url = $('#access_link').val();
	var days_completion = $('#days_completion').val();

	//if ((item_url != '' && check_kms) || item_url == '') {
		if ($('#access_class_select').val() == 'new') {
			var item_class_name = $('#new_classification_name').val();
			var logo_src = $('input[name=class_logo]:checked').val();
				var logo_src_hover = logo_src.substring(0, logo_src.indexOf('.png')) + 'Hover.png';
			var logo_name = 'img/' + $('#logo_name').val() + '.png';
				var logo_hover = 'img/' + $('#logo_name').val() + 'Hover.png';
			var type = $('#access_type').val();
			var acct = $('#team_accounts').val();  
		}
		else {
			var item_class_name = '';
			var logo_src = '';
			var logo_src_hover = '';
			var logo_name = '';
			var logo_hover = '';
			var type = 0;
			var acct = 0;
		}

		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				action: 'add_access_item',
				item_class: item_class,
				item_class_name: item_class_name,
				item_name: item_name,
				//item_url: item_url,
				logo_src: logo_src,
				logo_src_hover: logo_src_hover,
				logo_name: logo_name,
				logo_hover: logo_hover,
				days_completion: days_completion,
				type: type,
				acct: acct
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data) {
				alert("Item added successfully!");
				window.location.reload();
			}
			else {
				alert("Error. Item not saved, try again.\n\rAvoid using single quotes(') or double quotes(\") in the access name.");
			}
		});
	//}
}

function saveEdit(id) {
	var item_id = id.substring(id.indexOf('-btn') + 4);
	var name = $('#edit-name' + item_id).val();
	//var url = $('#edit-link' + item_id).val();
	
	if (confirm("Are you sure you wish to update this item?")) {
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				action: 'save_edit_access_item',
				id: item_id,
				name: name
				//url: url
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data) {
				alert("Item updated!");
				//if (url == '')
					$('#itemname_' + item_id).html(name);
				//else	
					//$('#itemname_' + item_id).html("<a href='" + url + "' target='_blank'>" + name + "</a>");
			}
			else {
				alert("Error, item not updated!\n\rAvoid using single quotes(') or double quotes(\") in the access name.");
				//if (url_buffer == '')
					$('#itemname_' + item_id).html(name);
				//else
					//$('#itemname_' + item_id).html("<a href='" + url_buffer + "' target='_blank'>" + name + "</a>");
			}
			$('#item-td' + item_id).css('display', 'table-cell');
			$('#edit-td' + item_id).css('display', 'none');
			$('.edit-span').css('display', 'block');
		});
	}
	
}

function cancelEdit(id) {
	var item_id = id.substring(id.indexOf('-btn') + 4);

	$('#item-td' + item_id).css('display', 'table-cell');
	$('#edit-td' + item_id).css('display', 'none');
	$('.edit-span').css('display', 'block');
}

function saveClassName(id) {
	var item_id = id.substring(id.indexOf('_') + 1);
	var name = $('#class-name-input_' + item_id).val();

	if (confirm("Are you sure you wish to rename this classification?")) {
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				action: 'save_edit_class_name',
				id: item_id,
				name: name
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data) {
				alert("Item renamed!");
			}
			else {
				alert("Error, item not renamed!\n\rAvoid using single quotes(') or double quotes(\") in the access name.");
			}
			window.location.reload();
			/*
			$('#class-name_' + item_id).html(name);
			$('#class-name-input_' + item_id).val('');
			$('#class-name-input-div_' + item_id).css('display', 'none');
			$('#class-name_' + item_id).css('display', 'block');
			$('.class-edit-btn').css('visibility', 'visible');
			$('.edit-span').css('display', 'block');
			*/
		}); 
	}
}

function cancelEditClassName(id) {
	var item_id = id.substring(id.indexOf('_') + 1);

	$('#class-name-input-div_' + item_id).css('display', 'none');
	$('#class-name_' + item_id).css('display', 'block');
	$('.class-edit-btn').css('visibility', 'visible');
	$('.edit-span').css('display', 'block');
}