var edit_span_buffer = "";

$(document).ready(function() {
	$('.add-item-btn').on('click', function() {
		$('#add_item_modal').modal('toggle');
		var id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);

		$('#training_class_select').val(id);
		$('#training_class_select').trigger('change');
	});

	$('#training_class_select').on('change', function() {
		if ($(this).val() == 'new') {
			$('.team-specific-option-td').css("display", 'table-cell');
		}
		else
			$('.team-specific-option-td').css("display", 'none');
	});

	$('#training_type').on('change', function() {
		if($(this).val() == 'team')
			$('.team-specific-option-subtd').css('display', 'table-cell');
		else
			$('.team-specific-option-subtd').css('display', 'none');
	});

	$('#add-item_save').on('click', function() {
		if (checkIfComplete() && confirm("Are you sure you wish to add this training item?")) {
			saveItem();
		}
		else if (!checkIfComplete()){
			alert("All the fields are required and must have valid values!");
		}
	});

	$('#logo_name').on('keydown', function(e) {
		if ((e.which < 48 && e.which != 8) || (e.which > 57 && e.which < 65) || (e.which > 90 && e.which < 97) || e.which > 122)
			e.preventDefault();
	});

	$('.edit-btn').on('click', function() {
		var span_id = $(this).parent().attr('id');
		var id = span_id.substring(span_id.indexOf('_') + 1);
		var name = $('#itemname_' + id).html();

		$('#edit-td' + id).css('display', 'table-cell');
		$('#item-td' + id).css('display', 'none');
		$('.edit-span').css('display', 'none');
	});

	$('.delete-btn').on('click', function() {
		var span_id = $(this).parent().attr('id');
		var id = span_id.substring(span_id.indexOf('_') + 1);
		
		if (confirm("Are you sure you really wish to delete this item? \n\rNOTE: This WILL delete the tracked item information for all employees with this training item!")) {
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {
					action: 'delete_training_item',
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

});

function checkIfComplete() {
	if ($('#training_class_select').val() != 'new') {
		console.log($('#training_class_select').val() != '' && $('#training_name').val() != '' && $('#days_completion').val() > 0);
		return ($('#training_class_select').val() != '' && $('#training_name').val() != '' && $('#days_completion').val() > 0);
	}
	else if ($('#training_class_select').val() == 'new')
		return ($('#new_classification_name') != '' && $('#logo_name').val() != '' && $('input[name=class_logo]:checked').val() && $('#training_name').val() != '' && $('#days_completion').val() > 0);
}

function saveItem() {
	var item_class = $('#training_class_select').val();
	var item_name = $('#training_name').val();
	var days_completion = $('#days_completion').val();

	if ($('#training_class_select').val() == 'new') {
		var item_class_name = $('#new_classification_name').val();
		var logo_src = $('input[name=class_logo]:checked').val();
			var logo_src_hover = logo_src.substring(0, logo_src.indexOf('.png')) + 'Hover.png';
		var logo_name = 'img/' + $('#logo_name').val() + '.png';
			var logo_hover = 'img/' + $('#logo_name').val() + 'Hover.png';
		var type = $('#training_type').val();
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
			action: 'add_training_item',
			item_class: item_class,
			item_class_name: item_class_name,
			item_name: item_name,
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
			alert("Error. Item not saved, try again.\n\rAvoid using single quotes(') or double quotes(\") in the training name.");
		}
	});
	
}

function saveEdit(id) {
	var item_id = id.substring(id.indexOf('-btn') + 4);
	var name = $('#edit-name' + item_id).val();
	
	if (confirm("Are you sure you wish to rename this item?")) {
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				action: 'save_edit_training_item',
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
				alert("Error, item not renamed!\n\rAvoid using single quotes(') or double quotes(\") in the training name.");
			}
			$('#item-td' + item_id).css('display', 'table-cell');
			$('#edit-td' + item_id).css('display', 'none');
			$('#itemname_' + item_id).html(name);
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