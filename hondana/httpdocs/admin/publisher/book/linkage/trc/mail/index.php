<?php
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/DefaultAction.php');
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/Database.php');
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/Renderer.php');


//本番環境用
$url = 'mysql://hondanaadmin:oti3ov7q@localhost/hondana';
$tomail = 'hotta@mxs.shiki.trc.co.jp';
$frommail = 'info@hondana.jp';

//本番環境テスト用
/*
$url = 'mysql://hondanaadmin:oti3ov7q@localhost/hondana';
$tomail = 'shiiba@evol-ni.com';
$frommail = 'info@hondana.jp';
*/

//テストサーバ用
/*
$url = 'mysql://hondana:hondana@localhost/hondana';
$tomail = 'deguchi@evol-ni.com';
$tomail = 'shiiba@evol-ni.com';
$frommail = 'info@evol-ni.com';
*/


$db = new Database($url);
$db->connect();


//publisherList
$result = $db->statement(dirname(__FILE__) . '/sql/get_publisher.sql');
$publisherList = $db->buildTree($result, 'publisher_no');
$db->assign('publisherList', $publisherList);


//送信対象データ全取得
$bookList = array();
$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_all.sql');
$bookList = $db->buildTree($result, 'linkage_trc_no');

$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
$row = $db->fetch_assoc($result);
$total = $row['total'];

$fbody = '';
$sendPublisherList = NULL;
foreach ( $bookList as $key => $val ){
	$row = join(',',$val);
	$row = mb_ereg_replace('\n|\r','',$row);
	$row = mb_ereg_replace(',[ \t\n\r　]*,',',,',$row);
	$row = mb_convert_encoding($row,'sjis-win','utf-8');
	$fbody .= $row . "\n";

}

//送信
if( $fbody ){
	$mailer = new Renderer();
	$mailer->template_dir = realpath(dirname(__FILE__) . '/..');
	$mailer->assign('publisherList', $publisherList);
	$mailer->assign('total',$total);
	$mes = $mailer->fetch('mail/mail_all.html','','trc');

	$toname = 'TRC CSVフォーマット データ入稿';
	$fromname = 'HONDANA';
	$sub = 'HONDANA'.date('m').'月近刊情報';
	$fname = 'kinkan.csv';

	if ( send( $toname, $tomail, $fromname, $frommail, $sub, $mes, $fname, $fbody ) ){

		//成功時　各publisherに対して送信完了メールを送信
		foreach( $publisherList as $publisher ){
			$db->assign('publisher_no', $publisher['publisher_no']);

			$bookList = array();
			$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data.sql');
			$bookList = $db->buildTree($result, 'linkage_trc_no');

			$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
			$row = $db->fetch_assoc($result);
			$total = $row['total'];

			$fbody = '';
			foreach ( $bookList as $key => $val ){
				$f = array();
				foreach ($val as $k => $v ){
					$f[] = mb_ereg_replace(',','.',$v);
				}
				$row = join(',',$f);
				$row = mb_ereg_replace('\n|\r','',$row);
				$row = mb_ereg_replace(',[ \t\n\r　]*,',',,',$row);
				$row = mb_convert_encoding($row,'sjis-win','utf-8');
				$fbody .= $row . "\n";
			}

			if( $fbody ){
				$mailer = new Renderer();
				$mailer->template_dir = realpath(dirname(__FILE__) . '/..');
				$mailer->assign('publisher',$publisher);
				$mailer->assign('total',$total);
				$mes = $mailer->fetch('mail/mail.html','','trc');

				$toname = $publisher['linkage_person_name'].様;
				$linkage_person_mail = $publisher['linkage_person_mail'];
				$sub = $publisher['name'].date('m').'[送信完了報告]';
				$fname = 'kinkan.csv';

				send( $toname, $linkage_person_mail, $fromname, $frommail, $sub, $mes, $fname, $fbody );
			}
		}

		$db->begin();
		$db->statement(dirname(__FILE__) . '/sql/set_end_process.sql');
		$db->commit();
	}

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
	$subject = mb_encode_mimeheader_ex($subject);
	$message = mb_convert_encoding($message, 'JIS', 'UTF-8');
	$to = mb_convert_encoding($mail['to']['name'], "JIS", "UTF-8");
	$to = "=?ISO-2022-JP?B?" . base64_encode($to) . '?= <' . $mail['to']['mail'] . '>';
	$from = mb_convert_encoding($mail['from']['name'], "JIS", "UTF-8");
	$from = "=?ISO-2022-JP?B?" . base64_encode($from) . '?= <' . $mail['from']['mail'] . '>';
	$filename = mb_convert_encoding($filename, 'JIS', 'UTF-8');
	$filename = "=?ISO-2022-JP?B?" . base64_encode($filename) . "?=";

	$head = '';
	$head .= "From: {$from}\n";
	$head .= "Bcc: admin@hondana.jp\n";
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


?>
