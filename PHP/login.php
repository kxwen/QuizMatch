<?php
/* Basic Login PHP Code for QuizMatch.
 * Both fields are marked as required.
 * Currently does not actually log into website yet.
 * Currently does not link to any other page.
 */
$loginERR = "";
$email = $password = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(empty($_POST["email"]) or empty($_POST["password"]))
	{
		$loginERR = "Email and Password is required"
	}else{
		$email = input($_POST["email"]);
		$password = input($_POST["password"]);
	}
}

function input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>