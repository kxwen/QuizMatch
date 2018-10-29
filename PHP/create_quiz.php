<?php
/* create_quiz.php
 * V2
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
 
 $Q_1 = $Q_2 = $Q_3 = $Q_4 = $Q_5 = "";
 
 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
	if(empty(trim($_POST["Q_name"])))
	{
		$Q_name_err = "Please enter a Questionaire Name.";
	}else{
		$Q_name = htmlspecialchars(trim($_POST["Q_name"]));
	}
	$DESC = htmlspecialchars(trim($_POST["DESC"]));
	if($_POST["size"] == "large"){
		$L_checked = "checked";
	}elseif($_POST["size"] == "medium"){
		$M_checked = "checked";
	}else{
		$S_checked = "checked";
	}
 }
 
 if(empty($S_checked) && empty($M_checked) && empty($L_checked)){//This should only be used once; upon arriving to the page.
	 $S_checked = "checked";
 }
 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create a Questionaire</title>
		<link rel="stylesheet" href="stupid.css">
		<style type="text/css"></style>
	</head>
	<body>
		<center>
			<div class="wrapper">
				<form id="quizForm" action="">
					<h2>Create a Questionaire:</h2>
					Number of Questions: <button type="button" class="btn small pink rounded" id="num_Qs"></button>
					<div class="tab"><h3>Theme and Details:</h3>
						<b>Questionaire Name:</b> <input type="text" name="Q_name" class="form-control" value="<?php echo $Q_name; ?>"><br><br>
						<b>Description:</b> <textarea name="DESC" rows="3" cols="33" maxlength="200"><?php echo $DESC;?></textarea><br><br>
						<b>Size:</b> <input type="radio" name="size" value="small" <?php echo $S_checked;?> onclick="updateQs()">Small 
							<input type="radio" name="size" value="medium" <?php echo $M_checked;?> onclick="updateQs()">Medium 
							<input type="radio" name="size" value="large" <?php echo $L_checked;?> onclick="updateQs()">Large<br><br>
					</div>
					
					<div class="tab"><h3>Question #1:</h3>
						<b>Question:</b> <input type="text" name="Q_1" class="form-control" value="<?php echo $Q_1; ?>"><br><br>
					</div>
					
					<div class="tab"><h3>Question #2:</h3>
						<b>Question:</b> <input type="text" name="Q_2" class="form-control" value="<?php echo $Q_2; ?>"><br><br>
					</div>

					<div class="tab"><h3>Question #3:</h3>
						<b>Question:</b> <input type="text" name="Q_3" class="form-control" value="<?php echo $Q_3; ?>"><br><br>
					</div>

					<div class="tab"><h3>Question #4:</h3>
						<b>Question:</b> <input type="text" name="Q_4" class="form-control" value="<?php echo $Q_4; ?>"><br><br>
					</div>
					
					<div class="tab"><h3>Question #5:</h3>
						<b>Question:</b> <input type="text" name="Q_5" class="form-control" value="<?php echo $Q_5; ?>"><br><br>
					</div>
					
					<div style="overflow:auto;">
						<div style="float:left;">
							<a href="quiz_home.php" class="btn pink rounded"><tt>Cancel</a>
						</div>
						<div style="float:right;">
							<button type="button" class ="btn pink rounded" id="prevBtn"
								onclick="nextPrev(-1)">Previous</button>
							<button type="button" class ="btn pink rounded" id="nextBtn"
								onclick="nextPrev(1)">Next</button>
						</div>
					</div>
					<div style="text-align:center;margin-top:40px;">
						<span class="step"></span>
						<span class="step"></span>
						<span class="step"></span>
						<span class="step"></span>
						<span class="step"></span>
						<span class="step"></span>
					</div>
				</form>
			</div>
		</center>
		<script>
			/* Javascript used to manage the page.
			 * Controls which Tab of the creation is shown and controls
			 * Transitions. Helpful as to avoid having the page be one long
			 * visual mess. Also restricts invalid inputs.
			 */
			var currentTab = 0; // Current tab is set to be the first tab (0)
			if(document.querySelector('input[name="size"]:checked').value == "small")
			{
				num_Questions = 5;
				document.getElementById("num_Qs").innerHTML = num_Questions;
			}else if(document.querySelector('input[name="size"]:checked').value == "medium"){
				num_Questions = 10;
				document.getElementById("num_Qs").innerHTML = num_Questions;
			}else if(document.querySelector('input[name="size"]:checked').value == "large"){
				num_Questions = 15;
				document.getElementById("num_Qs").innerHTML = num_Questions;
			}
			showTab(currentTab); // Display the current tab

			function showTab(n) {
			  // This function will display the specified tab of the form ...
			  var x = document.getElementsByClassName("tab");
			  x[n].style.display = "block";
			  // ... and fix the Previous/Next buttons:
			  if (n == 0) {
				document.getElementById("prevBtn").style.display = "none";
			  } else {
				document.getElementById("prevBtn").style.display = "inline";
			  }
			  if (n == (x.length - 1)) {
				document.getElementById("nextBtn").innerHTML = "Submit";
			  } else {
				document.getElementById("nextBtn").innerHTML = "Next";
			  }
			  // ... and run a function that displays the correct step indicator:
			  fixStepIndicator(n)
			}

			function nextPrev(n) {
			  // This function will figure out which tab to display
			  var x = document.getElementsByClassName("tab");
			  // Exit the function if any field in the current tab is invalid:
			  //if (n == 1 && !validateForm()) return false;
			  // Hide the current tab:
			  x[currentTab].style.display = "none";
			  // Increase or decrease the current tab by 1:
			  currentTab = currentTab + n;
			  // if you have reached the end of the form... :
			  if (currentTab >= x.length) {
				//...the form gets submitted:
				document.getElementById("quizForm").submit();
				return false;
			  }
			  // Otherwise, display the correct tab:
			  showTab(currentTab);
			}

			/*function validateForm() {
			  // This function deals with validation of the form fields
			  var x, y, i, valid = true;
			  x = document.getElementsByClassName("tab");
			  y = x[currentTab].getElementsByTagName("input");
			  // A loop that checks every input field in the current tab:
			  for (i = 0; i < y.length; i++) {
				// If a field is empty...
				if (y[i].value == "") {
				  // add an "invalid" class to the field:
				  y[i].className += " invalid";
				  // and set the current valid status to false:
				  valid = false;
				}
			  }
			  // If the valid status is true, mark the step as finished and valid:
			  if (valid) {
				document.getElementsByClassName("step")[currentTab].className += " finish";
			  }
			  return valid; // return the valid status
			}*/

			function fixStepIndicator(n) {
			  // This function removes the "active" class of all steps...
			  var i, x = document.getElementsByClassName("step");
			  for (i = 0; i < x.length; i++) {
				x[i].className = x[i].className.replace(" active", "");
			  }
			  //... and adds the "active" class to the current step:
			  x[n].className += " active";
			}
			
			function updateQs() {
				if(document.querySelector('input[name="size"]:checked').value == "small")
				{
					num_Questions = 5;
					document.getElementById("num_Qs").innerHTML = num_Questions;
				}else if(document.querySelector('input[name="size"]:checked').value == "medium"){
					num_Questions = 10;
					document.getElementById("num_Qs").innerHTML = num_Questions;
				}else if(document.querySelector('input[name="size"]:checked').value == "large"){
					num_Questions = 15;
					document.getElementById("num_Qs").innerHTML = num_Questions;
				}
			}
		</script>
	</body>
</html>