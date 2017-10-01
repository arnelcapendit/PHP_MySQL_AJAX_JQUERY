<?php 
	
	if (isset($_POST["password"])) {
		$password = $_POST["password"];
		$username = $_POST["username"];
		$loggedin = 0;
		$database = file_get_contents("database.txt");

		$pairs = explode("-", $database);

		for ($i=0; $i < 3 ; $i++) { 
			$user = explode(":", $pairs[$i]);
			if (($user[0] == $username) && ($user[1] == $password)) {
				echo "Welcome, ". $user[0]."<br><h1>Good Day, Welcome to this area!!!</h1>";
				$loggedin = 1;
			}
		}

		if($loggedin != 1){
			echo "Wrong login";
		}

	}

	
?>