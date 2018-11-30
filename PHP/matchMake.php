<?php
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	//header("location: login.php");
	exit;
}
require_once "funct_user_info.php";

function getMatches($result_id){
	$user_current = getUserInfo($_SESSION["id"]);
	$user_current_age = calculateUserAge($user_current["DoB"]);
	$sql = "SELECT * FROM user_results WHERE result_id = ".$result_id." AND NOT $user_id = "$_SESSION["id"];
	$results = mysqli_query($link, $sql);
	$total = array();
	while($row = mysqli_fetch_assoc($results)){
		$user_other = getUserInfo($row["user_id"]);
		$user_other_age = calculateUserAge($user_other["DoB"]);
		if($user_other_age >= (($user_current_age/2)+7) && $user_other_age <= (($user_current_age-7)*2)){
			if((empty($user_other["gender_pref"]) || $user_other["gender_pref"] == $user_current["gender"])
			 && (empty($user_current["gender_pref"]) || $user_current["gender_pref"] == $user_other["gender"])){
				$total[]=$user_other;
			}
		}
	}
	return $total;
}
?>
