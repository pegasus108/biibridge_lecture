<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Uploader.php');

class Action extends AuthAction {
	function prepare(){


	}

	function validate() {

		$this->errors = array();
		$d = $this->getErrorMessages();

		if (!$this->data_type_1) {
			$this->errors['data_type_1'] = '選択をご確認ください。';
		}

		$this->convert($this->isbn_1,"rna");
		if (!$this->isbn_1) {
			$this->errors['isbn_1'] = '記入をご確認ください。';
		}elseif( $this->isbn_1 == $this->new['isbn_1'] ){
			$this->errors['isbn_1'] = 'ハイフンを含めてご記入ください。';
		}elseif(!mb_ereg_match('^([0-9-]{11}-[0-9X]{1}|[0-9]{3}-[0-9-]{11}-[0-9X]{1})$', $this->isbn_1)){
			$this->errors['isbn_1'] = '半角13桁か17桁でご記入ください。';
		} elseif($this->isGarbled($this->isbn_1)) {
			$this->errors['isbn_1'] = $d['garbled'];
		}

		if($this->data_type_1 == '1' || $this->data_type_1 == '4' || $this->data_type_1 == '5'){

			$this->convert($this->category_1,"n");
			if (!$this->category_1) {
			}elseif( !mb_ereg_match('^[0-9]{4}$', $this->category_1) ){
			$this->errors['category_1'] = '半角数字4桁でご記入ください。';
			} elseif($this->isGarbled($this->category_1)) {
				$this->errors['category_1'] = $d['garbled'];
			}

			if (!$this->name_1) {
				$this->errors['name_1'] = '記入をご確認ください。';
			} elseif($this->isGarbled($this->name_1)) {
				$this->errors['name_1'] = $d['garbled'];
			}

			$this->convert($this->kana_1,"KVC");
			if (!$this->kana_1) {
				$this->errors['kana_1'] = '記入をご確認ください。';
			} elseif(!$this->isKatakana($this->kana_1)) {
				$this->errors['kana_1'] = '全角カタカナでご入力ください';
			} elseif($this->isGarbled($this->kana_1)) {
				$this->errors['kana_1'] = $d['garbled'];
			}

			if(!$this->version_1) {
			} elseif($this->isGarbled($this->version_1)) {
				$this->errors['version_1'] = $d['garbled'];
			}

			if(!$this->series_1) {
			} elseif($this->isGarbled($this->series_1)) {
				$this->errors['series_1'] = $d['garbled'];
			}
			$this->convert($this->series_kana_1,"KVC");

			$this->author_1 = trim(mb_convert_kana($this->author_1, "s"));
			$this->convert($this->author_1,"S");
			$this->author_1 = mb_ereg_replace(",","，",$this->author_1);
			if(!$this->author_1) {
			} elseif($this->isGarbled($this->author_1)) {
				$this->errors['author_1'] = $d['garbled'];
			}
			$this->convert($this->author_kana_1,"KVC");
			$this->author_kana_1 = trim(mb_convert_kana($this->author_kana_1, "s"));
			$this->convert($this->author_kana_1,"S");
			$this->author_kana_1 = mb_ereg_replace(",","，",$this->author_kana_1);
			if(!$this->author_kana_1) {
			} elseif($this->isGarbled($this->author_kana_1)) {
				$this->errors['author_kana_1'] = $d['garbled'];
			}
			if(!$this->author_type_1) {
			} elseif($this->isGarbled($this->author_type_1)) {
				$this->errors['author_type_1'] = $d['garbled'];
			}

			$this->author_2 = trim(mb_convert_kana($this->author_2, "s"));
			$this->convert($this->author_2,"S");
			$this->author_2 = mb_ereg_replace(",","，",$this->author_2);
			if(!$this->author_2) {
			} elseif($this->isGarbled($this->author_2)) {
				$this->errors['author_2'] = $d['garbled'];
			}
			$this->convert($this->author_kana_2,"KVC");
			$this->author_kana_2 = trim(mb_convert_kana($this->author_kana_2, "s"));
			$this->convert($this->author_kana_2,"S");
			$this->author_kana_2 = mb_ereg_replace(",","，",$this->author_kana_2);
			if(!$this->author_kana_2) {
			} elseif($this->isGarbled($this->author_kana_2)) {
				$this->errors['author_kana_2'] = $d['garbled'];
			}
			if(!$this->author_type_2) {
			} elseif($this->isGarbled($this->author_type_2)) {
				$this->errors['author_type_2'] = $d['garbled'];
			}

			$this->author_3 = trim(mb_convert_kana($this->author_3, "s"));
			$this->convert($this->author_3,"S");
			$this->author_3 = mb_ereg_replace(",","，",$this->author_3);
			if(!$this->author_3) {
			} elseif($this->isGarbled($this->author_3)) {
				$this->errors['author_3'] = $d['garbled'];
			}
			$this->convert($this->author_kana_3,"KVC");
			$this->author_kana_3 = trim(mb_convert_kana($this->author_kana_3, "s"));
			$this->convert($this->author_kana_3,"S");
			$this->author_kana_3 = mb_ereg_replace(",","，",$this->author_kana_3);
			if(!$this->author_kana_3) {
			} elseif($this->isGarbled($this->author_kana_3)) {
				$this->errors['author_kana_3'] = $d['garbled'];
			}
			if(!$this->author_type_3) {
			} elseif($this->isGarbled($this->author_type_3)) {
				$this->errors['author_type_3'] = $d['garbled'];
			}

			$this->convert($this->book_date_1_year,"n");
			$this->convert($this->book_date_1_month,"n");
			if (!($this->book_date_1_year.$this->book_date_1_month)) {
				$this->errors['book_date_1'] = '記入をご確認ください。';
			}elseif ( !mb_ereg_match('^[0-9]{6}$', $this->book_date_1_year.$this->book_date_1_month) ) {
				$this->errors['book_date_1'] = '記入形式をご確認ください。';
			}elseif ( !$this->isFormatedMonthDate($this->book_date_1_year.$this->book_date_1_month) ) {
				$this->errors['book_date_1'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->book_date_1_year.$this->book_date_1_month)) {
				$this->errors['book_date_1'] = $d['garbled'];
			}

			$this->convert($this->release_date_1_year,"n");
			$this->convert($this->release_date_1_month,"n");
			$this->convert($this->release_date_1_day,"n");
			if (!($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day)) {
	//			$this->errors['release_date_1'] = '記入をご確認ください。';
			}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day) ) {
				$this->errors['release_date_1'] = '記入形式をご確認ください。';
			}elseif ( !$this->isFormatedDate($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day) ) {
				$this->errors['release_date_1'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->release_date_1_year.$this->release_date_1_month.$this->release_date_1_day)) {
				$this->errors['release_date_1'] = $d['garbled'];
			}

