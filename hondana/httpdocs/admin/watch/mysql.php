<?php
$mysqli = new mysqli('10.20.0.201', 'hondana', 'oti3ov7q', 'hondana');
if($mysqli->connect_error) {
	header('HTTP/1.1 503 Service Unavailable');
	syslog(LOG_ALERT, "HONDANA: [MySQL]Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	exit();
}

if(($result = $mysqli->query("SELECT id FROM publisher WHERE publisher_no = 1")) === false) {
	header('HTTP/1.1 503 Service Unavailable');
	syslog(LOG_ALERT, "HONDANA: [MySQL]Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	exit();
}

$mysqli->close();

?>