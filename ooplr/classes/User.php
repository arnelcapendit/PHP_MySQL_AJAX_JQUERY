<?php

class User {
	private $_db = null,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;

	public function __construct($user = null){
		 $this->_db = DB::getInstance();
		 
		$this->_sessionName = Config::get('session/session_name');
		 $this->_cookieName = Config::get('remember/cookie');
		 //echo Config::get('session/session_name');


		 if(!$user){
		 	if(Session::exists($this->_sessionName)){
		 		$user = Session::get($this->_sessionName);
		 		if($this->find($user)){
		 			$this->_isLoggedIn = true;
		 		} else {
		 			//process logout
		 		}
		 	} else {
		 		 $this->find($user);
		 		
		 	}
		 }
	}

	public function update($fields = array(), $id = null){

		if(!$id && $this->isLoggedIn()){
			$id = $this->data()->id;
		} else if(!$this->_db->update('users', $id, $fields)){
			throw new Exception('There was a problem updating!');
		}
	}


	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){
			throw new Exception('There was a problem creating an account!');
		}
	}

	public function find($user = null){
		//echo $user;
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			//echo $field;
			$data = $this->_db->get('users', array($field, '=', $user));
			//print($data->count());
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}

		return false;
	}

	public function login($username= null, $password = null, $remember = false){
		
		if(!$username && !$password && $this->exists()){	
			Session::put($this->_sessionName, $this->data()->id);
		} else {
				$user = $this->find($username);
				if($user){
					if($this->data()->username === Input::get('username')) {
						Session::put($this->_sessionName, $this->data()->id);
						//echo 'OK';
						if($remember){
							$hash = Hash::unique();
							$hasCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

							if(!$hasCheck->count()){
								$this->_db->insert('users_session', array(
									'user_id' => $this->data()->id,
									'hash' => $hash
								));
							} else {
								$hash = $hasCheck->first()->hash;
							}

							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
						}
						return true;
					}	
				}
			}
		return false;
	}

	public function hasPermission($key){
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		//print_r($group->first());
		if($group->count()){
			$permissions = json_decode($group->first()->permissions, true);
			//print_r($permissions);
			if($permissions[$key] == true){
				//echo 'Okay na po';
				return true;
			}
		}

	}


	public function logout(){
	 	$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

	 	Session::delete($this->_sessionName);
	 	Cookie::delete($this->_cookieName);
	}

	public function data() {
		return $this->_data;
	}

	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}

	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
}