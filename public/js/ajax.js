$(function(){

	$('.ajax_abs').on('change', function(){
		$.ajax({
			url: '/ajax/ajax_absencee.php',
			type: 'POST',
			datatype: 'html',
			data: {
				date : $('select[name="date"]').val()
			}
		}).done(function(data){
			$('.ajax_a').html(data);
		});
	});

	$('.ajax_comment').on('click', function(){
		event.preventDefault();
		if($('textarea[name="comment"]').val().length){
			$.ajax({
				url: '/ajax/ajax_comments.php',
				type: 'POST',
				datatype: 'html',
				data: {
					schedule_id : $('input[name="schedule_id"]').val(),
					user_id : $('input[name="user_id"]').val(),
					comment : $('textarea[name="comment"]').val()
				}
			}).done(function(data){
				$('.ajax_a').html(data);
				$('textarea').val("");
			});
		}
	});

	$('.ajax_dlt').on('click', function(){
		event.preventDefault();
		let val = $(this).prev().val();
		$.ajax({
			url: '/ajax/ajax_delete.php',
			type: 'POST',
			data: {
				id : val
			}
		}).done(function(){

		});
		$(this).unwrap();
		$(this).prev().prev().remove();
		$(this).prev().remove();
		$(this).remove();

	});
});
