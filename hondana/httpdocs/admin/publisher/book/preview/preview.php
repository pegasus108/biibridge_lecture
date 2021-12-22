<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/publisher/core/book/detail.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class PreviewAction extends Action {

	function init(){
		parent::init();

		$this->publisher_no = $_SESSION['publisher_no'];
	}

	function prepare() {
		parent::prepare();

		$siteroot = $_SERVER['DOCUMENT_ROOT'];

		$this->book = $_REQUEST['book'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/publisher/'.$_SESSION['id'].'/images/book',
			$siteroot
		);
		//thumb size
		$this->width = 200;
		$this->height = 300;

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);
	}

	function execute() {
//Set Preview Flag
		$this->preview_status = true;

		// フォームデータ等を待避
		$bookDetail['name'] = $_REQUEST['name'];
		$bookDetail['volume'] = $_REQUEST['volume'];
		$bookDetail['new_status'] = $_REQUEST['new_status'];
		$bookDetail['next_book'] = $_REQUEST['next_book'];
		$bookDetail['sub_name'] = $_REQUEST['sub_name'];
		$bookDetail['opus_list'] = $_REQUEST['opus_list'];
		$bookDetail['author_type_list'] = $_REQUEST['author_type_list'];
		$bookDetail['author_type_other'] = $_REQUEST['author_type_other'];
		$bookDetail['book_series_list'] = $_REQUEST['book_series_list'];
		$bookDetail['book_genre_list'] = $_REQUEST['book_genre_list'];
		$bookDetail['book_year'] = $_REQUEST['book_year'];
		$bookDetail['book_month'] = $_REQUEST['book_month'];
		$bookDetail['book_day'] = $_REQUEST['book_day'];
		$bookDetail['isbn'] = $_REQUEST['isbn'];
		$bookDetail['magazine_code'] = $_REQUEST['magazine_code'];
		$bookDetail['c_code'] = $_REQUEST['c_code'];
		$bookDetail['version'] = $_REQUEST['version'];
		$bookDetail['book_size_no'] = $_REQUEST['book_size_no'];
		$bookDetail['page'] = $_REQUEST['page'];
		$bookDetail['price'] = $_REQUEST['price'];
		$bookDetail['stock_status'] = $_REQUEST['stock_status_no'];
		$bookDetail['cart_status'] = $_REQUEST['cart_status'];
		$bookDetail['outline'] = $_REQUEST['outline'];
		$bookDetail['explain'] = $_REQUEST['explain'];
		$bookDetail['content'] = $_REQUEST['content'];

		if(isset($_REQUEST['news_relate_list']))
			$bookDetail['book_relate_list'] = $_REQUEST['news_relate_list'];

		$title = $_REQUEST['title'];

		$this->bookDetail['image'] = NULL;

		// Get Detail
		parent::execute();

		// ■ フリー項目
		$free = serialize($_REQUEST['free']);
		$free = unserialize($free);
		// Chromeでプレビューをした際のタグ除外対応
		if(preg_match("/chrome/i", $_SERVER['HTTP_USER_AGENT'])){
			// フリー項目からの除外
			foreach ($free as $k => &$v) {
				// scriptタグを除外する
				$v = preg_replace("/<script.*?<\/script>/i", "", $v);

				// iframeタグを除外する
				$v = preg_replace("/<iframe.*?<\/iframe>/i", "", $v);
			}

			// 内容説明からのiframeタグ除外
			$bookDetail['content'] = preg_replace("/<iframe.*?<\/iframe>/i", "", $bookDetail['content']);
		}

		$this->bookDetail['freeitem'] = $free;

		if(!isset($_REQUEST['book_no']))
		{
			$this->bookRelateNewsList = NULL;
		}

		$db =& $this->_db;
		$db->assign($this->getProperties());
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		//preview用処理
		$this->publisher_no = $_SESSION['publisher_no'];
		$this->commonPublisher['url'] = "https://www.hondana.jp/publisher/{$_SESSION['id']}/";

		//画像処理
		$up =& $this->_uploader;
		$key = 'book';
		if($_REQUEST['clear_image'][0])
		{
			$this->bookDetail['image'] = NULL;
		}
		elseif ($this->book['http_path'])
		{
			$temp_path = $up->getTemporaryPath($key);
			$this->convertGeometry($temp_path,$this->width,$this->height);
			$this->bookDetail['image'] = $up->getTemporaryHttpPath($key);
		}
		elseif ($_REQUEST['book_httppath'])
		{
			$this->bookDetail['image'] = $_REQUEST['book_httppath'];
		}
		elseif(isset($_REQUEST['book_no']) && $this->bookDetail['image'])
		{
			$this->bookDetail['image'] = $this->commonPublisher['url'] . $this->bookDetail['image'];
		}
		else
		{
			$this->bookDetail['image'] = NULL;
		}

		$this->bookDetail['name'] = $bookDetail['name'];
		$this->bookDetail['volume'] = $bookDetail['volume'];
		$this->bookDetail['new_status'] = $bookDetail['new_status'];
		$this->bookDetail['next_book'] = $bookDetail['next_book'];
		$this->bookDetail['sub_name'] = $bookDetail['sub_name'];

		if($bookDetail['opus_list'])
		{
			/* author list */
			$result = $db->statement('admin/publisher/book/sql/author_list.sql');
			$tree = $db->buildTree($result, 'author_no');
			$this->authorList = $tree;

			/* author type list */
			$result = $db->statement('admin/publisher/book/sql/author_type_list.sql');
			$tree = $db->buildTree($result, 'author_type_no');
			$this->authorTypeList = $tree;

			$this->bookDetail['opus_list'] = $bookDetail['opus_list'];
			foreach($bookDetail['author_type_list'] as $key1 => $val1)
				if($val1 == "16" && $bookDetail['author_type_other'][$key1]){
					$hash = md5($bookDetail['author_type_other'][$key1]);
					$bookDetail['author_type_list'][$key1] = $hash;
					$this->authorTypeList[$hash]['name'] = $bookDetail['author_type_other'][$key1];
					$this->authorTypeList[$hash]['author_type_no'] = $hash;
				}

			$this->bookDetail['author_type_list'] = $bookDetail['author_type_list'];
			$this->bookDetail['author_no'] = true;

		}
		else
		{
			$this->bookDetail['author_no'] = false;
		}

		if($bookDetail['book_series_list'])
		{
			$seriesList = array();
			foreach($bookDetail['book_series_list'] as $series_no)
			{
				$db->assign('series_no', $series_no);

				//series list
				$result = $db->statement('admin/publisher/book/sql/preview_series.sql');

				$tree = $db->buildTree($result, 'series_no');
				$seriesList = array_merge($seriesList, $tree);
			}

			asort($seriesList);

			$this->seriesList = $seriesList;

			$this->bookDetail['book_series_no'] = true;
		}
		else
		{
			$this->bookDetail['book_series_no'] = false;
		}

		if($bookDetail['book_genre_list'])
		{
			$genreList = array();
			foreach($bookDetail['book_genre_list'] as $genre_no)
			{
				$db->assign('genre_no', $genre_no);

				//genre list
				$result = $db->statement('admin/publisher/book/sql/preview_genre.sql');

				$tree = $db->buildTree($result, 'genre_no');
				$genreList = array_merge($genreList, $tree);
			}

			asort($genreList);

			$this->genreList = $genreList;

			$this->bookDetail['book_genre_no'] = true;
		}
		else
		{
			$this->bookDetail['book_genre_no'] = false;
		}

		$this->bookDetail['book_date'] = $bookDetail['book_date'];
		$this->bookDetail['isbn'] = $bookDetail['isbn'];
		$this->bookDetail['magazine_code'] = $bookDetail['magazine_code'];
		$this->bookDetail['c_code'] = $bookDetail['c_code'];
		$this->bookDetail['version'] = $bookDetail['version'];
		$this->bookDetail['book_size_no'] = $bookDetail['book_size_no'];
		$this->bookDetail['page'] = $bookDetail['page'];
		$this->bookDetail['price'] = $bookDetail['price'];
		$this->bookDetail['cart_status'] = $bookDetail['cart_status'];
		$this->bookDetail['outline'] = $bookDetail['outline'];
		$this->bookDetail['explain'] = $bookDetail['explain'];
		$this->bookDetail['content'] = $bookDetail['content'];

		$this->bookDetail['ss_no'] = $bookDetail['stock_status'];;
		$this->bookDetail['stock_status'] = $bookDetail['stock_status'];
		if($bookDetail['book_year'])
		{
			$this->bookDetail['book_date'] = $bookDetail['book_year'].'/'.$bookDetail['book_month'].'/'.$bookDetail['book_day'];
			$this->bookDetail['b_stamp'] = $bookDetail['book_year'].'/'.$bookDetail['book_month'].'/'.$bookDetail['book_day'];
		}

		if($this->bookDetail['stock_status'])
		{
			$result = $db->statement('admin/publisher/book/sql/stock_status_list.sql');
			$tree = $db->buildTree($result, 'stock_status_no');
			$this->bookDetail['stock_status'] = $tree[$this->bookDetail['stock_status']]['name'];
		}

		if(isset($_REQUEST['news_relate_list']))
		{
			$db->assign('relateList', $bookDetail['book_relate_list']);

			//book relate book list
			$result = $db->statement('admin/publisher/book/sql/preview_relate_book.sql');
			$tree = $db->buildTree($result, 'book_no');

			// 並び順反映
			$bookRelateBookList = array();
			foreach ($bookDetail['book_relate_list'] as $k => $v) {
				$bookRelateBookList[$v] = $tree[$v];
			}
			$this->bookRelateBookList = $bookRelateBookList;

			$db->assign('bookRelateBookList', $bookRelateBookList);
			$bookNoList=array();
			foreach($bookRelateBookList as $b)
				$bookNoList[] = $b['book_no'];
			$db->assign('bookNoList', $bookNoList);

			//opus list
			$result = $db->statement('admin/publisher/book/sql/opus_list.sql');
			$tree = $db->buildTree($result, 'opus_no');
			$this->opusList = $tree;
		}
		else
		{
			$this->bookRelateBookList = NULL;
		}

		//titleへ追加
		$this->title = $this->bookDetail['name'] . " - " . $title;

		//出版社名にdesignが含まれていれば、デザインテンプレートを使用
		//デザイン取得
		$result = $db->statement('admin/publisher/book/sql/publisher.sql');
		$row = $db->fetch_assoc($result);
		$design = $row['design'];

		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/publisher/{$_SESSION['id']}/book/detail.html")) {
			return "/publisher/{$_SESSION['id']}/book/detail";
		} else {
			$this->commonPublisher['url'] = "https://www.hondana.jp/publisher/{$design}/";
			return "/publisher/{$design}/book/detail";
		}
	}

	function getSqlPath() {
		return 'publisher/core/';
	}
	function getActibook ($book) {
		$actibook = sprintf('/files/actibook/%s/_SWF_Window.html', $book['book_no']);
		if(file_exists(sprintf('%s/%s%s', $_SERVER['DOCUMENT_ROOT'] . "/publisher", $this->commonPublisher['id'], $actibook))) {
                    if($_REQUEST['clear_actibook']){
                        return false;
                    }else{
			return $actibook;
                    }

		} else {
                    if($_REQUEST['actibook']){
                        return $actibook;
                    } else {
                        return false;
                    }
		}
	}
}
?>