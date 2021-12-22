$(function()
{
/*
	$myNote = $("p#author_note_1_" + $('select[@name=newauthor_1]').val());
	$("p#author_note_1").html($myNote.text());
	$myNote = $("p#author_note_2_" + $('select[@name=newauthor_2]').val());
	$("p#author_note_2").html($myNote.text());
*/

//*****全反映
	$('input[@type=button][@name=reflect]').click(function()
	{
		var $table = $(this).parent().parent().parent().parent();

		var $buttons = $('input[@type=button]',$table);
		$buttons.each(function()
		{
			if($(this).attr("name") != "reflect")
			{
				$(this).trigger("click");
			}
		});
	});

//*****汎用
	$('input[@type=button][@name=p2t]').click(function()
	{
		$p = $(this).parent().parent('tr');
		$("[@type=text]",$p).val($('p.newItem',$p).text());
	});

	$('input[@type=button][@name=p2te]').click(function()
	{
		$p = $(this).parent().parent('tr');
		$("[@cols]",$p).val($('p.newItem',$p).text());
	});

	$('input[@type=button][@name=p2date]').click(function()
	{
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];
		$d = $("[@type=text]",$p)[2];

		$y.value=vals.substring(0,4);
		$m.value=vals.substring(4,6);
		$d.value=vals.substring(6,8);
	});

	$('input[@type=button][@name=p2date_s]').click(function()
	{
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];

		$y.value=vals.substring(0,4);
		$m.value=vals.substring(4,6);
	});

	$('input[@type=button][@name=p2time]').click(function()
	{
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();
		$y = $("[@type=text]",$p)[0];
		$m = $("[@type=text]",$p)[1];
		$d = $("[@type=text]",$p)[2];

		$y.value=vals.substring(0,2);
		$m.value=vals.substring(2,4);
		$d.value=vals.substring(4,6);
	});


	$('input[@type=button][@name=p2mcode]').click(function()
	{
		var $p = $(this).parent().parent('tr');
		var vals = $('p.newItem',$p).text();
		var $t = $("[@type=text]",$p);
		$f1 = $($t[0]);
		$f2 = $($t[1]);

		$s1 = $($t[2]);
		$s2 = $($t[3]);
		$s3 = $($t[4]);

//		if(vals.length == 8 || $('.hasWeekly.none').size() > 0){
			$f1.val(vals.substring(0,5));
			$f2.val(vals.substring(5,7));
			$s1.val('');
			$s2.val('');
			$s3.val('');
/*		}else if($('.hasWeekly.none').size() == 0){
			$f1.value='';
			$f2.value='';
			$s1.value=vals.substring(0,5);
			$s2.value=vals.substring(6,8);
			$s3.value=vals.substring(9,11);
		}*/
	});

	$('input[@type=button][@name=p2isbn]').click(function()
	{
		$p = $(this).parent().parent('tr');
		vals = $('p.newItem',$p).text();

		$("[@type=text]",$p).val(vals);
	});

	$('input[@type=button][@name=p2select]').click(function()
	{
		$p = $(this).parent().parent('tr');
		val = $('p.newItem',$p).text();
		val = $.trim(val);
		$y = $("select",$p);

		$('option',$y).each(function()
		{
			if($(this).text() == val)
			{
				$(this).attr('selected',true);
			}
		});

		$y.trigger("change");
	});

	$('input[@type=button][@name=p2swother]').click(function()
	{
		var $p = $(this).parent().parent('tr');
		var val = $( 'p.newItem' , $p ).text();
		val = $.trim(val);
		var $y = $("select",$p);

		size = $('option',$y).length;
		$('option',$y).each(function(i)
		{
			if($(this).text() == val)
			{
				$(this).attr('selected',true);
			}
		});

		if($y.val() != val)
		{
			$y.val('x');
			$("input[type=text]",$p).val(val);
		}else
			$("input[type=text]",$p).val('');

		$y.trigger("change");
	});

	$('input[@type=button][@name=s2t]').click(function()
	{
		$p = $(this).parent().parent('tr');
		$y = $("select",$p);
		val = $y.val();
		val = $.trim(val);

		$("[@type=text]",$p).val(val);
	});

