<?php 

	$userName = $_POST['username'];
	$userEmail = $_POST['email'];

	echo $userName;
	echo "<p>Sent password recovery to: </>";
	echo $userEmail;
?>
<br><a href="login.html"><b>Back to login?</b></a>
