<?php
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "quiz_DB_access_functions.php";

$q = $_GET["q"];
$arg[0] = $arg[1] = $arg[2] = "";
$arg = explode("_",$q);
echo json_encode(getSortedOtherQuizzes($link, $arg[0]." ".$arg[1], $arg[2]));
?>