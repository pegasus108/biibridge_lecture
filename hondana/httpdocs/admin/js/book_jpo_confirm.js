$(document).ready(function(){
	if($("#isConfirmJPOHeader").length == 0){
		var jpoItem = $(".jpoOnlyItems,.errorJPO");
		jpoItem.hide(0);
	}
	// 児童図書ジャンル 項目 表示対応
	$(".jpochildren").hide();
	if($('#subject_code').val() == 23) {
		$(".jpochildren").show();
	}
});
