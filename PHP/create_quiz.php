<?php
/* create_quiz.php
 * Page Form to create a new quiz
 * Final details subject to change
 *
 * Two ideas:
 *		fixed sized forms of 3 different sizes <---- Current
 * 		Dynamic sized forms
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
 
 $Q_name_err = "";
 $Q_name = "";
 $DESC = "";
 $S_checked = $M_checked = $L_checked = "";
 $size_err = "";
 $RES_1 = $RES_2 = "";
 $RES_err = "";
 $Q_1 = "";
 $A_1_1 = $A_1_2 = $A_1_3 = $A_1_4 = "";
 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
	if(empty(trim($_POST["Q_name"])))
	{
		$Q_name_err = "Please enter a Questionaire Name.";
	}else{
		$Q_name = htmlspecialchars(trim($_POST["Q_name"]));
	}
	$DESC = htmlspecialchars(trim($_POST["DESC"]));
	if(isset($_POST["size"]))
	{
		if($_POST["size"] == "small")
		{
			$S_checked = "checked";
		}elseif($_POST["size"] == "medium"){
			$M_checked = "checked";
		}elseif($_POST["size"] == "large"){
			$L_checked = "checked";
		}else{
			$size_err = "Please select a Questionaire Size.";
		}
	}else{
		$size_err = "Please select a Questionaire Size.";
	}
	if(empty(trim($_POST["res_1"])))
	{
		$RES_err = "Please fill out the top two Results.";
	}else{
		$RES_1 = htmlspecialchars(trim($_POST["res_1"]));
		if(empty(trim($_POST["res_2"])))
		{
			$RES_err = "Please fill out the top two Results.";
		}else{
			$RES_2 = htmlspecialchars(trim($_POST["res_2"]));
		}
	}
 }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create Quiz</title>
		<link rel ="stylesheet" href="stupid.css">
		<style type="text/css">
			body{ font: 14px sans-serif; }
			.wrapper{ width: 350px; padding: 20px; }
		</style>
	</head>
	<body>
		<center>
			<div class="wrapper">
				<h2>Create a Questionaire</h2>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="form-group <?php echo (!empty($Q_name_err)) ? 'has-error' : ''; ?>">
						<label>Questionaire Name</label>
						<span class="help-block"><font color="red"><br><?php echo $Q_name_err;?></font></span>
						<input type="text" name="Q_name" class="form-control" value="<?php echo $Q_name; ?>"><br>
					</div>
					<div>
						<label>Description</label>
						<textarea name="DESC" rows="3" cols="33" maxlength="200"><?php echo $DESC;?></textarea>
					</div>
					<div class="form-group <?php echo (!empty($size_err)) ? 'has-error' : ''; ?>">
						<br><label>Size:</label><br>
						<span class="help-block"><font color="red"><?php echo $size_err;?><br></font></span>
						<input type="radio" name="size" value="small" <?php echo $S_checked;?>>Small  
						<input type="radio" name="size" value="medium" <?php echo $M_checked;?>>Medium  
						<input type="radio" name="size" value="large" <?php echo $L_checked;?>>Large<br><br>
					</div>
					<div>
						<label>Results</label>
						<span class="help-block"><font color="red"><br><?php echo $RES_err;?></font></span><br>
						1st Result:<input type="text" name="res_1" class="form-control" value="<?php echo $RES_1; ?>">
						2nd Result:<input type="text" name="res_2" class="form-control" value="<?php echo $RES_2; ?>"><br>
					</div>
					<div>
						<label>Question 1</label><br>
						Question:<input type="text" name="Q_1" class="form-control" value="<?php echo $Q_1; ?>">
						Answer 1:<input type="text" name="A_1_1" class="form-control" value="<?php echo $A_1_1; ?>">
						Answer 2:<input type="text" name="A_1_2" class="form-control" value="<?php echo $A_1_2; ?>">
						Answer 3:<input type="text" name="A_1_3" class="form-control" value="<?php echo $A_1_3; ?>"> <!--optional-->
						Answer 4:<input type="text" name="A_1_4" class="form-control" value="<?php echo $A_1_4; ?>"> <!--optional-->
					</div>
					<div class="form-group">
						<a href="quiz_home.php" class="btn pink rounded"><tt>Cancel</a>
						<input type="submit" class="btn pink rounded" value="Submit">
						<input type="reset" class="btn pink rounded" value="Reset">
						<br>
					</div>
				</form>
			</div>
		</center>
	</body>
</html>