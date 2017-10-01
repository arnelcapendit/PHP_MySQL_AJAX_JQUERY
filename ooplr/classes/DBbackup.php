<?php

class DB2 {
	private $_db = null;
		private static $_instance = null;
		private $_pdo, 
			$_query, 	
			$_error = false, 
			$_results, 
			$_count = 0;

	public function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host')	. ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
			//echo 'Connected';
		} catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public static function getInstance() {
	if(!isset(self::$_instance)){
		self::$_instance = new DB2();
		}
		//echo 'Connected';
		return self::$_instance;
	}

	public function query($sql, $params = array()) {
		$this->_error = false;

		if ($this->_query = $this->_pdo->prepare($sql)){
			//echo 'Success';
			$x = 1;
			if(count($params)){
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
				echo $this->_count;
			} else {
				$this->_error = true;	
				$this->_error = $this->error();
			}
		}	

		return $this;
	}

	public function action($action, $table, $where = array()) {
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=');

			$field 		= $where[0];
			$operator	= $where[1];
			$value 		= $where[2];
			echo $field, $operator, $value;
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				echo $sql, '<br>';
				
				if(!$this->query($sql, array($value))->error()){
					//echo 'No Record Found';
					//echo $sql, '<br>';
					return $this;
				}
			}
		}

		return false;
	}

	//Getting user info
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	public function count() {
		return $this->_count;
	}

	public function error() {
		return $this->_error;
	}

}