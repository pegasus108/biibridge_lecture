<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/JPOController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/util/yondemillController.php');

class Action extends AuthAction {

    var $sqlDirectoryPath = "admin/publisher/book/sql/";

	function execute() {

		if(isset($_REQUEST['new_entry'])) {
			$this->clearProperties();

			$this->__controller->redirectToURL('./');
			exit();
		}

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlDirectoryPath);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		if(!empty($this->back)) {
			// 戻るボタンで戻ってきた
			$this->back = false;
			$this->new_entry = false;

			// その後の処理をスキップ
			return;
		} elseif(!empty($this->new_entry)) {
			// パラメータ「cleardata」がある場合は入力内容をクリア
			$this->clearProperties();
			$this->new_entry = false;
		}

		// 画像アップロード用の文字列 （複数タブ対応）
		$this->upkey = time();

		if(!isset($_REQUEST['book_label_list']))
			$this->book_label_list = null;
		if(!isset($_REQUEST['book_genre_list']))
			$this->book_genre_list = null;
		if(!isset($_REQUEST['book_series_list']))
			$this->book_series_list = null;
		if(!isset($_REQUEST['opus_list']))
			$this->opus_list = null;
		if(!isset($_REQUEST['news_relate_list']))
			$this->news_relate_list = null;
		if(!isset ($_REQUEST["jpo_author_type_list"]))
			$this->jpo_author_type_list = null;

		// 関連書籍情報 取得
		if(!empty($this->news_relate_list)) {
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

		/* publisher */
		$this->publisher = $db->statementFetch('publisher');
		$this->publisher['free'] = array();
		if ($this->publisher['freeitem']) {
			$this->publisher['free'] = $this->unserialize($this->publisher['freeitem']);
			foreach($this->publisher["free"] as $k => $v){
				if(empty($v))
					unset($this->publisher['free'][$k]);
			}
		}

		$this->sitelist = $db->statementTree('get_sites', 'sites_no');
		$this->labelList = $db->statementTree('label_list', 'label_no');
		$this->genreList = $db->statementTree('genre_list', 'genre_no');
		$this->seriesList = $db->statementTree('series_list', 'series_no');

		if(!empty($this->opus_list)) {
			// 著者一覧取得
			$db->assign('opus_list',$this->opus_list);
			$this->author_data_list = array();
			$this->author_data_list =  $db->statementTree("admin/publisher/book/edit/sql/author_data_list.sql", 'author_no');
		}

		$this->authorTypeList = $db->statementTree('author_type_list', 'author_type_no');
		$this->bookSizeList = $db->statementTree('book_size_list', 'book_size_no');
		$this->stockStatusList = $db->statementTree('stock_status_list', 'stock_status_no');

		$jpo = new JPOController();
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
		$row = $db->statementFetch('book_jpo');
		$this->setProperties($row);

		$row = $db->statementFetch('current_timestamp');
		$this->setProperties($row);

		$this->book_format_list = null;
		$this->bookFormatBook = null;

		// 書籍フォーマット
		$result = $db->statement('admin/publisher/book/sql/book_format.sql');
		$this->bookFormat = $db->buildTree($result, 'id');

		$this->actibook=null;
		if ($this->publisher['jpo']) {
			$adTime = 0;
			if (date("G") >= 6) {
				$adTime = 1;
			}

			$this->jpo_outside_end = mktime(0, 0, 0, date("m")  , date("d")+180+$adTime, date("Y"));
		} else {
			unset($this->jpo_outside_end);
		}

		$this->yondemill_userid = $this->publisher['yondemill_userid'];
		$this->ebook_store_status = $this->publisher['ebook_store_status'];
		if(!empty($this->ebook_store_status)) {
			// 電子書籍書店
			// YONDEMILL APIより電子書籍情報取得
			$yondemill = new yondemillController($db);
			$this->ebookstoreList = null;
			$this->ebookstoreList = $yondemill->getEbookstores(array(
				'publisher_no' => $_SESSION['publisher_no'],
			));
		}
	}
}
?>