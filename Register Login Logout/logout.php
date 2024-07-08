
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
		<link type="text/css" rel="stylesheet" href="style.css"/>
		<title>Logged Out</title>
	</head>
	
<body>

	<?php
		include('header.php');
	?>
				
	<?php
		session_start();
		unset($_SESSION['Username']);
		session_destroy();
	?>

	<br><br>
	<div class="loggedout">
		<h2>You have successfully logged out</h2><br>
		<div class='Form'><h3><a href='login.php'>Log back in here</a> <br></h3></div>
	</div>
	<br><br>

	<?php
		include('footer.php');
	?>
	
</body>
</html>