<?php

class JPOController extends DefaultController {

	// JPO出版情報登録センター（取次広報誌）ジャンル・コード表
	// http://www2.hanmoto.com/jpokinkan/reference/jbt_genre_code.html
	var $subjectCode = array(
		"01"=>"文芸",
		"02"=>"新書",
		"03"=>"社会一般",
		"04"=>"資格・試験",
		"05"=>"ビジネス",
		"06"=>"スポーツ・健康",
		"07"=>"趣味・実用",
		"09"=>"ゲーム",
		"10"=>"芸能・タレント",
		"11"=>"テレビ・映画化",
		"12"=>"芸術",
		"13"=>"哲学・宗教",
		"14"=>"歴史・地理",
		"15"=>"社会科学",
		"16"=>"教育",
		"17"=>"自然科学",
		"18"=>"医学",
		"19"=>"工業・工学",
		"20"=>"コンピュータ",
		"21"=>"語学・辞事典",
		"22"=>"学参",
		"23"=>"児童図書",
		"24"=>"ヤングアダルト",
		"29"=>"新刊セット",
		"30"=>"全集",
		"31"=>"文庫",
		"36"=>"コミック文庫",
		"41"=>"コミックス(欠番扱)",
		"42"=>"コミックス(雑誌扱)",
		"43"=>"コミックス(書籍)",
		"44"=>"コミックス(廉価版)",
		"51"=>"ムック",
	);

	var $unpricedItemType = array(
		"00"=>"単品分売不可",
		"31"=>"セット商品分売可",
		"10"=>"セット商品分売不可",
	);

	var $audienceCodeValue = array(
		"01"=>"0～2歳",
		"02"=>"3～5歳",
		"03"=>"小学低学年",
		"04"=>"小学中学年",
		"05"=>"小学高学年",
		"06"=>"小学全般",
		"07"=>"中学以上",
		"08"=>"高校",
	);

	var $audienceDescription = array(
		// "1"=>"成人指定有り", // ONIX 1.0
		// "2"=>"成人指定なし", // ONIX 1.0
		"00"=>"成人指定なし",
		"01"=>"成人指定（理由明記なし）",
		"02"=>"成人向け",
		"03"=>"成人向け（性）",
		"04"=>"成人向け（暴力）",
		"05"=>"成人向け（薬物）",
		"06"=>"成人向け（言語）",
	);

	var $containeditem = array(
		"0"=>"付録なし",
		"1"=>"付録有り",
	);

	var $language = array(
		"ara"=>"アラビア語",
		"chi"=>"中国語",
		"eng"=>"英語",
		"epo"=>"エスペラント",
		"fre"=>"フランス語",
		"ger"=>"ドイツ語",
		"gre"=>"ギリシャ語",
		"ita"=>"イタリア語",
		"jpn"=>"日本語",
		"kor"=>"韓国語",
		"lat"=>"ラテン語",
		"may"=>"マレー語",
		"rus"=>"ロシア語",
		"spa"=>"スペイン語",
	);

	var $reselling = array(
		"1"=>"再販商品",
		"2"=>"非再販商品",
		"3"=>"時限再販",
	);

	var $supplyRestrictionDetail = array(
		"03"=>"委託",
		"02"=>"買切",
		"00"=>"注文",
	);

	var $contributorRole = array(
		"A01"=>"著・文・その他",
		"B01"=>"編集",
		"B20"=>"監修",
		"B06"=>"翻訳",
		"A12"=>"イラスト",
		"A38"=>"原著",
		"A10"=>"企画・原案",
		"A08"=>"写真",
		"A21"=>"解説",
		"E07"=>"朗読"
	);

	var $notification_types = array(
		"02" => "確定前",
		"03" => "確定後",
	);

	function getSubjectCode(){
		return $this->subjectCode;
	}

	function getUnpricedItemType(){
		return $this->unpricedItemType;
	}

	function getAudienceCodeValue() {
		return $this->audienceCodeValue;
	}

	function getAudienceDescription() {
		return $this->audienceDescription;
	}

	function getContaineditem(){
		return $this->containeditem;
	}

	function getLanguage() {
		return $this->language;
	}

	function getReselling(){
		return $this->reselling;
	}

	function getSupplyRestrictionDetail(){
		return $this->supplyRestrictionDetail;
	}

	function getContributorRole(){
		return $this->contributorRole;
	}

	function getNotificationTypes(){
		return $this->notification_types;
	}

	// これから出る本 「分類」 (半角数字4桁)
	function getRecentPublicationTypes() {
		return array(
			'0000' => '総記',
			'0101' => '哲学 心理学 思想',
			'0102' => '宗教',
			'0201' => '歴史',
			'0202' => '地理',
			'0301' => '政治 法律',
			'0302' => '経済 財政 統計 経営',
			'0303' => 'ビジネス',
			'0304' => '社会',
			'0305' => '環境問題',
			'0401' => '数学 物理 天文・地学',
			'0402' => '化学 生物学',
			'0403' => '医学 歯学 薬学',
			'0501' => '情報科学',
			'0502' => '建築 土木工学 都市工学',
			'0503' => '電気 機械',
			'0504' => 'その他の工学',
			'0600' => '産業',
			'0701' => '芸術',
			'0702' => '漫画 コミック',
			'0800' => '言語 語学',
			'0901' => '文学総記',
			'0902' => '日本文学',
			'0903' => '外国文学',
			'1001' => '家庭 家事 料理',
			'1002' => '旅',
			'1003' => 'スポーツ 趣味娯楽',
			'1004' => '資格試験',
			'1100' => '学習参考書',
			'1200' => '児童',
			'1300' => 'ヤングアダルト',
			'1401' => '新書',
			'1402' => '文庫',
		);
	}

