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
			body, html
			{
				height:100%;
			}
			body
			{
				background-color: #f2f2f2;
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
			div.buttonSide
			{
				display:flex;
				justify-content: space-between;
				padding: 0% 3% 0% 5%;
			}
			div.editProfileRight
			{
				display:flex;
				justify-content: flex-end;
				padding-right: 3%;
			}
			div.profileImage
			{
				width: 270px;
				height: 270px;
				position: relative;
				overflow: hidden;
				border-radius: 50%;
			}
			#avatar
			{
				background-image: url('images/mega.jpg');
				height: 300px;
				width: auto;
				background-size: cover;
				background-position: center;
				border-radius: 50%;
			}
			div.child
			{
				width: 100%;
				border:1px solid blue;
			}
		</style>
		<script>
			var cw = $('.child').width();
			$('.child').css(
			{
				'height':cw + 'px'
			});
				
		</script>
	</head>
	<body>
		<div class = "content">
			<div class = "container">
				<div class = "fill">
					<div class = "buttonSide">
					<a href="userprofile.php" class="btn large pink rounded"><tt>Home&#x1F3E0;</tt></a>
					<a href="quiz_home.php" class="btn large pink rounded"><tt>Quizzes!&#10004;</tt></a>
					</div>
				</div>
			</div>
			
			<div class = "container center">
				<div class = "quarter white rounded">
					<div class = "padded">
					<!--	<div class = "child">
							<center><div id="avatar"></div></center>
						</div>
							<!--<h2><center><img src="images/mega.jpg" alt="Default User Profile"></center></h2><br>-->
						<h3>
							<b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br>
							Age <br>
							Gender <br>
						</h3>
					</div>
				</div>
				<div class = "twothirds" style = "margin-left:1%;">
					<div class = "contentRoundBorders">
						<h6>
							Bio:
						</h6>
						<p>
							
						</p>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Created:
							<div class = "contentRoundBorders">
								<p>I go here!</p>
							</div>
						</h6>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Taken:
							<div class = "contentRoundBorders">
								<p>I go here!</p>
							</div>
						</h6>
					</div>
				</div>
			</div>
			<div class = "editProfileRight">
				<a href="edit_profile.php" class="btn large pink rounded"><tt>Edit Profile&#9998;</tt></a> 	
			</div>
		</div>
	</body>
</html>