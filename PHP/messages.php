<?php
/* messages.php, page that stores all conversations for a given user
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "config.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
$current_username = $profile["username"];
$current_email = $profile["email"];
$current_desc = $profile["bio"];
$current_gender = $profile["gender"];
$current_id = $profile['id'];

$q = "";
if(isset($_GET["q"])){
	$q = htmlspecialchars($_GET["q"]);
}else{
	$q = -1;
}

$location = 'images/'; 
$image_name = $location.$profile["id"].'.png';
if(!file_exists($image_name)) $image_name = $location.'default-user2.png';

$other_image_name = $location.$q.'.png';
if(!file_exists($other_image_name)) $other_image_name = $location.'default-user2.png';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset = "UTF-8">
		<title>QuizMatch: My Messages</title>
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
<script>
(function(t,a,l,k,j,s){
s=a.createElement('script');s.async=1;s.src="https://cdn.talkjs.com/talk.js";a.head.appendChild(s)
;k=t.Promise;t.Talk={v:1,ready:{then:function(f){if(k)return new k(function(r,e){l.push([f,r,e])});l
.push([f])},catch:function(){return k&&new k()},c:l}};})(window,document,[]);
</script>
<!-- container element in which TalkJS will display a chat UI -->
<div id="talkjs-container" style="width: 90%; margin: 30px; height: 500px"><i>Loading chat...</i></div>

<!-- TalkJS initialization code, which we'll customize in the next steps -->
<script>
Talk.ready.then(function() {

    var me = new Talk.User({
        id: "<?php echo $current_id;?>",
        name: "<?php echo $current_username;?>",
        email: "<?php echo $current_email;?>",
        photoUrl: "<?php echo $image_name;?>",
        welcomeMessage: "Hey there! How are you? :-)"
    });
    window.talkSession = new Talk.Session({
        appId: "tIkXzdS1",
        me: me
    });

   var user = [];
   
   function getUser(user_id){
      //user_id = 3;
	  xmlhttp = new XMLHttpRequest();
	  xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			// Successful Execution
			user = JSON.parse(this.responseText);
			var other = new Talk.User({ 
            "id": user['id'],
            "name": user['username'],
            "email": user['email'],
            "photoUrl":"<?php echo $other_image_name;?>",
            "welcomeMessage": "Hey, let's have a chat!"
            });
            
            var conversation = talkSession.getOrCreateConversation(Talk.oneOnOneId(me, other))
            conversation.setParticipant(me);
            conversation.setParticipant(other);
            var inbox = talkSession.createInbox({selected: conversation});
            inbox.mount(document.getElementById("talkjs-container"));
		}
	}
	  xmlhttp.open("GET", "getUser.php?q="+user_id, true);
	  xmlhttp.send();
    }
	var user_id = <?=$q?>;
	if(Number.isInteger(user_id) && user_id >= 0) getUser(user_id);  
});
</script>
