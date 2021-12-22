$(function(){
/*
	$myNote = $("p#author_note_1_" + $('select[@name=newauthor_1]').val());
	$("p#author_note_1").html($myNote.text());
	$myNote = $("p#author_note_2_" + $('select[@name=newauthor_2]').val());
	$("p#author_note_2").html($myNote.text());
*/

//*****全反映
	$('input[@type=button][@name=reflect]').click(function(){
		var $table = $(this).parent().parent().parent().parent();

		var $buttons = $('input[@type=button]',$table);
		for(var i = 0 ; i < $buttons.size() ; i++){
			var $button = $($buttons[i]);
			if($button.attr("name") != "reflect"){
				$button.trigger("click");
			}
		}
	});

//*****汎用
	$('input[@type=button][@name=p2t]').click(function(){
		$p = $(this).parent().parent('tr');
		var textdata = $('p.newItem',$p).text();
		$("[@type=text],textarea",$p).val(textdata);
	});

	$('input[@type=button][@name=p2te]').click(function(){
		$p = $(this).parent().parent('tr');
		$("[@cols]",$p).val($('p.newItem',$p).text());
	});

	$('input[@type=button][@name=p2date]').click(function(){
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];
		$d = $("[@type=text]",$p)[2];

		$y.value=vals.substring(0,4);
		$m.value=vals.substring(4,6);
		$d.value=vals.substring(6,8);
	});

	$('input[@type=button][@name=p2date_s]').click(function(){
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];

		$y.value=vals.substring(0,4);
		$m.value=vals.substring(4,6);
	});

	$('input[@type=button][@name=p3date]').click(function(){
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];
		$d = $("select",$p);

		$y.value=vals.substring(0,4);
		$m.value=vals.substring(4,6);
		$('option',$d).each(function(){
			if($(this).val() == vals.substring(6,8)){
				$(this).attr('selected',true);
			}
		});
		$d.trigger("change");
	});


	$('input[@type=button][@name=p2mcode]').click(function(){
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];

		$y.value=vals.substring(0,5);
		$m.value=vals.substring(6,8);
	});

	$('input[@type=button][@name=p2isbn]').click(function(){
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();

		if(vals.length == 10)
		$("[@type=text]",$p).val($('p.newItem',$p).text());
	});

	$('input[@type=button][@name=p2select]').click(function(){
		$p = $(this).parent().parent('tr');
		val = $('p.newItem',$p).text();
		val = $.trim(val);
		$y = $("select",$p);

		$('option',$y).each(function(){
			if($(this).text() == val){
				$(this).attr('selected',true);
			}
		});

		$y.trigger("change");
	});

	$('input[@type=button][@name=s2t]').click(function(){
		$p = $(this).parent().parent('tr');
		$y = $("select",$p);
		val = $y.val();
		val = $.trim(val);

		$("[@type=text],textarea",$p).val(val);
	});

//*****author
	$('input[@type=button][@name=s2author]').click(function(){
		$p = $(this).parent().parent('tr');
		$y = $("select",$p);
		$m = $("option[@selected]",$y);

		var val = $m.text();
		val = $.trim(val);

		var no = $y.attr('name').charAt( $y.attr('name').length - 1 );
		if(no != ""){
			myNoteID = "p#author_kana_" + no + "_" + $y.val();
			targetID = "input[@name=author_" + no+"]";
			targetKanaID = "input[@name=author_kana_" + no + "]";
			$myNote = $(myNoteID);
			$target = $(targetID);
			$targetKana = $(targetKanaID);
			$target.val(val);
			$targetKana.val($myNote.text());
			if($myNote.text() == null){
				$targetKana.val("");
			}
		}
	});
	$('select').change(function(){
		var no = $(this).attr('name').charAt( $(this).attr('name').length - 1 );
		if(no != ""){
			myNoteID = "p#author_note_" + no + "_" + $(this).val();
			targetID = "p#author_note_" + no;
			$myNote = $(myNoteID);
			$target = $(targetID);
			$target.html($myNote.text());
			if($myNote.text() == null){
				$target.html("");
			}
		}
	});

//*****series
	$('input[@type=button][@name=s2series]').click(function(){
		$p = $(this).parent().parent('tr');
		$y = $("select",$p);
		$m = $("option[@selected]",$y);

		var no = $y.attr('name').charAt( $y.attr('name').length - 1 );
		if(no != ""){
			myID = "p#series_" + no + "_" + $y.val();
			myNoteID = "p#series_kana_" + no + "_" + $y.val();
			targetID = "input[@name=series_" + no+"]";
			targetKanaID = "input[@name=series_kana_" + no + "]";
			$my = $(myID);
			$myNote = $(myNoteID);
			$target = $(targetID);
			$targetKana = $(targetKanaID);
			$target.val($my.text());
			$targetKana.val($myNote.text());
			if($myNote.text() == null){
				$targetKana.val("");
			}
		}
	});