//*****author
	$('input[@type=button][@name=s2author]').click(function()
	{
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

	$('select').change(function()
	{
		var no = $(this).attr('name').charAt( $(this).attr('name').length - 1 );
		if(no != "")
		{
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
	$('input[@type=button][@name=s2series]').click(function()
	{
		$p = $(this).parent().parent('tr');
		$y = $("select",$p);
		sub_string = '';
		if($y.attr('name') == 'new_sub_series_1'){
			sub_string = 'sub_';
		}
		$m = $("option[@selected]",$y);

		var no = $y.attr('name').charAt( $y.attr('name').length - 1 );
		if(no != ""){
			myID = "p#series_" + no + "_" + $y.val();
			myNoteID = "p#"+sub_string+"series_kana_" + no + "_" + $y.val();
			targetID = "input[@name="+sub_string+"series_" + no+"]";
			targetKanaID = "input[@name="+sub_string+"series_kana_" + no + "]";
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




	commissionerCategoryTypeIds = '#commissioner_category_code_1_mag,#commissioner_category_code_1_com,#commissioner_category_code_1_sml,#commissioner_category_code_1_new,#commissioner_category_code_1_all,#commissioner_category_code_1_gen';
	$commissionerCategoryTypeButton = $(commissionerCategoryTypeIds);

//*****commissioner ジャンルごとの項目変更
	var commissionerFieldList = {
		"":[0,1,2
		],
		"51":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,37,40,41,42,43,44,45,46,47,48,53,54,57,61,63,64,65,66,67
		],
		"81":[0,1,2,3,4,5,6,7,8,9,10,20,21,23,24,25,27,37,40,41,42,43,44,45,46,47,48,53,57,61,63,64,65,66,67
		],
		"86":[0,1,2,3,4,5,6,7,8,9,10,20,21,23,24,25,27,37,40,41,42,43,44,45,46,47,48,53,57,61,63,64,65,66,67
		],
		"71":[0,1,2,3,4,5,6,7,8,9,10,20,21,27,37,40,41,42,43,44,45,46,47,48,53,57,61,63,64,65,66,67
		],
		"44":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,49,50,51,53,54,55,57,58,59,60,61,63,64,65,66,67
		],
		"42":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,49,50,51,53,54,55,57,58,59,60,61,63,64,65,66,67
		],
		"41":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,49,50,51,53,54,55,58,61,63,64,65,66,67
		],
		"43":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,49,50,51,53,54,55,58,59,60,61,63,64,65,66,67
		],
		"36":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,17,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,53,54,55,58,61,63,64,65,66,67
		],
		"31":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,17,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,53,54,55,58,61,63,64,65,66,67
		],
		"2":[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,53,54,55,61,63,64,65,66,67
		],
		"30":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,17,18,19,20,21,22,23,24,25,26,28,29,30,31,32,33,34,35,36,37,40,41,42,44,46,47,48,49,50,51,52,53,54,55,61,63,64,65,66,
		],
		"23":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,39,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"1":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"3":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"4":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"5":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"6":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"7":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"9":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"10":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"11":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"12":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"13":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"14":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"15":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"16":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"17":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"18":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"19":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"20":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"21":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"22":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"24":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,53,54,55,61,63,64,65,66,67
		],
		"29":[0,1,2,3,4,5,6,7,8,9,10,14,15,16,17,18,20,21,22,23,24,25,28,29,30,31,32,33,34,35,36,37,40,41,42,43,44,46,47,48,49,50,51,52,53,54,55,61,63,64,65,66,67
		]
	};
	var commissionerImpFieldList = {
		"":[
		],
		"51":[1,2,3,5,6,10,11,12,20,21,37,40,41,43,44,47,48,53,54,57,63
		],
		"81":[1,2,3,5,6,10,20,21,23,24,37,40,41,43,44,47,48,53,57,63
		],
		"86":[1,2,3,5,6,10,20,21,23,24,37,40,41,43,44,47,48,53,57,63
		],
		"71":[1,2,3,5,6,10,20,21,27,37,40,41,43,44,47,48,53,57,63
		],
		"44":[1,2,3,5,6,10,11,12,20,21,28,29,30,40,41,44,47,48,53,54,55,57,58,63
		],
		"42":[1,2,3,5,6,10,11,12,20,21,28,29,30,40,41,44,47,48,53,54,55,57,58,63
		],
		"41":[1,2,3,5,6,10,11,12,20,21,28,29,30,40,41,44,47,48,53,54,55,58,63
		],
		"43":[1,2,3,5,6,10,11,12,20,21,28,29,30,40,41,44,47,48,53,54,55,58,63
		],
		"36":[1,2,3,5,6,10,20,21,28,29,30,40,41,44,47,48,53,54,55,58,63
		],
		"31":[1,2,3,5,6,10,20,21,28,29,30,40,41,44,47,48,53,54,55,58,63
		],
		"2":[1,2,3,5,6,10,11,12,20,21,28,29,30,37,40,41,44,47,48,53,54,55,63
		],
		"30":[1,2,3,5,6,10,14,15,17,18,19,20,21,28,29,30,37,40,41,44,47,48,52,53,54,55,63
		],
		"23":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"1":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"3":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"4":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"5":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"6":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"7":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"9":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"10":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"11":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"12":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"13":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"14":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"15":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"16":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"17":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"18":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"19":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"20":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"21":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"22":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"24":[1,2,3,5,6,10,20,21,28,29,30,37,39,40,41,44,47,48,53,54,55,63
		],
		"29":[1,2,3,5,6,10,17,18,20,21,28,29,30,37,39,40,41,44,47,48,52,53,54,55,63
		]
	};

	var clearAllFields = function(){
		var $p = $commissionerCategoryTypeButton.parent().parent().parent().parent().parent('table');
		var $rowList = $('tr', $p);

		for(var i = 0 ; i < $rowList.size() ; i++){

			var view = false;
			for(var j = 0 ; j < commissionerFieldList[""].length ;j++){
				var $row = $($rowList[i]);
				view = (i == commissionerFieldList[""][j]);
				if( view ){
					$row.show();
					break;
				}
			}

			if(!view){
				$row.hide();
			}

		}
	};
	var weeklyDisable = function(){
		var $item = $('.hasWeekly');
		var $input = $('input[type=text][name=magazine_code_1_2_1],input[type=text][name=magazine_code_1_2_2],input[type=text][name=magazine_code_1_2_3]' , $item);
		$item.addClass('none');
		$input.val('');
	}
	var weeklyEnable = function(){
		var $item = $('.hasWeekly');
		$item.removeClass('none');
	}

