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

require_once "config.php";

 $Q_name = $DESC = $size = "";
 $Result = "";
 $Question = "";
 $Answer = "";
 $Trait = "";
 
 $valid_quiz = $valid_questions = $valid_answers = $valid_results = true;
 $sql_quiz = "INSERT INTO quizzes (owner_id, name, description, size) VALUES (?, ?, ?, ?)";
 $sql_question = "INSERT INTO questions (quiz_id, question) VALUES (?, ?)";
 $sql_result = "INSERT INTO quiz_results (quiz_id, catagory_name, primary_trait, secondary_trait) VALUES (?,?,?,?)";
 $sql_answer = "INSERT INTO answers (question_id, answer, trait) VALUES (?, ?, ?)";
 
 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
	$Q_name = trim($_POST["Q_name"]);
	$DESC = htmlspecialchars(trim($_POST["DESC"]));
	$size = $_POST["size"];
	// Begin Insertion Preparation of Quiz
	if($stmt_quiz = mysqli_prepare($link, $sql_quiz)){
		mysqli_stmt_bind_param($stmt_quiz, "isss", $param_owner_id, $param_Q_name, $param_DESC, $param_size);
		$param_owner_id = $_SESSION["id"];
		$param_Q_name = $Q_name;
		$param_DESC = $DESC;
		$param_size = $size;
		// Attempt to insert Quiz
		if(mysqli_stmt_execute($stmt_quiz)){
			// Quiz Insertion Success
			$quiz_id = mysqli_insert_id($link); // Store quiz_id for reference from questions and results
			for($i = 1; $i <= 15; $i++){ // Iterates through all possible questions
				if(isset($_POST[("Q_".$i)])){ // Verifies that question exists
					$Question = trim($_POST[("Q_".$i)]);
					// Begins Insertion Preparation of Question
					if($stmt_question = mysqli_prepare($link, $sql_question)){
						mysqli_stmt_bind_param($stmt_question, "is", $param_quiz_id, $param_Question);
						$param_quiz_id = $quiz_id;
						$param_Question = $Question;
						// Attempt to insert Question
						if(mysqli_stmt_execute($stmt_question)){
							// Question Insert Success
							$question_id = mysqli_insert_id($link);
							for($j = 1; $j <= 5; $j++){ // Iterates through all possible answers for question i
								if(isset($_POST[("A_".$i."_".$j)])){ // Verifies that answer exists
									$Answer = trim($_POST[("A_".$i."_".$j)]);
									$Trait = $_POST[("A_TAG_".$i."_".$j)];
									// Begins Insertion Preparation of Answer
									if($stmt_answer = mysqli_prepare($link, $sql_answer)){
										mysqli_stmt_bind_param($stmt_answer, "iss", $param_question_id, $param_answer, $param_trait);
										$param_question_id = $question_id;
										$param_answer = $Answer;
										$param_trait = $Trait;
										// Attempt to insert Answer
										if(!mysqli_stmt_execute($stmt_answer)){
											// Answer Insertion Failure
											echo "An Error has occurred. Please try again later.";
											$valid_answers = false;
										}
									}
									mysqli_stmt_close($stmt_answer);
								}else{
									// If the for loop encounters a non-existent answer, it should have
									// already reached the last answer.
									break;
								}
							}
						}else{
							// Question Insertion Failure
							echo "An Error has occurred. Please try again later.";
							$valid_questions = false;
						}
					}
					mysqli_stmt_close($stmt_question);
				}else{
					// If the for loop encounters a non-existent question, it should have
					// already reached the last question.
					break;
				}
			}
			for($i = 1; $i <=12; $i++){// Itterates through all Results
				$Result = trim($_POST[("R_".$i)]);
				// Begin Insertion Preparation of Result
				if($stmt_result = mysqli_prepare($link, $sql_result)){
					mysqli_stmt_bind_param($stmt_result, "isss", $param_quiz_id, $param_result, $param_trait_1, $param_trait_2);
					$param_quiz_id = $quiz_id;
					$param_result = $Result;
					$param_trait_1 = $catagories[$i][0];
					$param_trait_2 = $catagories[$i][1];
					// Attempts to insert Result
					if(!mysqli_stmt_execute($stmt_result)){
						// Result Insertion Failure
						echo "An Error has occurred. Please try again later.";
						$valid_results = false;
					}
				}
				mysqli_stmt_close($stmt_result);
			}
		}else{
			// Quiz Insertion Failure
			echo "An Error has occurred. Please try again later.";
			$valid_quiz = false;
		}
		mysqli_stmt_close($stmt_quiz);
	}
	if($valid_quiz && $valid_questions && $valid_answers && $valid_results){
		header("location: quiz_home.php"); // Temporary Destination
	}
	mysqli_close($link);
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
			<div class="container">
			<center>
				<form id="quizForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<h2>Create a Questionnaire:</h2>
					Number of Questions: <span style = "border-radius:10px; background:#ee6e73; padding:3px; color: white; font-weight:bold;" id="num_Qs"></span>
					<div class="tab"><h3>Theme and Details:</h3>
						<br><b>Questionnaire Name:</b><br><span class="help-block"><font color="red" id="Q_name_err"></font></span><br> <input style = "font-family: Helvetica" type="text" name="Q_name" class="form-control"><br><br>
						<b>Description:</b><br><br> <textarea style = "font-family: Helvetica" name="DESC" rows="5" cols="33" maxlength="200"></textarea><br><br>
						<b>Size:</b> <input type="radio" name="size" value="small" checked onclick="updateQs()">Small 
							<input type="radio" name="size" value="medium" onclick="updateQs()">Medium 
							<input type="radio" name="size" value="large" onclick="updateQs()">Large<br><br>
					</div>
					
					<!--Area where the question tabs will be placed by Javascript-->
					<span id="questions"></span>
					
					<!--Area for Final Results-->
					<div class="tab" style="white-space:nowrap"><h3>Catagory Names:</h3>
						<br><b>Result #1:</b> <input type="text" name="R_1" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[0].'/'.$traits[1])?>><span class="help-block"><font color="red" id="R_1_ERR"></font></span><br>
						<br><b>Result #2:</b> <input type="text" name="R_2" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[0].'/'.$traits[2])?>><span class="help-block"><font color="red" id="R_2_ERR"></font></span><br>
						<br><b>Result #3:</b> <input type="text" name="R_3" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[0].'/'.$traits[3])?>><span class="help-block"><font color="red" id="R_3_ERR"></font></span><br>
						<br><b>Result #4:</b> <input type="text" name="R_4" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[1].'/'.$traits[0])?>><span class="help-block"><font color="red" id="R_4_ERR"></font></span><br>
						<br><b>Result #5:</b> <input type="text" name="R_5" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[1].'/'.$traits[2])?>><span class="help-block"><font color="red" id="R_5_ERR"></font></span><br>
						<br><b>Result #6:</b> <input type="text" name="R_6" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[1].'/'.$traits[3])?>><span class="help-block"><font color="red" id="R_6_ERR"></font></span><br>
						<br><b>Result #7:</b> <input type="text" name="R_7" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[2].'/'.$traits[0])?>><span class="help-block"><font color="red" id="R_7_ERR"></font></span><br>
						<br><b>Result #8:</b> <input type="text" name="R_8" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[2].'/'.$traits[1])?>><span class="help-block"><font color="red" id="R_8_ERR"></font></span><br>
						<br><b>Result #9:</b> <input type="text" name="R_9" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[2].'/'.$traits[3])?>><span class="help-block"><font color="red" id="R_9_ERR"></font></span><br>
						<br><b>Result #10:</b> <input type="text" name="R_10" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[3].'/'.$traits[0])?>><span class="help-block"><font color="red" id="R_10_ERR"></font></span><br>
						<br><b>Result #11:</b> <input type="text" name="R_11" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[3].'/'.$traits[1])?>><span class="help-block"><font color="red" id="R_11_ERR"></font></span><br>
						<br><b>Result #12:</b> <input type="text" name="R_12" class="form-control" style="font-family: Helvetica; width:50%" placeholder=<?php echo ($traits[3].'/'.$traits[2])?>><span class="help-block"><font color="red" id="R_12_ERR"></font></span><br>
					</div>
					
					<!--Buttons that control Navigation of page and website-->
					<div style="float:left;">
						<button type="button"
						        class="btn pink rounded"
								onclick="confirmLeave('Do you wish to leave this page?\n You will lose all unsaved data.','quiz_home.php')">Cancel
						</button>
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
						<div class="step" data-tooltip="" data-tooltip-message="Theme & Details" data-tooltip-position="top"></div><!--Area where page indicators will be placed by Javascript--><span id="num_pages"></span><!--End of Insertion Area--><div class="step" data-tooltip="" data-tooltip-message="Catagory Names" data-tooltip-position="top"></div>
					</div>
					<div>
						<br>Learn about the Traits:<br>
						<a class ="btn pink rounded" href="https://psychologia.co/sanguine-personality/" target="_blank" data-tooltip="" data-tooltip-position="bottom"</ data-tooltip-message="People with the Sanguine trait are often cheerful, creative, and optimistic. Click this button to learn more.">Sanguine</a>
						<a class ="btn pink rounded" href="https://psychologia.co/phlegmatic-personality/" target="_blank" data-tooltip="" data-tooltip-position="bottom" data-tooltip-message="People with the Phlegmatic trait are often agreeable, cooperative, and considerate. Click this button to learn more.">Phlegmatic</a>
						<a class ="btn pink rounded" href="https://psychologia.co/choleric-personality/" target="_blank" data-tooltip="" data-tooltip-position="bottom" data-tooltip-message="People with the Choleric trait are often practical, independent, and tough-minded. Click this button to learn more.">Choleric</a>
						<a class ="btn pink rounded" href="https://psychologia.co/melancholic-personality/" target="_blank" data-tooltip="" data-tooltip-position="bottom" data-tooltip-message="People with the Melancholic trait are often loyal, calm, and patient. Click this button to learn more.">Melancholic</a>
					</div>
				</form>
				<center>
			</div>
		<script>
			/* Javascript used to manage this specific page.
			 * Functions used to add and remove questions and answers from page, as well as to increase/decrease progress "bar"
			 */
			var currentTab = 0; // Current tab is set to be the first tab (0)
			var num_Questions = 0; // Current number of questions
			var target_Questions = 0; // Target number of questions; used to update form size
			var Question_num_ANS = [min_num_Ans]; // Records number of Answers for each question.
			for(var i = 0; i<15; i++)Question_num_ANS[i] = 0;
			updateQs();
			showTab(currentTab); // Display the current tab
			
			// Used to reset error message regarding field & invalid flag on input field
			function reset_Validation_Error(input_field, element_id){
				document.getElementById(element_id).innerHTML = "";
				input_field.className -= " invalid";
			}
			
			// Assigns error message to error message location (element_id), sets invalid flag on input field, and returns false for "valid" boolean
			function validationError(input_field, element_id, error_message){
				document.getElementById(element_id).innerHTML = error_message;
				input_field.className += " invalid";
				return false;
			}
			
			// Override of validateForm() of config.php. Specifically for this page
			function validateForm() {
			  // This function deals with validation of the form fields
			  var x, y, z, i, j, valid = true;
			  var Tag_err = false;
			  var prev_questions;
			  x = document.getElementsByClassName("tab");
			  y = x[currentTab].getElementsByTagName("input");
			  z = x[currentTab].getElementsByTagName("select");
			  
			  // A loop that checks every input field in the current tab:
			  for (i = 0; i < y.length; i++) {
				if(currentTab == 0 && i == 0){
					reset_Validation_Error(y[i], "Q_name_err");
					if(y[i].value.trim().match(/^([A-Za-z0-9][\.\?\!\-\sA-Za-z0-9]{2,})$/) == null){
						if(y[i].value.trim() == ""){
							valid = validationError(y[i], "Q_name_err", "Please enter a Questionnaire Name.");
						}else if(y[i].value.trim().length < min_Quiz_Name_Length){
							valid = validationError(y[i], "Q_name_err", "Questionnaire name must be at least "+min_Quiz_Name_Length+" characters long.");
						}else{
							valid = validationError(y[i], "Q_name_err", "Questionnaire Name cannot have any special characters except '. ? ! -'.");
						}
					}
				}else if(currentTab == (num_Questions + 1)){ // Results Page Verification
					reset_Validation_Error(y[i], "R_"+(i+1)+"_ERR");
					// Checks to see if catagory name matches naming criteria
					if(y[i].value.trim().match(/^([A-Za-z0-9]+[\sA-Za-z0-9]*)$/) == null){
						if(y[i].value == ""){
							// Empty String
							valid = validationError(y[i], "R_"+(i+1)+"_ERR", "Please enter a catagory name.");
						}else{
							// String contains illegal characters
							valid = validationError(y[i], "R_"+(i+1)+"_ERR", "Catagory name cannot have special characters.");
						}
					}else{
						// Checks to see if current catagory is a duplicate of a previous one
						for(j = 0; j < i; j++){
							if(y[i].value.toLowerCase() === y[j].value.toLowerCase()){
								valid = validationError(y[i], "R_"+(i+1)+"_ERR", "Duplicate catagory. Please enter a unique catagory.");
							}						
						}
					}
				}else if(currentTab != 0 && currentTab != (num_Questions+1)){
					if(i==0){
						reset_Validation_Error(y[i], "Q_"+currentTab+"_ERR");
						if(y[i].value.trim().match(/^([A-Za-z0-9]+[\.\?\!\-\sA-Za-z0-9]*)$/) == null){
							if(y[i].value.trim() == ""){
								// Empty String
								valid = validationError(y[i], "Q_"+currentTab+"_ERR", "Please enter a question.");
							}else{
								// String contains illegal characters
								valid = validationError(y[i], "Q_"+currentTab+"_ERR", "Question cannot have any special characters except '. ? ! -'.");
							}
						}else{
							for(j = 1; j < currentTab ; j++){
								if(document.getElementById("Q_"+j).value == y[i].value){
									valid = validationError(y[i], "Q_"+currentTab+"_ERR", "Duplicate Question. Please enter a unique question.");
								}
							}
						}
					}else{
						reset_Validation_Error(y[i], "A_"+currentTab+"_"+i+"_ERR");
						reset_Validation_Error(z[i-1], "A_"+currentTab+"_"+i+"_ERR");
						if(y[i].value.trim().match(/^([A-Za-z0-9]+[\.\?\!\-\sA-Za-z0-9]*)$/) == null){
							if(y[i].value.trim() == ""){
								// Empty String
								valid = validationError(y[i], "A_"+currentTab+"_"+i+"_ERR", "Please enter an answer.");
							}else{
								// String contains illegal characters
								valid = validationError(y[i], "A_"+currentTab+"_"+i+"_ERR", "Answer cannot have any special characters except '. ? ! -'.");
							}
						}else{
							for(j = 1; j < i; j++){
								// Iterates through all previous answer fields to make sure no two are exactly the same.
								if(y[i].value.toLowerCase() === y[j].value.toLowerCase()){
									valid = validationError(y[i], "A_"+currentTab+"_"+i+"_ERR", "Please enter a unique answer.");
								}	
							}
						}
					}
				}
			  }
			  for(i = 0 ; i < z.length; i++){
				  for(j = 0 ; j < i; j++){
					  if(z[i].value == z[j].value){
						  if(document.getElementById("A_"+currentTab+"_"+(i+1)+"_ERR").innerHTML == ""){
							  valid = validationError(z[i], "A_"+currentTab+"_"+(i+1)+"_ERR", "Duplicate Tag. Please select another.");
						  }
					  }
				  }
			  }
			  
			  // If the valid status is true, mark the step as finished and valid:
			  if (valid) {
				document.getElementsByClassName("step")[currentTab].className += " finish";
				document.getElementsByClassName("step")[currentTab].setAttribute("onclick","switch_to_Finished_Tab("+currentTab+")");
			  }
			  return valid; // return the valid status
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
			
			// Universal Function to be used by deleteQuestion() and removeAns(); assumes inputs are IDs to DIV Elements 
			function deleteElement(parentDIV,childDIV){
				if(parentDIV == childDIV)
				{
					alert("An Error has occured: Parent cannot be removed.");
				}else if(document.getElementById(childDIV)){
					var child = document.getElementById(childDIV);
					var parent = document.getElementById(parentDIV);
					parent.removeChild(child);
				}else{
					alert("An Error has occured: Child div not found.");
				}
			}
			
			// Creates a new Question Tab and Page Indicator
			function createQuestion(){
				// Creation of core components
				var local_tab = document.createElement("DIV");
				var question_input = document.createElement("INPUT");
				var page_indicator = document.createElement("DIV");
				
				//<span class="help-block"><font color="red" id="R_12_ERR"></font></span>
				var err_block = document.createElement("SPAN");
				var err_msg = document.createElement("FONT");
				
				err_block.setAttribute("class", "help-block");
				err_msg.setAttribute("color", "red");
				err_msg.setAttribute("id", "Q_"+(num_Questions+1)+"_ERR");
				err_block.appendChild(err_msg);
				
				// Setting up attributes for Tab DIV
				local_tab.setAttribute("class", "tab");
				local_tab.setAttribute("style", "white-space:nowrap");
				local_tab.innerHTML = "<h3>Question #"+(num_Questions+1)+":</h3> <b>Question: </b>";
				
				// Setting Attributes of the Question Text Field
				question_input.setAttribute("type", "text");
				question_input.setAttribute("class", "form-control");
				question_input.setAttribute("Name", "Q_"+(num_Questions+1));
				question_input.setAttribute("id", "Q_"+(num_Questions+1));
				question_input.setAttribute("style", "font-family: Helvetica; width:50%");
				
				// Sub Span used to increase number of answers
				var sub_span = document.createElement('span');
				// Button to increase number of answers
				var add_Ans = document.createElement("button");
				// Button to decrease number of answers
				var rmv_Ans = document.createElement("button");
				
				// Setting up Attributes for Add and RMV Buttons; RMV button hidden by default
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
				add_Ans.setAttribute("onclick", "addAns(currentTab-1)");
				rmv_Ans.setAttribute("onclick", "removeAns(currentTab-1)");
				
				// Setting up Attributes for the Page Indicator
				page_indicator.setAttribute("class", "step");
				page_indicator.setAttribute("id", "Q_page_"+(num_Questions+1));
				page_indicator.setAttribute("data-tooltip", "");
				page_indicator.setAttribute("data-tooltip-message", "Question #"+(num_Questions+1));
				page_indicator.setAttribute("data-tooltip-position", "top");
				
				// Providing Sub Span with an ID for ease of Answer Insertion
				sub_span.setAttribute("id", "add_Ans_field_"+(num_Questions+1));
				
				// Appending most Elements into Tab; Preparation for Insertion
				local_tab.appendChild(question_input);
				local_tab.appendChild(err_block);
				local_tab.innerHTML += "<br>";
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
			
			// Helper Function used to create Tag drop down menu for Answers
			// Traits array defined in config.php
			function createSelect(){
				var sel_list = document.createElement("SELECT");
				var Opt_0 = document.createElement("OPTION");
				var Opt_1 = document.createElement("OPTION");
				var Opt_2 = document.createElement("OPTION");
				var Opt_3 = document.createElement("OPTION");
				var Opt_4 = document.createElement("OPTION");
				
				Opt_0.setAttribute("value", "No Trait");
				Opt_1.setAttribute("value", "<?php echo $traits[0];?>");
				Opt_2.setAttribute("value", "<?php echo $traits[1];?>");
				Opt_3.setAttribute("value", "<?php echo $traits[2];?>");
				Opt_4.setAttribute("value", "<?php echo $traits[3];?>");
				
				Opt_0.innerHTML = "No Trait";
				Opt_1.innerHTML = "<?php echo $traits[0];?>";
				Opt_2.innerHTML = "<?php echo $traits[1];?>";
				Opt_3.innerHTML = "<?php echo $traits[2];?>";
				Opt_4.innerHTML = "<?php echo $traits[3];?>";
				
				sel_list.appendChild(Opt_0);
				sel_list.appendChild(Opt_1);
				sel_list.appendChild(Opt_2);
				sel_list.appendChild(Opt_3);
				sel_list.appendChild(Opt_4);
				
				return sel_list;
			}
			
			// Insertion of new Answer field
			function addAns(current_question){
				var num_Ans = Question_num_ANS[current_question];
				// Adds answer only if current total for current question does not meet maximum.
				if(num_Ans < max_num_Ans){
					// Create HTML Elements
					var div_answer = document.createElement("DIV");
					var answer_input = document.createElement("INPUT");
					var tag_list = createSelect();
					
					var err_block = document.createElement("SPAN");
					var err_msg = document.createElement("FONT");
				
					err_block.setAttribute("class", "help-block");
					err_msg.setAttribute("color", "red");
					err_msg.setAttribute("id", "A_"+(current_question+1)+"_"+(num_Ans+1)+"_ERR");
					err_block.appendChild(err_msg);
					
					// Assigns DIV attributes needed to reference specific question
					div_answer.setAttribute("id", "A_DIV_"+((current_question+1)+"_"+(num_Ans+1)));
					div_answer.setAttribute("style", "white-space:nowrap");
					div_answer.innerHTML="<br><b>Answer #"+(num_Ans+1)+": </b>";
					
					// Assigns text input field attributes to be referenced when form is submitted
					answer_input.setAttribute("type", "text");
					answer_input.setAttribute("Name", ("A_"+(current_question+1)+"_"+(num_Ans+1)));
					answer_input.setAttribute("id", ("A_"+(current_question+1)+"_"+(num_Ans+1)));
					answer_input.setAttribute("style", "font-family: Helvetica; width:50%");
					
					// Assigns id to Select List for reference later
					tag_list.setAttribute("id", ("A_TAG_"+(current_question+1)+"_"+(num_Ans+1)));
					tag_list.setAttribute("name", ("A_TAG_"+(current_question+1)+"_"+(num_Ans+1)));
					tag_list.setAttribute("style", "width:15%");
					
					// Append text field and Tag list to DIV
					div_answer.appendChild(answer_input);
					div_answer.appendChild(tag_list);
					div_answer.appendChild(err_block);
					
					// Append DIV to Form
					document.getElementById(("add_Ans_field_"+(current_question+1))).appendChild(div_answer);
					
					// Update array and determine which buttons need to be hidden/shown if any
					Question_num_ANS[current_question]++;
					if(Question_num_ANS[current_question] >= max_num_Ans){
						// Sets Add Answer button to hidden; button re-revealed in removeAns()
						document.getElementById("add_Ans_"+(current_question+1)).style.display = "none";
					}else if(Question_num_ANS[current_question] > min_num_Ans){
						// Sets Remove Answer button to revealed; button re-hidden in removeAns()
						document.getElementById("rmv_Ans_"+(current_question+1)).style.display = "inline";
					}
				}
			}
			
			// Remove Answer field from the bottom of the list
			function removeAns(current_question){
				var num_Ans = Question_num_ANS[current_question];
				// Removes only if current total number of answers for the question meets minimum requirements
				if(num_Ans > min_num_Ans){
					// Deletes Answer by latest one
					deleteElement(("add_Ans_field_"+(current_question+1)), ("A_DIV_"+(current_question+1)+"_"+num_Ans));
					
					// Update array and determine which buttons need to be hidden/shown if any
					Question_num_ANS[current_question]--;
					if(Question_num_ANS[current_question] <= min_num_Ans){
						// Sets Remove Answer button to hidden; button re-revealed in addAns()
						document.getElementById("rmv_Ans_"+(current_question+1)).style.display = "none";
					}else if(Question_num_ANS[current_question] < max_num_Ans){
						// Sets Add Answer button to revealed; button re-hidden in addAns()
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