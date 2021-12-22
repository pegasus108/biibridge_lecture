$(function(){
	$('.popupOpen').click(function(){
		var name = 'popup';
		var optionValuesString =
			'width=665,height=580,left=0,top=0,menubar=no,toolbar=no,location=no,status=no,resizable=no,scrollbars=yes';

		window.open(
			this.href,
			name,
			optionValuesString
		);

		return false;
	});
	$('.hasLink').children('ul').each(function(index) {
		if($(this).children('li').html()) {
			$(this).css('margin', '0 0 6px 0');
		}
	});

	//preview用
	$('form :submit[@name!="submit"]').click(function() {
		var action = $(this).attr('name');
		if(action == 'preview') {
			$('form').attr('target', '_blank');
			$('form').attr('action', '../preview/');
		} else if(action == 'new') {
			$('form').attr('target', '');
			$('form').attr('action', '../new/');
		} else if(action == 'confirm') {
			$('form').attr('target', '');
			$('form').attr('action', './?action=confirm');
		} else if(action == 'process') {
			$('form').attr('target', '');
			$('form').attr('action', './?action=process');
		} else if(action == 'back') {
			$('form').attr('target', '');
			$('form').attr('action', './?action=input&back=true');
		}
	});

	// クリック連打対応
	$('form').submit(function(){
		$('form .button').find('input,button').not('[name=preview]').attr('disabled','disabled');
		setTimeout(function() {
			// 10秒経ったら 有効化
			$('form .button').find('input,button').not('[name=preview]').removeAttr('disabled');
		}, 10000);
	});

	// 増減する要素 制御
	if($('#main [data-set-wrap]').length) {
		// セットを削除
		$("#main [data-set-delete]").live('click',function(){
			$(this).closest('[data-set]').remove();
		});
		// セットを追加
		$("#main [data-set-add]").bind('click',function(){
			const set = $(this).closest("[data-set-wrap]").find('[data-set]');
			const copy = set.first().clone();
			// コピー元の入力内容を削除
			copy.find('input,textarea').val('');
			set.last().after(copy);
		});
	}

	// 掲載サイト専用項目の出し分け
	const siteset = $('#main [data-site-set]');
	if(siteset.length) {
		// ページ表示時
		siteset.find('input').each(function(){
			if(!$(this).is(':checked')) {
				// チェックが外れている
				$('[data-site=' + $(this).val() + ']').hide().find('input,textarea').not('[type=hidden]').attr('disabled','disabled');
			}
		});
		// チェック変更時
		siteset.find('input').change(function(){
			if($(this).is(':checked')) {
				// チェックされた
				$('[data-site=' + $(this).val() + ']').fadeIn('fast').find('input,textarea').not('[type=hidden]').removeAttr('disabled');
			} else {
				// チェックが外れた
				$('[data-site=' + $(this).val() + ']').fadeOut('fast').find('input,textarea').not('[type=hidden]').attr('disabled','disabled');
			}
		});
	}
});
function unSelectItem(element){
	var target = $(element).parent('td').parent('tr');

	$(element).parent('td').parent('tr').remove();

	if(!target.children('tr').html()) {
		target.css('margin', '0');
	}

	// テーブルの並び替え機能使用時
	if(typeof setSortable == "function") {
		setSortable();
	}

	return false;
};