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
require_once "funct_user_info.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
$current_username = $profile["username"];
$current_email = $profile["email"];
if(!empty($profile["bio"])){
	$current_desc = $profile["bio"];
}else{
	$current_desc = "No biography yet. You can make one through the edit profile button below.";
}
$current_gender = $profile["gender"];
$location = 'images/'; 
$image_name = $location.$profile["id"].'.png';
if(!file_exists($image_name)) $image_name = $location.'default-user2.png';

$quizzes_created = getMyQuizzes($link);
$my_results_raw = getMyResults($link);
$quizzes_taken = array();
$my_results = array();
for($i = 0; $i < sizeof($my_results_raw); $i++){
	$quizzes_taken[] = getQuizInfo($link, $my_results_raw[$i]["quiz_id"]);
	$my_results[] = getQuizResult($link, $my_results_raw[$i]["result_id"]);
}

$transfer_quizzes_created = json_encode($quizzes_created);
$transfer_quizzes_taken = json_encode($quizzes_taken);
$transfer_my_results = json_encode($my_results);
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
		<title>QuizMatch: My Profile</title>
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
							<?php echo htmlspecialchars($current_email); ?><br><br>
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
							Quizzes Created:
						</h6>
						<p>
							<span id="my_quizzes"></span>
						</p>
					</div>
					
					<div class = "contentRoundBorders">
						<h6>
							Quizzes Taken:
						</h6>
						<p>
							<span id="taken_quizzes"></span>
						</p>
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
			var taken_quizzes = [];
			var my_results = [];
			my_quizzes = <?= $transfer_quizzes_created?>;
			taken_quizzes = <?= $transfer_quizzes_taken?>;
			my_results = <?=$transfer_my_results?>;
			createQuizzesList(my_quizzes, "my_quizzes");
			createTakenQuizzesList(taken_quizzes, my_results, "taken_quizzes");
			
			function createQuizzesList(quizzes, destination){
				var num_quizzes = quizzes.length;
				var list = document.createElement("DIV");
				if(num_quizzes != 0){
					for(var i = 0; i<num_quizzes; i++){
						list.appendChild(createQuizBtnProfile(quizzes, i));
					}
				}else{
					list.innerHTML = "You have not created any quizzes yet.";
				}
				document.getElementById(destination).appendChild(list);
			}
			
			function createTakenQuizzesList(quizzes, results, destination){
				var num_quizzes = quizzes.length;
				var list = document.createElement("DIV");
				if(num_quizzes != 0){
					for(var i = 0; i<num_quizzes; i++){
						list.appendChild(createTakenQuizBtn(quizzes, results, i));
					}
				}else{
					list.innerHTML = "You have not taken any quizzes yet.";
				}
				document.getElementById(destination).appendChild(list);
			}
		</script>
	</body>
</html>