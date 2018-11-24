<?php
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "relationships.php";

$total = getRelations($link);
echo (json_encode($total));
?>