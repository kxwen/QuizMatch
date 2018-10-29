<?php
/* userprofile.php
 * altered Vin's homepage to verify that user has signed in.
 * If they are not signed in, they are redirected to the login page.
 * This prevents the user from using the back button of their browser
 * to return here after they had already signed out.
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
?>
<!-- 
This is the homepage for QuizMatch. It contains links to the login page and
the sign up page.
Hovering over the card QuizMatch will produce one of many random anecdotes.
-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Your Page!</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			div.logoCenter
			{
				margin-top:20%;
				display:flex;
				justify-content:center
				
			}
			div.buttonCenter
			{
				margin-top:3%;
				display:flex;
				justify-content:center
			}
		</style>
	</head>
	<body>
		<center><h2>Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2></center>
		<div class = "logoCenter">
			<a href="login.php" class="btn large white rounded"><tt>My Profile<i class="material-icons">person</i></tt></a> 	
		</div>

		<div class = "buttonCenter">
			<a class="btn large pink rounded"><tt>Matches&#x1F50D;</tt></a> 	
			<a class="btn large pink rounded"><tt>Messages&#9993;</tt></a>
			<a href="quiz_home.php" class="btn large pink rounded"><tt>Test Me!&#10004 </tt></a>
			<a href="logout.php" class="btn large pink rounded"><tt>Logout</tt></a>
		</div>
	</body>
</html>