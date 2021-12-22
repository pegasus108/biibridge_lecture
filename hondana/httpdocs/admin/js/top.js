$(function() {
	$('#contents #dashboard').each(function(s_count) {
		$('.numbers',this).children('span').each(function(a_count) {
			$(this).mouseover(function(e) {
				$('#helpTextDisplay').css({'display':'block','top':e.pageY + 18,'left':e.pageX - 10});
				$('#helpTextDisplay .contents').html($('.dashboardHelpText p:eq(' + a_count + ')').html());
			});
			$(this).mouseout(function() {
				$('#helpTextDisplay').css('display', 'none');
			});
		});
	});
	$('#contents .section').each(function(s_count) {
		$(this).children('ul').children('li').children('a').each(function(a_count) {
			$(this).mouseover(function(e) {
				$('#helpTextDisplay').css({'display':'block','top':e.pageY + 18,'left':e.pageX - 10});
				$('#helpTextDisplay .contents').html($('.helpText:eq(' + s_count + ') p:eq(' + a_count + ')').html());
			});
			$(this).mouseout(function() {
				$('#helpTextDisplay').css('display', 'none');
			});
		});
	});

/*	$.datepicker.setDefaults($.datepicker.regional['ja']);
	$.datepicker.formatDate("yy-mm-dd" );
	$(".datepicker").datepicker();

	$(".moreOpenButton a").live("click",function(){
		var dd = $("dd").has(this);
		dd.css({width:dd.width()});
		dd.find("ol").css({width:dd.find("ol").width()});
		
		dd.find(".moredata").slideDown(300,function(){
			dd.css({width:"auto"});
			dd.find("ol").css({width:"auto"});
		});
//		$("dd").has(this).find(".moredata").show(1000);
		dd.find(".moreCloseButton").show(200);
		$(this).parent().hide(200);
	});

	$(".moreCloseButton a").live("click",function(){
		var dd = $("dd").has(this);
		dd.css({width:dd.width()});
		dd.find("ol").css({width:dd.find("ol").width()});

		dd.find(".moredata").slideUp(300,function(){
			dd.css({width:"auto"});
			dd.find("ol").css({width:"auto"});
		});
//		$("dd").has(this).find(".moredata").hide(1000);
		dd.find(".moreOpenButton").show(200);
		$(this).parent().hide(200);
		
	});
*/
});