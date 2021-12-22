<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/JPOController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/yondemillController.php');

class Action extends AuthAction {

	var $sqlDirectoryPath = "admin/publisher/book/sql/";

	function execute() {
		$this->_db->setSqlDirectoryPath($this->sqlDirectoryPath);
		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);
		if(!empty($this->back)) {
			// 戻るボタンで戻ってきた
			$this->back = false;

			// 関連書籍情報 再取得
			if(empty($this->bookviewlist) && !empty($this->news_relate_list)) {
				// 関連書籍 書籍情報を取得
				$db->assign('booknolist',implode(",", $this->news_relate_list));
				$bookviewlist = $db->statementTree("get_relate_book", 'book_no');
				$bookarraylist = array();
				// 関連書籍 並び順反映
				foreach ($this->news_relate_list as $k => $v) {
					$bookarraylist[] = $bookviewlist[$v];
				}
				$this->bookviewlist = $bookarraylist;
			}

			return;
		}
		$this->book_label_list = null;
		$this->book_genre_list = null;
		$this->book_series_list = null;
		$this->opus_list = null;
		$this->news_relate_list = null;

		$this->jpo_author_type_list = null;
		$this->jpoSync = null;

		// 画像アップロード用の文字列 （複数タブ対応）
		$this->upkey = time();

		if(isset($_REQUEST['book_no'])){
			$this->errors = NULL;
			$this->errors_jpo = NULL;
			$this->attentions_jpo = NULL;
		}

		$jpo = new JPOController();
		$this->jpoSyncTime = "6";
		$jpoSyncTime = $jpo->makeJpoSyncTime($this->jpoSyncTime);
		$db->assign('jpoSyncTime', $jpoSyncTime);

		$this->publisher = $db->statementFetch("publisher");
		$this->publisher['free'] = array();
		if ($this->publisher['freeitem']) {
			$this->publisher['free'] = $this->unserialize($this->publisher['freeitem']);
			foreach($this->publisher["free"] as $k => $v){
				if(empty($v)) {
					unset($this->publisher['free'][$k]);
				}
			}
		}

		$this->labelList = $db->statementTree("label_list", 'label_no');
		$this->genreList = $db->statementTree("genre_list", 'genre_no');
		$this->seriesList = $db->statementTree("series_list", 'series_no');
		$this->authorTypeList = $db->statementTree("author_type_list", 'author_type_no');
		$this->bookSizeList = $db->statementTree("book_size_list", 'book_size_no');

		$this->stockStatusList = $db->statementTree("stock_status_list", 'stock_status_no');

		$this->oldBookLabelList = $db->statementTree("book_label", 'book_label_no');
		$this->viewOldBookLabelList = $this->oldBookLabelList;

		$this->oldBookGenreList = $db->statementTree("book_genre", 'book_genre_no');
		$this->viewOldBookGenreList = $this->oldBookGenreList;

		$this->oldBookSeriesList = $db->statementTree("book_series", 'book_series_no');
		$this->viewOldBookSeriesList = $this->oldBookSeriesList;

		$this->oldOpusList = $db->statementTree("opus", 'opus_no');
		$this->viewOldOpusList = $this->oldOpusList;

		$this->oldBookRelateList = $db->statementTree("book_relate", 'book_relate_no');
		$this->viewOldBookRelateList = $this->oldBookRelateList;

		// 特設サイトリンク
		$this->oldSpsite = $db->statementTree("special_site_link_list", 'special_site_link_no');
		// キャンペーンサイトURL
		$this->oldCpsite = $db->statementTree("campaign_site_link_list", 'campaign_site_link_no');

		$row = $db->statementFetch("current_timestamp");
		$this->setProperties($row);

