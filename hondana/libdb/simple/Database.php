<?php
require_once('DatabaseWrapper.php');
require_once('DatabaseRenderer.php');

class Database extends DatabeseRenderer {
	var $url = null;
	var $connection = null;
	var $sql = null;
	var $sqlDirectoryPath = null;

	var $wrapper = null;

	function __construct($url) {
		$this->url = $url;

		DatabeseRenderer::__construct();

		$this->wrapper = new DatabaseWrapper();
	}

	function init() {
		DatabeseRenderer::init();
	}

	/**
	* Connect database.
	* @access public
	* @return resource
	*/
	function connect() {
		if (!$this->connection) {
			$this->connection = $this->wrapper->connect($this->url);
		}
		return $this->connection;
	}

	/**
	* Close database connection and connect again.
	* @access public
	* @return resource
	*/
	function reconnect() {
		$this->close();
		return $this->connect();
	}

	/**
	* Close database connection.
	* @access public
	* @return resource
	*/
	function close() {
		if (!$this->connection) {
			return false;
		}

		$result =& $this->wrapper->close($this->connection);
		$this->connection = null;
		return $result;
	}

	/**
	* Query SQL.
	* @access public
	* @return resource
	*/
	function query($sql) {
		if (!$this->connect()) {
			return null;
		}
		if (!$sql) {
			return null;
		}

		$this->print_rOnlyDebug($sql);

		$result = null;
		if (!$this->wrapper->getDebugMode()) {
			$result = $this->wrapper->query($this->connection, $sql);
		}
		$this->sql = $sql;
		return $result;
	}

	/**
	* Query last queried SQL.
	* @access public
	* @return resource
	*/
	function requery() {
		if (!$this->sql) {
			return null;
		}

		return $this->query($this->sql);
	}

	/**
	* Query bigin.
	* @access public
	* @return resource
	*/
	function begin() {
		return $this->query('begin');
	}

	/**
	* Query commit.
	* @access public
	* @return resource
	*/
	function commit() {
		return $this->query('commit');
	}

	/**
	* Query rollback.
	* @access public
	* @return resource
	*/
	function rollback() {
		return $this->query('rollback');
	}

    /**
     * @author Yoshifumi Shiiba
     */
    function statementTrees($array){
        foreach($array as $val){
            $this->$val["value"] = $this->statementTree($val["sql"], $val["keys"]);
        }
    }

    /**
     * @author Yoshifumi Shiiba
     */
    function statementTree($path,$key = null){
		$result = $this->statement($path);
        if(empty($result))
            return false;

		return $this->buildTree($result, $key);
    }

    /**
     * @author Yoshifumi Shiiba
     */
    function statementFetch($path){
		$result = $this->statement($path);
        if(empty($result))
            return false;

		return $this->fetch_assoc($result);
    }

	/**
	* Build tree by keys.
	* @access public
	* @return array
	*/
	function buildTree(&$result, $keys = null) {
		if (!$result) {
			return null;
		}

		if ($keys && !is_array($keys)) {
			$keys = array($keys);
		}
		$list = array();
		while ($row = $this->wrapper->fetch_assoc($result)) {
			$this->_buildTree($list, $row, $keys);
		}
		return $list;
	}
	function _buildTree(&$list, $row, $keys = null) {
		if (!is_array($list) || !$row) {
			return;
		}

		$currentKey = null;
		$currentValue = null;
		$childKey = null;
		$childValue = null;

		if ($keys && is_array($keys) && 0 < count($keys)) {
			if (0 < count($keys)) {
				$currentKey = $keys[0];
				if (isset($row[$keys[0]])) {
					$currentValue = $row[$currentKey];
				}
			}
			if (1 < count($keys)) {
				$childKey = $keys[1];
				if (isset($row[$keys[1]])) {
					$childValue = $row[$childKey];
				}
			}
		}
		if ($currentValue && $childKey) {
			if (!is_array($list[$currentValue])) {
				$list[$currentValue] = array();
			}
			array_shift($keys);
			$this->_buildTree($list[$currentValue], $row, $keys);
		}
		else if ($currentValue) {
			$list[$currentValue] = $row;
		}
		else {
			$list[] = $row;
		}
	}

