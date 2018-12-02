<?php
/* quiz_results.php
 * Page displaying results of a quiz
 * For a specified quiz will display the unique trait values
 * This is a destination page from quiz_take.php
 * Info is not saved to database or file at the moment.
 * 0 - SANGUINE, 1 - PHLEGMATIC, 2 - CHOLERIC, 3 - MELANCHOLIC
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
} 
require_once "categorize.php";
$num_traits = 4;
$num_questions = $_POST["num_questions"];
for ($i = 0; $i < $num_traits; $i++)
		$values[$i] = 0;
for ($i = 0; $i < $num_questions; $i++) {
	$answers[$i] = $_POST['q_'.$i.'_a'];
	if ($answers[$i] == "SANGUINE") { $values[0]++; }
	else if ($answers[$i] == "PHLEGMATIC") { $values[1]++; }
	else if ($answers[$i]== "CHOLERIC") { $values[2]++; }
	else if ($answers[$i] == "MELANCHOLIC") { $values[3]++; }
}
categorizeUser($values);
writeUser_results($_POST["quiz_id"]);
echo "<br> Your personality values are:  <br><br>";
echo "SANGUINE +" . $values[0] . "<br>PHLEGMATIC + " . $values[1] . "<br>CHOLERIC +" . $values[2] . "<br>MELANCHOLIC +" . $values[3];
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>QuizMatch: Your Results!</title>
	<link rel="stylesheet" href="stupid.css">
	<style type="text/css"></style>
</head>
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
<div class="form">
	<h2>Quiz Match Results</h2> 
	<a class="btn pink rounded" href="quiz_home.php">Return to Quiz List</a>
	<a class="btn pink rounded" href="matches.php">See your Matches</a>
</div>
<div class="extra">
	<br>Find out more about each: <br><br>
	<a class ="btn pink rounded" href="https://psychologia.co/sanguine-personality/" target="_blank" data-tooltip="" data-tooltip-position="top"</ data-tooltip-message="People with the Sanguine trait are often cheerful, creative, and optimistic. Click this button to learn more.">Sanguine</a>
	<a class ="btn pink rounded" href="https://psychologia.co/phlegmatic-personality/" target="_blank" data-tooltip="" data-tooltip-position="top" data-tooltip-message="People with the Phlegmatic trait are often agreeable, cooperative, and considerate. Click this button to learn more.">Phlegmatic</a>
	<a class ="btn pink rounded" href="https://psychologia.co/choleric-personality/" target="_blank" data-tooltip="" data-tooltip-position="top" data-tooltip-message="People with the Choleric trait are often practical, independent, and tough-minded. Click this button to learn more.">Choleric</a>
	<a class ="btn pink rounded" href="https://psychologia.co/melancholic-personality/" target="_blank" data-tooltip="" data-tooltip-position="top" data-tooltip-message="People with the Melancholic trait are often loyal, calm, and patient. Click this button to learn more.">Melancholic</a>
</div>

</center>
</body>
</html>