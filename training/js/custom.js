$(document).ready(function() {
	$('.edit-datepicker').on('keydown', function(e) {
		if (e.keyCode === 13) {
			console.log("ASD");
			$('#' + $(this).parent().attr('id') + ' .edit-save-date-btn').trigger('click');
		}
	});

	$('.edit-save-date-btn').on('click', function() {
		var div_id = $(this).parent().attr('id');
		var id = div_id.substring(div_id.indexOf("_") + 1);
		var comp_date = $('#edit-datepicker_' + id).val();

		if (confirm("Are you sure you wish to correct the completion date for this item?")) {
			$.ajax({
				type: "POST",
				url: "process.php",
				data: {
					action: 'save_edit_date',
					comp_date: comp_date,
					id: id
				},
				dataType: 'json'				
			})
			.done(function(data) {
				if (data) {
					alert("Date successfully updated!");
					window.location.reload();
				}  
				else {
					alert("Error! Date not updated, make sure your date is correct and formatted properly.");
					$('#edit-datepicker_' + id).popover('show');
					setTimeout(function() {
						$('#edit-datepicker_' + id).popover('hide');
					}, 1500);
				}
			});
		}
	});

	$('.edit-cancel-date-btn').on('click', function() {
		$(this).parent().css('display', 'none');
		$('.edit-date-btn').css('visibility', 'visible');
	});

	$('#team_select').on('change', function() {
		if ($(this).val())
			window.location.href = 'manager_view.php?team=' + $(this).val();
	});
});	

function item_class_mo(id) {
	document.getElementById(id).src = 'img/' + id + 'Hover.png';
}

function item_class_moh(id) {
	document.getElementById(id).src = 'img/' + id + '.png';
}

function show_class(id) {
	current_div = id.substring(0, id.length - 3);
	var item_class_num = item_classes.length;
	for (var a = 0; a < item_class_num; a++) {
		if (item_classes[a]['element_id'] != id)
			document.getElementById(item_classes[a]['element_id'] + 'Div').style.display = 'none';

		if (item_classes[a]['element_id'] + 'Div' == id) {
			var name = item_classes[a]['name'];
			var completion = item_classes[a]['completion'];
		}
	}
	document.getElementById(id).style.display = 'block';
	document.getElementById('progressHeader').innerHTML = name;
	$('#percentageRing').removeClass();
	$('#percentageRing').addClass('c100');
	$('#percentageRing').addClass('p' + completion);
	$('#percentageRing').addClass('big');
	document.getElementById('percentageRing_val').innerHTML = completion + '%';
}

function markAsComplete(id, item) {
	var item_ids = [];
	if ($('.progress-selection-checkbox').is(':checked')) {
		$('.progress-selection-checkbox:checked').each(function() {
			var item_id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);
			item_ids.push(item_id);
		});
	}
	else {
		var item_id = id.substring(id.indexOf('w') + 1);
		item_ids.push(item_id);
	}
	console.log(item_ids);

	if (confirm("Are you sure you wish to mark " + item_ids.length + " item(s) as Completed?\n\rDouble check your selection if selecting multiple items.")) {
		$.ajax({
			type: "POST",
			url: 'process.php',
			data: {
				action: 'mark_complete',
				item_ids: item_ids
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data) {
				alert("Item(s) marked as completed!");
				if (manager_view)
					window.location.href = 'progress.php?id=' + current_div + '&user=' + current_user;
				else
					window.location.href = 'progress.php?id=' + current_div;
			}
			else {
				alert("Error!");
			}
		});
	}
}

function markAsNotApplicable(id) {
	var item_ids = [];
	if ($('.progress-selection-checkbox').is(':checked')) {
		$('.progress-selection-checkbox:checked').each(function() {
			var item_id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);
			item_ids.push(item_id);
		});
	}
	else {
		var item_id = id.substring(id.indexOf('w') + 1);
		item_ids.push(item_id);
	}
	console.log(item_ids);

	if (confirm("Are you sure you wish to mark " + item_ids.length + " item(s) as Not Applicable?\n\rDouble-check your selection if selecting multiple items.")) {
		$.ajax({
			type: "POST",
			url: "process.php",
			data: {
				action: 'mark_notapplicable',
				item_ids: item_ids
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data) {
				alert("Item(s) marked as not applicable!");
				window.location.reload();
			}
			else {
				alert("Error!");
			}
		});
	}
}

function editCompletionDate(id) {
	var item_id = id.substring(id.indexOf('_') + 1);

	$('.edit-date-btn').css('visibility', 'hidden');
	$('#edit-datepicker-div_' + item_id).css('display', 'block');

	$('#edit-datepicker_' + item_id).popover({
		content: 'Date must be in YYYY-MM-DD format.',
		placement: 'top',
		trigger: 'manual'
	});
	$('#edit-datepicker_' + item_id).popover('show');
	setTimeout(function() {
		$('#edit-datepicker_' + item_id).popover('hide');
	}, 1500);
}


function showIRM(){
	var x = document.getElementById('IRM');
	if(x.style.display == 'none'){
		document.getElementById('IRM').style.display = 'block';
	} else{
		document.getElementById('IRM').style.display = 'none';
	}
}

function showPM(){
	var x = document.getElementById('PM');
	if(x.style.display == 'none'){
		document.getElementById('PM').style.display = 'block';
	} else{
		document.getElementById('PM').style.display = 'none';
	}
}

function showEM(){
	var x = document.getElementById('EM');
	if(x.style.display == 'none'){
		document.getElementById('EM').style.display = 'block';
	} else{
		document.getElementById('EM').style.display = 'none';
	}
}

function showADKB(){
	var x = document.getElementById('ADKB');
	if(x.style.display == 'none'){
		document.getElementById('ADKB').style.display = 'block';
	} else{
		document.getElementById('ADKB').style.display = 'none';
	}
}

function showCM(){
	var x = document.getElementById('CM');
	if(x.style.display == 'none'){
		document.getElementById('CM').style.display = 'block';
	} else{
		document.getElementById('CM').style.display = 'none';
	}
}

function showST(){
	var x = document.getElementById('ST');
	if(x.style.display == 'none'){
		document.getElementById('ST').style.display = 'block';
	} else{
		document.getElementById('ST').style.display = 'none';
	}
}

function showKPM(){
	var x = document.getElementById('KPM');
	if(x.style.display == 'none'){
		document.getElementById('KPM').style.display = 'block';
	} else{
		document.getElementById('KPM').style.display = 'none';
	}
}

function showBJO(){
	var x = document.getElementById('BJO');
	if(x.style.display == 'none'){
		document.getElementById('BJO').style.display = 'block';
	} else{
		document.getElementById('BJO').style.display = 'none';
	}
}

function showCRR(){
	var x = document.getElementById('CRR');
	if(x.style.display == 'none'){
		document.getElementById('CRR').style.display = 'block';
	} else{
		document.getElementById('CRR').style.display = 'none';
	}
}

function showTSP(){
	var x = document.getElementById('TSP');
	if(x.style.display == 'none'){
		document.getElementById('TSP').style.display = 'block';
	} else{
		document.getElementById('TSP').style.display = 'none';
	}
}