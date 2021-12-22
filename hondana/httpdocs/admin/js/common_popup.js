$(function(){
	function getTarget(){
		var $parent = $(window.opener.document);
		var targetName = '#' + $('input[name=actionTarget]').val();
		if($(targetName, $parent).find(".sortable").size()) {
			targetName += " .sortable";
		}
		return $(targetName, $parent);
	}

	$('input[type=button][name=changeItem]').click(function(){
		var $target = getTarget();
		$target.css('margin', '0 0 6px 0');

		$target.empty();
		$('input[name=item][checked]').each(function(){
			$item = $(this).siblings('.none');
			$target.html($target.html() + $item.html());
		});
		window.close();
	});

	$('input[type=button][name=addItem]').click(function(){
		var $target = getTarget();
		var items = "";
		$target.css('margin', '0 0 6px 0');

		$items = $('input[name=item][checked]');
		$items.each(function(){
			$item = $(this).siblings('.none');

			add = true;
			$('tr',$target).each(function(){
				if($('input',$item).val() == $('input',$(this)).val()){
					add=false;
				}
			});

			if(add){
//				items += $item.html();
//				$target.html($target.html() + $item.html());
				$target.append($item.find("tbody").html());
				// テーブルの並び替え機能使用時
				// IEで動作しなかったため、if文をコメントアウト
				// if(typeof window.opener.setSortable == "function") {
					window.opener.setSortable();
				// }
			}
		});
//		$target.html($target.html() + items);
		window.close();
	});
});
