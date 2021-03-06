<?php


require_once('pudl.php');
require_once('pudlShellResult.php');


class pudlShell extends pudl {
	public function __construct($data) {
		$this->path = empty($data['path']) ? '' : $data['path'];
		parent::__construct($data);
	}



	function __destruct() {
		$this->disconnect();
		parent::__destruct();
	}



	public static function instance($data) {
		return new pudlShell($data);
	}



	protected function process($query) {
		$result = false;
		exec('php5 ' . escapeshellarg($this->path) . ' ' . escapeshellarg($query), $result);
		return $this->_process($result[0]);
	}



	protected function _process($json) {
		$item = new pudlShellResult($json, $this);
		$this->insertId	= $item->insertId();
		$this->updated	= $item->updated();
		$this->errno	= $item->error();
		$this->error	= $this->errno ? $item->errormsg() : '';
		return $item;
	}



	public function insertId() {
		return $this->insertId;
	}


	public function updated() {
		return $this->updated;
	}


	public function errno() {
		return $this->errno;
	}


	public function error() {
		if (!empty($this->error)) return $this->error;
		switch ($this->errno()) {
			case JSON_ERROR_NONE:			return '';
			case JSON_ERROR_DEPTH:			return 'Maximum stack depth exceeded';
			case JSON_ERROR_STATE_MISMATCH:	return 'Underflow or the modes mismatch';
			case JSON_ERROR_CTRL_CHAR:		return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:			return 'Syntax error, malformed JSON';
			case JSON_ERROR_UTF8:			return 'Malformed UTF-8 characters';
		}
		return 'Unknown error';
	}


	protected $path		= '';
	protected $errno	= false;
	protected $error	= false;
	protected $insertId	= 0;
	protected $updated	= 0;
}