//*****commissioner ジャンル選択大項目ごとの小項目変更
	$('select#commissioner_category_code_1_big').change(function(){
		itemsNames = '#category_code_wrapper_mag,#category_code_wrapper_com,#category_code_wrapper_sml,#category_code_wrapper_new,#category_code_wrapper_all,#category_code_wrapper_gen';
		$(itemsNames).attr('class','none');

		var selId = $(this).val();
		$commissionerCategoryTypeButton.each(function(i){
			if('commissioner_category_code_1_' + selId != $(this).attr('id'))
				$(this).val("");
		});
		if(selId == ''){
			clearAllFields();
			return;
		}
		var targetId = "#category_code_wrapper_" + selId;

		$(targetId).attr('class','');
		if($('option',$(targetId)).size() == 2)
			$('option',$(targetId)).each(function(i){
				if($(this).val() != '')
					$(this).attr('selected','true')
			});

		$(commissionerCategoryTypeIds , $(targetId)).trigger('change');
	});

	var prevGenre = 0;
	$commissionerCategoryTypeButton.change(function(){
		var $p = $(this).parent().parent().parent().parent().parent('table');
		var $rowList = $('tr', $p);

		for(var i = 0 ; i < $rowList.size() ; i++){

			var view = false;
			for(var j = 0 ; j < commissionerFieldList[$(this).val()].length ;j++){

				var $row = $($rowList[i]);
				view = (i == commissionerFieldList[$(this).val()][j]);
				if( view ){
					$row.show();
					break;
				}
			}
			if(!view){
				$row.hide();
			}

			var imp = false;
			for(var j = 0 ; j < commissionerImpFieldList[$(this).val()].length ;j++){

				var $row = $($rowList[i]);
				imp = (i == commissionerImpFieldList[$(this).val()][j]);
				if( imp ){
					$('th strong',$row).attr('class' , '');
					break;
				}
			}
			if(!imp){
				$('th strong',$row).attr('class' , 'none');
			}

		}

		$('.isGenre'+prevGenre).addClass('none');
		$('.isNotGenre'+prevGenre).removeClass('none');
		prevGenre = $(this).val();

		$('.isGenre'+$(this).val()).removeClass('none');
		$('.isNotGenre'+$(this).val()).addClass('none');


		if($(this).val() == "51" || $(this).val() ==  "44" || $(this).val() ==  "42"){
			weeklyDisable();
		}else{
			weeklyEnable();
		}
	});
	$('select#commissioner_category_code_1_big').trigger('change');





