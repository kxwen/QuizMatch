<?php
require_once "funct_user_info.php";

function getMyMatches($link, $results){
	$total = array();
	for($i = 0; $i < sizeof($results); $i++){
		$total = array_merge($total, getMatchesForResult($link, $results[$i]["result_id"]));
	}
	return $total;
}

function getMatchesForResult($link, $result_id){
	$user_current = getUserInfo($link, $_SESSION["id"]);
	$user_current_age = calculateUserAge($user_current["DoB"]);
	$sql = "SELECT * FROM user_results WHERE result_id = ".$result_id." AND NOT user_id = ".$_SESSION["id"];
	$results = mysqli_query($link, $sql);
	$total = array();
	while($row = mysqli_fetch_assoc($results)){
		// Checks to see which id is the lesser value.
		if($_SESSION["id"] < $row["user_id"]){
			$sql_relation = "SELECT * FROM relationships WHERE user_one_id = ".$_SESSION["id"]." AND user_two_id = ".$row["user_id"];
		}else{
			$sql_relation = "SELECT * FROM relationships WHERE user_one_id = ".$row["user_id"]." AND user_two_id = ".$_SESSION["id"];
		}
		$result_relation = mysqli_query($link, $sql_relation);
		// Should not match a user with another with whom they have some sort of relation with each other.
		if(mysqli_affected_rows($link)<=0){
			$user_other = getUserInfo($link, $row["user_id"]);
			$user_other_age = calculateUserAge($user_other["DoB"]);
			if($user_other_age >= (($user_current_age/2)+7) && $user_other_age <= (($user_current_age-7)*2)){
				if((empty($user_other["gender_pref"]) || $user_other["gender_pref"] == $user_current["gender"])
				 && (empty($user_current["gender_pref"]) || $user_current["gender_pref"] == $user_other["gender"])){
					$total[]=$user_other;
				}
			}
		}
	}
	return $total;
}
?>
