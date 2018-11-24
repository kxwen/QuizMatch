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
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<title>Your HomePage!</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			div.logoCenter
			{
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
		<div class = "container">
			<center><h2>Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2></center>
			<br>
			<center><img src="images/default-user2.png" alt="Default User Profile" width="15%" height="15%"></center>
			<br>
			<div class = "logoCenter">
				<a href="userprofile_extended.php" class="btn large white rounded"><tt>My Profile<i class="material-icons">person</i></tt></a> 	
			</div>
				<div class = "buttonCenter">
					<div class = "container">
						<a href="friends.php" class="btn large pink rounded"><tt>Friends</tt></a> 
						<a class="btn large pink rounded"><tt>Matches&#x1F50D;</tt></a> 	
						<a class="btn large pink rounded"><tt>Messages&#9993;</tt></a>
						<a href="quiz_home.php" class="btn large pink rounded"><tt>Quizzes!&#10004 </tt></a>
						<a href="logout.php" class="btn large pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
