<?php
require_once "config.php";

// Get all relationships that the user possesses, excluding all relationships where current user is blocked by the other
function getRelations($link){
	$sql = "SELECT * FROM relationships WHERE user_one_id = ".$_SESSION["id"]." OR user_two_id = ".$_SESSION["id"];
	$results = mysqli_query($link, $sql);
	$i = 0;
	$total = array();
	while($row = mysqli_fetch_assoc($results)){
		if(!($row["status"]==2 && $row["last_user_id"] != $_SESSION["id"])){ 
			$target_id = ($_SESSION["id"] == $row["user_one_id"])? $row["user_two_id"]: $row["user_one_id"];
			$sql_names = "SELECT username FROM users WHERE id = ".$target_id;
			$results_name = mysqli_query($link, $sql_names);
			$results_name_entry = mysqli_fetch_assoc($results_name);
			$total[$i][0] = $results_name_entry["username"];
			$total[$i][1] = $row["status"];
			$total[$i][2] = $row["last_user_id"];
			$total[$i][3] = $target_id;
			$i++;
		}
	}
	return $total;
}

// Deletes specified relationship with user of $target_id
function deleteRelation($link, $target_id){
	$user_one = $_SESSION["id"];
	$user_two = $target_id;
	if($user_one > $user_two){
		$temp = $user_one;
		$user_one = $user_two;
		$user_two = $temp;
	}
	$sql = "DELETE FROM relationships WHERE user_one_id = ".$user_one." AND user_two_id = ".$user_two;
	$result = mysqli_query($link, $sql);
	if(mysql_affected_rows($result) > 0){
		return true;
	}
	return false;
}

// Create a relationship with user of $target_id; can be used to either add friend or block user
function createRelation($link, $target_id, $status){
	$user_one = $_SESSION["id"];
	$user_two = $target_id;
	if($user_one > $user_two){
		$temp = $user_one;
		$user_one = $user_two;
		$user_two = $temp;
	}
	$sql = "INSERT INTO relationships (user_one_id, user_two_id, status, last_user_id) VALUES (".$user_one.", ".$user_two.", ".$status.", ".$_SESSION["id"].")";
	$result = mysqli_query($link, $sql);
	if(mysql_affected_rows($result) > 0){
		return true;
	}
	return false;
}

// Updates relationship status of current user and target user
function updateRelation($link, $target_id, $status){
	$user_one = $_SESSION["id"];
	$user_two = $target_id;
	if($user_one > $user_two){
		$temp = $user_one;
		$user_one = $user_two;
		$user_two = $temp;
	}
	$sql = "UPDATE relationships SET status=".$status.", last_user_id=".$_SESSION["id"]." WHERE user_one_id=".$user_one." AND user_two_id=".$user_two;
	$result = mysqli_query($link, $sql);
	if(mysql_affected_rows($result) > 0){
		return true;
	}
	return false;
}
?>