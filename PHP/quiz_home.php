<?php
/* quiz_home.php
 * Homepage of questionaire list
 * Lists all of the questionaires available
 * Should link to individual questionaire info pages,
 * and create questionaire page. Should also allow user to go back to their profile homepage.
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset = "UTF-8">
		<title>Questionaires</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		div.topBarLayout
		{
			margin-top:2%;
		}
		div.bodyLayout
		{
			margin-top:5%;
		}
	</style>
	<header>
		<center>
			<div class = "topBarLayout">
				<a href="userprofile.php" class="btn pink rounded"><tt>Home <i class="fa fa-home"></i></tt></a>
				<a href="logout.php" class="btn pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
			</div>
		</center>
	</header>
	<body>
		<center>
		<div class = "bodyLayout">
			<div class="wrapper">
				<h2>Questionaire List</h2>
				<a href="create_quiz.php" class="btn large pink rounded"><tt>Create your own!&#x2611;</tt></a>
			</div>
		</div>
		</center>
	</body>
</html>