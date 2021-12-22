$(function(){
	var validateSet = {
		'content_1' : {
			'id' : 'content_1',
			'maxLength' : 105,
			'target' : 'p.error',
			'messege' : '105文字以内でご記入ください。',
			'prev':''
		},
		'win_info_1' : {
			'id' : 'win_info_1',
			'maxLength' : 30,
			'target' : 'p.error',
			'messege' : '30文字以内でご記入ください。',
			'prev':''
		}
		,'by_obi_1' : {
			'id' : 'by_obi_1',
			'maxLength' : 100,
			'target' : 'p.error',
			'messege' : '100文字以内でご記入ください。',
			'prev':''
		}
		,'representative_comment_1' : {
			'id' : 'representative_comment_1',
			'maxLength' : 200,
			'target' : 'p.error',
			'messege' : '200文字以内でご記入ください。',
			'prev':''
		}
	};
	
	var myForm = document.forms[0];
	/*
	for(var i in validateSet ){
		$(myForm[i]).change(function(){
		});
	}*/
	function validate(){
		for(var i in validateSet ){
			var now = myForm[i].value;
			var prev = validateSet[i].prev;
			if( prev != now) {
				var val = now;
				while( val.match("\n") != null ) val = val.replace("\n",'');
				
				if (val.length > validateSet[i].maxLength){
					var $item = $(myForm[i]).siblings(validateSet[i].target);
					$item.text(validateSet[i].messege);
				}else{
					var $item = $(myForm[i]).siblings(validateSet[i].target);
					$item.text('');
				}
			}
			validateSet[i].prev = now;
		}
		setTimeout(validate,500);
	}
	setTimeout(validate,250);
	
	$('select[@name=reader_page_status_1]').change(function(){
		var myBool = false;
		if($(this).val() != '1') myBool = true;
		
		$('input[@type=text][@name=reader_page_1]').attr('disabled',myBool);
	});
	$('select[@name=reader_page_status_1]').trigger('change');
});

