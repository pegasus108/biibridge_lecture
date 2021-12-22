<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/AuthAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/DefaultAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../libdb/simple/Renderer.php');

class Action extends AuthAction {
	function prepare() {
		$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/admin/publisher/book/linkage/process_commissioner/mail.ini', true);
		$ini = $ini['customer'];

		$ini['subject'] = sprintf($ini['subject'],$this->publisher['name']);
		if($this->publisher['linkage_person_name'])
			$ini['toName'] = $this->publisher['linkage_person_name'].'様';
		else
			$ini['toName'] = 'データ連携担当者様';
		$ini['toMail'] = $this->publisher['linkage_person_mail'];
		$ini['fileName'] = sprintf($ini['fileName'],$this->publisher['name'],date('m'));

		$this->ini = $ini;
	}
	function execute() {
		if(!$this->bookList)
			return;

		$row = join(",",$this->getFieldNameList());
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$fbody = $row . "\n";

		foreach ( $this->bookList as $key => $val ){
			$temp = $val;
			$temp['release_date_1'] = mb_convert_kana($temp['release_date_1'],'N');
			$temp['isbn_1'] = 'ISBN'.$temp['isbn_1'];
			$row = join(",",$temp);
			$row = mb_ereg_replace('\n|\r','',$row);
			$row = mb_ereg_replace(',[\s\t\n\r]*,',',,',$row);
			$row = mb_convert_encoding($row,'sjis-win','utf-8');
			$fbody .= $row . "\n";
		}

		if( $fbody ){
			$mailer = new Renderer();
			$mailer->template_dir = realpath(dirname(__FILE__) . '/..');
			$mailer->assign($this->getProperties());
			$body = $mailer->fetch('process_commissioner/mail.html');

			if( $this->send(
					$this->ini['toName'],
					$this->ini['toMail'],
					$this->ini['fromName'],
					$this->ini['fromMail'],
					$this->ini['subject'],
					$body,
					$this->ini['fileName'],
					$fbody)
			){
				$db =& $this->_db;
				$db->assign($this->getProperties());
				$db->assign('publisher_no', $_SESSION['publisher_no']);

				$db->begin();
				$db->statement('admin/publisher/book/linkage/sql/complete_commissioner.sql');
				$db->commit();
			}
		}

		$this->clearProperties();
		$this->__controller->redirectToURL('index.php?action=complete');

		return false;
	}

	function send($toname , $tomail , $fromname , $frommail , $sub , $mes , $fname , $fbody){
		$mail['to']['name'] = $toname;
		$mail['to']['mail'] = $tomail;
		$mail['from']['name'] = $fromname;
		$mail['from']['mail'] = $frommail;
		$subject = $sub;
		$message = $mes;
		$filename = $fname;
		$attach_file = $fbody;

		$mime_type = "application/octet-stream";

		$attach_file = chunk_split(base64_encode($attach_file), 76, "\n"); // Base64に変換し76Byte分割

		$boundary = '----=_Boundary_' . uniqid(rand(1000,9999) . '_') . '_';
		$subject = mb_convert_encoding($subject, 'JIS', 'UTF-8');
		$subject = $this->mb_encode_mimeheader_ex($subject);
		$message = mb_convert_encoding($message, 'JIS', 'UTF-8');
		$to = mb_convert_encoding($mail['to']['name'], "JIS", "UTF-8");
		$to = "=?ISO-2022-JP?B?" . base64_encode($to) . '?= <' . $mail['to']['mail'] . '>';
		$from = mb_convert_encoding($mail['from']['name'], "JIS", "UTF-8");
		$from = "=?ISO-2022-JP?B?" . base64_encode($from) . '?= <' . $mail['from']['mail'] . '>';
		$filename = mb_convert_encoding($filename, 'JIS', 'UTF-8');
		$filename = "=?ISO-2022-JP?B?" . base64_encode($filename) . "?=";

		$head = '';
		$head .= "From: {$from}\n";
		$head .= "MIME-Version: 1.0\n";
		$head .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n";
		$head .= "Content-Transfer-Encoding: 7bit";
		$body = '';
		$body .= "--{$boundary}\n";
		$body .= "Content-Type: text/plain; charset=ISO-2022-JP\n" .
				  "Content-Transfer-Encoding: 7bit\n";
		$body .= "\n";
		$body .= "{$message}\n";
		$body .= "--{$boundary}\n";
		$body .= "Content-Type: {$mime_type}; name=\"{$filename}\"\n" .
				 "Content-Transfer-Encoding: base64\n" .
				 "Content-Disposition: attachment; filename=\"{$filename}\"\n";
		$body .= "\n";
		$body .= "{$attach_file}\n";
		$body .= "--$boundary--\n";

		return mail($to, $subject, $body, $head);
	}

