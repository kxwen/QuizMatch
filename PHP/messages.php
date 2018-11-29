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

?>

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
        photoUrl: "https://demo.talkjs.com/img/alice.jpg",
        welcomeMessage: "Hey there! How are you? :-)"
    });
    window.talkSession = new Talk.Session({
        appId: "tIkXzdS1",
        me: me
    });

   var user = [];
   
   function getUser(){
      user_id = 2;
	  xmlhttp = new XMLHttpRequest();
	  xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			// Successful Execution
			user = JSON.parse(this.responseText);
			var other = new Talk.User({ 
            "id": user['id'],
            "name": user['username'],
            "email": user['email'],
            "photoUrl":"https://demo.talkjs.com/img/sebastian.jpg",
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
    
    getUser();
   

    
});
</script>
