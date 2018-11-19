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
require_once "config.php";

//$user_id = $_SESSION["id"];

// $sql = "SELECT name, description, size FROM quizzes";

// if($stmt = mysqli_prepare($link, $sql)) {
// 	mysqli_stmt_bind_param($stmt, "s", $param_name, $param_description);
// 	$param_name = htmlspecialchars(trim($_POST["name"]));
// 	$param_description = htmlspecialchars(trim($_POST["description"]));
// 	if(mysqli_stmt_execute($stmt)) {
// 		// Successful Execution
// 		mysqli_stmt_store_result($stmt);
// 	}else{
// 		// Unsuccessful Execution
// 		echo "An error has occurred. Please try again later.";
// 	}
// }
// mysqli_stmt_close($stmt);

// while($row = $result->fetch_assoc()) {
// 	if ($user_id == $row["owner_id"]) {
// 	    $names[$count] = $row["name"];
// 	    $descs[$count] = $row["description"];
// 	    $count++;
// 	}
// }


// Temporary dummy values until I can retrieve from database
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
		<title>Questionaires</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
		#nextBtn{margin-left:500px;}
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
		<center>
		<div class = "bodyLayout">
			<div class="wrapper">
				<h2>Questionaire List</h2>
				<a href="create_quiz.php" class="btn large pink rounded"><tt>Create your own!&#x2611;</tt></a>
				<div class="tab">
					
				</div>
				<div class="tab">
					
				</div>
				<div class="tab">
					
				</div>
				<div class="tab">
					
				</div>
				<div class="tab">
					
				</div>
				
			</div>
		</div>
		
		<div>
				<button type="button" class ="btn pink rounded" id="prevBtn"
					onclick="updateQuizzesByTab(currentTab); displayQuizByTab(--currentTab);">Previous</button>
				<button type="button" class ="btn pink rounded" id="nextBtn"
					onclick="updateQuizzesByTab(currentTab); displayQuizByTab(++currentTab);">Next</button>
		</div>
		<div style="text-align:center;margin-top:40px;">
			<div class="step"></div><div class="step"></div>
			<div class="step"></div><div class="step"></div>
			<div class="step"></div>

		</div>
		
		<script>
			var currentTab = 0; // Current tab is set to be the first tab (0)
			showTab(currentTab); // Display the current tab



			var total_quizzes = 46; // test for 46 quizzes that user created
			var max_quiz_page = 10; // number of quizzes that can be diplayed per page

			displayQuizByTab(currentTab);

			// Displays the set of quizzes according to the tab
			// Only 10 quizzes are displayed at a time
			function displayQuizByTab(currentTab) {
				if ((max_quiz_page * currentTab) < total_quizzes) {
					var numToDisplay = 0;
					var first = 0;
					var last = 0;
					if (total_quizzes - (max_quiz_page * currentTab) < 10)
						numToDisplay = total_quizzes - (max_quiz_page * currentTab);
					else
						numToDisplay = 10;
					first = max_quiz_page * currentTab;
					last = first + numToDisplay;

					for (var i = first; i < last; i++)
						displayQuizes(i);
					showTab(currentTab); // Display the current tab

					if (document.getElementById("nextBtn").innerHTML == "Submit")
						document.getElementById("nextBtn").style.display = "none";
					else
						document.getElementById("nextBtn").style.display = "inline";
				}
			}

			// Creates the current set of quizzes to be displayed
			// Currently does not retrieve data from DB (mySQL)
			function displayQuizes(x) {
				// for (var i = 0; i < x; i++) {
				// 	<?php $count++;?>
				// }
				var t0 = document.createTextNode("Quiz #" + x);
				var t1 = document.createTextNode(" desc");
				//var t0 = document.createTextNode("<?php echo $names[$count] . " " . $descs[$count];?>");
				var btn = document.createElement("button");
				btn.setAttribute("id", "Div1");
				//var object1 = document.getElementById('Div1');

				btn.setAttribute("class", "btn large pink rounded");
				btn.setAttribute("class", "btn large pink rounded");


				//object1.setAttribute('href','questions_result.php');
				//alert(object1.getAttribute('href'));

				btn.appendChild(t0);
				btn.appendChild(t1);

				document.body.appendChild(btn);
			}

			// Each time tab is changed, deletes the previous set of quizzes
			function updateQuizzesByTab(currentTab) {
				if ((max_quiz_page * currentTab) < total_quizzes) {
					var numToDisplay = 0;
					if (total_quizzes - (max_quiz_page * currentTab) < 10)
						numToDisplay = total_quizzes - (max_quiz_page * currentTab);
					else
						numToDisplay = 10;

					for (var i = 0; i < numToDisplay; i++) {
						var btn = document.getElementById("Div1");
						document.body.removeChild(btn);
					}
				}
			}


		</script>
		</center>
	</body>
</html>