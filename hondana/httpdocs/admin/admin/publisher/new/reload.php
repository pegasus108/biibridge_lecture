<?php
	//apacheのリロード
	$output = "";
	@exec("sudo /etc/init.d/httpd reload > /dev/null 2>&1", $output);
	echo 'リロードしました';
?>
