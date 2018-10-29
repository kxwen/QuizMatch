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
		<link rel ="stylesheet" href="stupid.css">
		<style type="text/css"></style>
	</head>
	<header>
		<center>
			<a href="userprofile.php" class="btn pink rounded"><tt>Home</tt></a>
			<a href="logout.php" class="btn pink rounded"><tt>Logout</tt></a>
		</center>
	</header>
	<body>
		<center>
			<div class="wrapper">
				<h2>Questionaire List</h2>
				<a href="create_quiz.php" class="btn large pink rounded"><tt>Create your own!</tt></a>
			</div>
		</center>
	</body>
</html>