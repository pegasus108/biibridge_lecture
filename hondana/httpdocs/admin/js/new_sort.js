
$(function(){
	$(".btn input").attr("disabled","true");

	// ドラッグによるソート
	$('#sortable').sortable();
	$('#sortable').disableSelection();
	$('#sortable').bind('sortstop', function (e, ui) {
		enableBtn();
		$("#sortable").closest("table").find("th").removeClass("headerSortUp");
		$("#sortable").closest("table").find("th").removeClass("headerSortDown");
	});

	// ヘッダクリックによるソート
	$("#sortable").closest("table").tablesorter({
		headers:{
			0:{sorter: false },
			2:{sorter: false },
			3:{sorter: false }
		}
	});
	$("#sortable").closest("table").find("th.header").click(enableBtn);
});

function enableBtn() {
	if($(".btn input:disabled").size()) {
		$(".btn input").removeAttr("disabled");
		$("#listForm .error").fadeOut("slow");
	}
}
