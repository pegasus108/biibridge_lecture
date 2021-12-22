$(function(){
	var validateSet = {
		'name' : {
			'id' : 'name',
			'maxLength' : 40,
			'target' : 'p.error',
			'messege' : 'タイトルは40文字以内でご記入ください。',
			'prev':''
		}
	};

	var myForm = document.forms[0];
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
	// ↓ タイトルの文字数制限解除につき コメントアウト
	// setTimeout(validate,250);

	$('select[name=public_status]').change(function(){
		var myBool = false;
		if($(this).val() != '1') {
			myBool = true;
			$('option[value=0]' , $('select[name=navi_display]')).attr('selected',true);
		}

		$('select[name=navi_display]').attr('disabled',myBool);
	});
	$('select[name=public_status]').trigger('change');

});

