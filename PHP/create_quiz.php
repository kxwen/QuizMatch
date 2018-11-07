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

 $Q_name_err = "";
 $Q_name = "";
 $DESC = "";
 $S_checked = $M_checked = $L_checked = "";
 $size_err = "";
 
 $Q_1 = $Q_2 = $Q_3 = $Q_4 = $Q_5 = "";
 $Q_6 = $Q_7 = $Q_8 = $Q_9 = $Q_10 = "";
 $Q_11 = $Q_12 = $Q_13 = $Q_14 = $Q_15 = "";
 $R_1 = $R_2 = $R_3 = $R_4 = $R_5 = $R_6 = $R_7 = $R_8 = $R_9 = $R_10 = $R_11 = $R_12 = "";
 
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
						<br><b>Questionnaire Name:</b><br><br> <input type="text" name="Q_name" class="form-control" value="<?php echo $Q_name; ?>"><br><br>
						<b>Description:</b><br><br> <textarea name="DESC" rows="5" cols="33" maxlength="200"><?php echo $DESC;?></textarea><br><br>
						<b>Size:</b> <input type="radio" name="size" value="small" <?php echo $S_checked;?> onclick="updateQs()">Small 
							<input type="radio" name="size" value="medium" <?php echo $M_checked;?> onclick="updateQs()">Medium 
							<input type="radio" name="size" value="large" <?php echo $L_checked;?> onclick="updateQs()">Large<br><br>
					</div>
					
					<!--Area where the question tabs will be placed by Javascript-->
					<span id="questions"></span>
					
					<!--Area for Final Results-->
					<div class="tab" style="white-space:nowrap"><h3>Final Results:</h3>
						<br><b>Result #1:</b> <input type="text" name="R_1" class="form-control" value="<?php echo $R_1; ?>"><br>
						<br><b>Result #2:</b> <input type="text" name="R_2" class="form-control" value="<?php echo $R_2; ?>"><br>
						<br><b>Result #3:</b> <input type="text" name="R_3" class="form-control" value="<?php echo $R_3; ?>"><br>
						<br><b>Result #4:</b> <input type="text" name="R_4" class="form-control" value="<?php echo $R_4; ?>"><br>
						<br><b>Result #5:</b> <input type="text" name="R_5" class="form-control" value="<?php echo $R_5; ?>"><br>
						<br><b>Result #6:</b> <input type="text" name="R_6" class="form-control" value="<?php echo $R_6; ?>"><br>
						<br><b>Result #7:</b> <input type="text" name="R_7" class="form-control" value="<?php echo $R_7; ?>"><br>
						<br><b>Result #8:</b> <input type="text" name="R_8" class="form-control" value="<?php echo $R_8; ?>"><br>
						<br><b>Result #9:</b> <input type="text" name="R_9" class="form-control" value="<?php echo $R_9; ?>"><br>
						<br><b>Result #10:</b> <input type="text" name="R_10" class="form-control" value="<?php echo $R_10; ?>"><br>
						<br><b>Result #11:</b> <input type="text" name="R_11" class="form-control" value="<?php echo $R_11; ?>"><br>
						<br><b>Result #12:</b> <input type="text" name="R_12" class="form-control" value="<?php echo $R_12; ?>"><br><br>
					</div>
					
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
						<div class="step"></div><!--Area where page indicators will be placed by Javascript--><span id="num_pages"></span><!--End of Insertion Area--><div class="step"></div>
					</div>
				</form>
			</div>
		</center>
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
				var page_indicator = document.createElement("DIV");
				
				// Setting up attributes for Tab DIV
				local_tab.setAttribute("class", "tab");
				local_tab.setAttribute("style", "white-space:nowrap");
				local_tab.innerHTML = "<h3>Question #"+(num_Questions+1)+":</h3> <b>Question: </b>";
				
				// Setting Attributes of the Question Text Field
				question_input.setAttribute("type", "text");
				question_input.setAttribute("class", "form-control");
				question_input.setAttribute("Name", "Q_"+(num_Questions+1));
				question_input.setAttribute("id", "Q_"+(num_Questions+1));
				question_input.setAttribute("style", "width:75%");
				
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
				
				// Providing Sub Span with an ID for ease of Answer Insertion
				sub_span.setAttribute("id", "add_Ans_field_"+(num_Questions+1));
				
				// Appending most Elements into Tab; Preparation for Insertion
				local_tab.appendChild(question_input);
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
				var Opt_1 = document.createElement("OPTION");
				var Opt_2 = document.createElement("OPTION");
				var Opt_3 = document.createElement("OPTION");
				var Opt_4 = document.createElement("OPTION");
				
				Opt_1.setAttribute("value", "<?php echo $traits[0];?>");
				Opt_2.setAttribute("value", "<?php echo $traits[1];?>");
				Opt_3.setAttribute("value", "<?php echo $traits[2];?>");
				Opt_4.setAttribute("value", "<?php echo $traits[3];?>");
				
				Opt_1.innerHTML = "<?php echo $traits[0];?>";
				Opt_2.innerHTML = "<?php echo $traits[1];?>";
				Opt_3.innerHTML = "<?php echo $traits[2];?>";
				Opt_4.innerHTML = "<?php echo $traits[3];?>";
				
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
					
					// Assigns DIV attributes needed to reference specific question
					div_answer.setAttribute("id", "A_DIV_"+((current_question+1)+"_"+(num_Ans+1)));
					div_answer.setAttribute("style", "white-space:nowrap");
					div_answer.innerHTML="<br><b>Answer #"+(num_Ans+1)+": </b>";
					
					// Assigns text input field attributes to be referenced when form is submitted
					answer_input.setAttribute("type", "text");
					answer_input.setAttribute("Name", "");
					answer_input.setAttribute("id", ("A_"+(current_question+1)+"_"+(num_Ans+1)));
					answer_input.setAttribute("style", "width:70%");
					
					// Assigns id to Select List for reference later
					tag_list.setAttribute("id", ("A_TAG_"+(current_question+1)+"_"+(num_Ans+1)));
					tag_list.setAttribute("style", "width:15%");
					
					// Append text field and Tag list to DIV
					div_answer.appendChild(answer_input);
					div_answer.appendChild(tag_list);
					
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