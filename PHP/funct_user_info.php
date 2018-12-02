<?php
require_once "config.php";

// Returns an associative array if successful, and FALSE if unsuccessful
function getUserInfo($link, $user_id){
	$sql = "SELECT * FROM users WHERE id = ".$user_id;
	$results = mysqli_query($link, $sql);
	return mysqli_fetch_assoc($results);
}

// Input: String
// Output: Int (representing years)
function calculateUserAge($DoB){
	$DoB_temp = explode("-", $DoB);
	$current_date = explode("-",date("Y-m-d"));
	return (int)($current_date[0]-$DoB_temp[0]);
}

// Returns an array of associative arrays. Will return an empty array if none are found.
function getMyResults($link){
	$sql = "SELECT quiz_id, result_id FROM user_results WHERE user_id = ".$_SESSION["id"];
	$results = mysqli_query($link, $sql);
	$total = array();
	while($row = mysqli_fetch_assoc($results)){
		$total[] = $row;
	}
	return $total;
}
?>