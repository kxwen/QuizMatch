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
$q = htmlspecialchars($_GET["q"]);
if(!isset($quiz)){
	$sql = "SELECT * FROM quizzes WHERE id =".$q;
	$quiz_entry = mysqli_query($link, $sql);
	if(!($quiz = mysqli_fetch_assoc($quiz_entry))){
		echo "<script>alert('Quiz does not exist or has been removed'); window.location.href='quiz_home.php';</script>";
	}
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
	$quiz_answers[] = getQuestionAnswers($link, $quiz_questions[$i]["id"]);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 	header("quiz_results.php");
}
?>


<!DOCTYPE html>
<head>
	<title>Quiz Match</title>
	<link href="stupid.css" type="text/css" rel="stylesheet" />
</head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
	div.topBarLayout
	{
		margin-top:2%;
	}
	div.contentRoundBorders
	{
		border-radius:15px;
		padding:1%;
		background:white;
		margin-bottom:1%;
		margin-top:1%;
		margin-left:5%;
		margin-right:5%;
		box-shadow: 0 0 3px rgba(0,0,0,0.5);
	}
</style>
<header>
	<center>
		<div class = "topBarLayout">
			<a href="quiz_home.php" class="btn pink rounded"><tt>Cancel </tt></a>
			<a href="userprofile.php" class="btn pink rounded"><tt>Home <i class="fa fa-home"></i></tt></a>
			<a href="logout.php" class="btn pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
		</div>
	</center>
</header>
<body>
	<center>

	<div class="contentRoundBorders">
		<form id="quizForm" class="form" method="POST" enctype="application/x-www-form-urlencoded" action="quiz_results.php" name="quiz">
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
			<input type="hidden" name="num_questions" value="<?=$num_questions?>">
			<input type="hidden" name="quiz_id" value="<?=$current_quiz?>">
			<input type="hidden" name="quiz_name" value="<?=$current_name?>">
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
			
			displayQuestionsByTab();
			function displayQuestionsByTab() {
				createQuestionPages();
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
						if(j == 0) radio.setAttribute("checked", "");
						question_tab.appendChild(radio);
						question_tab.appendChild(ans_content);
						question_tab.innerHTML += "<br>";
					}
					question_tab.innerHTML +="<br>";
					question_step = document.createElement("DIV");
					question_tab.setAttribute("class", "tab");
					question_step.setAttribute("class", "step");
					document.getElementById("total_pages").appendChild(question_tab);
					document.getElementById("total_steps").appendChild(question_step);
				}
			}
			function createQuestionPages() {
				var total_pages = document.createElement("DIV");
				var total_steps = document.createElement("DIV");
				total_pages.setAttribute("id", "total_pages");
				total_steps.setAttribute("id", "total_steps");
				document.getElementById("Quiz_Pages").appendChild(total_pages);
				document.getElementById("Quiz_Page_Steps").appendChild(total_steps);
				createQuestion();
			}
			function getNumQuestions() {
				return total_questions;
			}
			
	</script>
</center>
</body>

</html>
