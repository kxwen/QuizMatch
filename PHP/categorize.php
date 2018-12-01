<?php
/*connection to user database to store results of categorization */
require_once "config.php";
if(!isset($profile)){
	$sql = "SELECT * FROM users WHERE id =".$_SESSION["id"];
	$profile_entry = mysqli_query($link, $sql);
	$profile = mysqli_fetch_assoc($profile_entry);
}

$test = array(3,3,3,1);

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
    /* not sure if initializing variables is needed in php */
    $firstMax = 0;
    $secondMax = 0;
    for($x = 0; $x < 4; $x++){
         /*check for index of 2 largest numbers*/
        if ($traits[$x] >= $traits[$firstMax]){
            $secondMax = $firstMax;
            $firstMax = $x;
        }
        else if($traits[$x] >= $traits[$secondMax] ){
            $secondMax = $x;
        }
    }
    /* concatenate the two maxes */
    $keyString = $firstMax . $secondMax;
    
    /* output the key lookup value */
    echo $category[$keyString];

}
/*execute the function */
categorizeUser($test);

/* look up result id from the created quiz by the user*/
$sqlresultID = "SELECT id FROM quiz_results WHERE primary_trait =".$firstMax "AND secondary_trait =".$secondMax "AND quiz_id =".$passedInQuiz;
$result_check = mysqli_query($link, $sqlresultID);
$resultIDcheck = mysqli($result_check);

/*finds the result id from the quiz_results database for the given quiz passed in, id provides the category for a given quiz*/
$resultID = $resultIDcheck['id'];

/*check if user already has a result entry from taking the quiz before */
$sqlUserCheck = "SELECT * FROM user_results WHERE quiz_id =".$passedInQuiz "AND user_id =". $profile['id'];
$exist_check = mysqli_query($link, $sqlUserCheck);
$store_check = mysqli_fetch_assoc($exist_check);

/*if they have taken the quiz, overwrite the previous result */
if(mysqli_affected_rows($link) > 0){
    $sqluser_results = "INSERT INTO user_results (user_id, quiz_id, result_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sqluser_results);
    $user_results = mysqli_bind_param();
}

?>
