<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/JPOController.php');

class Action extends AuthAction {

	var $sqlDirectoryPath = "admin/publisher/book/sql/";

	function prepare() {
		$siteroot = $_SERVER['DOCUMENT_ROOT'];
		$this->_uploader = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/img/uploads/book',
			$siteroot
		);

		$this->_uploaderAB = new Uploader(
			$siteroot . '/admin/images/tmp',
			$siteroot . '/web/files/uploads/actibook',
			$siteroot
		);

		$this->jpoSyncTime = "6";

		// other auth type no
		$this->other_auth = 16;

		// thumb size
		$this->width = 1000;
		$this->height = 2000;
		//グレートサイズのフラグが立っている出版社の場合は、大きさを変更
		if($this->publisher['great_img_status'] == 1) {
			$this->width = 1000;
			$this->height = 2000;
		}

		if(empty($_REQUEST["livOld"])){
			if(!isset($_REQUEST['new_status'])){
				$this->new_status = NULL;
			}
			if(!isset($_REQUEST['next_book'])){
				$this->next_book = NULL;
			}
			if(!isset($_REQUEST['recommend_status'])){
				$this->recommend_status = NULL;
			}
			if(!isset($_REQUEST['book_label_list'])){
				$this->book_label_list = NULL;
			}
			if(!isset($_REQUEST['book_genre_list'])){
				$this->book_genre_list = NULL;
			}
			if(!isset($_REQUEST['book_series_list'])){
				$this->book_series_list = NULL;
			}
			if(!isset($_REQUEST['opus_list'])){
				$this->opus_list = NULL;
				$this->author_type_list = NULL;
			}
			if(!isset($_REQUEST['news_relate_list'])){
				$this->news_relate_list = NULL;
			}
			if(!isset($_REQUEST['book_format_list'])){
				$this->book_format_list = NULL;
			}
			if(!isset($_REQUEST['public_year'])){
				$this->public_year = NULL;
			}
			if(!isset($_REQUEST['public_month'])){
				$this->public_month = NULL;
			}
			if(!isset($_REQUEST['public_day'])){
				$this->public_day = NULL;
			}
			if(!isset($_REQUEST['public_hour'])){
				$this->public_hour = NULL;
			}
			if(!isset($_REQUEST['public_minute'])){
				$this->public_minute = NULL;
			}
			if(!isset($_REQUEST['public_second'])){
				$this->public_second = NULL;
			}
			if(!isset($_REQUEST['recommend_order'])){
				$this->recommend_order = NULL;
			}
			if(!isset($_REQUEST['recommend_sentence'])){
				$this->recommend_sentence = NULL;
			}
			if(!isset($_REQUEST['honzuki'])){
				$this->honzuki = NULL;
			}
		}

		$properties = $this->_uploader->getProperties();
		$this->setProperties($properties);

		// 画像処理
		$key = 'book'.$this->upkey;
		if (empty($this->{$key}['http_path'])) {
			// 書影がアップロードされていない
			$this->book_httppath = null;
		}elseif($this->{$key}["size"] > 10485760){
			// 書影サイズが10MBを超えている
			$this->{$key} = array();
			$this->book_httppath = null;
		}else{
			$this->book_httppath = $this->{$key}["http_path"];
			$temp_path = $this->_uploader->getTemporaryPath($key);
		}

		if($this->publisher['recommend_type'] == 2) {
			$key = 'recommend_image';
			if ($this->recommend_image['http_path']) {
				$temp_path = $this->_uploader->getTemporaryPath($key);
				$command = sprintf('convert -thumbnail 694x\> -gravity center -crop 694x228+0+0 %s %s', $temp_path, $temp_path);
				exec($command);
			}
		}

		// フリー項目
		$this->publisher['free'] = array();
		if ($this->publisher['freeitem']) {
			$this->publisher['free'] = $this->unserialize($this->publisher['freeitem']);
			foreach($this->publisher["free"] as $k => $v){
				if(empty($v))
					unset($this->publisher['free'][$k]);
			}
		}

		$db =& $this->_db;
		$db->setSqlDirectoryPath($this->sqlDirectoryPath);
		$db->assign('publisher_no', $_SESSION['publisher_no']);

		$this->sitelist = $db->statementTree('get_sites', 'sites_no');
		$this->labelList = $db->statementTree('label_list', 'label_no');
		$this->genreList = $db->statementTree('genre_list', 'genre_no');
		$this->seriesList = $db->statementTree('series_list', 'series_no');

		// 関連書籍 書籍情報を取得
		if(isset($this->news_relate_list)) {
			$db->assign('booknolist',implode(",", $this->news_relate_list));
			$bookviewlist = $db->statementTree("get_relate_book", 'book_no');
			$bookarraylist = array();
			// 関連書籍 並び順反映
			foreach ($this->news_relate_list as $k => $v) {
				$bookarraylist[] = $bookviewlist[$v];
			}
			$this->bookviewlist = $bookarraylist;
			$row = $db->statementFetch('recommend_count');
			$this->recommend_count = $row['recommend_count'];
		}

