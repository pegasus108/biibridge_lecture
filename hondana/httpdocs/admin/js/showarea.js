$(function() {
	$('.switch').each(function(s_count) {
		$(this).click(function(){	
			var parentArea = $(this).parent().parent().parent();
			$('.area',parentArea).eq(s_count).slideToggle('slow');			   
		});
	});
});