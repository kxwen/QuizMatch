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
		<title>QuizMatch!</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<style>
			
			div.cardCenter
			{
				margin-top:2%;
				margin-bottom: 30px;
				display:flex;
				justify-content:center
			}
			div.buttonCenter
			{
				margin-top:1.5%;
				display:flex;
				justify-content:center
			}
			html
			{
				height:100%;
				width:100%;
			}
			body{
				min-height:100%;
				background-color: white;
			}
			div.cardBack
			{
				 word-wrap: break-word;
			}
		</style>
	</head>
	<body>
	<div class = "container">
		<div class = "buttonCenter">
			<a href="userprofile.php" class="btn large pink rounded"><tt>Home&#x1F3E0;</tt></a>
			<a href="quiz_home.php" class="btn large pink rounded"><tt>Quizzes!&#10004;</tt></a>
		</div>
		<div class ="cardCenter">
			<div class="card" style="width:350px; height:500px">
				<div class = "container">
					
					<!-- Front of the Card -->
					<div class = "white padded rounded">
						<h2 style = "text-align: center;">
						<img src="images/default-user2.png" alt="Default User Profile" width="70%"></center></h2>
						<h4>
							<b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
						<br>
							Age: 
						<br>
							Gender:
						</h4>
						<br>
					</div>
					
					<!-- Back of the Profile Card -->
					<div class="black padded rounded">
						<div class = "cardBack">
							<h4 style = "text-align: center;">Desription:</h4>
							(Click me for more!)
							<!-- This is the description, holds 700 characters. Limit to a nice 500 characters.-->
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<div class = "container">
		<div class = "buttonCenter">
			<a href="edit_profile.php" class="btn large pink rounded"><tt>Edit Profile&#9998;</tt></a> 	
			<a href="userprofile_extended.php" class="btn large pink rounded"><tt>More Info</tt></a> 	
		</div>
	</div>
	</body>
</html>