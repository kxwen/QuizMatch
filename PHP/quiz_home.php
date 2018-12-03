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
require_once "quiz_DB_access_functions.php";
$quizzes = getOtherQuizzes($link);
$transfer = json_encode($quizzes);
$count = 0;
 for ($i = 0; $i < 46; $i++) { 
		$names[$i] = "Quiz #" . $i;
		$descs[$i] = "description #" . $i;
 }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset = "UTF-8">
		<title>Questionnaires</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		div.topBarLayout
		{
			margin-top:2%;
		}
		div.bodyLayout
		{
			margin-top:5%;
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
				<a href="userprofile.php" class="btn pink rounded"><tt>Home <i class="fa fa-home"></i></tt></a>
				<a href="logout.php" class="btn pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
			</div>
		</center>
	</header>
	<body>
		<div class="container">
		<center>
			<b><h2>Questionaire List&#10004</h2></b>
			<br>
			<a href="create_quiz.php" class="btn large pink rounded"><tt>Create your own!&#x2611;</tt></a>
			<button class="btn large pink rounded" onclick="selectRandom();"><tt>Take a random Quiz!&#127922;</tt></button>
		<div>
		<br>
		Filter:
		<select style="width:10%" id="target">
			<option value="name">Name</option>
			<option value="size">Size</option>
		</select>
		<select style = "width:10%" id="order">
			<option value="asc">Descending</option>
			<option value="desc">Ascending</option>
		</select>
		Search:
		<input type="text" value="" placeholder="Quiz Name" style="width:15%; font-family: Helvetica" id="phrase">
		<button type="button" class ="btn small pink rounded" id="search"
				onclick="searchDB();">Go</button>
		</div>
		<br>
		<div class="contentRoundBorders">
			<span id="Quiz_Pages"></span>
		</div>
		<div>
			<button type="button" class ="btn pink rounded" id="prevBtn"
				onclick="nextPrev(-1);">Previous</button>
			<button type="button" class ="btn pink rounded" id="nextBtn"
				onclick="nextPrev(1);">Next</button>
		</div>
		<div style="text-align:center;margin-top:40px;">
			<span id="Quiz_Page_Steps"></span>
		</div>
		<script src="config.js"></script>
		<script>
			var currentTab = 0; // Current tab is set to be the first tab (0)
			var quizzes = [];
			quizzes = <?=$transfer?>;
			var total_quizzes = quizzes.length;
			var max_quiz_page = 10; // number of quizzes that can be diplayed per page
			displayQuizByTab(currentTab);
			
			function displayQuizByTab(currentTab) {
				createQuizPages();
				showTab(currentTab); // Display the current tab
			}
			
			function createQuizList(start, end){
				var quiz_list = document.createElement("DIV");
				var quiz_list_step = document.createElement("SPAN");
				quiz_list.setAttribute("class", "tab");
				quiz_list_step.setAttribute("class", "step");
				for(var i = start; i<end; i++){
					quiz_list.appendChild(createQuizBtn(quizzes, i));
				}
				document.getElementById("total_pages").appendChild(quiz_list);
				document.getElementById("total_steps").appendChild(quiz_list_step);
			}
			
			function createQuizPages(){
				var upper_bound = 0;
				var total_pages = document.createElement("DIV");
				var total_steps = document.createElement("DIV");
				total_pages.setAttribute("id", "total_pages");
				total_steps.setAttribute("id", "total_steps");
				document.getElementById("Quiz_Pages").appendChild(total_pages);
				document.getElementById("Quiz_Page_Steps").appendChild(total_steps);
				if(total_quizzes != 0){
					for(var lower_bound = 0; lower_bound < total_quizzes; lower_bound+=max_quiz_page) {
						if(total_quizzes-lower_bound < max_quiz_page){
							upper_bound = total_quizzes;
						}else{
							upper_bound += (max_quiz_page-1);
						}
						createQuizList(lower_bound, upper_bound);
					}
				}else{
					createQuizList(0,0);
					document.getElementById("total_pages").innerHTML +="<p>No quizzes have been created yet with searched phrase. You can help by clicking the button above!</p>";
				}
			}
			
			function refreshList(){
				deleteElement("Quiz_Pages", "total_pages");
				deleteElement("Quiz_Page_Steps", "total_steps");
				currentTab=0;
				displayQuizByTab(currentTab);
			}
			
			function searchDB(){
				var phrase = document.getElementById("phrase").value;
				var order = document.getElementById("order").value;
				var target = document.getElementById("target").value;
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function(){
					if(this.readyState==4 && this.status==200){
						quizzes = JSON.parse(this.responseText)
						total_quizzes = quizzes.length;
						refreshList();
					}
				}
				xmlhttp.open("GET", "searchDB.php?q="+target+"_"+order+"_"+phrase, true);
				xmlhttp.send();
			}
			
			function selectRandom(){
				xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function(){
					if(this.readyState==4 && this.status==200){
						if(this.responseText != ""){
							window.location.href="quiz_take.php?q="+this.responseText;
						}else{
							alert("No quizzes are available at this time.\nPlease try again later.");
						}
					}
				}
				xmlhttp.open("GET", "searchRandom.php", true);
				xmlhttp.send();
			}
		</script>
		</center>
		</div>
	</body>
</html>