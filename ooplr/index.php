<?php
require_once 'core/init.php';

//echo Config::get('mysql/host');

//$db = new DB();

//Getting Results using get functions
//$user = DB::getInstance()->query("SELECT username FROM users where username =? ", array('arnel'));
//$user = DB::getInstance()->get('users', array('username', '=', 'arnel'));
//$users = DB::getInstance()->query("SELECT * FROM users");

//$users = DB::getInstance()->get('users', array('username', '=', 'arnel'));

//$user = DB::getInstance()->get('users', array('username', '=', 'jack'));

//if(!$user->count()){
//	echo 'no user';
//} else {
//	echo 'ok!';
//	foreach ($user->results() as $user) {
//	echo $user->username, '<br>';
//	echo $user->password, '<br>';
	
//	}
//}




//Inserting Results
//$user = DB::getInstance()->insert('users', array(
//	'username' => 'Bok',
//	'password' => 'password',
//	'salt' => 'salt'
//	));

//Updating Results
//$user = DB::getInstance()->update('users', 3, array(
//	'username' => 'Bok5',
//	'password' => 'new'
//	));
//
//if(!$user){
//		echo 'user added successfully';
//} else {
//	echo 'user added failed';
//}

//Getting user results
//if(!$user->count()){
//	echo 'No user';
//} else {
	//For loopping results
	//foreach ($user->results() as $user) {
	//	echo $user->username, '<br>';
	//}

	//For Single user 
//	echo $user->first()->username;
//}

//Session successfully prompt || Template
//if(Session::exists('success')){
//	echo Session::flash('success');
//}/

if(Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

//echo Session::get(Config::get('session/session_name'));
$user = new User();

//echo $user->data()->username;
if($user->isLoggedIn()){
?>
<p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>" ><?php echo escape($user->data()->username); ?></a>!</p>
<ul>
	<li><a href="logout.php">Logout</a></li>	
	<li><a href="update.php">Update Details</a></li>
	<li><a href="changepassword.php">Change Password</a></li>
	
</ul>	

<?php
	if($user->hasPermission('admin')){
		echo "You are an administrator";
	}


} else {
	echo '<p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}






?>