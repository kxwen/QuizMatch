<!-- 
This is the homepage for QuizMatch. It contains links to the login page and
the sign up page.
Hovering over the card QuizMatch will produce one of many random anecdotes.
-->

<?php
/* userprofilecard.php
 * altered Richard's login verification to verify that user has signed in.
 * If they are not signed in, they are redirected to the login page.
 * This prevents the user from using the back button of their browser
 * to return here after they had already signed out.
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}

require_once "matchMake.php";

$results = getMyResults($link);
$matches = getMyMatches($link, $results);
$transfer_matches = json_encode($matches);
?>

<!DOCTYPE html>
<html lang = "en">
	<head>
		<meta charset="utf-8">
		<title>Your Matches</title>
		<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<style>
			html
			{
				height:100%;
				width:100%;
			}
			body
			{
				min-height:100%;
				background-color: white;
			}
			div.buttonCenter
			{
				margin-top:1.5%;
				display:flex;
				justify-content:center
			}
			div.matches
			{
				margin-top: -50px;
			}
			div.contentRoundBorders
			{
				border-radius:15px;
				padding:1%;
				background:white;
				margin-bottom:1%;
				margin-top:1%;
				box-shadow: 0 0 3px rgba(0,0,0,0.5);
			}
			div.buttonSide
			{
				display:flex;
				justify-content: space-between;
				padding: 1% 3% 1% 5%;
			}
			
			div.topBarLayout
			{
				margin-top:2%;
			}
		</style>
		
	</head>
		<header>
		<center>
			<div class = "topBarLayout">
				<a href="userprofile.php" class="btn pink rounded"><tt>Home <i class="fa fa-home"></i></tt></a>
				<a href="quiz_home.php" class="btn pink rounded"><tt>Quizzes!&#10004;</tt></a>
				<a href="logout.php" class="btn pink rounded"><tt>Logout <i class="fa fa-sign-out"></i></tt></a>
			</div>
		</center>
	</header>
	
	<body>
	<div class = "container">
		<div class = "matches">
			<center>
				<br><h2>Matches&#x1F50D;</h2>
				
				<span id="Matches_Pages"></span>
				
				<button type="button" class ="btn pink rounded" id="prevBtn"
					onclick="nextPrev(-1);">Previous</button>
				<button type="button" class ="btn pink rounded" id="nextBtn"
					onclick="nextPrev(1);">Next</button>
				<div style="text-align:center;margin-top:40px;">
					<span id="Matches_Page_Steps"></span>
				</div>
			</center>
		</div>
	</div>
	<script src="config.js"></script>
	<script>
		var currentTab = 0;
		var currentMatch = 1;
		var currentPage = 1;
		var matches = <?=$transfer_matches?>;
		var total = matches.length;
		var max_matches_page = 10;
		
		displayMatchesByTab(currentTab);
		
		function displayMatchesByTab(n){
			createMatchesTabs();
			showTab(n);
		}
		
		function createActionBtns(id){
			var match_action_btns = document.createElement("SPAN");
			match_action_btns.setAttribute("id", "match_btn_actions_"+currentMatch);
			var add_match = document.createElement("button");
			add_match.setAttribute("class", "btn small pink rounded");
			add_match.setAttribute("onclick", "createRelation("+id+", 0, 'match_btn_actions_"+currentMatch+"', 'match_btn_"+currentMatch+"', 'Sent Friend Request')");
			add_match.innerHTML = "Add as Friend";
			var chat_match = document.createElement("a");
			chat_match.setAttribute("class", "btn small pink rounded");
			chat_match.setAttribute("href", "messages.php?q="+id);
			chat_match.innerHTML = "Chat with User";
			match_action_btns.appendChild(add_match);
			match_action_btns.appendChild(chat_match);
			return match_action_btns
		}
		
		function createMatchBtn(name, id){
			var match_name = document.createTextNode(name);
			var match_btn = document.createElement("DIV");
			match_btn.setAttribute("class", "btn large silver rounded");
			match_btn.setAttribute("id", "match_btn_"+currentMatch);
			match_btn.appendChild(match_name);
			match_btn.innerHTML += "<br>";
			match_btn.appendChild(createActionBtns(id));
			currentMatch++;
			return match_btn;
		}
		
		function createMatchesTab(start, end){
			var matches_tab = document.createElement("DIV");
			var matches_tab_step = document.createElement("DIV");
			matches_tab.setAttribute("id", "page_"+currentPage);
			matches_tab.setAttribute("class", "tab");
			matches_tab_step.setAttribute("id", "page_"+currentPage+"_step");
			matches_tab_step.setAttribute("class", "step");
			for(var i = start; i<=end; i++){
				matches_tab.appendChild(createMatchBtn(matches[i]["username"], matches[i]["id"]));
			}
			document.getElementById("total_pages").appendChild(matches_tab);
			document.getElementById("total_steps").appendChild(matches_tab_step);
			currentPage++;
		}
		
		function createMatchesTabs(){
			var total_pages = document.createElement("DIV");
			var total_steps = document.createElement("DIV");
			total_pages.setAttribute("id", "total_pages");
			total_steps.setAttribute("id", "total_steps");
			document.getElementById("Matches_Pages").appendChild(total_pages);
			document.getElementById("Matches_Page_Steps").appendChild(total_steps);
			if(total > 0){
				var upper_bound = 0;
				for(var lower_bound = 0; lower_bound < total; lower_bound+=max_matches_page){
					if(total-(lower_bound+1) < max_matches_page){
						upper_bound = total-1;
					}else{
						upper_bound += max_matches_page-1;
					}
					createMatchesTab(lower_bound, upper_bound);
				}
			}else{
				// No matches have been found.
				var empty_friends = document.createElement("DIV");
					var empty_friends_step = document.createElement("SPAN");
					var empty_friends_pseudocard = document.createElement("DIV");
					var empty_friends_btn = document.createElement("a");
					
					empty_friends.setAttribute("class", "tab");
					empty_friends_step.setAttribute("class", "step");
					
					empty_friends_pseudocard.setAttribute("class", "btn large silver rounded");
					empty_friends_pseudocard.innerHTML = "There is no one here.<br><br>"
					
					empty_friends_btn.setAttribute("class", "btn large pink rounded");
					empty_friends_btn.innerHTML = "Click here to take more quizzes.";
					empty_friends_btn.setAttribute("href", "quiz_home.php");
					
					empty_friends_pseudocard.appendChild(empty_friends_btn);
					empty_friends.appendChild(empty_friends_pseudocard);
					total_pages.appendChild(empty_friends);
					total_steps.appendChild(empty_friends_step);
			}
		}
	</script>
	</body>
</html>