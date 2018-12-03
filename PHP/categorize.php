<?php
/*connection to user database to store results of categorization */
require_once "quiz_DB_access_functions.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}
//define global variables, done to let writeUser_results() function have access to info about firstMax and secondMax
$firstMax = 1;
$secondMax = 0;
/* function to output categorization*/
function categorizeUser($traits, $quiz_id){
    $category = array();
	global $link;
	$category_raw = getQuizResults($link, $quiz_id);
    $category['01'] = $category_raw[0]["catagory_name"];
    $category['02'] = $category_raw[1]["catagory_name"];
    $category['03'] = $category_raw[2]["catagory_name"];
    $category['10'] = $category_raw[3]["catagory_name"];
    $category['12'] = $category_raw[4]["catagory_name"];
    $category['13'] = $category_raw[5]["catagory_name"];
    $category['20'] = $category_raw[6]["catagory_name"];
    $category['21'] = $category_raw[7]["catagory_name"];
    $category['23'] = $category_raw[8]["catagory_name"];
    $category['30'] = $category_raw[9]["catagory_name"];
    $category['31'] = $category_raw[10]["catagory_name"];
    $category['32'] = $category_raw[11]["catagory_name"];
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
    return $category[$keyString];
}

function categorizeUserTraits($traits){
    $category = array();
    $category['01'] = 'SANGUINE and PHLEGMATIC';
    $category['02'] = 'SANGUINE and CHOLERIC';
    $category['03'] = 'SANGUINE and MELANCHOLIC';
    $category['10'] = 'PHLEGMATIC and SANGUINE';
    $category['12'] = 'PHLEGMATIC and CHOLERIC';
    $category['13'] = 'PHLEGMATIC and MELANCHOLIC';
    $category['20'] = 'CHOLERIC and SANGUINE';
    $category['21'] = 'CHOLERIC and PHLEGMATIC';
    $category['23'] = 'CHOLERIC and MELANCHOLIC';
    $category['30'] = 'MELANCHOLIC and SANGUINE';
    $category['31'] = 'MELANCHOLIC and PHLEGMATIC';
    $category['32'] = 'MELANCHOLIC and CHOLERIC';
    /* not sure if initializing variables is needed in php */
    global $firstMax, $secondMax;
    /* concatenate the two maxes */
    $keyString = $firstMax . $secondMax;
    
    /* output the key lookup value */
    echo $category[$keyString];
}

//function to write results of quiz to database
function writeUser_results($quiz_id){
    //calls global variables from this file and config.php to establish mysql connection
    global $profile, $link, $traits, $firstMax, $secondMax;
    //look up result id from the created quiz by the user
    $sqlresultID = "SELECT id FROM quiz_results WHERE primary_trait ='".$traits[$firstMax]."' AND secondary_trait ='".$traits[$secondMax]."' AND quiz_id =".$quiz_id;
    $result_check = mysqli_query($link, $sqlresultID);
    $resultIDcheck = mysqli_fetch_assoc($result_check);
    //finds the result id from the quiz_results database for the given quiz passed in, id provides the category for a given quiz
    $resultID = $resultIDcheck['id'];
    //check if user already has a result entry from taking the quiz before 
    $sqlUserCheck = "SELECT * FROM user_results WHERE quiz_id =".$quiz_id." AND user_id =".$profile['id'];
    $exist_check = mysqli_query($link, $sqlUserCheck);
    $store_check = mysqli_fetch_assoc($exist_check);
    //if they have taken the quiz, overwrite the previous result, otherwise write a new entry 
    if(mysqli_affected_rows($link) > 0){
        $sqluser_results = "UPDATE user_results SET result_id =".$resultID." WHERE user_id = ".$profile['id']." AND quiz_id = ".$quiz_id;
		mysqli_query($link, $sqluser_results);
   
    }else{
        $sqluser_results = "INSERT INTO user_results (user_id, quiz_id, result_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sqluser_results);
        $user_results = mysqli_stmt_bind_param($stmt, "sss", $param_userid, $param_quizid, $param_resultid);
        $param_userid = $profile['id'];
        $param_quizid = $quiz_id;
        $param_resultid = $resultID;
        mysqli_stmt_execute($stmt);
    }
}
?>