	// これから出る本 読者対象 (半角数字2桁)
	function getRecentPublicationReader() {
		return array(
			'01' => '専門',
			'02' => '教養',
			'03' => '一般',
			'04' => '大学',
			'05' => '高校',
			'06' => '中学',
			'07' => '小学',
			'08' => '小学上',
			'09' => '小学中',
			'10' => '小学初',
			'11' => '幼児',
		);
	}

	// 刊行形態 取得
	function getPublicationForm() {
		return array(
			"01" => "月刊誌",
			"02" => "週刊誌",
			"03" => "コミックス",
			"04" => "ムック",
			"05" => "オーディオ商品",
			"06" => "直販誌",
			"07" => "PB商品",
		);
	}

	// 配本の有無 取得
	function getExtent() {
		return array(
			"1" => "新刊ライン送品を希望する",
			"0" => "希望しない",
		);
	}

	// 完結フラグ 取得
	function getCompletion() {
		return array(
			"0" => "未完",
			"1" => "完結",
		);
	}

	// 取次会社取扱い 取得
	function getIntermediaryCompanyHandlings() {
		return array(
			"1" => "あり",
			"0" => "なし",
		);
	}

	// 読者書き込み 取得
	function getReadersWrite() {
		return array(
			"0" => "なし",
			"1" => "あり",
		);
	}

	// 制作特記項目 取得
	function getProductionNotesItem() {
		return array(
			"01" => "復刊",
			"02" => "複製本",
			"03" => "大活字",
			"04" => "オンデマンド",
			"05" => "紙芝居",
			"06" => "シールブック",
			"07" => "ポストカード",
		);
	}

	// 付属資料（CD／DVD）の館内外貸出可否 取得
	function getCDDVD() {
		return array(
			"1" => "可",
			"2" => "不可",
			"3" => "館内のみ",
		);
	}

	// 児童書詳細ジャンル 取得
	function getChildrensBookGenre() {
		return array(
			"01" => "幼児向",
			"02" => "絵本",
			"03" => "しかけ絵本",
			"04" => "フィクション",
			"05" => "ノンフィクション",
			"06" => "まんが",
			"07" => "その他",
		);
	}

	// ルビの有無 取得
	function getRuby() {
		return array(
			"0" => "なし",
			"1" => "あり",
		);
	}

	// その他特記事項 取得
	function getOtherNotices() {
		return array(
			"1" => "改題",
			"2" => "新装丁",
			"3" => "その他",
		);
	}

	// 帯（ムック） 取得
	function getBand() {
		return array(
			"0" => "なし",
			"1" => "あり",
		);
	}

	// カバー（ムックの場合） 取得
	function getCover() {
		return array(
			"0" => "なし",
			"1" => "あり",
		);
	}

	function changeAuthorType($type){
		$array = array(
			"1"=>"A01",
			"2"=>"B06",
			"3"=>"A01",
			"4"=>"A10",
			"5"=>"A10",
			"6"=>"B01",
			"7"=>"B01",
			"8"=>"B01",
			"9"=>"B01",
			"10"=>"B20",
			"11"=>"B20",
			"12"=>"A01",
			"13"=>"A12",
			"14"=>"A12",
			"15"=>"A08",
		);

		return $array[$type];
	}

	function checkDigit($prm_jan){
		$len  = mb_strlen($prm_jan);
		$ichi = 0;
		$suuji_wa = 0;
		$n = 0;
		for($i = 0; $i < $len; $i++){
		   if($n == 1){
			  $suuji_wa += substr($prm_jan, $ichi, 1) * 3;
			  $n = 0;
			}else{
			  $suuji_wa += substr($prm_jan, $ichi, 1);
			  $n = 1;
			}
		  $ichi++;
		}

		$suu_amari = $suuji_wa % 10;
		$cd = 10 - $suu_amari;
		if($cd == 10){
		  $cd = 0;
		}
		$return_cd = $cd;

		return($return_cd);
	}

	function isClearISBN($isbn){
		$digit = mb_substr($isbn, -1);
		$main = mb_substr($isbn, 0,-1);

		$nowDigit = $this->checkDigit($main);
		if($digit!=$nowDigit)
			return false;
		elseif(!preg_match ("/^(978)|(979)/u", $isbn))
			return false;
		else
			return true;


	}



	function isBeforeReleaseDate($dateString,$jpoSyncTime){
		if(empty ($dateString))
			return false;

		$jpoSyncDate = $this->makeJpoSyncTime($jpoSyncTime);
		$jpoSyncTime = strtotime($jpoSyncDate);
		$jpoSyncTime += 60*60*24*180;

		$date = strtotime($dateString);
		if($date > $jpoSyncTime)
			return true;
		else
			return false;

	}

	function isAfterReleaseDate($dateString,$jpoSyncTime){
		if(empty ($dateString))
			return false;

		$jpoSyncDate = $this->makeJpoSyncTime($jpoSyncTime);
		$jpoSyncTime = strtotime($jpoSyncDate);

		$date = strtotime($dateString);
		if($date < $jpoSyncTime)
			return true;
		else
			return false;
	}

	function makeJpoSyncTime($time){
		$targetDate = time();
		$timeString = sprintf("%02d",$time);
		$returnDate="";
		if(date("G") >= $time){
			$targetDate += 60 * 60 * 24;
			$returnDate = date("Y-m-d {$timeString}:00:00",$targetDate);

		}else{
			$targetDate += 60 * 60 * 24;
			$returnDate = date("Y-m-d {$timeString}:00:00");

		}

		return $returnDate;

	}
}
?>