		$this->keyword = mb_ereg_replace('　',' ',$this->keyword);
	}

	function validate() {
		$db =& $this->_db;

		$this->convert($this->book_year,"n");
		$this->convert($this->book_month,"n");
		$this->convert($this->book_day,"n");
		$bookDateString = $this->book_year.$this->book_month.$this->book_day;
		$this->book_date = $this->book_year.'-'.$this->book_month.'-'.$this->book_day;

		// 初版発行日
		$this->convert($this->fei_year,"n");
		$this->convert($this->fei_month,"n");
		$this->convert($this->fei_day,"n");
		$feiDateString = $this->fei_year.$this->fei_month.$this->fei_day;
		$this->first_edition_issue_date = $this->fei_year.'-'.$this->fei_month.'-'.$this->fei_day;
		if($this->first_edition_issue_date == '--') {
			$this->first_edition_issue_date = null;
		}

		$this->convert($this->release_year,"n");
		$this->convert($this->release_month,"n");
		$this->convert($this->release_day,"n");
		$releaseDateString = $this->release_year.$this->release_month.$this->release_day;
		$this->release_date = $this->release_year.'-'.$this->release_month.'-'.$this->release_day;

		$this->convert($this->public_year,"n");
		$this->convert($this->public_month,"n");
		$this->convert($this->public_day,"n");
		$this->convert($this->public_hour,"n");
		$this->convert($this->public_minute,"n");
		$this->convert($this->public_second,"n");
		$publicDateString = $this->public_year.$this->public_month.$this->public_day.
			$this->public_hour.$this->public_minute.$this->public_second;
		$this->public_date = $this->public_year.'-'.$this->public_month.'-'.$this->public_day.' '.
			$this->public_hour.':'.$this->public_minute.':'.$this->public_second;

		$jpo = new JPOController();
		$this->errors = array();
		$this->errors_jpo = array();
		$this->attentions_jpo = array();

		if (!$this->name) {
			$this->errors['name'] = '書名の記入をご確認ください。';
		}

		$this->convert($this->kana,"KVC");
		if (!$this->kana) {
			$this->errors_jpo["kana"] = "JPO連携には書名カナが必要です。";
		}elseif(!$this->isKatakana($this->kana)){
			$this->errors['kana'] = '書名カナの記入は全角カタカナでお願いいたします。';
		}

		$this->convert($this->sub_kana,"KVC");
		if (!$this->sub_kana) {
		}elseif(!$this->isKatakana($this->sub_kana)){
			$this->errors['sub_kana'] = 'サブタイトルカナの記入は全角カタカナでお願いいたします。';
		}

		$this->convert($this->isbn,"rn");
		if (!$this->isbn) {
			$this->errors_jpo["isbn"] = "JPO連携には13桁のISBNが必要です。";
		}elseif(!preg_match('/^([0-9]{9}[0-9X]{1}|[0-9]{12}[0-9X]{1})$/u', $this->isbn) && (!$this->publisher["jpo"] || !$this->jpoSync)){
			$this->errors['isbn'] = 'ISBNの記入は半角10桁か13桁でお願いいたします。';
		}elseif(!preg_match('/^([0-9]{12}[0-9X]{1})$/u', $this->isbn) && ($this->publisher["jpo"] && $this->jpoSync)){
			$this->errors_jpo['isbn'] = 'JPO連携の際はISBNの記入は半角13桁でお願いいたします。';
		}elseif(!preg_match('/^([0-9]{12}[0-9X]{1})$/u', $this->isbn)){
			$this->errors_jpo['isbn'] = 'JPO連携には13桁のISBNが必要です。';
		}elseif(! $jpo->isClearISBN($this->isbn)){
			$this->errors_jpo['isbn'] = 'ISBNの上3桁かチェックデジットに問題があります。ISBNの内容をご確認下さい。';
		}else{
			$db =& $this->_db;
			$result = $db->query("select * from book where isbn='{$this->isbn}' and sync_allowed is not null limit 1;");
			if($row = $db->fetch_assoc($result)) {
				$duplicate = $row;
				if($duplicate["publisher_no"]==$this->publisher['publisher_no']){
					$this->errors_jpo['isbn'] = "『{$duplicate["name"]}』とISBNが重複しています。JPO連携する場合には重複しないISBNを登録して下さい。";
				}else{
					$this->errors_jpo['isbn'] = "ISBNに問題があります。JPO連携できません。";
				}
			}
		}
		$this->convert($this->e_isbn,"rn");
		if (!$this->e_isbn) {
		}elseif(!preg_match('/^[0-9]*$/u', $this->e_isbn)){
			$this->errors['e_isbn'] = 'eISBNの記入は半角数字ハイフンなしでお願いいたします。';
		}

		$this->convert($this->magazine_code_1,"n");
		$this->convert($this->magazine_code_2,"n");
		if (!$this->magazine_code_1 || !$this->magazine_code_2) {
		}elseif(!preg_match('/^[0-9]{5}$/u', $this->magazine_code_1)){
			$this->errors['magazine_code'] = '雑誌コードの記入は半角数字5桁と2桁でお願いいたします。';
		}elseif(!preg_match('/^[0-9]{2}$/u', $this->magazine_code_2)){
			$this->errors['magazine_code'] = '雑誌コードの記入は半角数字5桁と2桁でお願いいたします。';
		}elseif(!preg_match('/^[0-9]{7}$/u', $this->magazine_code_1.$this->magazine_code_2)){
			$this->errors['magazine_code'] = '雑誌コードの記入は半角数字5桁と2桁でお願いいたします。';
		}

		// Cコード
		$this->convert($this->c_code,"n");
		if (!$this->c_code) {
			$this->errors_jpo["c_code"] = "JPO連携にはCコードが必要です。";
		}elseif(!preg_match('/^[0-9]{4}$/u', $this->c_code)){
			$this->errors['c_code'] = 'Cコードの記入は半角数字4桁でお願いいたします。';
		} elseif($this->jpoSync) {
			// Cコードで 3桁目、4桁目を除外するコードリスト
			// https://goo.gl/5kvqDM
			$ignore_ccode = array(
				'03', '05', '06', '07', '08', '09',
				'13', '17', '18', '19',
				'24', '27', '28', '29',
				'35', '38',
				'46', '48', '49',
				'59',
				'64', '66', '67', '68', '69',
				'83', '86', '88', '89',
				'94', '96', '99',
			);
			if(preg_match('/(' . implode('|',$ignore_ccode) . ')$/', $this->c_code)) {
				$this->errors_jpo['c_code'] = '[JPO連携チェック] 正しい形式のCコードを入力してください。';
			}
		}

		// 商品コード
		$this->convert($this->product_code,"n");
		if (!$this->product_code) {
		}elseif(!preg_match('/^[0-9a-zA-Z_]+$/u', $this->product_code)){
			$this->errors['product_code'] = '商品コードの記入は半角英数でお願いいたします。';
		}

		// JANコード
		$this->convert($this->jan_code,"n");
		if (!$this->jan_code) {
		}elseif(!preg_match('/^[0-9]{13}$/u', $this->jan_code)){
			$this->errors['jan_code'] = 'JANコードの記入は半角数字13桁でお願いいたします。';
		} elseif($this->jpoSync && !preg_match('/^4[58][0-9]{11}$/u', $this->jan_code)) {
			$this->errors['jan_code'] = '[JPO連携チェック] JANコードは45または49から始まる13ケタでご入力ください。';
		}

		if (!$bookDateString) {
		}elseif(!$this->isDate($this->book_year , $this->book_month , $this->book_day)){
			$this->errors['book_date'] = '出版年月日が正しくありません。ご確認ください。';
		}

		// 初版発行日
		if (!$feiDateString) {
		} elseif(empty($this->fei_year) || empty($this->fei_month) || empty($this->fei_day)){
			$this->errors['fei_date'] = '初版発行日が正しくありません。ご確認ください。';
		} elseif(!$this->isDate($this->fei_year , $this->fei_month , $this->fei_day)){
			$this->errors['fei_date'] = '初版発行日が正しくありません。ご確認ください。';
		}

		// 初版発行部数
		if (!$this->first_edition_circulation_number) {
		} elseif(!$this->isNumber($this->first_edition_circulation_number)) {
			$this->errors['first_edition_circulation_number'] = '初版発行部数は 半角数字でご入力ください。';
		}

		// 書店発売日
		if (!$releaseDateString) {
			$this->errors_jpo["release_date"] = "JPO連携には書店発売日が必要です。";
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->release_year)){
			$this->errors['release_date'] = '書店発売日の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->release_year , $this->release_month , $this->release_day)){
			$this->errors['release_date'] = '書店発売日が正しくありません。ご確認ください。';
		// ** JPRO フェーズ2 で既刊も取り扱うようになった
		// }elseif($jpo->isAfterReleaseDate ($this->release_date, $this->jpoSyncTime)){
		// 	$this->errors_jpo["release_date"] = "書店発売日がJPO連携の対象範囲外です。";
		}elseif($jpo->isBeforeReleaseDate($this->release_date, $this->jpoSyncTime)){
			$this->attentions_jpo["release_date"] = "書店発売日がJPO連携の対象範囲外です。期日までは連携されません。";
		}

		if (!$this->version) {
		}elseif($this->version){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}

		if (!$this->book_size_no) {
			// 判型の入力は必須じゃなくなった？？
			// $this->errors_jpo["book_size_no"] = "JPO連携には判型が必要です。";
		// *** [todo]20180821判型実寸の入力が必須になったけど、JPROのバリデートは通るので 一旦解除 if文の条件も変な気がする・・・ ****
		// }elseif(empty($this->measure_height) && empty($this->measure_width) && empty($this->measure_thickness)){
		// 	$this->errors_jpo["measure"] = "JPO連携には判型を選択時 実寸の入力が必要です。";
		}

		// 初版予定部数
		$iname = '初版予定部数';
		$key = 'number_of_first_edition';
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		} elseif(!$this->isNumber($this->{$key})) {
			$this->errors[$key] = $iname . 'の記入は半角数字でお願いいたします。';
		}

		$this->convert($this->book_width,"n");
		if (!$this->book_width) {
		}elseif(!preg_match('/^[0-9]{1,9}$/u', $this->book_width)){
			$this->errors['book_width'] = '束幅の記入はmm単位、半角数字9桁以内でお願いいたします。';
		}
		$this->convert($this->page,"n");
		if (!$this->page) {
		}elseif(!preg_match('/^[0-9]{1,9}$/u', $this->page)){
			$this->errors['page'] = 'ページ数の記入は半角数字9桁以内でお願いいたします。';
		}elseif(!preg_match('/^[0-9]{1,6}$/u', $this->page)){
			$this->errors_jpo['page'] = 'ページ数の記入は半角数字6桁以内でお願いいたします。';
		}
		if (!$this->copyright) {
		}elseif( !mb_ereg_match('^.{0,100}$', mb_ereg_replace('\n|\r', '', $this->copyright)) ){
			$this->errors['copyright'] = 'コピーライトは100文字以内でご記入ください。';
		}
		$this->convert($this->price,"n");
		if (!$this->price) {
			$this->errors_jpo["price"] = "JPO連携には本体価格が必要です。";
		}elseif(!preg_match('/^[0-9]{1,9}$/u', $this->price)){
			$this->errors['price'] = '本体価格の記入は半角数字9桁以内でお願いいたします。';
		}
		$this->convert($this->ebook_price,"n");
		if (!$this->ebook_price) {
		}elseif(!preg_match('/^[0-9]{1,9}$/u', $this->ebook_price)){
			$this->errors['ebook_price'] = '電子版価格の記入は半角数字9桁以内でお願いいたします。';
		}
		if (!$this->outline) {
		}elseif( !mb_ereg_match('^.{0,62}$', mb_ereg_replace('\n|\r', '', $this->outline)) ){
			$this->errors['outline'] = '概要（長文）は62文字以内でご記入ください。';
		}
		if (!$this->outline_abr) {
		}elseif( !mb_ereg_match('^.{0,20}$', mb_ereg_replace('\n|\r', '', $this->outline_abr)) ){
			$this->errors['outline_abr'] = '概要（短文）は20文字以内でご記入ください。';
		}
		if (!$this->keyword) {
		}elseif( !mb_ereg_match('^.{0,130}$', $this->keyword) ){
			$this->errors['keyword'] = 'キーワードは130文字以内でご記入ください。';
		}elseif( !mb_ereg_match('^.{0,250}$', $this->keyword) ){
			$this->errors_jpo["keyword"] = "JPO連携には250文字以内のキーワードが必要です。";
		}

		if (!$this->public_status!='') {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}

		if (!$publicDateString) {
		}elseif(!$this->isDateTime($this->public_date)){
			$this->errors['public_date'] = '公開日時の記入をご確認ください。';
		}elseif(!$this->isDate($this->public_year , $this->public_month , $this->public_day)){
			$this->errors['public_date'] = '公開日付が正しくありません。ご確認ください。';
		}

		$this->convert($this->public_date_order,"n");
		if ($this->public_date_order=='') {
		}elseif(!preg_match('/^[0-9]*$/u', $this->public_date_order)){
			$this->errors['public_date_order'] = '同一日付表示順の記入は半角数字でお願いいたします。';
		}
		if (!$this->new_status!='') {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}
		if (!$this->next_book!='') {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}
		if ($this->recommend_status=='') {
		}elseif($this->recommend_status=='1' && $this->recommend_count >= 10){
			$this->errors['recommend_status'] = '既に10冊の書誌がおすすめに設定されています。';
		}
		$this->convert($this->recommend_order,"n");
		if ($this->recommend_order=='') {
		}elseif(!preg_match('/^[0-9]*$/u', $this->recommend_order)){
			$this->errors['recommend_order'] = 'おすすめ表示順の記入は半角数字でお願いいたします。';
		}
		if($this->recommend_status=='1' && $this->publisher['recommend_type'] == 2) {
			if (!isset($this->recommend_image['http_path'])) {
				$this->errors['recommend_image'] = 'おすすめ用の画像を登録してください。';
			}
		}
		if (!$this->stock_status_no) {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}
		if (!$this->cart_status) {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}
		if (!$this->news_relate_no) {
		}elseif($this->aaa){
//			$this->errors['aaa'] = '著者名カナの選択をご確認ください。';
		}

		// 特設サイト
		$keyimg = 'spsiteimg'.$this->upkey;
		$keytext = 'spsite'.$this->upkey;
		if(count($this->{$keytext}) >= 2 || !empty($this->{$keyimg})) {
			// 複数件登録があるか、1つ目に画像が設定されている
			foreach ($this->{$keytext} as $k => $v) {
				if(empty($v)) {
					$this->errors['spsite'] = 'URLが入力されていないセットがあります。';
				}
			}
		}

		// JPRO用著者区分 初期化
		$this->jpo_author_type_list = null;
		if(isset($_REQUEST['jpo_author_type_list'])) {
			$this->jpo_author_type_list = $_REQUEST['jpo_author_type_list'];
		}

		$this->errors_author_jpo=array();
		if(!$this->opus_list){
			// $this->errors_jpo["author"] = "JPO連携には著者が必要です。";
		}else{
			$autherNameList = "";

			// 著者一覧取得
			$db->assign('opus_list',$this->opus_list);
			$this->author_data_list = array();
			$this->author_data_list =  $db->statementTree("admin/publisher/book/edit/sql/author_data_list.sql", 'author_no');
			foreach( $this->opus_list as $i => $val ){
				if(!$this->author_type_list[$i]){
					$this->errors['author'] = '著者タイプの選択をご確認ください。';
				}elseif($this->author_type_list[$i]==$this->other_auth && empty($this->author_type_other[$i])){
					$this->errors['author'] = '著者タイプの入力をご確認ください。';
				}elseif(empty($this->author_data_list[$val]["kana"])){
					$autherNameList .= '<br /><a href="/admin/publisher/author/edit/?author_no='.$val.'" target="_blank" style="color:#1B9662;">'.$this->author_data_list[$val]["name"].'</a>';
					$this->errors_jpo["author"] = "著者名カナの入力がない著者がJPO連携に設定されています。ご確認ください。";
				}elseif(!mb_ereg_match('^.{0,500}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->author_data_list[$val]["value"]))) {
					$this->errors_jpo["author"] = "JPO連携には著者プロフィールが500文字以内である必要がございます。(改行は&lt;br&gt;に置き換えられます。)";
				}elseif(preg_match('/<(?!p>|P>|br|BR|p |P |\/p>|\/P>)/', $this->author_data_list[$val]["value"])) {
					$this->errors_jpo["author"] = "JPO連携時は著者プロフィールに&lt;br&gt;タグと&lt;p&gt;タグ以外のタグは利用できません。";
				}elseif($this->author_type_list[$i] == $this->other_auth){
					if(empty($_REQUEST["livOld"])){
						$this->errors_author_jpo[] = $i;
					}
				}else{
					$type = $jpo->changeAuthorType($this->author_type_list[$i]);
					$this->jpo_author_type_list[$val] = $type;
				}
			}
			if(!empty($this->errors_jpo["author"] )) {
				$this->errors_jpo["author"] .= $autherNameList;
			}
		}
		if(!$this->news_relate_list){
		}elseif(count($this->news_relate_list) > 10){
//			$this->errors['book_relate'] = '関連書誌の選択は10冊以内でお願いいたします。';
		}


		/**
		 * JPO connection
		 */
		$key = "measure_height";
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		}elseif(!preg_match('/^[0-9]{1,6}$/u', $this->{$key})){
			$this->errors[$key] = '判型（実寸）タテの記入は半角数字6桁以内でお願いいたします。';
		}

		$key = "measure_width";
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		}elseif(!preg_match('/^[0-9]{1,6}$/u', $this->{$key})){
			$this->errors[$key] = '判型（実寸）ヨコの記入は半角数字6桁以内でお願いいたします。';
		}

		$key = "measure_thickness";
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		}elseif(!preg_match('/^[0-9]{1,6}$/u', $this->{$key})){
			$this->errors[$key] = '判型（実寸）厚さの記入は半角数字6桁以内でお願いいたします。';
		}

		$key = "imprint_collationkey";
		$this->convert($this->{$key},"KVC");
		if (!$this->{$key}) {
		}elseif(!$this->isKatakana($this->{$key})){
			$this->errors[$key] = 'レーベル名（カナ）の記入は全角カタカナでお願いいたします。';
		}

		$key = "each_volume_kana";
		$this->convert($this->{$key},"KVC");
		if (!$this->{$key}) {
		}elseif(!$this->isKatakana($this->{$key})){
			$this->errors[$key] = '各巻書名（カナ）の記入は全角カタカナでお願いいたします。';
		}

		$key = "extent_value";
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		}elseif(!preg_match('/^[1-9][0-9]{0,99}$/u', $this->{$key})){
			$this->errors[$key] = '配本回数の記入は半角数字でお願いいたします。';
		}

		$key = "price_amount";
		$this->convert($this->{$key},"n");
		if (!$this->{$key}) {
		}elseif(!preg_match('/^[0-9]{1,12}$/u', $this->{$key})){
			$this->errors[$key] = '特価本体価格の記入は半角数字12桁以内でお願いいたします。';
		}

		// **** JPROフェーズ2で使わなくなった？？ ****
		// $key = "price_effective_from";
		// $key1 = "{$key}_year";
		// $key2 = "{$key}_month";
		// $key3 = "{$key}_day";
		// $this->convert($this->{$key1},"n");
		// $this->convert($this->{$key2},"n");
		// $this->convert($this->{$key3},"n");
		// $clearFrom = false;
		// if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
		// 	if(!empty($this->price_amount)) {
		// 		$this->errors[$key] = '特価本体価格を入力時は特価期限（開始日）をご入力ください。';
		// 	}
		// }elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
		// 	$this->errors[$key] = '特価期限（開始日）の形式が正しくありません。ご確認ください。';
		// }elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
		// 	$this->errors[$key] = '特価期限（開始日）が正しくありません。ご確認ください。';
		// }else{
		// 	$clearFrom = strtotime($this->{$key1}."-".$this->{$key2}."-".$this->{$key3});
		// }

		$key = "price_effective_until";
		$key1 = "{$key}_year";
		$key2 = "{$key}_month";
		$key3 = "{$key}_day";
		$this->convert($this->{$key1},"n");
		$this->convert($this->{$key2},"n");
		$this->convert($this->{$key3},"n");
		$clearUntil = false;
		if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
			if(!empty($this->price_amount)) {
				$this->errors[$key] = '特価本体価格を入力時は特価期限をご入力ください。';
			}
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
			$this->errors[$key] = '特価期限の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
			$this->errors[$key] = '特価期限が正しくありません。ご確認ください。';
		}else{
			$clearUntil = strtotime($this->{$key1}."-".$this->{$key2}."-".$this->{$key3});
		}

		if($clearFrom && $clearUntil && $clearFrom > $clearUntil){
			$this->errors[$key] = '特価期限に開始日より前の終了日が指定されています。ご確認ください。';
		}

		$key = "Issued_date";
		$key1 = "{$key}_year";
		$key2 = "{$key}_month";
		$key3 = "{$key}_day";
		$this->convert($this->{$key1},"n");
		$this->convert($this->{$key2},"n");
		$this->convert($this->{$key3},"n");
		if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
			$this->errors[$key] = '発行年月日の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
			$this->errors[$key] = '発行年月日が正しくありません。ご確認ください。';
		}

		$key = "on_sale_date";
		$key1 = "{$key}_year";
		$key2 = "{$key}_month";
		$key3 = "{$key}_day";
		$this->convert($this->{$key1},"n");
		$this->convert($this->{$key2},"n");
		$this->convert($this->{$key3},"n");
		if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
			$this->errors[$key] = '発売協定日の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
			$this->errors[$key] = '発売協定日が正しくありません。ご確認ください。';
		}

		$key = "pre_order_limit";
		$key1 = "{$key}_year";
		$key2 = "{$key}_month";
		$key3 = "{$key}_day";
		$this->convert($this->{$key1},"n");
		$this->convert($this->{$key2},"n");
		$this->convert($this->{$key3},"n");
		if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
			$this->errors[$key] = '注文・申込締切の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
			$this->errors[$key] = '注文・申込締切が正しくありません。ご確認ください。';
		}

		$key = "announcement_date";
		$key1 = "{$key}_year";
		$key2 = "{$key}_month";
		$key3 = "{$key}_day";
		$this->convert($this->{$key1},"n");
		$this->convert($this->{$key2},"n");
		$this->convert($this->{$key3},"n");
		if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
		}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
			$this->errors[$key] = '発売情報解禁日の形式が正しくありません。ご確認ください。';
		}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
			$this->errors[$key] = '発売情報解禁日が正しくありません。ご確認ください。';
		}
		if($this->jpoSync) {
			$key = "recent_publication_date";
			$key1 = "{$key}_year";
			$key2 = "{$key}_month";
			$key3 = "{$key}_day";
			$this->convert($this->{$key1},"n");
			$this->convert($this->{$key2},"n");
			$this->convert($this->{$key3},"n");
			if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
			}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
				$this->errors[$key] = 'これ本掲載時期の形式が正しくありません。ご確認ください。';
			}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
				$this->errors[$key] = 'これ本掲載時期が正しくありません。ご確認ください。';
			}

			// 取次搬入予定日
			$key = "delivery_date";
			$key1 = "{$key}_year";
			$key2 = "{$key}_month";
			$key3 = "{$key}_day";
			$this->convert($this->{$key1},"n");
			$this->convert($this->{$key2},"n");
			$this->convert($this->{$key3},"n");
			if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
			}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
				$this->errors[$key] = '取次搬入予定日の形式が正しくありません。ご確認ください。';
			}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
				$this->errors[$key] = '取次搬入予定日が正しくありません。ご確認ください。';
			}

			// 返品期限・L表記
			$key = "return_deadline";
			$key1 = "{$key}_year";
			$key2 = "{$key}_month";
			$key3 = "{$key}_day";
			$this->convert($this->{$key1},"n");
			$this->convert($this->{$key2},"n");
			$this->convert($this->{$key3},"n");
			if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
			}elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
				$this->errors[$key] = '返品期限・L表記の形式が正しくありません。ご確認ください。';
			}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
				$this->errors[$key] = '返品期限・L表記が正しくありません。ご確認ください。';
			}

			// 児童図書ジャンル 制御
			if(!empty($this->subject_code) && $this->subject_code == 23) {
				// 対象読者
				$key = "audience_code_value";
				if (!$this->{$key}) {
					// 未入力
					$this->errors[$key] = '児童図書の場合は 対象読者をご選択ください。';
				}

				// 児童書詳細ジャンル
				$key = "childrens_book_genre";
				if (!$this->{$key}) {
					// 未入力
					$this->errors[$key] = '児童図書の場合は 児童書詳細ジャンルをご選択ください。';
				}

				// 本文活字の大きさ
				$key = "font_size";
				if (!$this->{$key}) {
					// 未入力
					$this->errors[$key] = '児童図書の場合は 本文活字の大きさをご入力ください。';
				}

				// ルビの有無
				$key = "ruby";
				if (!isset($this->{$key}) || $this->{$key} == '') {
					// 未入力
					$this->errors[$key] = '児童図書の場合は ルビの有無をご選択ください。';
				}
			}
		}

		if($this->jpoSync && $this->reselling == 3) {
			// 時限再販のとき

			// 再販 → 非再販 （時限再販）の日付のバリデーション
			$key = "reselling_date";
			$key1 = "{$key}_year";
			$key2 = "{$key}_month";
			$key3 = "{$key}_day";
			$this->convert($this->{$key1},"n");
			$this->convert($this->{$key2},"n");
			$this->convert($this->{$key3},"n");
			if (empty($this->{$key1})&&empty($this->{$key2})&&empty($this->{$key3})) {
				$this->errors['reselling'] = '時限再販の切り替え日をご入力ください。';
			} elseif(!preg_match ('/^[0-9]{4}$/u',$this->{$key1})){
				$this->errors['reselling'] = '時限再販の切り替え日の形式が正しくありません。ご確認ください。';
			}elseif(!$this->isDate($this->{$key1} , $this->{$key2} , $this->{$key3})){
				$this->errors['reselling'] = '時限再販の切り替え日が正しくありません。ご確認ください。';
			}

			// **** JPROフェーズ2で 判断しなくなった？？？ ****
			// if(empty($this->errors['reselling']) && !empty($this->price_amount)) {
			// 	// 時限再販のエラー無し かつ 特価本体価格入力時
			// 	$reselling_date = $this->reselling_date_year . '/' . $this->reselling_date_month . '/' . $this->reselling_date_day;
			// 	$price_effective_from = $this->price_effective_from_year . '/' . $this->price_effective_from_month . '/' . $this->price_effective_from_day;
			// 	$price_effective_until = $this->price_effective_until_year . '/' . $this->price_effective_until_month . '/' . $this->price_effective_until_day;
			// 	if($this->resellingdatecheck == 'before') {
			// 		if(strtotime($reselling_date) < strtotime($price_effective_until)) {
			// 			$this->errors['price_effective_until'] = '特価期限が時限再販切り替え日より後に設定されています。';
			// 		}
			// 	} elseif($this->resellingdatecheck == 'after') {
			// 		if(strtotime($reselling_date) > strtotime($price_effective_from)) {
			// 			$this->errors['price_effective_from'] = '特価期限が時限再販切り替え日より前に設定されています。';
			// 		}
			// 	}
			// }
		}

		// 読者書き込み
		$key = "readers_write";
		if (!$this->{$key}) {
			// 未入力
		} else {
			// 入力がある
			if(empty($this->readers_write_page)){
				$this->errors[$key] = '読者書き込みページ数をご入力ください。';
			}
		}
		if(empty($this->readers_write_page)){
		} elseif(!$this->isNumber($this->readers_write_page)) {
			$this->errors[$key] = '読者書き込みページ数は 半角数字でご入力ください。';
		}

		// 本文活字の大きさ
		$key = "font_size";
		if (!$this->{$key}) {
			// 未入力
		} elseif(!$this->isNumber($this->{$key})) {
			$this->errors[$key] = '本文活字の大きさは 半角数字でご入力ください。';
		}

		// マンガの割合
		$key = "percentage_of_manga";
		if (!$this->{$key}) {
			// 未入力
		} elseif(!$this->isNumber($this->{$key})) {
			$this->errors[$key] = 'マンガの割合は 半角数字でご入力ください。';
		}

		// 重量
		$key = "weight";
		if (!$this->{$key}) {
			// 未入力
		} elseif(!$this->isNumber($this->{$key})) {
			$this->errors[$key] = '重量は 半角数字でご入力ください。';
		}

		// 結束数
		$key = "number_of_cohesions";
		if (!$this->{$key}) {
			// 未入力
		} elseif(!preg_match('/^[1-9][0-9]$/', $this->{$key})) {
			$this->errors[$key] = '結束数は 半角数字2文字でご入力ください。';
		}

		$key = "long_description";
		if (!$this->{$key}) {
		} elseif( !mb_ereg_match('^.{0,1300}$', $this->{$key}) ){
			$this->errors[$key] = '内容紹介2は1300文字以内でご記入ください。';
		} elseif($this->jpoSync && preg_match('/<(?!p>|P>|br|BR|p |P |\/p>|\/P>)/', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))) {
			$this->errors[$key] = '内容紹介2には<br>タグと<p>タグのみを利用可能です。(改行は<br>に置き換えられます。)';
		}

		$key = "product_form_description";
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,200}$', $this->{$key}) ){
			$this->errors[$key] = '付録の内容は200文字以内でご記入ください。';
		}

		$key = "recent_publication_author";
		if (!$this->{$key}) {
		} elseif( !mb_ereg_match('^.{0,40}$', $this->{$key}) ){
			$this->errors[$key] = '著者表示は40文字以内でご記入ください。';
		} elseif($this->jpoSync) {
			$sumtext = $this->{$key} . $this->recent_publication_content;
			if(strlen($sumtext) > 206) {
				$this->errors[$key] = '「著者表示」と「内容紹介」は合計68文字以内でご入力ください';
				$this->errors['recent_publication_content'] = '「著者表示」と「内容紹介」は合計68文字以内でご入力ください';
			}
		}

		$key = "recent_publication_content";
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,63}$', $this->{$key}) ){
			$this->errors[$key] = '内容紹介は63文字以内でご記入ください。';
		}

		$key = "promotional_text";
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,9999}$', $this->{$key}) ){
//			$this->errors[$key] = 'その他出版社記入欄は9999文字以内でご記入ください。';
		}

		// 内容紹介 図書館選書用
		$key = "library_selection_content";
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,105}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))){
			$this->errors[$key] = '内容紹介 図書館選書用は105文字以内でご記入ください。(改行は<br>に置き換えられます。)';
		}

		// 担当者コメント
		$key = "comments";
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,200}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))){
			$this->errors[$key] = '担当者コメントは200文字以内でご記入ください。(改行は<br>に置き換えられます。)';
		}

		// 帯内容
		$key = "band_contents";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))){
			$this->errors[$key] = '帯内容は' . $moji . '文字以内でご記入ください。(改行は<br>に置き換えられます。)';
		}

		// 類書・競合書
		$key = "competition";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))){
			$this->errors[$key] = '類書・競合書は' . $moji . '文字以内でご記入ください。(改行は<br>に置き換えられます。)';
		}

		// 別送資料
		$key = "separate_material";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', preg_replace('/(\r\n|\r|\n)/', '<br>',$this->{$key}))){
			$this->errors[$key] = '別送資料は' . $moji . '文字以内でご記入ください。(改行は<br>に置き換えられます。)';
		}

		// 装丁者名
		$key = "bond_name";
		$moji = 10;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', $this->{$key}) ){
			$this->errors[$key] = '装丁者名は' . $moji . '文字以内でご記入ください。';
		}

		// 特殊な装丁
		$key = "special_binding";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', $this->{$key}) ){
			$this->errors[$key] = '特殊な装丁は' . $moji . '文字以内でご記入ください。';
		}

		// しかけの有無
		$key = "special_binding";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', $this->{$key}) ){
			$this->errors[$key] = 'しかけの有無は' . $moji . '文字以内でご記入ください。';
		}

		// 製本所
		$key = "binding_place";
		$moji = 100;
		if (!$this->{$key}) {
		}elseif( !mb_ereg_match('^.{0,' . $moji . '}$', $this->{$key}) ){
			$this->errors[$key] = '製本所は' . $moji . '文字以内でご記入ください。';
		}

		/**
		 * ActiBook
		 */
		$up =& $this->_uploaderAB;
		$key = 'actibook';
		if(isset($this->actibook['http_path'])) {
			$temp_path = $up->getTemporaryPath($key);
			$command = sprintf('zipinfo -1 %s', $temp_path);

			$rs = array();
			exec($command,$rs);

//			if($this->actibook["size"] > 157286400){
			if($this->actibook["size"] > 51200000){
				$this->errors['actibook'] = 'ファイルサイズは50メガバイト以内でお願いいたします。';
			}elseif(strpos($rs[0], "actibook/")===false){
				$this->errors['actibook'] = '圧縮するフォルダの名前は"actibook"でお願いいたします。';
			}else{
				$filenames = implode("", $rs);
				if(strpos($filenames,  "_SWF_Window.html") === false){
					$this->errors['actibook'] = 'ActiBookファイルが正しくありません。';
				}

			}
		}


		if(!$this->publisher["jpo"] || !$this->jpoSync){
			unset($this->errors_jpo);
			unset($this->errors_author_jpo);
		}

		if(isset($this->errors) && count($this->errors)) {
			return 'input';
		}else if(isset($this->errors_jpo) && count($this->errors_jpo)) {
			return 'input';
		}else if(isset($this->errors_author_jpo) && count($this->errors_author_jpo)){
			return 'author_input';
		}

		if($bookDateString == ''){
			$this->book_date = '0000-00-00';
		}
		if($releaseDateString == ''){
			$this->release_date = '0000-00-00';
		}
		if($publicDateString == ''){
			$this->public_date = '0000-00-00 00:00:00';
		}


		$this->magazine_code = $this->magazine_code_1.$this->magazine_code_2;

		$this->book_relate_list = $this->news_relate_list;

		return true;
	}
}
?>
