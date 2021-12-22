<?php
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/DefaultAction.php');
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/Database.php');
require_once(dirname(__FILE__) .'/../../../../../../../libdb/simple/Renderer.php');


//本番環境用
$url = 'mysql://hondanaadmin:oti3ov7q@localhost/hondana';
$tomail = 'db2@jbpa.or.jp';
$frommail = 'info@hondana.jp';

//本番環境テスト用
/*
$url = 'mysql://hondanatestadmin:pvGFlu05@localhost/hondanatest';
$tomail = 'nagata_nozomi@flyingline.co.jp';
$frommail = 'info@hondana.jp';
*/

$db = new Database($url);
$db->connect();

$result = $db->statement(dirname(__FILE__) . '/sql/get_publisher.sql');
$publisherList = $db->buildTree($result, 'publisher_no');

$result = $db->statement(dirname(__FILE__) . '/sql/get_set_month.sql');
$row = $db->fetch_assoc($result);
$setMonth = $row['set_month'];

foreach( $publisherList as $publisher ){
	$db->assign('publisher_no', $publisher['publisher_no']);

	$bookList1 = array();
	$bookList2 = array();
	$bookList3 = array();
	$bookList4 = array();
	$fbody = '';
//刊行予定情報
	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data1.sql');
	$bookList1 = $db->buildTree($result, 'linkage_trc_no');

	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
	$row = $db->fetch_assoc($result);
	$total1 = $row['total'];

	foreach ( $bookList1 as $key => $val ){
		$row = join("\t",$val);
		$row = mb_ereg_replace('\n|\r','',$row);
		$row = mb_ereg_replace(',[ \t\n\r　]*,',',,',$row);
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$fbody .= $row . "\n";
	}

//長期品切情報
	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data2.sql');
	$bookList2 = $db->buildTree($result, 'linkage_trc_no');

	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
	$row = $db->fetch_assoc($result);
	$total2 = $row['total'];

	foreach ( $bookList2 as $key => $val ){
		$row = join("\t",$val);
		$row = mb_ereg_replace('\n|\r','',$row);
		$row = mb_ereg_replace(',[ \t\n\r　]*,',',,',$row);
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$fbody .= $row . "\n";
	}

//本体価格改定情報
	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data3.sql');
	$bookList3 = $db->buildTree($result, 'linkage_trc_no');

	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
	$row = $db->fetch_assoc($result);
	$total3 = $row['total'];

	foreach ( $bookList3 as $key => $val ){
		$row = join("\t",$val);
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$row = mb_ereg_replace('\n|\r','　',$row);
		$row = mb_ereg_replace(',[\s\t\n\r　]*,',',,',$row);
		$fbody .= $row . "\n";
	}

//既刊情報
	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data4.sql');
	$bookList4 = $db->buildTree($result, 'linkage_trc_no');

	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
	$row = $db->fetch_assoc($result);
	$total4 = $row['total'];

	foreach ( $bookList4 as $key => $val ){
		$row = join("\t",$val);
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$row = mb_ereg_replace('\n|\r','　',$row);
		$row = mb_ereg_replace(',[\s\t\n\r　]*,',',,',$row);
		$fbody .= $row . "\n";
	}

//これから出る本情報
	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data5.sql');
	$bookList5 = $db->buildTree($result, 'linkage_trc_no');

	$result = $db->statement(dirname(__FILE__) . '/sql/get_process_data_total.sql');
	$row = $db->fetch_assoc($result);
	$total5 = $row['total'];

	foreach ( $bookList5 as $key => $val ){
		$row = join("\t",$val);
		$row = mb_convert_encoding($row,'sjis-win','utf-8');
		$row = mb_ereg_replace('\n|\r','　',$row);
		$row = mb_ereg_replace(',[\s\t\n\r　]*,',',,',$row);
		$fbody .= $row . "\n";
	}

	if( $fbody ){
		$mailer = new Renderer();
		$mailer->template_dir = realpath(dirname(__FILE__) . '/..');
		$mailer->assign('publisher',$publisher);
		$mailer->assign('total1', $total1);
		$mailer->assign('total2', $total2);
		$mailer->assign('total3', $total3);
		$mailer->assign('total4', $total4);
		$mailer->assign('total5', $total5);
		$mailer->assign('total' , $total1 + $total2 + $total3 + $total4 + $total5 );
		$mes = $mailer->fetch('mail/mail.html','','jbpa');

		$code = "";
		if($publisher['transaction_code']) {
			$code = $publisher['transaction_code'] . " ";
		}

		$toname = '書協';
		$sub = $publisher['name'] . " " . $code . '書協 TSVフォーマット データ入稿';
		$fromname = 'HONDANA';

		$fname = $publisher['transaction_code'].'_'.date('y').date('m').date('d').date('H').date('i').'.txt';

		if ( send( $toname, $tomail, $fromname, $frommail, $sub, $mes, $fname, $fbody ) ){
			$db->begin();
			$db->statement(dirname(__FILE__) . '/sql/set_end_process.sql');
			$db->commit();

			$toname = $publisher['linkage_person_name'];
			$linkage_person_mail = $publisher['linkage_person_mail'];
			$sub .= '[送信完了報告]';
			send( $toname, $linkage_person_mail, $fromname, $frommail, $sub, $mes, $fname, $fbody );
		}

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
