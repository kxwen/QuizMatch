<?php
/* edit_profile.php
 * Allows user to edit profile details
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
 
require_once "config.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
if(isset($_POST['submit'])){
    $name       = $_FILES['file']['name'];  
    $temp_name  = $_FILES['file']['tmp_name'];
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($name,PATHINFO_EXTENSION));
	
	$check = getimagesize($_FILES["file"]["tmp_name"]);
	if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
	// Check file size
	if ($_FILES["file"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		$location = 'images/';
		if (move_uploaded_file($temp_name, $location.$profile["id"].'.png')) {
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}
 
$new_email = $new_username = $new_desc = "";
$email_err = $username_err = "";
$M_checked_new = $F_checked_new = $O_checked_new = ""; // Used for radio inputs for gender
$M_P_checked_new = $F_P_checked_new = $O_P_checked_new = $N_P_checked_new = "";
$new_gender = "";
$location = 'images/'; 
$image_name = $location.$profile["id"].'.png';
if(file_exists($image_name))
{
	echo "";
}
else
{
	$image_name = $location.'default-user1.png';
}
$current_username = $profile["username"];
$current_email = $profile["email"];
$current_desc = $profile["bio"];
$M_checked_curr = $F_checked_curr = $O_checked_curr = ""; // Used for radio inputs for gender
if($profile["gender"] == "Male"){
	$M_checked_curr = "checked";
}else if($profile["gender"] == "Female"){
	$F_checked_curr = "checked";
}else{ // gender is either selected as "other" or is null
	$O_checked_curr = "checked";
}
$M_P_checked_curr = $F_P_checked_curr = $O_P_checked_curr = $N_P_checked_curr = "";
if($profile["gender_pref"] == "Male"){
	$M_P_checked_curr = "checked";
}else if($profile["gender_pref"] == "Female"){
	$F_P_checked_curr = "checked";
}else if($profile["gender_pref"] == "Non-Binary/Other"){ // gender is either selected as "other" or is null
	$O_P_checked_curr = "checked";
}else{
	$N_P_checked_curr = "checked";
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(preg_match('/^[A-Za-z0-9]+$/', trim($_POST["username"]))){
		// Checks to see if username meets length requirements
		if(strlen(trim($_POST["username"])) >= $min_username_len){
			// Perpare to search DB for existing user
			if(htmlspecialchars(trim($_POST["username"])) != $current_username){
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
							$new_username = htmlspecialchars(trim($_POST["username"]));
						}
					}else{
						// Unsuccessful Execution
						echo "An error has occurred. Please try again later.";
					}
				}
				mysqli_stmt_close($stmt);
			}else{
				$new_username = $current_username;
			}
		}else{
			$username_err = "Username must be at least ".$min_username_len." characters long.";
		}
	}else{
		$username_err = "Username can only have letters and numbers.";
	}
	
	if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
	{
		$email_err = "Please enter a valid email.";
	}else{
		// Prepare tp search DB for existing email
		if(htmlspecialchars(trim($_POST["email"])) != $current_email){
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
						$new_email = htmlspecialchars(trim($_POST["email"]));
					}
				}else{
					// Unsuccessful Execution
					echo "An error has occurred. Please try again later.";
				}
			}
			mysqli_stmt_close($stmt);
		}else{
			$new_email = $current_email;
		}
	}
	
	$new_desc = htmlspecialchars(trim($_POST["desc"]));
	
	if($_POST["gender"] == "Male"){
		$M_checked_new = "checked";
	}else if($_POST["gender"] == "Female"){
		$F_checked_new = "checked";
	}else if($_POST["gender"] == "Non-Binary/Other"){
		$O_checked_new = "checked";
	}
	$new_gender = $_POST["gender"];
	
	if($_POST["gender_pref"] == "Male"){
		$M_P_checked_new = "checked";
	}else if($_POST["gender_pref"] == "Female"){
		$F_P_checked_new = "checked";
	}else if($_POST["gender_pref"] == "Non-Binary/Other"){
		$O_P_checked_new = "checked";
	}else if($_POST["gender_pref"] == ""){
		$N_P_checked_new = "checked";
	}
	$new_gender_pref = $_POST["gender_pref"];
	
	if(empty($username_err) && empty($email_err)){
		$sql = "UPDATE users SET username=?, email=?, bio=?, gender=?, gender_pref=? WHERE id=".$_SESSION["id"];
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_email, $param_bio, $param_gender, $param_gender_pref);
			$param_username = $new_username;
			$param_email = $new_email;
			$param_bio = $new_desc;
			$param_gender = $new_gender;
			$param_gender_pref = $new_gender_pref;
			if(mysqli_stmt_execute($stmt)){
				$_SESSION["username"] = $new_username;
				header("location: userprofile_extended.php");
			}else{
				echo "An Error has occurred. Please try again later.";
			}
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<link href= "stupid.css" type = "text/css" rel = "stylesheet"/>
		<meta charset = "UTF-8">
		<title>QuizMatch: Edit Profile</title>
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
		div.buttonSpaceLeft
		{
			margin-left: 5%;
			margin-top: 2%;
		}
		#avatar
		{
			background-image: url(<?php echo ($image_name);?>);
			width: 300px;
			height: 300px;
			background-size: cover;
			background-position: center;
			border-radius:50%;
		}
		div.topBarLayout
		{
			margin-top:2%;
			text-align:center;
		}
		</style>
	</head>
	<body>
		<div class = "topBarLayout">
			<a class="btn large pink rounded" onclick="confirmLeave('Are you sure you want to leave?\nYou will lose all unsaved data.', 'userprofile_extended.php')"><tt>Home&#x1F3E0;</tt></a>
		</div>
		<center>
			<div class="inputBar">
				<h2>Edit Profile</h2>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
					<div id="avatar"></div>
					<br>
					<input type="file" name="file" id="file"><br><br>

					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
					<br>
						<label>Username:</label>
						<br><span class="help-block"><font color="red"><?php echo $username_err;?></font></span>
						<br><input type="text" style = "font-family: Helvetica" name="username" class="form-control" value="<?php if(isset($new_username) && !empty($new_username)){echo $new_username;}else{echo $current_username;}?>"><br><br>
						
						<label>Email:</label>
						<br><span class="help-block"><font color="red"><?php echo $email_err;?></font></span>
						<br><input type="text" style = "font-family: Helvetica" name="email" class="form-control" value="<?php if(isset($new_email) && !empty($new_email)){echo $new_email;}else{echo $current_email;}?>"><br><br>
						
						<label>Biography:</label>
						<br><br><textarea style = "font-family: Helvetica" name="desc" rows="5" cols="33" maxlength="200" ><?php if(isset($new_desc) && !empty($new_desc)){echo $new_desc;}else{echo $current_desc;}?></textarea><br><br>
						
						<label>Gender:</label>
							<input type="radio" name="gender" value="Male" <?php if($M_checked_new != "" || $F_checked_new != "" || $O_checked_new != ""){echo $M_checked_new;}else{echo $M_checked_curr;}?>> Male
							<input type="radio" name="gender" value="Female" <?php if($M_checked_new != "" || $F_checked_new != "" || $O_checked_new != ""){echo $F_checked_new;}else{echo $F_checked_curr;}?>> Female 
							<input type="radio" name="gender" value="Non-Binary/Other" <?php if($M_checked_new != "" || $F_checked_new != "" || $O_checked_new != ""){echo $O_checked_new;}else{echo $O_checked_curr;}?>> Non-binary/Other<br><br>
						
						<label>Gender Preference:</label>
							<input type="radio" name="gender_pref" value="Male" <?php if($M_P_checked_new != "" || $F_P_checked_new != "" || $O_P_checked_new != "" || $N_P_checked_new != ""){echo $M_P_checked_new;}else{echo $M_P_checked_curr;}?>> Male
							<input type="radio" name="gender_pref" value="Female" <?php if($M_P_checked_new != "" || $F_P_checked_new != "" || $O_P_checked_new != "" || $N_P_checked_new != ""){echo $F_P_checked_new;}else{echo $F_P_checked_curr;}?>> Female 
							<input type="radio" name="gender_pref" value="Non-Binary/Other" <?php if($M_P_checked_new != "" || $F_P_checked_new != "" || $O_P_checked_new != "" || $N_P_checked_new != ""){echo $O_P_checked_new;}else{echo $O_P_checked_curr;}?>> Non-binary/Other
							<input type="radio" name="gender_pref" value="" <?php if($M_P_checked_new != "" || $F_P_checked_new != "" || $O_P_checked_new != "" || $N_P_checked_new != ""){echo $N_P_checked_new;}else{echo $N_P_checked_curr;}?>>No Preference<br><br>
						
						<div class="form-group">
						<br>
							<input type="submit" class="btn pink rounded" value = "Submit" name="submit" style = "font-family: Helvetica";>
						</div>
					</div>
				</form>
			</div>
		</center>
	<script src="config.js"></script>
	
	</body>
</html>