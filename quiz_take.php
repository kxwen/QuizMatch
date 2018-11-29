<?php
/* quiz_take.php
 * Page used to take a questionnaire
 * Contains a set amount of questions for user to answer and submit
 * For a specified quiz_id will display all necessary info
 * Uses the quiz, question, answer, quiz results from the DB to display quiz
 * This is a destination page from quiz_home.php
 * Info is not saved to database or file at the moment.
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "quiz_DB_access_functions.php";

if(!isset($quiz)){
	$sql = "SELECT * FROM quizzes WHERE owner_id =".$_SESSION["id"];
	$quiz_entry = mysqli_query($link, $sql);
	$quiz = mysqli_fetch_assoc($quiz_entry);
}

$current_quiz = $quiz["id"];
$current_owner = $quiz["owner_id"];
$current_name = $quiz["name"];
$current_desc = $quiz["description"];
$current_size = $quiz["size"];

// Array containing each question from specified quiz
$quiz_questions = getQuizQuestions($link, $current_quiz);

// Array containing each set of answers for specified question
$quiz_answers = array();

// For each question, retrieve set of answers for specified quiz id
// quiz
for ($i = 0; $i < 20; $i++) {

	if(!isset($question)){
		$sql = "SELECT * FROM questions WHERE quiz_id = ".$current_quiz;
		$question_entry = mysqli_query($link, $sql);
		$question = mysqli_fetch_assoc($question_entry);
	}

	$current_question_id = $question["id"];
	// $current_quiz_id = $question["quiz_id"];
	// $current_question = $question["question"];
	$quiz_answers[$i] = getQuestionAnswers($link, $current_question_id);
}

?>


<html>
<head>
	<title>Quiz Match</title>
	<!-- <link href="stupid.css" type="text/css" rel="stylesheet" /> -->
	<style>
	.form {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size: 0.8em;
	width: 55em;
	padding: 2em;
	border: 2px solid #ccc;
	}

	.form fieldset {
	border: none;
	padding-left: 20;
	}

	.form legend,
	.form label {
	padding: 5;
	font-weight: bold;
	}

	.form label.choice {
	font-size: 0.9em;
	font-weight: normal;
	}

	.form button {
	padding: 1em;
	border-radius: 0.5em;
	background: #eee;
	border: none;
	font-weight: bold;
	margin-top: 1em;
	}

	</style>
</head>
<body>
	<center>
	<h2> <?php echo "Quiz: " . $current_name;?> </h2>
	<h4> <?php echo $current_desc;?> </h4>
	<form class="form" method="POST" enctype="application/x-www-form-urlencoded" action="questions_result.php" name="quiz">

	<fieldset>

	<legend> <?php echo $quiz_questions[0]["question"];?> </legend>
	<p><label class="choice"> <input type="radio" name="r1" value="A"> <?php echo $quiz_answers[0][0]["answer"];?> </label></p>
	<p><label class="choice"> <input type="radio" name="r1" value="B"> <?php echo $quiz_answers[0][1]["answer"];?> </label></p><br><br>

	<legend> <?php echo $quiz_questions[1]["question"];?> </legend>
	<p><label class="choice"> <input type="radio" name="r1" value="A"> <?php echo $quiz_answers[1][0]["answer"];?> </label></p>
	<p><label class="choice"> <input type="radio" name="r1" value="B"> <?php echo $quiz_answers[1][1]["answer"];?> </label></p><br><br>

	<legend> <?php echo $quiz_questions[2]["question"];?> </legend>
	<p><label class="choice"> <input type="radio" name="r1" value="A"> <?php echo $quiz_answers[2][0]["answer"];?> </label></p>
	<p><label class="choice"> <input type="radio" name="r1" value="B"> <?php echo $quiz_answers[2][1]["answer"];?> </label></p><br><br>

	<legend> <?php echo $quiz_questions[3]["question"];?> </legend>
	<p><label class="choice"> <input type="radio" name="r1" value="A"> <?php echo $quiz_answers[3][0]["answer"];?> </label></p>
	<p><label class="choice"> <input type="radio" name="r1" value="B"> <?php echo $quiz_answers[3][1]["answer"];?> </label></p><br><br>

	<legend> <?php echo $quiz_questions[4]["question"];?> </legend>
	<p><label class="choice"> <input type="radio" name="r1" value="A"> <?php echo $quiz_answers[4][0]["answer"];?> </label></p>
	<p><label class="choice"> <input type="radio" name="r1" value="B"> <?php echo $quiz_answers[4][1]["answer"];?> </label></p>


	<input type="submit" value="Submit Quiz" />

	</fieldset>
</center>
</form>
</body>

</html>
