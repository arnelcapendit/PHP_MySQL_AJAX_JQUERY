<?php
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'lr'
	),

	'remember' => array(
		'cookie' => 'hash',
		'cookie_expiry' => 604800
	),

	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);


spl_autoload_register(function($class){
	require_once 'classes/' . $class . '.php';
	//echo 'classes/' . $class . '.php';
});


require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie')) && !Session::exists(Config::get('session/session_name'))){
	//echo 'users are being modified';
	$hash = Cookie::get(Config::get('remember/cookie'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

	if($hashCheck->count()){
		//echo $hashCheck->first()->user_id;
		$user = new User($hashCheck->first()->user_id);
		$user->login();
		//var_dump($user->login());
		//echo 'hashes matches';
	}
}



?>
