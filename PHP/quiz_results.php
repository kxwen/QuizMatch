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
require_once "quiz_take.php";

$num_traits = 4;
$num_questions = getNumQuestions();

for ($i = 0; $i < $num_traits; $i++)
		$values[$i] = 0;

for ($i = 0; $i < $num_questions; $i++) {
	$answers[$i] = $_POST['q_ ' + i + '_a'];

	if ($answers[$i] == "SANGUINE") { $values[0]++; }
	else if ($answers[$i] == "PHLEGMATIC") { $values[1]++; }
	else if ($answers[$i]== "CHOLERIC") { $values[2]++; }
	else if ($answers[$i] == "MELANCHOLIC") { $values[3]++; }
}

categorizeUser($values);


echo "<br> Your personality values are:  <br><br>";
echo "SANGUINE +" . $values[0] . "<br>PHLEGMATIC + " . $values[1] . "<br>CHOLERIC +" . $values[2] . "<br>MELANCHOLIC +" . $values[3];

?>
<html>
<head>
	<style>
	
	.form {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size: 1.2em;
	width: 30em;
	padding: 3em;
	border: 2px solid #ccc;
	}

	.extra {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size: 0.8em;
	}

	.tab { margin-left: 30px; }

	</style>
</head>
<body>
<center>
<div class="form">
<h1>Quiz Match Results</h1> 
</div>
<div class="extra">
<br>Find out more about each: <br><br>
<a href="https://psychologia.co/sanguine-personality/">SANGUINE </a>
<a class="tab" href="https://psychologia.co/phlegmatic-personality/">PHLEGMATIC </a>
<a class="tab" href="https://psychologia.co/choleric-personality/">CHOLERIC </a>
<a class="tab" href="https://psychologia.co/melancholic-personality/">MELANCHOLIC </a>
</div>

</center>
</body>
</html>
