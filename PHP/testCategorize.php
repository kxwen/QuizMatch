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
$test = array(0,0,5,5);

/* function to output categorization*/
function categorizeUser($traits){
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
    $firstMax = 1;
    $secondMax = 0;
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
/*execute the function */
categorizeUser(array(1,2,0,0));
categorizeUser(array(0,2,1,0));
categorizeUser(array(0,2,0,1));

?>