	// mb_encode_mimeheaderのバグ対策用
	function mb_encode_mimeheader_ex($text, $split_count = 34) {
		$position = 0;
		$encorded = '';

		while ($position < mb_strlen($text, 'JIS')) {
			if ($encorded != '') {
				$encorded .= "\r\n ";
			}
			$output_temp = mb_strimwidth($text, $position, $split_count, '', 'JIS');
			$position = $position + mb_strlen($output_temp, 'JIS');
			$encorded .= "=?ISO-2022-JP?B?" . base64_encode($output_temp) . "?=";
		}

		return $encorded;
	}

	function getFieldNameList(){
		return array(
			'データ区分'
			,'送信区分'
			,'送信元区分'
			,'送信元コード'
			,'送信先区分'
			,'送信先コード'
			,'作成日'
			,'レコード区分'
			,'取消区分'
			,'ジャンルコード（書店・読者向け）'
			,'取引コード'
			,'取引コード（枝番）'
			,'発売所'
			,'発売所（カナ）'
			,'発行所'
			,'発行所（カナ）'
			,'扱い社'
			,'扱い区分'
			,'新書・文庫・ｺﾐｯｸ・ﾑｯｸｼﾘｰｽﾞ名'
			,'新書・文庫・ｺﾐｯｸ・ﾑｯｸｼﾘｰｽﾞ名（カナ）'
			,'新書・文庫・ｺﾐｯｸ・ﾑｯｸｼﾘｰｽﾞ名（巻次）'
			,'サブタイトル・シリーズ名'
			,'シリーズ名カナ'
			,'シリーズ巻次'
			,'全巻数'
			,'別巻数'
			,'配本回数'
			,'書名・雑誌名'
			,'書名カナ・雑誌名カナ'
			,'書名巻次・誌名巻次'
			,'後サブタイトル・副題'
			,'後サブタイトルカナ'
			,'後サブタイトル巻次'
			,'完結'
			,'月号・号数表記'
			,'編著者名１'
			,'編著者名カナ１'
			,'編著区分１'
			,'編著者名２'
			,'編著者名カナ２'
			,'編著区分２'
			,'編著者名３'
			,'編著者名カナ３'
			,'編著区分３'
			,'内容'
			,'内容・予備'
			,'対象読者'
			,'判型'
			,'判型（実寸）'
			,'ページ数'
			,'綴じ'
			,'発売予定日'
			,'返品期限・L表記'
			,'予価表記の有無'
			,'本体価格・セット本体価格'
			,'税込価格（定価）・セット税込価格'
			,'特価本体価格・セット特別本体価格'
			,'特価税込価格（特別定価）・セット特別税込価格'
			,'特価期限'
			,'分売不可区分'
			,'販売条件'
			,'ISBNコード'
			,'C分類'
			,'雑誌コード'
			,'雑誌コード(表４表記）'
			,'成人指定'
			,'事前注文'
			,'注文・申込締切'
			,'発行部数'
			,'修正内容'
			,'原稿入力者名'
			,'原稿入力者連絡先電話番号'
			,'原稿入力年月日'
			,'原稿入力時刻'
			,'コンクール・報奨金等の販売情報'
		);
	}
}
?>