			$this->convert($this->book_size_2_other_l,"n");
			$this->convert($this->book_size_2_other_r,"n");
			if (!$this->book_size_2) {
			}elseif($this->book_size_2=='x'){
				if( !(mb_ereg_match('^[0-9]+$',$this->book_size_2_other_l)&&mb_ereg_match('^[0-9]+$',$this->book_size_2_other_r)) ){
					$this->errors['book_size_2'] = 'その他の詳細は半角数字でご記入ください。';
				}
			} elseif($this->isGarbled($this->book_size_2)) {
				$this->errors['book_size_2'] = $d['garbled'];
			}

			$this->convert($this->page_1,"n");
			if (!$this->page_1) {
			}elseif(!preg_match('/^[0-9]*$/u', $this->page_1)){
				$this->errors['page_1'] = '半角数字でご記入ください。';
			} elseif($this->isGarbled($this->page_1)) {
				$this->errors['page_1'] = $d['garbled'];
			}

			$this->convert($this->price_1,"n");
			if (!$this->price_1) {
				$this->errors['price_1'] = '記入をご確認ください。';
			}elseif( !mb_ereg_match('^[0-9]*$', $this->price_1) ){
				$this->errors['price_1'] = '半角数字でご記入ください。';
			} elseif($this->isGarbled($this->price_1)) {
				$this->errors['price_1'] = $d['garbled'];
			}

