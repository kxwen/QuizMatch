<?php
/* deleteRelation.php
 * Helper File that is intended to be called by Javascript to update database
 * Deletes specified relationship through the use of a predefined function of the same name
 * takes input in the form of "deleteRelation.php?q=<other user's id>"
 */
session_start();
if(!isset($_SESSION["loggedin"])||$_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
require_once "relationships.php";

$q=$_GET['q'];
deleteRelation($link, $q);
?>