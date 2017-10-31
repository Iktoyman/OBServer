

function item_class_mo(id) {
	document.getElementById(id).src = 'img/' + id + 'Hover.png';
}

function item_class_moh(id) {
	document.getElementById(id).src = 'img/' + id + '.png';
}

function show_class(id) {
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
	var parent_div_id = $('#' + id).parent().parent().parent().parent().parent().parent().attr('id');
	var len = parent_div_id.length - 3;

	$.ajax({
		type: "POST",
		url: 'process.php',
		data: {
			action: 'mark_complete',
			id: item
		},
		dataType: 'json'
	})
	.done(function(data) {
		if (data['result']) {
			alert("Item marked as completed!");
			window.location.reload();
		}
		else {
			alert("Error!");
		}
	});
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