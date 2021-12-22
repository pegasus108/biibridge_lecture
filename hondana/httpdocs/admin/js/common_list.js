$(function(){
	var targetSlimeList = {
		set_label:"add_label_no",
		delete_label:"add_label_no",
		set_genre:"add_genre_no",
		delete_genre:"add_genre_no",
		set_series:"add_series_no",
		delete_series:"add_series_no",
		set_stock_status:"add_stock_status_no",
		set_category:"add_category_no",
		cancel_linkage:"cancel_linkage_id"
	};
	showOneSlime('select#selectSlime');

	$('#allSlimeCheck').click(function() {
		if($(this).attr('checked')) {
			$('.slimeCheck').attr('checked', true);
		} else {
			$('.slimeCheck').attr('checked', false);
		}
	});
	$('select#selectSlime').change(function() {
		var action;
		action = $(this).val();
		showOneSlime('select#selectSlime');
		showSlime('select[name=' + targetSlimeList[action] + ']');

		if(!action) {
			action = "";
		} else {
			action += "/";
		}
		$('form#listForm').attr('action', action);
	});

	$('table.help th a.help').each(function(a_count) {
		$(this).mouseover(function(e) {
			$('#helpTextDisplay').css({'display':'block','top':e.pageY + 18,'left':e.pageX - 10});
			$('#helpTextDisplay .contents').html($('.helpText p:eq(' + a_count + ')').html());
		});
		$(this).mouseout(function() {
			$('#helpTextDisplay').css('display', 'none');
		});
	});

	// nestable
	if($('.dd').length > 0){
		$('.dd').nestable();
	}

	// 並び順を保存
	$('.save').click(function(){
		if(confirm('並び順を保存してもよろしいですか？')){
			// 一時的にボタンを無効化
			var button = $(this);
			button.prop('disabled', true);

			// メッセージをクリア
			$('.saveWrap p').text('');

			// 並び順を取得
			var json = $('.dd').nestable('serialize');

			$.ajax({
				type: 'post',
				url: 'sort_all/index.php',
				data: JSON.stringify(json),
				contentType: 'application/JSON',
				success: function(msg){
					// メッセージ表示
					$('.saveWrap p').text(msg);
				},
				complete: function(){
					// 完了したらボタンを有効化
					button.prop('disabled', false);
				}
			});
		}
		return false;
	});

	// 一括操作 機能が全て無効化されている場合は 非表示
	if($('.slimeWrapper select option').length == 1) {
		$('.slimeWrapper').remove();
	}
});

function showOneSlime(targetInput){
	$('.slime').hide();
	showSlime(targetInput);
}
function showSlime(targetInput){
	$(targetInput).parent('.slime').show();
}
