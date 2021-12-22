<?php
require_once('Debug.php');

class DatabaseWrapper extends Debug {
	var $dbtype = null;

	var $TYPE_PGSQL = 'pgsql';
	var $TYPE_MYSQL = 'mysql';
	var $MYSQLiConnection;

	function connect($url) {
		$parts = parse_url($url);

		$this->dbtype = $parts['scheme'];
		$host = $parts['host'];
		$port = null;
		if(isset($parts['port'])) {
			$port = $parts['port'];
		}
		$user = $parts['user'];
		$password = $parts['pass'];
		$dbname = substr($parts['path'], 1);

		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			if ($port) {
				$host .= ':' . $port;
			}

			if( isset( $_SERVER['SCRIPT_URL'] ) && preg_match('/search/',$_SERVER['SCRIPT_URL']) ){
				$connection = mysqli_connect('p:'.$host, $user, $password,$dbname);
			}else{
				$connection = mysqli_connect($host, $user, $password,$dbname);
			}
			if(!$connection) {
				// データベースの接続に失敗しました。
				return false;
			}
			$this->MYSQLiConnection =& $connection;
			mysqli_query($connection,'set character set utf8;');
			return $connection;
		case $this->TYPE_PGSQL:
			$attrs = array();
			if ($host) {
				$attrs[] = 'host=' . $host;
			}
			if ($port) {
				$attrs[] = 'port=' . $port;
			}
			$attrs[] = 'dbname=' . $dbname;
			if ($user) {
				$attrs[] = 'user=' . $user;
			}
			if ($password) {
				$attrs[] = 'password=' . $password;
			}
			$connection = pg_connect(implode(' ', $attrs));
			return $connection;
		}
	}

	function close(&$connection) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysqli_close($connection);
		case $this->TYPE_PGSQL:
			return pg_close($connection);
		}
	}

	function query(&$connection, $query) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysqli_query($connection, $query);
		case $this->TYPE_PGSQL:
			return pg_query($connection, $query);
		}
	}

	function escape_string($str, &$connection) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysqli_real_escape_string($connection,$str);
		case $this->TYPE_PGSQL:
			return pg_escape_string($str);
		}
	}

	function fetch_assoc(&$result, $row = null) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysqli_fetch_assoc($result);
		case $this->TYPE_PGSQL:
			if ($row) {
				return pg_fetch_assoc($result, $row);
			}
			else {
				return pg_fetch_assoc($result);
			}
		}
	}

	function num_rows(&$result) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysqli_num_rows($result);
		case $this->TYPE_PGSQL:
			return pg_num_rows($result);
		}
	}

	function affected_rows(&$result) {
		switch ($this->dbtype) {
		case $this->TYPE_MYSQL:
			return mysql_affected_rows($this->MYSQLiConnection);
		case $this->TYPE_PGSQL:
			return pg_affected_rows($result);
		}
	}
}

#
?>
