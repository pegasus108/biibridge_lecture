
$(function(){
	// ドラッグによるソート
	$('.sortable').sortable();
	// $('.sortable').disableSelection();
	$('.sortable').bind('sortstop', function (e, ui) {
		$(".sortable").closest("table").find("th").removeClass("headerSortUp");
		$(".sortable").closest("table").find("th").removeClass("headerSortDown");
	});

	// ヘッダクリックによるソート
	setSortable();

	dispEbookStoresArea();
	$("#ebook_status").change(function(){ // 電子書籍のチェックが切り替わったら
		dispEbookStoresArea();
	});

	var ebookStoreSetting = $("#ebookStoreSetting");
	ebookStoreSetting.find("label").change(function(){
		toggleUrlBox(this);
	}).each(function(){
		toggleUrlBox(this);
	});
	function toggleUrlBox(self) {
		var target = $(self).closest("li").find(".url");
		if($(self).find("input:checked").length) {
			target.fadeIn("fast");
		} else {
			target.fadeOut("fast");
		}
	}

	function dispEbookStoresArea() {
		var ebookStoreSetting = $("#ebookStoreSetting");
		if($($("#ebook_status").selector+":checked").length) {
			// 電子書籍に設定されたら
			ebookStoreSetting.fadeIn("fast");
			// フォーマットの切り替え
			if($("#this_format:disabled").length) {
				$("#this_format option").removeAttr("selected");
				$("#this_format option").eq(4).attr("selected","selected");
			}
		} else {
			// 電子書籍が解除されたら
			ebookStoreSetting.fadeOut("fast");
			// フォーマットの切り替え
			if($("#this_format:disabled").length) {
				$("#this_format option").removeAttr("selected");
				$("#this_format option").eq(0).attr("selected","selected");
			}
		}

	}

	$("#yondemill").change(function() {
		if($(this).val().length) {
			// 立ち読みファイルが選択される
			if($(this).val().match(/\.pdf/)) {
				// PDFが選択
				$("#ytypeh").val(1);
				var selectType = $(".selectType").fadeOut("fast");
				selectType.find("div input").attr("disabled","disabled");
			} else if($(this).val().match(/\.book|\.epub/)) {
				// .book or .epub が選択
				$("#ytypeh").val(0);
				var selectType = $(".selectType").fadeIn("fast");
				selectType.find("div input").removeAttr("disabled");
			} else {
				$(this).val(null);
			}
 		}
 	});
});

function setSortable() {
	var sortableItem = $('.sortable');
	var setFormat = $("#setFormat");
	sortableItem.each(function(){
		var sortabled = $(this);
		var thistable = sortabled.closest("table");
		thistable.find("th").unbind();
		thistable.find("th").removeClass();
		if(sortabled.find("tr").length >= 1) {
			// setFormat.find(".info").fadeIn("fast");
			if(sortabled.find("tr").length >= 2) {
				if(thistable.attr("id") == 'newsRelate') {
					thistable.tablesorter({
						headers:{
							0:{sorter: false },
							// 1:{sorter: false },
							3:{sorter: false }
						}
					});
				} else {
					thistable.tablesorter({
						headers:{
							0:{sorter: false },
							// 1:{sorter: false },
							2:{sorter: false }
						}
					});
				}
			}
			if(thistable.find("thead:hidden").length) {
				thistable.find("thead").show();
				thistable.closest("td").find(".message").show();
			}
			if(thistable.attr("id") == 'bookFormat' && !setFormat.find(".info:visible").length) {
				setFormat.find(".info").fadeIn("fast").find("select,input").removeAttr("disabled");
			}
		} else if(!sortabled.find("tr").length) {
			thistable.find("thead").hide();
			thistable.closest("td").find(".message").hide();
			if(thistable.attr("id") == 'bookFormat') {
				setFormat.find(".info").fadeOut("fast").find("select,input").attr("disabled","disabled");
			}
		}
		setFormatView();
		setFormat.find("select").change(function(){
			showTextBox(this);
		});
	});
}

function setFormatView() {
	var setFormat = $("#setFormat");
	setFormat.find("select").each(function(){
		showTextBox(this);
	});
}

function showTextBox(self) {
	if($(self).val() == 6) {
		$(self).parent().find("input").fadeIn("fast");
	} else {
		$(self).parent().find("input").fadeOut("fast");
	}
}
