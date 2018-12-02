<?php
/*connection to user database to store results of categorization */
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
//define global variables, done to let writeUser_results() function have access to info about firstMax and secondMax
$firstMax = 1;
$secondMax = 0;

/* function to output categorization*/
function categorizeUser($traits, $passedInQuiz){
    $category = array();
    $category['01'] = 'Charmander';
    $category['02'] = 'Squirtle';
    $category['03'] = 'Bulbasaur';
    $category['10'] = 'Torchic';
    $category['12'] = 'Mudkip';
    $category['13'] = 'Treecko';
    $category['20'] = 'Chimchar';
    $category['21'] = 'Piplup';
    $category['23'] = 'Turtwig';
    $category['30'] = 'Cyndaquil';
    $category['31'] = 'Tododile';
    $category['32'] = 'Chikorita';
    /*initializing global variables is needed in php */
    global $firstMax, $secondMax;
    for($x = 0; $x < 4; $x++){
         /*check for index of 2 largest numbers*/
	if($x != $firstMax){
		if ($traits[$x] >= $traits[$firstMax]){
		    $secondMax = $firstMax;
		    $firstMax = $x;
		}
		else if($traits[$x] >= $traits[$secondMax] ){
		    $secondMax = $x;
		}
	}
    }
    /* concatenate the two maxes */
    $keyString = $firstMax . $secondMax;
    
    /* output the key lookup value */
    echo $category[$keyString];

}


//function to write results of quiz to database
function writeUser_results($passedInQuiz){
    //calls global variables from this file and config.php to establish mysql connection
    global $link, $traits, $firstMax, $secondMax;
    //look up result id from the created quiz by the user
    $sqlresultID = "SELECT id FROM quiz_results WHERE primary_trait =".$traits[$firstMax] "AND secondary_trait =".$traits[$secondMax] "AND quiz_id =".$passedInQuiz;
    $result_check = mysqli_query($link, $sqlresultID);
    $resultIDcheck = mysqli($result_check);

    //finds the result id from the quiz_results database for the given quiz passed in, id provides the category for a given quiz
    $resultID = $resultIDcheck['id'];

    //check if user already has a result entry from taking the quiz before 
    $sqlUserCheck = "SELECT * FROM user_results WHERE quiz_id =".$passedInQuiz "AND user_id =". $profile['id'];
    $exist_check = mysqli_query($link, $sqlUserCheck);
    $store_check = mysqli_fetch_assoc($exist_check);

    //if they have taken the quiz, overwrite the previous result, otherwise write a new entry 
    if(mysqli_affected_rows($link) > 0){
        $sqluser_results = "UPDATE user_results (result_id) VALUES (?) WHERE user_id = ".$profile['id'] "AND quiz_id = ".$passedInQuiz;
        $stmt = mysqli_prepare($link, $sqluser_results);
        $user_results = mysqli_bind_param($stmt, "s", $param_resultid);
        $param_resultid = $resultID;
        mysqli_stmt_execute($stmt);
   
    }else{
        $sqluser_results = "INSERT INTO user_results (user_id, quiz_id, result_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sqluser_results);
        $user_results = mysqli_bind_param($stmt, "sss", $param_userid, $param_quizid, $param_resultid);
        $param_userid = $profile['id'];
        $param_quizid = $passedInQuiz;
        $param_resultid = $resultID;
        mysqli_stmt_execute($stmt);
    }
}
?>
