<?php
	$host = "localhost";
	$username = "root";
	$password = "";
	$db = "college";
	$con = mysqli_connect($host, $username, $password, $db);


	$sqlqry = "SELECT * FROM student";

	$run = mysqli_query($con, $sqlqry);
		
		echo "
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Contact</th>
				</tr>
			</table>
		";
		while($row = mysqli_fetch_assoc($run)){
			$id = $row['id'];
			$name = $row['name'];
			$email = $row['email'];
			$contact = $row['contact'];			
			echo "
				<tr>
					<td>$id</td>
					<td>$name</td>
					<td>$email</td>
					<td>$contact</td>
				</tr></br>
			";
		}

?>


	