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

require_once "quiz_DB_access_functions.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}

$location = 'images/'; 
$image_name = $location.$profile["id"].'.png';
if(!file_exists($image_name)) $image_name = $location.'default-user2.png';
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
				text-align:center;
			}
			div.buttonCenter
			{
				margin-top:3%;
				text-align:center;
			}
			#avatar
			{
				background-image: url(<?php echo ($image_name);?>);
				width: 300px;
				height: 300px;
				background-size: cover;
				background-position: center;
				border-radius:50%;
			}
		</style>
	</head>
	<body>
		<div class = "container">
			<center><h2>Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2></center>
			<br>
			<center>
			<div id="avatar"></div>
			</center>
			<br>
			<div class = "logoCenter">
				<a href="userprofile_extended.php" class="btn large white rounded"><tt>My Profile<i class="material-icons">person</i></tt></a> 	
			</div>
				<div class = "buttonCenter">
					<div class = "container">
						<a href="friends.php" class="btn large pink rounded"><tt>Friends&#128214;</tt></a> 
						<a href = "matches.php" class="btn large pink rounded"><tt>Matches&#x1F50D;</tt></a> 	
						<a class="btn large pink rounded"><tt>Messages&#9993;</tt></a>
						<a href="quiz_home.php" class="btn large pink rounded"><tt>Quizzes!&#10004 </tt></a>
						<a href="logout.php" class="btn large pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
