<?php
require_once('../new/confirm.php');

class PreviewAction extends Action {
	function execute() {
		$lifetime = 24 * 60 * 60;
		$getflg = true;

		$this->title = '会社情報 プレビュー';

		// テンプレートファイル 確認
		$pathlist = array(
			'/__company/detail.html',
			'/include/head/meta.html',
			'/include/head/ogp.html',
			'/include/head/link.html',
			'/include/head/script.html',
			'/include/header.html',
			'/include/sns-btn.html',
			'/include/footer.html',
		);

		foreach ($pathlist as $k => $v) {
			$filename = './tmp/' . $v;
			$getcontent = '';
			// if(file_exists($filename)) {
			// 	if(abs(time() - filemtime($filename)) < $lifetime) {
					// 保持期間内の場合は キャッシュファイルを読み込み
					$getcontent = file_get_contents($filename);
			// 	}
			// }
			// if(!$getcontent) {
			// 	// キャッシュが無い or 保持期間を過ぎた場合は ダウンロード
			// 	$getcontent = file_get_contents(getenv("PREVIEW_SITE_URL") . $v);
			// 	if($getcontent) {
			// 		file_put_contents($filename, $getcontent);
			// 	} else {
			// 		echo "ファイルの読み込みに失敗しました。";
			// 		$getflg = false;
			// 		exit();
			// 	}
			// }
		}

		// $db =& $this->_db;

		// preview用処理
		// $db->assign('news_no', $_REQUEST['news_no']);
		$this->publisher_no = $_SESSION['publisher_no'];
		// $db->assign('publisher_no',$this->publisher_no);
		$this->preview_status = true;

		// 値のセット
		$this->companyDetail = array(
			'name' => $this->name,
			'value' => $this->value,
		);

		// 静的ファイル等は、サイトより直接読み込み
		$this->commonPublisher['url'] = getenv("PREVIEW_SITE_URL") . '/';

		return './tmp/__company/detail';
	}
}
?>