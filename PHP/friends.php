<?php
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "relationships.php";

$total = getRelations($link);
$relationships = json_encode($total);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset = "UTF-8">
		<title>QuizMatch: My Friends</title>
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
		div.contentRoundBorders
		{
			border-radius:15px;
			padding:1%;
			background:white;
			margin-bottom:1%;
			margin-top:1%;
			box-shadow: 0 0 3px rgba(0,0,0,0.5);
			width:90%;
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
		<center>
			<div class = "bodyLayout">
				<div class="wrapper">
					<h2>My Friends</h2>
				</div>
			</div>
			<div class="contentRoundBorders">
				<span id="Friends_Pages"></span>
			</div>
			<div>
				<button type="button" class ="btn pink rounded" id="prevBtn"
					onclick="nextPrev(-1);">Previous</button>
				<button type="button" class ="btn pink rounded" id="refresh"
					onclick="getRelations();">Refresh</button>
				<button type="button" class ="btn pink rounded" id="nextBtn"
					onclick="nextPrev(1);">Next</button>
			</div>
			<div style="text-align:center;margin-top:40px;">
				<span id="Friends_Page_Steps"></span>
			</div>
			<script src="config.js"></script>
			<script>
				var currentTab = 0;
				var currentFriend = 1;
				var currentPage = 1;
				var max_friends_page = 10;
				
				var relationships = [];
				
				relationships = <?= $relationships?>;
				var total = relationships.length;
				
				displayFriendsByTab(currentTab);
				
				function displayFriendsByTab(n){
					createFriendTabs();
					showTab(n);
				}
				
				function refreshFriendsByTab(){
					deleteElement("Friends_Pages", "total_pages");
					deleteElement("Friends_Page_Steps", "total_steps");
					currentTab=0;
					displayFriendsByTab(currentTab);
				}
				
				function createActionBtns(status, last_editor, other_user){
					var friend_action_btns = document.createElement("DIV");
					friend_action_btns.setAttribute("id", "friend_btn_actions_"+currentFriend);
					if(status == 0){
						// Friend Request
						if(last_editor == <?php echo $_SESSION["id"]?>){
							// Was sent by current User; button to recind/cancel
							var recind_btn = document.createElement("button");
							recind_btn.innerHTML = "Cancel Friend Request";
							recind_btn.setAttribute("class", "btn small pink rounded");
							
							recind_btn.setAttribute("onclick", "deleteRelation("+other_user+", 'friend_btn_actions_"+currentFriend+"', 'friend_btn_"+currentFriend+"', 'Cancelled Friend Request')");
							
							friend_action_btns.appendChild(recind_btn);
						}else{
							// Accept and Decline Btns
							var accept_btn = document.createElement("button");
							var decline_btn = document.createElement("button");
							accept_btn.innerHTML = "Accept Friend Request";
							decline_btn.innerHTML = "Decline Friend Request";
							accept_btn.setAttribute("class", "btn small pink rounded");
							decline_btn.setAttribute("class", "btn small pink rounded");
							
							accept_btn.setAttribute("onclick", "updateRelation("+other_user+", 1, 'friend_btn_actions_"+currentFriend+"', 'friend_btn_"+currentFriend+"', 'Accepted Friend Request')");
							decline_btn.setAttribute("onclick", "deleteRelation("+other_user+", 'friend_btn_actions_"+currentFriend+"', 'friend_btn_"+currentFriend+"', 'Declined Friend Request')");
							
							friend_action_btns.appendChild(accept_btn);
							friend_action_btns.appendChild(decline_btn);
						}
					}else if(status == 1){
						// Friend; btn to remove friend
						var unfriend_btn = document.createElement("button");
						unfriend_btn.innerHTML = "Unfriend";
						unfriend_btn.setAttribute("class", "btn small pink rounded");
						
						unfriend_btn.setAttribute("onclick", "deleteRelation("+other_user+", 'friend_btn_actions_"+currentFriend+"', 'friend_btn_"+currentFriend+"', 'Removed Friend')");
						
						friend_action_btns.appendChild(unfriend_btn);
					}else if(status == 2){
						// Blocked by Current User; btn to unblock
						var unblock_btn = document.createElement("button");
						unblock_btn.innerHTML = "Unblock";
						unblock_btn.setAttribute("class", "btn small pink rounded");
						
						unblock_btn.setAttribute("onclick", "deleteRelation("+other_user+", 'friend_btn_actions_"+currentFriend+"', 'friend_btn_"+currentFriend+"', 'Unblocked User')");
						
						friend_action_btns.appendChild(unblock_btn);
					}
					if(status != 2){
						var chat_btn = document.createElement("a");
						chat_btn.innerHTML = "Chat";
						chat_btn.setAttribute("class", "btn small pink rounded");
						chat_btn.setAttribute("href", "messages.php?q="+other_user);
						friend_action_btns.appendChild(chat_btn);
					}
					return friend_action_btns;
				}
				
				function createFriendBtn(name, status, last_editor, other_user, parentDiv){
					var friend_name_text = document.createTextNode(name);
					var friend_name = document.createElement("h3");
					friend_name.appendChild(friend_name_text);
					var friend_btn = document.createElement("DIV");
					var action_btns = createActionBtns(status, last_editor, other_user);
					friend_btn.appendChild(friend_name);
					friend_btn.setAttribute("class", "contentRoundBorders");
					friend_btn.setAttribute("id", "friend_btn_"+currentFriend);
					friend_btn.appendChild(action_btns);
					currentFriend++;
					return friend_btn;
				}
				
				function createFriendTab(start, end){
					var friends_list = document.createElement("DIV");
					var friends_list_step = document.createElement("DIV");
					friends_list.setAttribute("id", "page_"+currentPage);
					friends_list.setAttribute("class", "tab");
					friends_list_step.setAttribute("id", "page_"+currentPage+"_step");
					friends_list_step.setAttribute("class", "step");
					for(var i = start; i<=end; i++){
						friends_list.appendChild(createFriendBtn(relationships[i][0], relationships[i][1], relationships[i][2], relationships[i][3], "page_"+currentPage));
					}
					document.getElementById("total_pages").appendChild(friends_list);
					document.getElementById("total_steps").appendChild(friends_list_step);
					currentPage++;
				}
				
				function createFriendTabs(){
					var total_pages = document.createElement("DIV");
					var total_steps = document.createElement("DIV");
					total_pages.setAttribute("id", "total_pages");
					total_steps.setAttribute("id", "total_steps");		
					document.getElementById("Friends_Pages").appendChild(total_pages);
					document.getElementById("Friends_Page_Steps").appendChild(total_steps);
					if(total > 0){
						var upper_bound = 0;
						for(var lower_bound = 0; lower_bound < total; lower_bound+=max_friends_page){
							if(total-(lower_bound+1) < max_friends_page){
								upper_bound = total - 1;
							}else{
								upper_bound += max_friends_page-1;
							}
							createFriendTab(lower_bound, upper_bound);
						}
					}else{
						var empty_friends = document.createElement("DIV");
						var empty_friends_step = document.createElement("SPAN");
						var empty_friends_pseudocard = document.createElement("DIV");
						var empty_friends_btn = document.createElement("a");
						
						empty_friends.setAttribute("class", "tab");
						empty_friends_step.setAttribute("class", "step");
						
						empty_friends_pseudocard.setAttribute("class", "contentRoundBorders");
						empty_friends_pseudocard.innerHTML = "There is no one here.<br><br>"
						
						empty_friends_btn.setAttribute("class", "btn large pink rounded");
						empty_friends_btn.innerHTML = "Click here to Matchmake & make friends";
						empty_friends_btn.setAttribute("href", "matches.php");
						
						empty_friends_pseudocard.appendChild(empty_friends_btn);
						empty_friends.appendChild(empty_friends_pseudocard);
						total_pages.appendChild(empty_friends);
						total_steps.appendChild(empty_friends_step);
					}
				}
			</script>
		</center>
	</body>
</html>