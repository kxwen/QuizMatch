<?php
/* Basic Signup PHP code for QuizMatch.
 * Currently, only email, username, and password are marked as required.
 * First and Last name are listed as separate fields in case USER does not
 * wish to provide their full name.
 * Currently does not actually register an account
 * Currently does not link to any other page.
 */
$usernameERR = $passwordERR = $CpasswordERR = $emailERR = "";
$username = $password = $Cpassword = $email = $firstname = $lastname = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Email Verification
	if(empty($_POST["email"]))
	{
		$emailERR = "Please enter a valid email";
	}else{
		$email = input($_POST["email"]);
	}
	// Username Verification
	if(empty($_POST["username"]))
	{
		$usernameERR = "Please enter a username";
	}else{
		$username = input($_POST["username"]);
	}
	// Password Verification
	if(empty($_POST["password"]) or empty($_POST["Cpassword"]))
	{
		if(empty($_POST["password"]))
		{
			$passwordERR = "Please enter a password";
		}
		if(empty($_POST["Cpassword"]))
		{
			$CpasswordERR = "Please re-enter your password";
		}
	}else{
		if(strcmp($_POST["password"],$_POST["Cpassword"]) == 0)
		{
			$password = input($_POST["password"]);
		}else{
			$CpasswordERR = "Password does not match";
		}
	}
	// Name Entry
	$firstname = input($_POST["firstname"]);
	$lastname = input($_POST["lastname"]);
}

//Helper function
function input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>