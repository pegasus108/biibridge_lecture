var confirmText = "このページから移動しますがよろしいですか？\n保存されていない情報の変更は失われてしまいます。\n[OK]を押すと移動、[キャンセル]を押すと現在のページに留まります。"

$(function() {
	$('form input, textarea, select').change(function() {
		$(this).attr('class', 'focus');
	});
	$('a[href*="/"][target!="_blank"]').click(function(){
		if(!$(this).attr('class')) {
			if($('.focus').size()) {
				if(confirm(confirmText)) {
					return true;
				} else {
					return false;
				}
			}
		}
	});
});