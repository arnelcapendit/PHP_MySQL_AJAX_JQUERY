<!DOCTYPE html>
<html>
<head>
	<title>AJAX with PHP and MYSQL</title>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
			$(function(){
				//Simple Ajax Results.....
				// $.ajax({
				// 	url: "date.php",
				// 	data: "",
				// 	dataType: "text",
				// 	success: function(date){
				// 		$("#showDate").text(date);
				// 	}
				// })



				//Inserting Data.
				$("#submit").click(function(){
					event.preventDefault();
					$.ajax({
					method: "POST",
					url: "insert.php",
					data: $("form").serialize(),
					dataType: "text",
					success: function(data){
						$("#message").text(data);
					}
				});
				});
				
			});

	</script>
</head>
<body>
<h1>Hello World</h1>
<div id="">
	<p id="message"></p>
	<form method="POST">
		<table>
				<tr>
					<th>Name</th>
					<td>
						<input id="name" type="text" name="name">
					</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>
						<input id="email" type="text" name="email">
					</td>
				</tr>
				<tr>
					<th>Contact</th>
					<td>
						<input id="contact" type="text" name="contact">
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input id="submit" type="submit" name="submit">
					</td>
				</tr>	
				<tr>
					<td>
						<a href="select.php">Load Data</a>
					</td>
				</tr>
						
		</table>

	</form>
</div>
	

</body>
</html>