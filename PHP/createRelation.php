<?php
/* createRelation.php
 * Helper File that is intended to be called by Javascript to update database
 * Creates a Relationship through the use of predefined function of the same name
 * takes input in the form of "createRelation.php?q=<other user's id>_<status>"
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "relationships.php";

$q=$_GET['q'];
$arg=explode("_",$q);
createRelation($link, $arg[0], $arg[1]);
?>