//*****jbpa data_type ごとの項目変更
	var jbpaFieldList = {
		"": [
			0,1
		],
		"1": [
			0,1,2,3,4,5, 7,8, 10,11, 13,14,15,16,17,18,19,20,21,22,23,24,25,26,27, 29,30, 32, 34,35, 40
		],
		"2": [
			0,1,2, 37,38
		],
		"3": [
			0,1,2, 27,28
		],
		"4": [
			0,1,2,3,4,5, 7,8, 10,11, 13,14,15,16,17,18,19,20,21,22,23,24,25,26,27, 29,30, 32, 34,35, 40
		],
		"5": [
//			0,1,2,3,4,5, 6,7,8, 9,10,11,12, 13,14,15,16,17,18,19,20,21,22,23,24,25,26,27, 29,30,31, 33, 34,35,36, 39,40
			0,1,2,3,4,5, 6,7,8, 9,10,11,12, 13,14,15,16,17,18,19,20,21,22,23,24,25,26,27, 31, 33, 34,35,36, 39,40
		]
	};
	$jbpaDataTypeButton = $('select#jbpa_data_type_1');
	$('select#jbpa_data_type_1').change(function(){
		var $p = $(this).parent().parent('tr').parent().parent('table');
		var $rowList = $('tr', $p);

		for(var i = 0 ; i < $rowList.size() ; i++){

			var view = false;
			for(var j = 0 ; j < jbpaFieldList[$(this).val()].length ;j++){

				var $row = $($rowList[i]);
				view = (i == jbpaFieldList[$(this).val()][j]);
				if( view ){
					$row.show();
					break;
				}
			}

			if(!view){
				$row.hide();
			}

		}
	});
	if($jbpaDataTypeButton.size() != 0){
		$jbpaDataTypeButton.trigger('change');
	}

//版型（書協）その他
	$('select[@name=book_size_2]').change(function(){
		var flag = true;
		if($(this).val()=="x"){
			flag = false;
		}
		$('input[@type=text][@name=book_size_2_other_l]').attr("disabled",flag);
		$('input[@type=text][@name=book_size_2_other_r]').attr("disabled",flag);
	});
	$('select[@name=book_size_2]').trigger("change");

	$('input[@type=button][@name=p2book_size_2]').click(function(){
		$p = $(this).parent().parent('tr');
		var val = $('p.newItem',$p).text();
		val = $.trim(val);
		$y = $("select",$p);
		if(val.indexOf('x') >= 0){
			val = val.replace('cm','');
			$('input[@name=book_size_2_other_l]',$p).val(val.split('x')[0]);
			$('input[@name=book_size_2_other_r]',$p).val(val.split('x')[1]);
			val = "x";
		} else if(val == 'B7') {
			$('input[@name=book_size_2_other_l]',$p).val("13");
			$('input[@name=book_size_2_other_r]',$p).val("10");
			val = "その他";
		}
		$('option',$y).each(function(){
			if($(this).text() == val){
				$(this).attr('selected',true);
			}
		});
		$('select[@name=book_size_2]').trigger("change");
	});

	$('input[@type=button][@name=list2multiple]').click(function(){
		var $p = $(this).parent().parent('tr');
		var $list = $('ul.newItem',$p);
		var $y = $("select",$p);
		$('option',$y).each(function(){
			$(this).attr('selected',false);
		});


		$('li',$list).each(function(){
    		var val = $(this).text();
			val = $.trim(val);

			$('option',$y).each(function(){
				var itemName = $(this).text();
				itemName = $.trim(itemName);
				if(val == itemName){
					$(this).attr('selected',true);
				}
			});
		});

	});

	$('input[@type=button][@name=list2check]').click(function(){
		var $p = $(this).parent().parent('tr');
		var $list = $('ul.newItem',$p);
		$('input[@type=checkbox]',$p).each(function(){
			$(this).attr('checked',false);
		});

		$('li',$list).each(function(){
    		var val = $(this).text();
			val = $.trim(val);

			$('input[@type=checkbox]',$p).each(function(){
				var itemName = $('label[@for='+$(this).attr('id')+']').text();
				itemName = $.trim(itemName);
				if(val == itemName){
					$(this).attr('checked',true);
				}
			});
		});

	});
	$('input[@type=button][@name=list3check]').click(function(){
		var $p = $(this).parent().parent('tr');
		var $list = $('ul.newItem',$p);
		$('input[@type=radio]',$p).each(function(){
			$(this).attr('checked',false);
		});

		$('li',$list).each(function(){
    		var val = $(this).text();
			val = $.trim(val);

			$('input[@type=radio]',$p).each(function(){
				var itemName = $('label[@for='+$(this).attr('id')+']').text();
				itemName = $.trim(itemName);
				if(val == itemName){
					$(this).attr('checked',true);
				}
			});
		});

	});


//入力文字数チェック関連
	var validateSet = {
		'preliminary_3' : {
			'maxLength' : 40,
			'target' : 'p.error',
			'messege' : '40文字を超えて入力されています。',
			'prev':''
		},
		'preliminary_6' : {
			'maxLength' : 70,
			'target' : 'p.error',
			'messege' : '70文字を超えて入力されています。',
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

				var $item = $(validateSet[i].target,$(myForm[i]).parent());
				if (val.length > validateSet[i].maxLength){
					$item.text(validateSet[i].messege);
				}else{
					$item.text('');
				}
			}
			validateSet[i].prev = now;
		}
		setTimeout(validate,500);
	}
	setTimeout(validate,250);


});
