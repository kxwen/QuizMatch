<!-- 
This is the homepage for QuizMatch. It contains links to the login page and
the sign up page.
Hovering over the card QuizMatch will produce one of many random anecdotes.
-->

<?php
/* userprofilecard.php
 * altered Richard's login verification to verify that user has signed in.
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

<!DOCTYPE html>
<html lang = "en">
	<head>
		<meta charset="utf-8">
		<title>Your Matches</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			html
			{
				height:100%;
				width:100%;
			}
			body
			{
				min-height:100%;
				background-color: white;
			}
			div.buttonCenter
			{
				margin-top:1.5%;
				display:flex;
				justify-content:center
			}
			div.matches
			{
				margin-top: -50px;
			}
			div.contentRoundBorders
			{
				border-radius:15px;
				padding:1%;
				background:white;
				margin-bottom:1%;
				margin-top:1%;
				box-shadow: 0 0 3px rgba(0,0,0,0.5);
			}
			div.buttonSide
			{
				display:flex;
				justify-content: space-between;
				padding: 1% 3% 1% 5%;
			}
		</style>
	</head>
	<body>
	<div class = "container fill">
		<div class = "buttonSide">
			<a href="userprofile.php" class="btn large pink rounded"><tt>Home&#x1F3E0;</tt></a>
			<a href="userprofile_extended.php" class="btn large pink rounded"><tt>My Profile<i class="material-icons">person</i></tt></a>
		</div>
	</div>
	<div class = "container">
		<div class = "matches">
			<h1><center>Matches&#x1F50D;</center></h1>
		</div>
		<div class = "container center">
			<div class = "threequarters">
				<div class = "contentRoundBorders">
					<h2 style = "margin-top:-7px">
						Quizzes Created:
					</h2>
					<div class = "contentRoundBorders">
						<p>
							I go here!
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class = "container fill">
		<div class = "buttonSide">
			<a href="userprofile.php" class="btn large pink rounded"><tt>Home&#x1F3E0;</tt></a>
			<a href="userprofile_extended.php" class="btn large pink rounded"><tt>My Profile<i class="material-icons">person</i></tt></a>
		</div>
	</div>
	</body>
</html>