//版型（書協）その他
	$('select[class=withOther]').change(function(){
		var flag = true;
		if($(this).val()=="x"){
			flag = false;
		}
		$('input[type=text]',$(this).parent()).attr("disabled",flag);
	});
	$('select[class=withOther]').trigger("change");

	$('input[@type=button][@name=p2book_size_3]').click(function(){
		$p = $(this).parent().parent('tr');
		var val = $('p.newItem',$p).text();
		val = $.trim(val);
		$y = $("select",$p);
		if(val.indexOf('x') >= 0){
			val = val.replace('cm','');
			$('input[@name=book_size_3_l]',$p).val(val.split('x')[0]);
			$('input[@name=book_size_3_r]',$p).val(val.split('x')[1]);
			val = "x";
		}
		$('option',$y).each(function(){
			if($(this).text() == val){
				$(this).attr('selected',true);
			}
		});
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




//入力文字数チェック関連
	var validateSet = {
		'issuer_1' : {
			'maxLength' : 20,
			'target' : 'p.error span',
			'messege' : '20文字を超えて入力されています。',
			'prev':''
		},
		'issuer_kana_1' : {
			'maxLength' : 20,
			'target' : 'p.error span',
			'messege' : '20文字を超えて入力されています。',
			'prev':''
		},
		'publisher_2' : {
			'maxLength' : 20,
			'target' : 'p.error span',
			'messege' : '20文字を超えて入力されています。',
			'prev':''
		},
		'publisher_kana_2' : {
			'maxLength' : 20,
			'target' : 'p.error span',
			'messege' : '20文字を超えて入力されています。',
			'prev':''
		},
		'handling_company_1' : {
			'maxLength' : 20,
			'target' : 'p.error span',
			'messege' : '20文字を超えて入力されています。',
			'prev':''
		},
		'series_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'series_kana_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'sub_series_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'sub_series_kana_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'name_1' : {
			'maxLength' : 30,
			'target' : 'p.error span',
			'messege' : '30文字を超えて入力されています。',
			'prev':''
		},
		'kana_1' : {
			'maxLength' : 30,
			'target' : 'p.error span',
			'messege' : '30文字を超えて入力されています。',
			'prev':''
		},
		'sub_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'sub_kana_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'present_volume_1' : {
			'maxLength' : 12,
			'target' : 'p.error span',
			'messege' : '12文字を超えて入力されています。',
			'prev':''
		},
		'author_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'author_kana_1' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'author_2' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'author_kana_2' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'author_3' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'author_kana_3' : {
			'maxLength' : 25,
			'target' : 'p.error span',
			'messege' : '25文字を超えて入力されています。',
			'prev':''
		},
		'content_1' : {
			'maxLength' : 62,
			'target' : 'p.error span',
			'messege' : '62文字を超えて入力されています。',
			'prev':''
		},
		'content_2' : {
			'maxLength' : 38,
			'target' : 'p.error span',
			'messege' : '38文字を超えて入力されています。',
			'prev':''
		},
		'typist_tel_1' : {
			'maxLength' : 11,
			'target' : 'p.error span',
			'messege' : '11文字を超えて入力されています。',
			'prev':''
		},
		'win_info_1' : {
			'maxLength' : 30,
			'target' : 'p.error span',
			'messege' : '30文字を超えて入力されています。',
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