	/**
	* Implant in query for assigned value.
	* @access public
	* @return resource
	*/
	function statement($path) {
        if(strpos($path, ".sql")===false && !empty($this->sqlDirectoryPath)){
            $path = $this->getPath($path);
        }

		$this->print_rOnlyDebug('[' . $path . ']');

		$sql = $this->fetch($path);

		$queryList = $this->explodeQuery($sql);
		$results = array();
		foreach ($queryList as $q) {
			$q = trim($q);
			if ($q) {
				$results[] = $this->query($q);
			}
		}

		if (count($results) == 1) {
			return $results[0];
		}
		else {
			return $results;
		}
	}
	function explodeQuery($sql) {
		$quoteChars = array(
			'"',
			'\''
		);
		$dummyChar = '|';
		$separator = ';';
		$marker = $dummyChar . $separator;

		$sql = $this->escapeQuote($sql, $quoteChars, $dummyChar);
		$tokens = $this->explodeQuote($sql, $quoteChars, $dummyChar);

		$length = count($tokens);
		for ($i=0; $i<$length; $i++) {
			if ($i % 2 == 0) {
				$token = $tokens[$i];
				$tokens[$i] = str_replace($separator, $marker, $token);
			}
		}
		$sql = implode('', $tokens);
		$querys = explode($marker, $sql);

		return $querys;
	}
	function escapeQuote($sql, $quoteChars, $dummyChar) {
		$escapeChar = '\\';
		$marker = $escapeChar . $dummyChar;
		$sql = str_replace($escapeChar, $marker, $sql);

		foreach ($quoteChars as $search) {
			$sql = str_replace($search, $dummyChar . $search, $sql);
			$sql = str_replace($marker . $dummyChar . $search, $marker . $search, $sql);
		}

		$sql = str_replace($marker , $escapeChar, $sql);

		return $sql;
	}
	function explodeQuote($sql, $quoteChars, $dummyChar) {
		if (count($quoteChars) <= 0) {
			return;
		}

		$quoteChar = array_shift($quoteChars);
		$marker = $dummyChar . $quoteChar;

		$tokens = explode($marker, $sql);
		$length = count($tokens);
		for ($i=0; $i<$length; $i++) {
			$token = $tokens[$i];
			if ($i % 2 == 0) {
				$subTokens = $this->explodeQuote($token, $quoteChars, $dummyChar);
				if (is_array($subTokens) && 3 <= count($subTokens)) {
					array_splice($tokens, $i, 1, $subTokens);
					$i+=count($subTokens) - 1;
				}
			}
			else {
				$tokens[$i] = $quoteChar . $token . $quoteChar;
			}
		}

		return $tokens;
	}

	/**
	* Assign non escaped value for statement.
	* @access public
	*/
	function assignNoEscape($name, $value = null) {
		if (is_array($name)) {
			$hash = $name;
			DatabeseRenderer::assign($hash);
		}
		else {
			DatabeseRenderer::assign($name, $value);
		}
	}

	/**
	* Assign escaped value for statement.
	* @access public
	*/
	function assign($name, $value = null, $nocache = false) {
		if (is_array($name)) {
			$hash = $name;
			foreach ($hash as $key => $value) {
				$value = $this->escape($value);
				DatabeseRenderer::assign($key,$value);
			}
		}
		else {
			$value = $this->escape($value);
			DatabeseRenderer::assign($name, $value);
		}
	}

	/**
	* Escape string for database.
	* @access public
	* @return mixed
	*/
	function escape(&$value) {
		if (!$value || !$this->wrapper) {
			return $value;
		}

		if (is_array($value) || is_object($value)) {
			$hash = $value;
			foreach ($hash as $key => $val) {
				$val = $this->escape($val);
				$hash[$key] = $val;
			}
		}
		else {
			if (!is_numeric($value)) {
				$value = $this->wrapper->escape_string($value, $this->connection);
			}
		}

		return $value;
	}

	/**
	* Wrap fetch_assoc.
	* @access public
	* @return mixed
	*/
	function fetch_assoc(&$result, $row = null) {
		return $this->wrapper->fetch_assoc($result, $row);
	}

	/**
	* Wrap num_rows.
	* @access public
	* @return mixed
	*/
	function num_rows(&$result) {
		return $this->wrapper->num_rows($result);
	}

	/**
	* Wrap affected_rows.
	* @access public
	* @return mixed
	*/
	function affected_rows(&$result) {
		return $this->wrapper->affected_rows($result);
	}

	/**
	* Wrap getDebugMode.
	* @access public
	* @return bool
	*/
	function getDebugMode() {
		return $this->wrapper->getDebugMode();
	}

	/**
	* Wrap print_r.
	* @access pubilc
	*/
	function print_r($var) {
		$this->wrapper->print_r($var);
	}

	/**
	* Wrap var_dump.
	* @access public
	*/
	function var_dump($var) {
		$this->wrapper->var_dump($var);
	}

	/**
	* Wrap print_rOnlyDebug.
	* @access private
	*/
	function print_rOnlyDebug($var) {
		$this->wrapper->print_rOnlyDebug($var);
	}

	/**
	* Wrap var_dumpOnlyDebug.
	* @access private
	*/
	function var_dumpOnlyDebug($var) {
		$this->wrapper->var_dumpOnlyDebug($var);
	}


    /**
     * @auhtor Yoshifumi Shiiba
     */
    function getPath($queryName){
        return "{$this->sqlDirectoryPath}{$queryName}.sql";
    }

    /**
     * @auhtor Yoshifumi Shiiba
     */
    function setSqlDirectoryPath($path){
        $this->sqlDirectoryPath = $path;
    }
}
?>