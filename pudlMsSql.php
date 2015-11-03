<?php


require_once('pudl.php');
require_once('pudlMsSqlResult.php');


class pudlMsSql extends pudl {
	public function __construct($data, $autoconnect=true) {
		parent::__construct($data);

		$this->escstart	= '[';
		$this->escend	= ']';
		$this->top		= true;

		//CONNECT TO THE SERVER
		if ($autoconnect) $this->connect();
	}



	public static function instance($data, $autoconnect=true) {
		return new pudlMsSql($data, $autoconnect);
	}



	public function connect() {
		$auth = $this->auth();

		$this->mssql = @mssql_pconnect(
			$auth['server'],
			$auth['username'],
			$auth['password']
		);

		if (!$this->mssql) {
			$this->mssql = @mssql_connect(
				$auth['server'],
				$auth['username'],
				$auth['password']
			);
		}

		if (!$this->mssql) {
			$error  = "<br />\n";
			$error .= 'Unable to connect to database server: "' . $auth['server'];
			$error .= '" with the username: "' . $auth['username'];
			$error .= "\"<br />\nError " . $this->errno() . ': ' . $this->error();
			die($error);
		}

		if (!@mssql_select_db($auth['database'], $this->mssql)) {
			$error  = "<br />\n";
			$error .= 'Unable to select database : "' . $auth['database'];
			$error .= "\"<br />\nError " . $this->errno() . ': ' . $this->error();
			die($error);
		}
	}



	protected function process($query) {
		if (!$this->mssql) return false;
		$result = @mssql_query($query, $this->mssql);
		return new pudlMsSqlResult($result, $this);
	}


	public function insertId() {
		if (!$this->mssql) return 0;
		$result = @mssql_query('SELECT @@IDENTITY', $this->mssql);
		if ($result === false) return false;
		$return = @mssql_result($result, 0, 0);
		@mssql_free_result($result);
		return $return;
	}


	public function updated() {
		if (!$this->mssql) return 0;
		$result = @mssql_query('SELECT @@ROWCOUNT', $this->mssql);
		if ($result === false) return false;
		$return = @mssql_result($result, 0, 0);
		@mssql_free_result($result);
		return $return;
	}


	public function errno() {
		return (int) !empty($this->error());
	}


	public function error() {
		return @mssql_get_last_message();
	}


	private $mssql	= false;
}
