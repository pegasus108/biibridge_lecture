$(document).ready(function(){
	if($("#radioJpoSync1").length > 0)
		var jpo = new BookJPO();
});


var BookJPO =function(){
	var self = this;
	self.speed = 300;

	self.openRadio = $("#radioJpoSync1");
	self.closeRadio = $("#radioJpoSync0");

	self.openLabel = $("label:has(#radioJpoSync1)");
	self.closeLablel = $("label:has(#radioJpoSync0)");

	self.init();
}

BookJPO.prototype.init = function(){
	var self = this;

	self.listenClick();
	self.bookSize();
}

BookJPO.prototype.listenClick = function(){
	var self = this;

	var open = self.openLabel;
	var close = self.closeLablel;

	open.click(function(){
		self.openAll();
	});

	close.click(function(){
		self.close();
	});

	if($(".JPOInput .error:parent").length){
		self.openAll();

	}else if(self.openRadio.attr("checked")){
		self.openAll();

	}else{
		self.close(true);
	}

	// 時限再販対応用
	$("#reselling").change(function(){
		resellingCheck();
	});
	resellingCheck();
	function resellingCheck() {
		if($("#reselling").val() == 3) {
			// 時限再販
			$("#resellingDate").show();
			$("#resellingControll").show();
		} else {
			// それ以	外
			$("#resellingDate").hide();
			$("#resellingControll").hide();
		}
	}

	// 児童図書ジャンル 項目 表示/非表示対応
	$("#subject_code").change(function(){
		self.childrenGenreCheck();
	});
	self.childrenGenreCheck();
}

BookJPO.prototype.openAll = function(){
	var self = this;
	var tr = $(".JPOInput");
	var input = tr.find(".inner");

	var jpoItem = $(".jpoOnlyItems,.errorJPO");
	jpoItem.fadeIn(self.speed);

	tr.show();
	input.animate({"opacity":"show"}, self.speed, "linear",function(){
		// 児童図書ジャンル 項目 表示/非表示対応
		self.childrenGenreCheck();
	});
}

BookJPO.prototype.open = function(){
	var self = this;

	var jpoItem = $(".jpoOnlyItems,.errorJPO");
	jpoItem.fadeIn(self.speed);

	self.closeInput(true);
}

BookJPO.prototype.close = function(fast){
	var self = this;
	var jpoItem = $(".jpoOnlyItems,.errorJPO");
	var speed = self.speed;

	if( fast ){
		jpoItem.hide();
	} else {
		jpoItem.fadeOut(speed);
	}

	self.closeInput(fast);
}

BookJPO.prototype.openInput = function(){
	var self = this;

	var tr = $(".JPOInput");
	var input = tr.find(".inner");

	tr.show();
	input.animate({"opacity":"show"}, self.speed, "linear");
}

BookJPO.prototype.closeInput = function(fast){
	var self = this;
	var tr = $(".JPOInput");
	var input = tr.find(".inner");

	var speed = self.speed;
	if(fast){
		speed = 0;
	}

	input.animate({"opacity":"hide"}, speed, "linear", function(){
		tr.hide();
	});
}

BookJPO.prototype.fastClose = function(){
	var self=this;
	self.close(true);
}

BookJPO.prototype.bookSize = function(){
	$("select[name=book_size_no]").change(function(){
		var val = $(this).val();
		var height = $("input[name=measure_height]");
		var width = $("input[name=measure_width]");

		if(val=="13"){
			height.val("105");
			width.val("148");
		}else if(val=="14"){
			height.val("105");
			width.val("148");
		}else if(val=="16"){
			height.val("257");
			width.val("364");
		}else if(val=="18"){
			height.val("218");
			width.val("304");
		}else if(val=="19"){
			height.val("");
			width.val("");
		}else{
			height.val("");
			width.val("");
		}
	});
}

// 児童図書ジャンル 項目 表示/非表示対応
BookJPO.prototype.childrenGenreCheck = function() {
	if($("#subject_code").val() == 23) {
		// 児童図書
		$(".jpochildren").show();
	} else {
		// それ以外
		$(".jpochildren").hide();
	}
}
