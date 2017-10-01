<?php

	$host = "localhost";
	$username = "root";
	$password = "";
	$db = "college";
	$con = mysqli_connect($host, $username, $password, $db);
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$contact = $_POST['contact'];

	$sqlqry = "INSERT INTO student values (null, '$name', '$email', '$contact')";

	$run = mysqli_query($con,$sqlqry);

	if($run){
		echo "Record successfully inserted";
	} else {
		echo "incorrect";
	}
?>	