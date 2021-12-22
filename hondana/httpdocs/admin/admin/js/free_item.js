function addRow(num) {
	if (num == 1) {
		$('#freeItem .free').removeClass('first');
	} else {
		$('#freeItem .free').removeClass('last');
	}
	$('#freeItem').append('<div class="free last"><input type="text" name="free['+num+']" value="" size="50" /> <a class="add" title="この下に項目を追加" href="javascript:addRow('+(num+1)+');">＋</a> <a class="delete" title="この項目を削除" href="javascript:delRow('+num+');">－</a></div>');
}
function delRow(num) {
	$('#freeItem .last').replaceWith("");
	if (num != 1) {
		$('#freeItem .free').eq(num-1).addClass('last');
	} else {
		$('#freeItem .free').eq(num-1).addClass('first');
	}
}

