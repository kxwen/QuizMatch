<?php
/* create_quiz.php
 * Page Form used to create a new questionnaire
 * Now includes functions that create "instances" of questions and answers;
 * basically now allows questions to be created and inserted individually.
 * Similarly, Answers may be created individually as well.
 * Now has the potential to create dynamic sized forms of N length; needs minor alterations to do so.
 * Info is not saved to database or file at the moment.
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
 $Q_6 = $Q_7 = $Q_8 = $Q_9 = $Q_10 = "";
 $Q_11 = $Q_12 = $Q_13 = $Q_14 = $Q_15 = "";
 $R_1 = $R_2 = $R_3 = $R_4 = $R_5 = $R_6 = $R_7 = $R_8 = "";
 
 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
	if(empty(trim($_POST["Q_name"])))
	{
		$Q_name_err = "Please enter a Questionnaire Name.";
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
		<title>Create a Questionnaire</title>
		<link rel="stylesheet" href="stupid.css">
		<style type="text/css"></style>
	</head>
	<body>
		<center>
			<div class="wrapper">
				<form id="quizForm" action="">
					<h2>Create a Questionnaire:</h2>
					Number of Questions: <button type="button" class="btn small pink rounded" id="num_Qs"></button>
					<div class="tab"><h3>Theme and Details:</h3>
						<b>Questionnaire Name:</b> <input type="text" name="Q_name" class="form-control" value="<?php echo $Q_name; ?>"><br><br>
						<b>Description:</b> <textarea name="DESC" rows="5" cols="33" maxlength="200"><?php echo $DESC;?></textarea><br><br>
						<b>Size:</b> <input type="radio" name="size" value="small" <?php echo $S_checked;?> onclick="updateQs()">Small 
							<input type="radio" name="size" value="medium" <?php echo $M_checked;?> onclick="updateQs()">Medium 
							<input type="radio" name="size" value="large" <?php echo $L_checked;?> onclick="updateQs()">Large<br><br>
					</div>
					
					<div class="tab"><h3>Final Results:</h3>
						<b>Result #1:</b> <input type="text" name="R_1" class="form-control" value="<?php echo $R_1; ?>"><br><br>
						<b>Result #2:</b> <input type="text" name="R_2" class="form-control" value="<?php echo $R_2; ?>"><br><br>
						<b>Result #3:</b> <input type="text" name="R_3" class="form-control" value="<?php echo $R_3; ?>"><br><br>
						<b>Result #4:</b> <input type="text" name="R_4" class="form-control" value="<?php echo $R_4; ?>"><br><br>
						<b>Result #5:</b> <input type="text" name="R_5" class="form-control" value="<?php echo $R_5; ?>"><br><br>
						<b>Result #6:</b> <input type="text" name="R_6" class="form-control" value="<?php echo $R_6; ?>"><br><br>
						<b>Result #7:</b> <input type="text" name="R_7" class="form-control" value="<?php echo $R_7; ?>"><br><br>
						<b>Result #8:</b> <input type="text" name="R_8" class="form-control" value="<?php echo $R_8; ?>"><br><br>
					</div>
					
					<!--Area where the question tabs will be placed by Javascript-->
					<span id="questions"></span>
					
					<!--Buttons that control Navigation of page and website-->
					<div style="float:left;">
						<a href="quiz_home.php" class="btn pink rounded"><tt>Cancel</a>
					</div>
					<div style="overflow:auto;">
						<div style="float:right;">
							<button type="button" class ="btn pink rounded" id="prevBtn"
								onclick="nextPrev(-1)">Previous</button>
							<button type="button" class ="btn pink rounded" id="nextBtn"
								onclick="nextPrev(1)">Next</button>
						</div>
					</div>
					<!--Controls Progress "bar"-->
					<div style="text-align:center;margin-top:40px;">
						<span class="step"></span><span class="step"></span><!--Area where page indicators will be placed by Javascript--><span id="num_pages"></span>
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
			var num_Questions = 0; // Current number of questions
			var target_Questions = 0; // Target number of questions; used to update form size
			var min_num_Ans = 2; // Minimum number of answers that a question must have
			var max_num_Ans = 5; // Maximum number of answers that a question may have
			var Question_num_ANS = [min_num_Ans]; // Records number of Answers for each question.
			for(var i = 0; i<15; i++)Question_num_ANS[i] = 0;
			updateQs();
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
			  if (n == 1 && !validateForm()) return false;
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

			function validateForm() {
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
			}

			function fixStepIndicator(n) {
			  // This function removes the "active" class of all steps...
			  var i, x = document.getElementsByClassName("step");
			  for (i = 0; i < x.length; i++) {
				x[i].className = x[i].className.replace(" active", "");
			  }
			  //... and adds the "active" class to the current step:
			  x[n].className += " active";
			}
			
			// Automation to fill out code for form dependent on size template
			function create_Question_fields(){
				if(num_Questions < target_Questions)
				{
					for(;num_Questions < target_Questions; num_Questions++){
						createQuestion();
						addAns(num_Questions);
						addAns(num_Questions);
					}
				}else if(num_Questions > target_Questions){
					for(;num_Questions > target_Questions; num_Questions--){
						deleteQuestion();
					}
				}
			}
			
			// Universal Function to be used by deleteQuestion() and removeAns()
			function deleteElement(parentDIV,childDIV){
				if(parentDIV == childDIV)
				{
					alert("Parent cannot be removed.");
				}else if(document.getElementById(childDIV)){
					var child = document.getElementById(childDIV);
					var parent = document.getElementById(parentDIV);
					parent.removeChild(child);
				}else{
					alert("Child div not found");
				}
			}
			
			// Creates a new Question Tab and Page Indicator
			function createQuestion(){
				// Creation of core components
				var local_tab = document.createElement("DIV");
				var question_input = document.createElement("INPUT");
				var page_indicator = document.createElement('span');
				
				// Setting up attributes for Tab DIV
				local_tab.setAttribute("class", "tab");
				local_tab.innerHTML = "<h3>Question #"+(num_Questions+1)+":</h3><b>Question:</b>";
				
				// Setting Attributes of the Question Text Field
				question_input.setAttribute("type", "text");
				question_input.setAttribute("class", "form-control");
				question_input.setAttribute("Name", "Q_"+(num_Questions+1));
				question_input.setAttribute("id", "Q_"+(num_Questions+1));
				
				// Sub Span used to increase number of answers
				var sub_span = document.createElement('span');
				// Button to increase number of answers
				var add_Ans = document.createElement("button");
				// Button to decrease number of answers
				var rmv_Ans = document.createElement("button");
				
				// Setting up Attributes for Add and RMV Buttons
				add_Ans.setAttribute("type", "button");
				add_Ans.setAttribute("Name", "add_Ans_"+(num_Questions+1));
				add_Ans.setAttribute("id", "add_Ans_"+(num_Questions+1));
				add_Ans.setAttribute("class", "btn small pink rounded");
				add_Ans.innerHTML = "Add Answer";
				rmv_Ans.setAttribute("type", "button");
				rmv_Ans.setAttribute("Name", "rmv_Ans_"+(num_Questions+1));
				rmv_Ans.setAttribute("id", "rmv_Ans_"+(num_Questions+1));
				rmv_Ans.setAttribute("class", "btn small pink rounded");
				rmv_Ans.style.display = "none";
				rmv_Ans.innerHTML = "Remove Answer";
				
				// Adding functionality to the buttons
				add_Ans.setAttribute("onclick", "addAns(currentTab-2)");
				rmv_Ans.setAttribute("onclick", "removeAns(currentTab-2)");
				
				// Setting up Attributes for the Page Indicator
				page_indicator.setAttribute("class", "step");
				page_indicator.setAttribute("id", "Q_page_"+(num_Questions+1));
				
				// Providing Sub Span with an ID for ease of Answer Insertion
				sub_span.setAttribute("id", "add_Ans_field_"+(num_Questions+1));
				
				// Appending most Elements into Tab; Preparation for Insertion
				local_tab.appendChild(question_input);
				local_tab.appendChild(sub_span);
				local_tab.innerHTML += "<br>";
				local_tab.appendChild(add_Ans);
				local_tab.appendChild(rmv_Ans);
				
				// Setting ID for Tab for ease of deletion later
				local_tab.setAttribute("id", "Q_tab_"+(num_Questions+1));
				
				// Insertion of Tab and Page Indicator into predesignated areas of HTML Code
				document.getElementById("questions").appendChild(local_tab);
				document.getElementById("num_pages").appendChild(page_indicator);
			}
			
			//Deletion of Question and its respective Page Indicator
			function deleteQuestion(){
				deleteElement("questions", ("Q_tab_"+num_Questions));
				deleteElement("num_pages", ("Q_page_"+num_Questions));
			}
			
			// Insertion of new Answer field
			function addAns(current_question){
				var num_Ans = Question_num_ANS[current_question];
				if(num_Ans < max_num_Ans){
					var div_answer = document.createElement("DIV");
					var answer_input = document.createElement("INPUT");
					div_answer.setAttribute("id", "A_DIV_"+((current_question+1)+"_"+(num_Ans+1)));
					div_answer.innerHTML="<b>Answer #"+(num_Ans+1)+":</b>";
					answer_input.setAttribute("type", "text");
					answer_input.setAttribute("Name", "");
					answer_input.setAttribute("id", ("A_"+(current_question+1)+"_"+(num_Ans+1)));
					div_answer.appendChild(answer_input);
					document.getElementById(("add_Ans_field_"+(current_question+1))).appendChild(div_answer);
					Question_num_ANS[current_question]++;
					if(Question_num_ANS[current_question] >= max_num_Ans){
						document.getElementById("add_Ans_"+(current_question+1)).style.display = "none";
					}else if(Question_num_ANS[current_question] > min_num_Ans){
						document.getElementById("rmv_Ans_"+(current_question+1)).style.display = "inline";
					}
				}
			}
			
			// Remove Answer field from the bottom of the list
			function removeAns(current_question){
				var num_Ans = Question_num_ANS[current_question];
				if(num_Ans > min_num_Ans){
					deleteElement(("add_Ans_field_"+(current_question+1)), ("A_DIV_"+(current_question+1)+"_"+num_Ans));
					Question_num_ANS[current_question]--;
					if(Question_num_ANS[current_question] <= min_num_Ans){
						document.getElementById("rmv_Ans_"+(current_question+1)).style.display = "none";
					}else if(Question_num_ANS[current_question] < max_num_Ans){
						document.getElementById("add_Ans_"+(current_question+1)).style.display = "inline";
					}
				}
			}
			
			// Alters the number of questions in the form.
			function updateQs() {
				if(document.querySelector('input[name="size"]:checked').value == "small")
				{
					target_Questions = 5;
				}else if(document.querySelector('input[name="size"]:checked').value == "medium"){
					target_Questions = 10;
				}else if(document.querySelector('input[name="size"]:checked').value == "large"){
					target_Questions = 15;
				}
				document.getElementById("num_Qs").innerHTML = target_Questions;
				create_Question_fields();
			}
		</script>
	</body>
</html>