			$this->convert($this->price_special_1,"n");
			if (!$this->price_special_1) {
	//			$this->errors['price_special_1'] = '記入をご確認ください。';
			}elseif( !mb_ereg_match('^[0-9]*$', $this->price_special_1) ){
				$this->errors['price_special_1'] = '半角数字でご記入ください。';
			} elseif($this->isGarbled($this->price_special_1)) {
				$this->errors['price_special_1'] = $d['garbled'];
			}

			$this->convert($this->price_special_policy_1_year,"n");
			$this->convert($this->price_special_policy_1_month,"n");
			$this->convert($this->price_special_policy_1_day,"n");
			if (!($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day)) {
	//			$this->errors['price_special_policy_1'] = '記入をご確認ください。';
			}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day) ) {
				$this->errors['price_special_policy_1'] = '記入形式をご確認ください。';
			}elseif ( !$this->isFormatedDate($this->price_special_policy_1_year.$this->price_special_policy_1_month.$this->price_special_policy_1_day) ) {
				$this->errors['price_special_policy_1'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->price_special_policy_1)) {
				$this->errors['price_special_policy_1'] = $d['garbled'];
			}

			if (!$this->publisher_1) {
				$this->errors['publisher_1'] = '記入をご確認ください。';
			} elseif($this->isGarbled($this->publisher_1)) {
				$this->errors['publisher_1'] = $d['garbled'];
			}

			if (!$this->publisher_2) {
			} elseif($this->isGarbled($this->publisher_2)) {
				$this->errors['publisher_2'] = $d['garbled'];
			}

			$this->convert($this->trade_code_1,"n");
			if (!$this->trade_code_1) {
				$this->errors['trade_code_1'] = '記入をご確認ください。';
			} elseif($this->isGarbled($this->trade_code_1)) {
				$this->errors['trade_code_1'] = $d['garbled'];
			}

		}
		if($this->data_type_1 == '2'){
			$this->convert($this->out_date_1_year,"n");
			$this->convert($this->out_date_1_month,"n");
			$this->convert($this->out_date_1_day,"n");
			if (!($this->out_date_1_year.$this->out_date_1_month.$this->out_date_1_day)) {
	//			$this->errors['out_date_1'] = '記入をご確認ください。';
			}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->out_date_1_year.$this->out_date_1_month.$this->out_date_1_day) ) {
				$this->errors['out_date_1'] = '記入形式をご確認ください。';
			}elseif ( !$this->isFormatedDate($this->out_date_1_year.$this->out_date_1_month.$this->out_date_1_day) ) {
				$this->errors['out_date_1'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->out_date_1_year.$this->out_date_1_month.$this->out_date_1_day)) {
				$this->errors['out_date_1'] = $d['garbled'];
			}
		}
		if($this->data_type_1 == '3'){
			$this->convert($this->price_1,"n");
			if (!$this->price_1) {
				$this->errors['price_1'] = '記入をご確認ください。';
			}elseif( !mb_ereg_match('^[0-9]*$', $this->price_1) ){
				$this->errors['price_1'] = '半角数字でご記入ください。';
			} elseif($this->isGarbled($this->price_1)) {
				$this->errors['price_1'] = $d['garbled'];
			}

			$this->convert($this->price_change_date_1_year,"n");
			$this->convert($this->price_change_date_1_month,"n");
			$this->convert($this->price_change_date_1_day,"n");
			if (!($this->price_change_date_1_year.$this->price_change_date_1_month.$this->price_change_date_1_day)) {
	//			$this->errors['price_change_date_1'] = '記入をご確認ください。';
			}elseif ( !mb_ereg_match('^[0-9]{8}$', $this->price_change_date_1_year.$this->price_change_date_1_month.$this->price_change_date_1_day) ) {
				$this->errors['price_change_date_1'] = '記入形式をご確認ください。';
			}elseif ( !$this->isFormatedDate($this->price_change_date_1_year.$this->price_change_date_1_month.$this->price_change_date_1_day) ) {
				$this->errors['price_change_date_1'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->price_change_date_1_year.$this->price_change_date_1_month.$this->price_change_date_1_day)) {
				$this->errors['price_change_date_1'] = $d['garbled'];
			}

		}

		// これから出る本情報（書協会員のみ・有料）
		if($this->data_type_1 == '5'){
			// 著者表示
			// 改行削除 + タグ削除
			$this->preliminary_3 = str_replace("\r\n", '', $this->preliminary_3);
			$this->preliminary_3 = strip_tags( $this->preliminary_3);
			if (!$this->preliminary_3) {
				$this->errors['preliminary_3'] = '記入をご確認ください。';
			} elseif( !mb_ereg_match('^.{0,40}$', $this->preliminary_3) ){
				// エラーメッセージはJSに任せる
				$this->errors['preliminary_3'] = ' ';
			} elseif($this->isGarbled($this->preliminary_3)) {
				$this->errors['preliminary_3'] = $d['garbled'];
			}

			if ($this->preliminary_4 == '' || $this->preliminary_4 == NULL) {
				$this->errors['preliminary_4'] = '選択をご確認ください。';
			} elseif($this->isGarbled($this->preliminary_4)) {
				$this->errors['preliminary_4'] = $d['garbled'];
			}

			if (!$this->preliminary_5) {
				$this->errors['preliminary_5'] = '選択をご確認ください。';
			} elseif($this->isGarbled($this->preliminary_5)) {
				$this->errors['preliminary_5'] = $d['garbled'];
			}

			// 内容紹介
			// 改行削除 + タグ削除
			$this->preliminary_6 = str_replace("\r\n", '', $this->preliminary_6);
			$this->preliminary_6 = strip_tags( $this->preliminary_6);
			if (!$this->preliminary_6) {
				$this->errors['preliminary_6'] = '記入をご確認ください。';
			} elseif( !mb_ereg_match('^.{0,70}$', $this->preliminary_6) ){
				// エラーメッセージはJSに任せる
				$this->errors['preliminary_6'] = ' ';
			} elseif($this->isGarbled($this->preliminary_6)) {
				$this->errors['preliminary_6'] = $d['garbled'];
			}

			$this->convert($this->preliminary_7_year,"n");
			$this->convert($this->preliminary_7_month,"n");
			$this->convert($this->preliminary_7_day,"n");
			if (!($this->preliminary_7_year.$this->preliminary_7_month.$this->preliminary_7_day)) {
				$this->errors['preliminary_7'] = '記入をご確認ください。';
			} elseif ( !mb_ereg_match('^[0-9]{8}$', $this->preliminary_7_year.$this->preliminary_7_month.$this->preliminary_7_day) ) {
				$this->errors['preliminary_7'] = '記入形式をご確認ください。';
			} elseif ( !$this->isFormatedDate($this->preliminary_7_year.$this->preliminary_7_month.$this->preliminary_7_day) ) {
				$this->errors['preliminary_7'] = '記入内容をご確認ください。';
			} elseif($this->isGarbled($this->preliminary_7_year.$this->preliminary_7_month.$this->preliminary_7_day)) {
				$this->errors['preliminary_7'] = $d['garbled'];
			}
		}

		if(count($this->errors)) {
			return 'input';
		}

		if(preg_match('/^x$/u',$this->book_size_2))
		$this->book_size_2 = $this->book_size_2_other_l.$this->book_size_2.$this->book_size_2_other_r.'cm';

		return true;
	}
}
?>