<?php
/* userprofile_extended.php
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
		<title>QuizMatch: Profile Extended</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			html
			{
				height:100%;
			}
			div.content
			{
				margin:1%;
				margin-right:2%;
				word-wrap: break-word;
			}
			div.contentBarrier
			{
				padding:1%;
			}
			body
			{
				background-color: #f2f2f2;
			}
		</style>
	</head>
	<body>
		<div class = "content">
			<div class = "container center">
				<div class = "quarter white rounded" style = "height:500px">
					<div class = "padded">
						<h2><center><img src="images/default-user2.png" alt="Default User Profile" width="75%"></center></h2><br>
						<h3>
						Name <br>
						Age <br>
						Gender <br>
						</h3>
					</div>
				</div>
				<div class = "twothirds white rounded" style = "margin-left:1%; height: 500px;">
					<div class = "container">
						<div style = "padding:3%">
							<h6>
								Description:
							</h6>
							<p>
								Hello, my name is Jim. I like to be a total creep on the internet. 
								Why you ask? I dunno. The person writing this really needed some filler content to make sure that it 
								hits the total character limit to see how much space is needed to incoporate for about... 500 characters? 
								Did I hit it yet? No? Are we there yet? Are we there yet? Are we there yet? No? No? No? No?
								Oh, ma ma ma mia figero! Beelzebub has a devil sights for me! For me! FOR ME!!! 
								Play that Queen music! Hit the funky music white boy!1
								----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
							</p>
						</div>
					</div>
					<div class = "container">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>