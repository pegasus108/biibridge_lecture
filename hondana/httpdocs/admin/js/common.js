$(function(){
	// 権限管理用 AdminView.phpに制御記述あり

	// 右カラム制御
	$("#lnavi li:has(li.meAndParentHasnotRole.isDepth)").remove();
	$("#lnavi li.meAndParentHasnotRole").remove();
	var lanvisec = $('#lnavi dl');
	lanvisec.each(function(){
		var self = $(this);
		// 有効な機能がないコンテンツは削除
		if(!self.find('a').length) {
			self.hide();
		}
	});

	// グローバルナビ制御
	$("#gnavi li.meAndParentHasnotRole").remove();

	// トップページ
	$(".functionlinks li.meAndParentHasnotRole").remove();
	$(".functionlinks .section").each(function(){
		var self = $(this);
		// 有効な機能がないコンテンツは削除
		if(!self.find('a').length) {
			self.hide();
		}
	});
});
