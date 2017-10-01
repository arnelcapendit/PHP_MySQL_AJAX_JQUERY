<!DOCTYPE html>
<html>
<head>
	<title>jQuery Portfolio Tutorials</title>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/filter.js"></script>
	<script type="text/javascript" src="js/jquery-ui-custom-1.12.1.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">


</head>
<body>
<div id="header">
	<div id="menu">
		<h1>Naruto Uzumaki Portfolio</h1>
		<input id="search" type="text" name="search" placeholder="search...">
	</div>
</div>

<div id="overlay"></div>
<div id="frame">
	<table id="frame-table">
		<tr>
			<td id="left">
				<img src="images/left.png" alt="left"/>
			</td>
			<td id="right">
				<img src="images/right.png" alt="right"/>
			</td>
		</tr>
	</table>
	<img id="main" src="" alt=""/>
	<div id="description">
		<p></p>
	</div>
</div>
	<div id="wrapper">
	<ul id="filter">
		<li class="active">all</li>
		<li>naruto</li>
		<li>shippuden</li>
		<li>uzumaki</li>
	</ul>

		<ul id="portfolio">
			<?php include_once("list.html")?>
		</ul>

	</div>
</body>
</html>