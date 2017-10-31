$(document).ready(function() {
	$('.add-item-btn').on('click', function() {
		$('#add_item_modal').modal('toggle');
		var id = $(this).attr('id').substring($(this).attr('id').indexOf('_') + 1);
		
		$('#training_class_select').val(id);
	});
});