		$this->subject_codes = $jpo->getSubjectCode();
		$this->unpriced_item_types = $jpo->getUnpricedItemType();
		$this->audience_code_values = $jpo->getAudienceCodeValue();
		$this->audience_descriptions = $jpo->getAudienceDescription();
		$this->containeditems = $jpo->getContaineditem();
		$this->languages = $jpo->getLanguage();
		$this->resellings = $jpo->getReselling();
		$this->supply_restriction_details = $jpo->getSupplyRestrictionDetail();
		$this->contributor_roles = $jpo->getContributorRole();
		$this->notification_types = $jpo->getNotificationTypes();
		$this->recent_publication_types = $jpo->getRecentPublicationTypes();
		$this->recent_publication_readers = $jpo->getRecentPublicationReader();
		$this->publication_forms = $jpo->getPublicationForm();
		$this->extents = $jpo->getExtent();
		$this->completions = $jpo->getCompletion();
		$this->Intermediary_company_handlings = $jpo->getIntermediaryCompanyHandlings();
		$this->readers_writes = $jpo->getReadersWrite();
		$this->production_notes_items = $jpo->getProductionNotesItem();
		$this->cd_dvds = $jpo->getCDDVD();
		$this->childrens_book_genres = $jpo->getChildrensBookGenre();
		$this->rubys = $jpo->getRuby();
		$this->other_noticess = $jpo->getOtherNotices();
		$this->bands = $jpo->getBand();
		$this->covers = $jpo->getCover();

		// JPO連携用データ取得
		$row = $db->statementFetch("book_jpo");
		$this->setProperties($row);

		// 書籍フォーマット
		$result = $db->statement('admin/publisher/book/sql/book_format.sql');
		$this->bookFormat = $db->buildTree($result, 'id');
		$this->book_format_list = null;
		$this->bookFormatBook = null;
		$result = $db->statement('admin/publisher/book/sql/book_format_book.sql');
		$this->bookFormatBook = $db->buildTree($result, 'id');
		$this->viewBookFormatBook = $this->bookFormatBook;

		$this->yondemill_id = null;
		$this->yondemill = null;

		// 書籍情報取得
		$row = $db->statementFetch("book");
		$this->setProperties($row);
		$this->this_format = $this->book_format;
		$this->this_format_other = $this->book_format_other;
		if ($this->freeitem) {
			$this->free = $this->unserialize($row['freeitem']);
		} else {
			$this->free = null;
		}

		// WYSIWYG入力フィールド内 ルートパス → フルパスへ変換して表示 (管理画面から画像が参照できるように)
		$this->setFullpath(array('content'),$_SESSION['publisher_url']);

		$this->jpoAfterRelease = false;
		if($jpo->isAfterReleaseDate($this->release_date, $this->jpoSyncTime)) {
			$this->jpoAfterRelease = true;
		}

		$this->jpoBeforeRelease = false;
		if($jpo->isBeforeReleaseDate($this->release_date, $this->jpoSyncTime)) {
			$this->jpoBeforeRelease = true;
		}

		// recommend_image
		if($this->publisher['recommend_type'] == 2 && $this->recommend_status) {
			$this->old_recommend_image = sprintf('/images/book/recommend/%s.jpg', $this->book_no);
		} else {
			$this->old_recommend_image = null;
		}

		$this->actibook=null;
		$this->has_actibook=false;
		$actibook = sprintf('%s/publisher/%s/files/actibook/%s/_SWF_Window.html', $_SERVER['DOCUMENT_ROOT'], $_SESSION['id'], $this->book_no);
		if(file_exists($actibook)) {
			$this->has_actibook = true;
		}

		if ($this->publisher['jpo']) {
			$adTime = 0;
			if (date("G") >= 6) {
				$adTime = 1;
			}

			$this->jpo_outside_end = mktime(0, 0, 0, date("m")  , date("d")+180+$adTime, date("Y"));
		} else {
			unset($this->jpo_outside_end);
		}

		/**
		 * Store old book
		 */
		$this->oldCurrentBook = $this->getProperties();

		// 変更点確認用
		$this->old['name'] = $this->name;
		$this->old['kana'] = $this->kana;
		$this->old['sub_name'] = $this->sub_name;
		$this->old['sub_kana'] = $this->sub_kana;
		$this->old['outline_abr'] = $this->outline_abr;
		$this->old['outline'] = $this->outline;
		$this->old['isbn'] = $this->isbn;

		$this->yondemill_userid = $this->publisher['yondemill_userid'];
		$this->ebook_store_status = $this->publisher['ebook_store_status'];
		if(!empty($this->ebook_store_status)) {
			// 電子書籍書店
			// YONDEMILL APIより電子書籍情報取得
			$yondemill = new yondemillController($db);
			$this->ebookstoreList = null;
			$this->ebookstoreList = $yondemill->getEbookstores(array(
				'publisher_no' => $_SESSION['publisher_no'],
				'book_no' => $this->book_no,
			));
		}
	}
}
?>