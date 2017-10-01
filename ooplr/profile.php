<?php

require_once 'core/init.php';

$username = new User();

//echo $username->data()->username;
//echo Input::get('user');
if(!$username = Input::get('user')){
	
	//echo $username;
	//echo Input::get('user');
	//Redirect::to('index.php');
}  else {
	$username = new User();
	//echo $username->data()->username;
	$user = new User($username->data()->username);
	if(!$username->exists()){
		echo 'Not exist';
		Redirect::to(404);
	} else {
		echo 'OK';
		$data = $username->data();
	}
	?>


	<h3><?php echo escape($data->username); ?></h3>
	<p>Full Name: <?php echo escape($data->name); ?></p>

	<?php
}


