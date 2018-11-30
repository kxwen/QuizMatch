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
require_once "quiz_DB_access_functions.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
$current_username = $profile["username"];
$current_email = $profile["email"];
$current_desc = $profile["bio"];
$current_gender = $profile["gender"];
$location = 'images/'; 
$image_name = $location.$profile["id"].'.png';
if(!file_exists($image_name)) $image_name = $location.'default-user2.png';

$quizzes_created = getMyQuizzes($link);

$transfer_quizzes_created = json_encode($quizzes_created);
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
				background-color: #f2f2f2;
			}
			div.contentRoundBorders
			{
				border-radius:15px;
				padding:2%;
				background:white;
				margin-bottom:1%;
				margin-top:1%;
				box-shadow: 0 0 3px rgba(0,0,0,0.5);
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
			div.profileBlock
			{
				text-align: center;
				margin:4%;
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
			div.topBarLayout
			{
				margin-top:2%;
				text-align:center;
			}
		</style>
		
	</head>
	<header>
		<div class = "topBarLayout">
			<a href="userprofile.php" class="btn pink rounded"><tt>Home <i class="fa fa-home"></i></tt></a>
			<a href="quiz_home.php" class="btn pink rounded"><tt>Quizzes!&#10004;</tt></a>
			<a href="logout.php" class="btn pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
		</div>
	</header>
	<body>
		<div class = "content">
			<div class = "container center">
				<div class = "quarter white rounded" style = "min-width:325px">
					<div class = "profileBlock">
						<center>
						<div id="avatar"></div>
						<h3>
							<b><?php echo htmlspecialchars($current_username); ?></b><br>
						</h3>
						<h4>
							<?php echo htmlspecialchars($current_email); ?><br>
							<?php echo htmlspecialchars($current_gender); ?>
						</h4>
						</center>
					</div>
				</div>
				<div class = "twothirds" style = "margin-left:1%;">
					<div class = "contentRoundBorders">
						<h6>
							Bio:
						</h6>
						<p>
							<?php echo htmlspecialchars($current_desc); ?>
						</p>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Created:<br><br>
								<span id="my_quizzes"></span>
						</h6>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Taken: <br><br>
							<p>No Quizzes Have Been Taken Yet! And I Don't Work Yet Either!</p>
						</h6>
					</div>
				</div>
			</div>
			<div class = "editProfileRight">
				<a href="edit_profile.php" class="btn large pink rounded"><tt>Edit Profile&#9998;</tt></a> 	
			</div>
		</div>
		<script src="config.js"></script>
		<script>
			var my_quizzes = [];
			my_quizzes = <?= $transfer_quizzes_created?>;
			createQuizzesList(my_quizzes, "my_quizzes");
			
			function createQuizzesList(quizzes, destination){
				var num_quizzes = quizzes.length;
				var list = document.createElement("DIV");
				if(num_quizzes != 0){
					for(var i = 0; i<num_quizzes; i++){
						list.appendChild(createQuizBtnProfile(quizzes, i));
					}
				}else{
					list.innerHTML = "<p>You have not created any quizzes yet.</p>";
				}
				document.getElementById(destination).appendChild(list);
			}
		</script>
	</body>
</html>