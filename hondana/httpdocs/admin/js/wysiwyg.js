(function() {
	// KCFinderを開くためのTinyMCEプラグイン
	tinymce.create('tinymce.plugins.openKCFinder', {
		init : function( ed,  url) {
			// コマンドを作る
			ed.addCommand( 'openkcf_cd',
			function() {
				window.open('/admin/js/kcfinder/browse.php?type=files',
					'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
					'directories=0, resizable=1, scrollbars=0, width=700, height=500'
				);
			});

			// 上で書いたコマンドを実行するボタンを作る
			ed.addButton( 'openkcf', {
				title : 'ファイルの管理',
				cmd : 'openkcf_cd'
			});
		}
	});
	tinymce.PluginManager.add('openkcf', tinymce.plugins.openKCFinder);
})();

tinymce.init({
	selector:'#tinyMCE textarea',
	language : 'ja',
	// KCFinderの呼び出し
	file_browser_callback:function(d,a,b,c){
		tinyMCE.activeEditor.windowManager.open({
			file:"/admin/js/kcfinder/browse.php?opener=tinymce4&field="+d+"&type=files",
			title:"KCFinder web file manager",
			width:700,
			height:500,
			inline:true,
			close_previous:false
		},{window:c,input:d});
		return false
	},
	menubar: false, // メニューバーを隠す
	plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'media table contextmenu paste code',
		'textcolor colorpicker hr emoticons openkcf',
	],
	extended_valid_elements : 'script[*],video[*],a[*]',
	toolbar: [ // 3段にする
		'bold italic underline strikethrough | alignleft aligncenter alignright | styleselect fontselect fontsizeselect',
		'undo redo | forecolor backcolor | link unlink | hr | subscript superscript | charmap emoticons | image media openkcf | code',
		'table | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | tablemergecells tablesplitcells'
	],
	contextmenu: "cut | copy | paste | link unlink | image | inserttable",
	forced_root_block: false,
	relative_urls : false
});



