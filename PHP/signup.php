<?php
/* signup.php
 * The form for a new user to create a profile on
 * QuizMatch. Altered to elaborate on verification of
 * username, password, and email. Removed fields for
 * First/Last Name; will perhaps move to profile settings.
 *
 * Redirects to: Login.html/php
 *
 * Assumes that DB Table contains fields for Username, Email, and Password.
 *
 * References to the Database have been commented out, and replaced with writing to
 * a testing .txt file as a temporary Database.
 */
 
require_once "config.php";
 
$email = $username = $password = $Cpassword = "";
$email_err = $username_err = $password_err = $Cpassword_err = "";
//$min_pw_len = 5;
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Username Entry and Verification
	if(empty(trim($_POST["username"])))
	{
		//Empty Field Case
		$username_err = "Please enter a username.";
	}else{
		if(strlen(trim($_POST["username"])) >= $min_username_len && strlen(trim($_POST["username"])) <= $max_username_len){
			// Perpare to search DB for existing user
			$sql = "SELECT id FROM users WHERE username = ?";
			if($stmt = mysqli_prepare($link, $sql))
			{
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				$param_username = htmlspecialchars(trim($_POST["username"]));
				if(mysqli_stmt_execute($stmt))
				{
					// Successful execution
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1)
					{
						// Username is taken; User already exists
						$username_err = "This username is already taken.";
					}else{
						// Username is available; Profile does not exist
						$username = htmlspecialchars(trim($_POST["username"]));
					}
				}else{
					// Unsuccessful Execution
					echo "An error has occurred. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
		}else{
			$username_err = "Username must be ".$min_username_len."-".$max_username_len." characters long.";
		}
	}
	
	// Email Entry and Verification
	if(empty(trim($_POST["email"])))
	{
		$email_err = "Please enter an email.";
	}else{
		if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		{
			$email_err = "Please enter a valid email.";
		}else{
			//$email = trim($_POST["email"]);
			// Prepare tp search DB for existing email
			$sql = "SELECT id FROM users WHERE email = ?";
			if($stmt = mysqli_prepare($link, $sql))
			{
				mysqli_stmt_bind_param($stmt, "s", $param_email);
				$param_email = htmlspecialchars(trim($_POST["email"]));
				if(mysqli_stmt_execute($stmt))
				{
					// Successful Execution
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1)
					{
						// Email is taken
						$email_err = "This email is already taken.";
					}else{
						// Email is available
						$email = htmlspecialchars(trim($_POST["email"]));
					}
				}else{
					// Unsuccessful Execution
					echo "An error has occurred. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
		}
	}
	
	// Password Entry
	if(empty(trim($_POST["password"])))
	{
		// Empty Field Case
		$password_err = "Please enter a password.";
	}elseif(strlen(trim($_POST["password"])) < $min_pw_len){
		// Password length is too short for security
		$password_err = "Password must be at least " . $min_pw_len . " characters.";
	}else{
		// Password meets requirements
		$password = htmlspecialchars(trim($_POST["password"]));
	}
	
	// Confirm Password Entry
	if(empty(trim($_POST["Cpassword"])))
	{
		// Empty Field Case
		$Cpassword_err = "Please confirm password.";
	}else{
		// A String has been submitted
		$Cpassword = htmlspecialchars(trim($_POST["Cpassword"]));
		if(empty($password_err) && ($password != $Cpassword))
		{
			// Confirmation Field does not match Password Field
			$Cpassword_err = "Password does not match.";
		}
	}
	
	
	if(empty($username_err) && empty($email_err) && empty($password_err) && empty($Cpassword_err))
	{
		// If there are no errors, registers the new profile into database and redirects USER to Login
		/*$local_file = "Testing_Form.txt";
		$handle = fopen($local_file, 'a') or die('cannot open file: ' . $local_file);
		$data = $username." ".$email." ".$password."\n";
		fwrite($handle, $data);
		fclose($handle);*/
		$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
		if($stmt = mysqli_prepare($link, $sql))
		{
			mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
			$param_username = $username;
			$param_email = $email;
			$param_password = password_hash($password, PASSWORD_DEFAULT);
			if(mysqli_stmt_execute($stmt))
			{
				// Emailing for Account Verification needs to be implemented.
				//$to = $email;
				//$subject = "QuizMatch Account Verification"
				//$message = "
				//Hello,
				//Thank you for signing up with QuizMatch. Your registration is almost complete.
				//Please click this link to activate your account:"
				header("location: login.php");
			}else{
				echo "An Error has occurred. Please try again later.";
			}
		}
		mysqli_close($stmt);
	}
	mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<meta charset = "UTF-8">
		<title>QuizMatch: Sign Up</title>
		<!--<link rel ="stylesheet" href="stupid.css">
		<!--<style type="text/css">
			body{ font: 14px sans-serif; }
			.wrapper{ width: 350px; padding: 20px; }
		</style>!-->
		<style>
		body
		{
			font: 14px sans-serif;
		}
		div.inputBar
			{
			width: 350px;
			padding: 20px; 
			}
		</style>
	</head>
	<body>
		<center>
			<div class="inputBar">
				<h2>Sign Up</h2>
				Please fill out this form to register.
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Username</label>
						<br><span class="help-block"><font color="red"><?php echo $username_err;?></font></span>
						<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
					</div>
					<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Email</label>
						<br><span class="help-block"><font color="red"><?php echo $email_err;?></font></span>
						<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
					</div>
					<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Password</label>
						<br><span class="help-block"><font color="red"><?php echo $password_err;?></font></span>
						<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
					</div>
					<div class="form-group <?php echo (!empty($Cpassword_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Confirm Password</label>
						<br><span class="help-block"><font color="red"><?php echo $Cpassword_err;?></font></span>
						<input type="password" name="Cpassword" class="form-control" value="<?php echo $Cpassword; ?>">
					</div>
					<!--<div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
						<label>Gender(M/F)</label>
						<br><span class="help-block"><font color="red"><?php echo $gender_err;?></font></span>
						<input type="text" name="gender" class="form-control" value="<?php echo $gender; ?>">
					</div>-->
					<div class="form-group">
					<br>
						<input type="submit" class="btn pink rounded" value = "Submit" style = "font-family: Helvetica";>
					</div>
					<br>
					Already have an account? <a href="login.php">Login here</a>.
				</form>
			</div>
		</center>
	</body>
</html>