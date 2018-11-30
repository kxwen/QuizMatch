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

$num_questions = 0;

if ($current_size == "small")
	$num_questions = 5;
else if ($current_size == "medium")
	$num_questions = 10;
else if ($current_size == "large")
	$num_questions = 15;

// Array containing each question from specified quiz
$quiz_questions = getQuizQuestions($link, $current_quiz);

// Array containing each set of answers for specified question
$quiz_answers = array();

// For each question, retrieve set of answers for specified quiz id
// quiz
for ($i = 0; $i < $num_questions; $i++) {

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


<!DOCTYPE html>
<head>
	<title>Quiz Match</title>
	<link href="stupid.css" type="text/css" rel="stylesheet" />
</head>

<body>
	<center>

	<div class="contentRoundBorders" id="quizForm">
		<form class="form" method="POST" enctype="application/x-www-form-urlencoded" action="questions_result.php" name="quiz">
			<h2> <?php echo "Quiz: " . $current_name;?> </h2>
			<h4> <?php echo $current_desc;?> </h4><br><br>
			<span id="Quiz_Pages"></span>

			<button type="button" class ="btn pink rounded" id="prevBtn"
				onclick="nextPrev(-1);">Previous</button>
			<button type="button" class ="btn pink rounded" id="nextBtn"
				onclick="nextPrev(1);">Next</button>
			<div style="text-align:center;margin-top:40px;">
				<span id="Quiz_Page_Steps"></span>
			</div>
		</form>

	</div>	
	<script src="config.js"></script>
	<script>
			var currentTab = 0; // Current tab is set to be the first tab (0)
			var max_answers = 4;
			var total_questions =  <?=$num_questions?>;
			var questions = [];
			questions = <?= json_encode($quiz_questions)?>;
			var answers = [];
			answers = <?= json_encode($quiz_answers)?>;

			

			displayQuestionsByTab(currentTab, questions, answers);

			function displayQuestionsByTab(currentTab, questions, answers) {
				createQuestionPages(currentTab, questions, answers);
				showTab(currentTab);

			}

			function createQuestion() {
				var question_tab;
				var question_step;
				for(var i =0;i<total_questions; i++){
					question_tab = document.createElement("DIV");
					var question_content;
				
					question_content = document.createTextNode(questions[i]["question"]);
					question_tab.appendChild(question_content);
					question_tab.innerHTML += "<br>";

					for (var j = 0; j < answers[i].length; j++) {

						var ans_content = document.createTextNode(" "+answers[i][j]["answer"]);
						var radio = document.createElement("input");

						radio.setAttribute("type", "radio");
						radio.setAttribute("name", "q_" + i + "_a");
						radio.setAttribute("value", answers[i][j]["trait"]);
						question_tab.appendChild(radio);
						question_tab.appendChild(ans_content);
						question_tab.innerHTML += "<br>";
					}

					question_step = document.createElement("DIV");
					question_tab.setAttribute("class", "tab");
					question_step.setAttribute("class", "step");
					document.getElementById("total_pages").appendChild(question_tab);
					document.getElementById("total_steps").appendChild(question_step);
				}

			}

			function createQuestionPages(currentTab, questions, answers) {
				var total_pages = document.createElement("DIV");
				var total_steps = document.createElement("DIV");
				total_pages.setAttribute("id", "total_pages");
				total_steps.setAttribute("id", "total_steps");
				document.getElementById("Quiz_Pages").appendChild(total_pages);
				document.getElementById("Quiz_Page_Steps").appendChild(total_steps);
				createQuestion();
			}


			


	</script>
</center>
</body>

</html>
