<?php
/* signup.php
 * The form for a new user to create a profile on QuizMatch.
 */
 
require_once "config.php";
 
$email = $username = $password = $Cpassword = $DoB = "";
$email_err = $username_err = $password_err = $Cpassword_err = $DoB_err = "";
$M_checked = $F_checked = $O_checked = "";
$M_P_checked = $F_P_checked = $O_P_checked = $N_P_checked = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Username Entry and Verification
	if(empty(trim($_POST["username"])))
	{
		//Empty Field Case
		$username_err = "Please enter a username.";
	}else{
		// Checks to see if username is Alphanumeric
		if(preg_match('/^[A-Za-z0-9]+$/', trim($_POST["username"]))){
			// Checks to see if username meets length requirements
			if(strlen(trim($_POST["username"])) >= $min_username_len){
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
				$username_err = "Username must be at least ".$min_username_len." characters long.";
			}
		}else{
			$username_err = "Username can only have letters and numbers.";
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
		$password_err = "Password must be at least " . $min_pw_len . " characters long.";
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
	
	if(empty($_POST["DoB"])){
		$DoB_err = "Please enter your Date of Birth.";
	}else{
		$DoB = $_POST["DoB"];
		$DoB_temp = explode("-", $DoB);
		$current_date = explode("-",date("Y-m-d"));
		
		if(($current_date[0]-$DoB_temp[0]) < 18){
			$DoB_err = "You must be 18 years or older to join.";
		}
	}
	
	if($_POST["gender"] == "male"){
		$M_checked = "checked";
	}else if($_POST["gender"] == "female"){
		$F_checked = "checked";
	}else{
		$O_checked = "checked";
	}
	
	if($_POST["gender_pref"] == "male"){
		$M_P_checked = "checked";
	}else if($_POST["gender_pref"] == "female"){
		$F_P_checked = "checked";
	}else if($_POST["gender_pref"] == "other"){
		$O_P_checked = "checked";
	}else{
		$N_P_checked = "checked";
	}
	
	if(empty($username_err) && empty($email_err) && empty($password_err) && empty($Cpassword_err) && empty($DoB_err))
	{
		// If there are no errors, registers the new profile into database and redirects USER to Login
		$sql = "INSERT INTO users (username, email, password, gender, gender_pref, DoB) VALUES (?, ?, ?, ?, ?, ?)";
		if($stmt = mysqli_prepare($link, $sql))
		{
			mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_email, $param_password, $param_gender, $param_gender_pref, $param_DoB);
			$param_username = $username;
			$param_email = $email;
			$param_password = password_hash($password, PASSWORD_DEFAULT);
			$param_gender = $_POST["gender"];
			$param_gender_pref = $_POST["gender_pref"];
			$param_DoB = $DoB;
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

if(empty($M_checked) && empty($F_checked) && empty($O_checked)) $O_checked = "checked";
if(empty($M_P_checked) && empty($F_P_checked) && empty($O_P_checked) && empty($N_P_checked)) $N_P_checked = "checked";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<meta charset = "UTF-8">
		<title>QuizMatch: Sign Up</title>
		<style>
		body
		{
			font: 14px sans-serif;
		}
		div.inputBar
		{
			width: 40%;
			padding: 20px; 
		}
		</style>
		<script type="text/javascript">
			var datefield=document.createElement("input")
			datefield.setAttribute("type", "date")
			if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
				document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
				document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
				document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n') 
			}
		</script>
		 
		<script>
		if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
			jQuery(function($){ //on document.ready
				$('#birthday').datepicker();
			})
		}
		</script>
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
						<input type="text" name="username" style = "font-family:Helvetica" class="form-control" value="<?php echo $username; ?>">
					</div>
					<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Email</label>
						<br><span class="help-block"><font color="red"><?php echo $email_err;?></font></span>
						<input type="text" style = "font-family:Helvetica" name="email" class="form-control" value="<?php echo $email; ?>">
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
						<input type="password" name="Cpassword" class="form-control" value="<?php echo $Cpassword; ?>"><br><br>
					</div>
					<div class="form-group">
					<label>Gender:</label>
						<input type="radio" name="gender" value="male" <?php echo $M_checked;?>> Male
						<input type="radio" name="gender" value="female" <?php echo $F_checked;?>> Female 
						<input type="radio" name="gender" value="other" <?php echo $O_checked;?>> Non-binary/Other<br><br>
					</div>
					<div class="form-group">
					<label>Gender Preference:</label>
						<input type="radio" name="gender_pref" value="male" <?php echo $M_P_checked;?>> Male
						<input type="radio" name="gender_pref" value="female" <?php echo $F_P_checked;?>> Female 
						<input type="radio" name="gender_pref" value="other" <?php echo $O_P_checked;?>> Non-binary/Other
						<input type="radio" name="gender_pref" value="" <?php echo $N_P_checked;?>>No Preference<br><br>
					</div>
					<div class="form-group">
					<label>Date of Birth:</label>
						<br><span class="help-block"><font color="red"><?php echo $DoB_err;?></font></span>
						<input type="date" id="birthday" name="DoB" size="20" style="width:50%;" value=<?php echo $DoB;?>>
					</div>
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