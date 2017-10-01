<?php

	class person {
		public $name;
		public $age;

		public function __construct($name, $age){
			$this->name = $name;
			$this->age = $age;
		}

		public function myName(){
			return $this->name;
		}

		public function myAge(){
			return $this->age;
		}

	}
	

?>