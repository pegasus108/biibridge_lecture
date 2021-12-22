$(function(){
	var validateSet = {
		'outline' : {
			'id' : 'outline',
			'maxLength' : 62,
			'target' : 'p.error',
			'messege' : '概要（長文）は62文字以内でご記入ください。',
			'prev':''
		},
		'outline_abr' : {
			'id' : 'outline_abr',
			'maxLength' : 20,
			'target' : 'p.error',
			'messege' : '概要（短文）は20文字以内でご記入ください。',
			'prev':''
		}
		,'keyword' : {
			'id' : 'keyword',
			'maxLength' : 130,
			'target' : 'p.error',
			'messege' : 'キーワードは130文字以内でご記入ください。',
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
	
	$('select[name=public_status]').trigger('change');

	$('input[name=recommend_status]').click(function(){
		var $myErea = $('[name=recommend_sentence]');
		if($(this).attr('checked') == true){
			var text = $myErea.val();
			if(text == ""){
				var outline = $('input[type=text][name=outline]').val();
				$myErea.val(outline);
			}
		}
	});
});

function orderOpus(obj ,action){
	var $row = $(obj).parent().parent();
	var $rows = $('tr',$row.parent().parent());

	$rows.each(function(i){
		if($('input[type=hidden]',$(this)).val() == $('input[type=hidden]',$row).val()){
			if(action == 'up' && i > 0){
				$($rows[i]).insertBefore($($rows[i-1]));
				return;
			}else if(action == 'down' && i < ($rows.length - 1) ){
				$($rows[i]).insertAfter($($rows[i+1]));
				return;
			}
		}
	});
}

function otherText(obj){
	var $text = $('input',$(obj).parent());
	if( $(obj).val()=='16' )
		$text.show();
	else
		$text.hide();
}
