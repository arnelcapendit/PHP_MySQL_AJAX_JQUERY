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



			// 	//Inserting Data.
			// 	$("#submit").click(function(){
			// 		event.preventDefault();
			// 		$.ajax({
			// 		method: "POST",
			// 		url: "insert.php",
			// 		data: $("form").serialize(),
			// 		dataType: "text",
			// 		success: function(data){
			// 			$("#message").text(data);
			// 		}
			// 	});
			// 	});
				
			// });

						//Selecting Data.
				$("#load").click(function(){
					event.preventDefault();
					$.ajax({
					url: "load.php",
					dataType: "html",
					success: function(data){
						$("#loadarea").html(data);
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
					<td>
						<input id="load" type="button" name="button" value="Load Data">
					</td>
					<td>
						<a href="index.php">Insert Data</a>
					</td>
				</tr>		
		</table>

	</form>
</div>
	
<div id="loadarea"></div>

</body>
</html>