<?php
/* logout.php
 * Simple script to destroy current session and returns user to
 * Non-user Homepage.
 *
 * Redirects to welcome.html upon completon
 */
 
session_start();
$_SESSION = array();
session_destroy();
header("location: homepage.html");
exit;
?>